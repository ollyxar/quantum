<?php

if (!defined('IN_QFINDER')) exit;

/**
 * Include access control config class
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/Core/AccessControlConfig.php";
/**
 * Include resource type config class
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/Core/ResourceTypeConfig.php";
/**
 * Include thumbnails config class
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/Core/ThumbnailsConfig.php";
/**
 * Include images config class
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/Core/ImagesConfig.php";


class QFinder_Connector_Core_Config
{
    /**
     * Is QFinder enabled
     *
     * @var boolean
     * @access private
     */
    private $_isEnabled = false;
    /**
	 * License Name
	 *
	 * @var string
	 * @access private
	 */
    private $_licenseName = "";
    /**
     * License Key
     *
     * @var string
     * @access private
     */
    private $_licenseKey = "";
    /**
     * Role session variable name
     *
     * @var string
     * @access private
     */
    private $_roleSessionVar = "QFinder_UserRole";
    /**
     * Access Control Configuration
     *
     * @var QFinder_Connector_Core_AccessControlConfig
     * @access private
     */
    private $_accessControlConfigCache;
    /**
     * ResourceType config cache
     *
     * @var array
     * @access private
     */
    private $_resourceTypeConfigCache = array();
    /**
     * Thumbnails config cache
     *
     * @var QFinder_Connector_Core_ThumbnailsConfig
     * @access private
     */
    private $_thumbnailsConfigCache;
    /**
     * Images config cache
     *
     * @var QFinder_Connector_Core_ImagesConfig
     * @access private
     */
    private $_imagesConfigCache;
    /**
     * Array with default resource types names
     *
     * @access private
     * @var array
     */
    private $_defaultResourceTypes = array();
    /**
     * Filesystem encoding
     *
     * @var string
     * @access private
     */
    private $_filesystemEncoding;
    /**
     * Check double extension
     *
     * @var boolean
     * @access private
     */
    private $_checkDoubleExtension = true;
    /**
     * Disallow unsafe characters in file and folder names
     *
     * @var boolean
     * @access private
     */
    private $_disallowUnsafeCharacters = false;
    /**
     * If set to true, validate image size
     *
     * @var boolean
     * @access private
     */
    private $_secureImageUploads = true;
    /**
     * Check file size after scaling images (applies to images only)
     *
     * @var boolean
     */
    private $_checkSizeAfterScaling = true;
    /**
     * For security, HTML is allowed in the first Kb of data for files having the following extensions only
     *
     * @var array
     * @access private
     */
    private $_htmlExtensions = array('html', 'htm', 'xml', 'xsd', 'txt', 'js');
    /**
     * Chmod files after upload to the following permission
     *
     * @var integer
     * @access private
     */
    private $_chmodFiles = 0777;
    /**
     * Chmod directories after creation
     *
     * @var integer
     * @access private
     */
    private $_chmodFolders = 0755;
    /**
     * Hide folders
     *
     * @var array
     * @access private
     */
    private $_hideFolders = array(".svn", "CVS");
    /**
     * Hide files
     *
     * @var integer
     * @access private
     */
    private $_hideFiles = array(".*");
    /**
     * If set to true, force ASCII names
     *
     * @var boolean
     * @access private
     */
    private $_forceAscii = false;
    /**
     * Temporary directory
     *
     * @var string
     * @access private
     */
    private $_tempDirectory = '';
    /**
     * If set to true send files using X-Sendfile server module
     * @var	bool $_xsendfile
     */
    private $_xsendfile = false;
    /**
     * Additional Nginx X-Sendfile configuration
     *
     * @var	array	$_xsendfileNginx	Configuration for location => root
     */
    private $_xsendfileNginx = array();

    function __construct()
    {
        $this->loadValues();
    }

    /**
	 * Get file system encoding, returns null if encoding is not set
	 *
	 * @access public
	 * @return string
	 */
    public function getFilesystemEncoding()
    {
        return $this->_filesystemEncoding;
    }

    /**
	 * Get "secureImageUploads" value
	 *
	 * @access public
	 * @return boolean
	 */
    public function getSecureImageUploads()
    {
        return $this->_secureImageUploads;
    }

    /**
	 * Get "checkSizeAfterScaling" value
	 *
	 * @access public
	 * @return boolean
	 */
    public function checkSizeAfterScaling()
    {
        return $this->_checkSizeAfterScaling;
    }

    /**
	 * Get "htmlExtensions" value
	 *
	 * @access public
	 * @return array
	 */
    public function getHtmlExtensions()
    {
        return $this->_htmlExtensions;
    }

    /**
	 * Get "forceAscii" value
	 *
	 * @access public
	 * @return array
	 */
    public function forceAscii()
    {
        return $this->_forceAscii;
    }

    /**
	 * Get regular expression to hide folders
	 *
	 * @access public
	 * @return array
	 */
    public function getHideFoldersRegex()
    {
        static $folderRegex;

        if (!isset($folderRegex)) {
            if (is_array($this->_hideFolders) && $this->_hideFolders) {
                $folderRegex = join("|", $this->_hideFolders);
                $folderRegex = strtr($folderRegex, array("?" => "__QMK__", "*" => "__AST__", "|" => "__PIP__"));
                $folderRegex = preg_quote($folderRegex, "/");
                $folderRegex = strtr($folderRegex, array("__QMK__" => ".", "__AST__" => ".*", "__PIP__" => "|"));
                $folderRegex = "/^(?:" . $folderRegex . ")$/uim";
            }
            else {
                $folderRegex = "";
            }
        }

        return $folderRegex;
    }

    /**
	 * Get regular expression to hide files
	 *
	 * @access public
	 * @return array
	 */
    public function getHideFilesRegex()
    {
        static $fileRegex;

        if (!isset($fileRegex)) {
            if (is_array($this->_hideFiles) && $this->_hideFiles) {
                $fileRegex = join("|", $this->_hideFiles);
                $fileRegex = strtr($fileRegex, array("?" => "__QMK__", "*" => "__AST__", "|" => "__PIP__"));
                $fileRegex = preg_quote($fileRegex, "/");
                $fileRegex = strtr($fileRegex, array("__QMK__" => ".", "__AST__" => ".*", "__PIP__" => "|"));
                $fileRegex = "/^(?:" . $fileRegex . ")$/uim";
            }
            else {
                $fileRegex = "";
            }
        }

        return $fileRegex;
    }

    /**
	 * Get "Check double extension" value
	 *
	 * @access public
	 * @return boolean
	 */
    public function getCheckDoubleExtension()
    {
        return $this->_checkDoubleExtension;
    }

    /**
	 * Get "Disallow unsafe characters" value
	 *
	 * @access public
	 * @return boolean
	 */
    public function getDisallowUnsafeCharacters()
    {
        return $this->_disallowUnsafeCharacters;
    }

    /**
	 * Get default resource types
	 *
	 * @access public
	 * @return array()
	 */
    public function getDefaultResourceTypes()
    {
        return $this->_defaultResourceTypes;
    }

    /**
	 * Is QFinder enabled
	 *
	 * @access public
	 * @return boolean
	 */
    public function getIsEnabled()
    {
        return $this->_isEnabled;
    }

    /**
	 * Get license key
	 *
	 * @access public
	 * @return string
	 */
    public function getLicenseKey()
    {
        return $this->_licenseKey;
    }

    /**
	* Get license name
	*
	* @access public
	* @return string
	*/
    public function getLicenseName()
    {
        return $this->_licenseName;
    }

    /**
	* Get chmod settings for uploaded files
	*
	* @access public
	* @return integer
	*/
    public function getChmodFiles()
    {
        return $this->_chmodFiles;
    }

    /**
	* Get chmod settings for created directories
	*
	* @access public
	* @return integer
	*/
    public function getChmodFolders()
    {
        return $this->_chmodFolders;
    }

    /**
	 * Get role sesion variable name
	 *
	 * @access public
	 * @return string
	 */
    public function getRoleSessionVar()
    {
        return $this->_roleSessionVar;
    }

    /**
	 * Get resourceTypeName config
	 *
	 * @param string $resourceTypeName
	 * @return QFinder_Connector_Core_ResourceTypeConfig|null
	 * @access public
	 */
    public function &getResourceTypeConfig($resourceTypeName)
    {
        $_null = null;

        if (isset($this->_resourceTypeConfigCache[$resourceTypeName])) {
            return $this->_resourceTypeConfigCache[$resourceTypeName];
        }

        if (!isset($GLOBALS['config']['ResourceType']) || !is_array($GLOBALS['config']['ResourceType'])) {
            return $_null;
        }

        reset($GLOBALS['config']['ResourceType']);
        while (list($_key,$_resourceTypeNode) = each($GLOBALS['config']['ResourceType'])) {
            if ($_resourceTypeNode['name'] === $resourceTypeName) {
                $this->_resourceTypeConfigCache[$resourceTypeName] = new QFinder_Connector_Core_ResourceTypeConfig($_resourceTypeNode);

                return $this->_resourceTypeConfigCache[$resourceTypeName];
            }
        }

        return $_null;
    }

    /**
     * Get thumbnails config
     *
     * @access public
     * @return QFinder_Connector_Core_ThumbnailsConfig
     */
    public function &getThumbnailsConfig()
    {
        if (!isset($this->_thumbnailsConfigCache)) {
            $this->_thumbnailsConfigCache = new QFinder_Connector_Core_ThumbnailsConfig(isset($GLOBALS['config']['Thumbnails']) ? $GLOBALS['config']['Thumbnails'] : array());
        }

        return $this->_thumbnailsConfigCache;
    }

    /**
     * Get images config
     *
     * @access public
     * @return QFinder_Connector_Core_ImagesConfig
     */
    public function &getImagesConfig()
    {
        if (!isset($this->_imagesConfigCache)) {
            $this->_imagesConfigCache = new QFinder_Connector_Core_ImagesConfig(isset($GLOBALS['config']['Images']) ? $GLOBALS['config']['Images'] : array());
        }

        return $this->_imagesConfigCache;
    }

    /**
     * Get access control config
     *
     * @access public
     * @return QFinder_Connector_Core_AccessControlConfig
     */
    public function &getAccessControlConfig()
    {
        if (!isset($this->_accessControlConfigCache)) {
            $this->_accessControlConfigCache = new QFinder_Connector_Core_AccessControlConfig(isset($GLOBALS['config']['AccessControl']) ? $GLOBALS['config']['AccessControl'] : array());
        }

        return $this->_accessControlConfigCache;
    }

    /**
     * Load values from config
     *
     * @access private
     */
    private function loadValues()
    {
        if (function_exists('CheckAuthentication')) {
            $this->_isEnabled = CheckAuthentication();
        }
        if (isset($GLOBALS['config']['LicenseName'])) {
            $this->_licenseName = (string)$GLOBALS['config']['LicenseName'];
        }
        if (isset($GLOBALS['config']['LicenseKey'])) {
            $this->_licenseKey = (string)$GLOBALS['config']['LicenseKey'];
        }
        if (isset($GLOBALS['config']['FilesystemEncoding'])) {
            $this->_filesystemEncoding = (string)$GLOBALS['config']['FilesystemEncoding'];
        }
        if (isset($GLOBALS['config']['RoleSessionVar'])) {
            $this->_roleSessionVar = (string)$GLOBALS['config']['RoleSessionVar'];
        }
        if (isset($GLOBALS['config']['CheckDoubleExtension'])) {
            $this->_checkDoubleExtension = QFinder_Connector_Utils_Misc::booleanValue($GLOBALS['config']['CheckDoubleExtension']);
        }
        if (isset($GLOBALS['config']['DisallowUnsafeCharacters'])) {
            $this->_disallowUnsafeCharacters = QFinder_Connector_Utils_Misc::booleanValue($GLOBALS['config']['DisallowUnsafeCharacters']);
        }
        if (isset($GLOBALS['config']['SecureImageUploads'])) {
            $this->_secureImageUploads = QFinder_Connector_Utils_Misc::booleanValue($GLOBALS['config']['SecureImageUploads']);
        }
        if (isset($GLOBALS['config']['CheckSizeAfterScaling'])) {
            $this->_checkSizeAfterScaling = QFinder_Connector_Utils_Misc::booleanValue($GLOBALS['config']['CheckSizeAfterScaling']);
        }
        if (isset($GLOBALS['config']['ForceAscii'])) {
            $this->_forceAscii = QFinder_Connector_Utils_Misc::booleanValue($GLOBALS['config']['ForceAscii']);
        }
        if (isset($GLOBALS['config']['HtmlExtensions'])) {
            $this->_htmlExtensions = (array)$GLOBALS['config']['HtmlExtensions'];
        }
        if (isset($GLOBALS['config']['HideFolders'])) {
            $this->_hideFolders = (array)$GLOBALS['config']['HideFolders'];
        }
        if (isset($GLOBALS['config']['HideFiles'])) {
            $this->_hideFiles = (array)$GLOBALS['config']['HideFiles'];
        }
        if (isset($GLOBALS['config']['ChmodFiles'])) {
            $this->_chmodFiles = $GLOBALS['config']['ChmodFiles'];
        }
        if (isset($GLOBALS['config']['ChmodFolders'])) {
            $this->_chmodFolders = $GLOBALS['config']['ChmodFolders'];
        }
        if (isset($GLOBALS['config']['DefaultResourceTypes'])) {
            $_defaultResourceTypes = (string)$GLOBALS['config']['DefaultResourceTypes'];
            if (strlen($_defaultResourceTypes)) {
                $this->_defaultResourceTypes = explode(",", $_defaultResourceTypes);
            }
        }
        if (isset($GLOBALS['config']['TempDirectory'])) {
          $this->_tempDirectory = $GLOBALS['config']['TempDirectory'];
        }
        if (isset($GLOBALS['config']['XSendfile'])) {
          $this->_xsendfile = QFinder_Connector_Utils_Misc::booleanValue($GLOBALS['config']['XSendfile']);
        }
        if (isset($GLOBALS['config']['XSendfileNginx'])) {
          $this->_xsendfileNginx = (array)$GLOBALS['config']['XSendfileNginx'];
        }
    }

    /**
     * Get all resource type names defined in config
     *
     * @return array
     * @access public
     */
    public function getResourceTypeNames()
    {
        if (!isset($GLOBALS['config']['ResourceType']) || !is_array($GLOBALS['config']['ResourceType'])) {
            return array();
        }

        $_names = array();
        foreach ($GLOBALS['config']['ResourceType'] as $key => $_resourceType) {
            if (isset($_resourceType['name'])) {
                $_names[] = (string)$_resourceType['name'];
            }
        }

        return $_names;
    }

    /**
     * Get temporary directory
     * @access public
     * @return string
     */
    public function getTempDirectory()
    {
      return $this->_tempDirectory;
    }

    /**
     * Get X-Sendfile option
     */
    public function getXSendfile(){
      return $this->_xsendfile;
    }

    /**
     * Get the dditional Nginx X-Sendfile configuration (location => root)
     */
    public function getXSendfileNginx(){
      $xsendfileNginx = array();
      foreach ( $this->_xsendfileNginx as $location => $root ){
        $root = (string)$root;
        $location = rtrim((string)$location,'/').'/';
        if ( substr($root,-1,1) != '/' && substr($root,-1,1) != '\\') {
          // root and location paths are concatenated
          // @see http://wiki.nginx.org/XSendfile
          $root = QFinder_Connector_Utils_FileSystem::combinePaths(rtrim($root,'/'),$location);
        }
        $xsendfileNginx[$location] = $root;
      }
      return $xsendfileNginx;
    }

}
