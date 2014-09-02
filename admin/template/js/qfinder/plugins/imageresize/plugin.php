<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_ImageResize extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * @access private
     */
    function getConfig()
    {
        $config = array();
        if (isset($GLOBALS['config']['plugin_imageresize'])) {
            $config = $GLOBALS['config']['plugin_imageresize'];
        }
        if (!isset($config['smallThumb'])) {
            $config['smallThumb'] = "90x90";
        }
        if (!isset($config['mediumThumb'])) {
            $config['mediumThumb'] = "120x120";
        }
        if (!isset($config['largeThumb'])) {
            $config['largeThumb'] = "180x180";
        }
        return $config;
    }

    /**
     * handle request and build XML
     * @access protected
     *
     */
    function buildXml()
    {
        if (empty($_POST['QFinderCommand']) || $_POST['QFinderCommand'] != 'true') {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $this->checkConnector();
        $this->checkRequest();

        //resizing to 1x1 is almost equal to deleting a file, that's why FILE_DELETE permissions are required
        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_DELETE) || !$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_UPLOAD)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!isset($_POST["fileName"])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $fileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_POST["fileName"]);

        if (!QFinder_Connector_Utils_FileSystem::checkFileName($fileName) || $resourceTypeInfo->checkIsHiddenFile($fileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if (!$resourceTypeInfo->checkExtension($fileName, false)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $filePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);

        if (!file_exists($filePath) || !is_file($filePath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        $newWidth = trim($_POST['width']);
        $newHeight = trim($_POST['height']);
        $quality = 80;
        $resizeOriginal = !empty($_POST['width']) && !empty($_POST['height']);

        if ($resizeOriginal) {
            if (!preg_match("/^\d+$/", $newWidth) || !preg_match("/^\d+$/", $newHeight) || !preg_match("/^\d+$/", $newWidth)) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
            }
            if (!isset($_POST["newFileName"])) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
            }
            $newFileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_POST["newFileName"]);
            if (!$resourceTypeInfo->checkExtension($newFileName)) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_EXTENSION);
            }
            if (!QFinder_Connector_Utils_FileSystem::checkFileName($newFileName) || $resourceTypeInfo->checkIsHiddenFile($newFileName)) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
            }
            $newFilePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $newFileName);
            if (!is_writable(dirname($newFilePath))) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
            }
            if ($_POST['overwrite'] != "1" && file_exists($newFilePath)) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ALREADY_EXIST);
            }
            $_imagesConfig = $_config->getImagesConfig();
            $maxWidth = $_imagesConfig->getMaxWidth();
            $maxHeight = $_imagesConfig->getMaxHeight();
            // Shouldn't happen as the JavaScript validation should not allow this.
            if ( ( $maxWidth > 0 && $newWidth > $maxWidth ) || ( $maxHeight > 0 && $newHeight > $maxHeight ) ) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
            }
        }

        require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/Thumbnail.php";

        if ($resizeOriginal) {
            $result = QFinder_Connector_CommandHandler_Thumbnail::createThumb($filePath, $newFilePath, $newWidth, $newHeight, $quality, false) ;
            if (!$result) {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
            }
        }

        $config = $this->getConfig();
        $nameWithoutExt = preg_replace("/^(.+)\_\d+x\d+$/", "$1", QFinder_Connector_Utils_FileSystem::getFileNameWithoutExtension($fileName));
        $extension = QFinder_Connector_Utils_FileSystem::getExtension($fileName);
        foreach (array('small', 'medium', 'large') as $size) {
            if (!empty($_POST[$size]) && $_POST[$size] == '1') {
                $thumbName = $nameWithoutExt."_".$size.".".$extension;
                $newFilePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $thumbName);
                if (!empty($config[$size.'Thumb'])) {
                    if (preg_match("/^(\d+)x(\d+)$/", $config[$size.'Thumb'], $matches)) {
                        QFinder_Connector_CommandHandler_Thumbnail::createThumb($filePath, $newFilePath, $matches[1], $matches[2], $quality, true) ;
                    }
                }
            }
        }

    }

    /**
     * @access public
     */
    function onInitCommand( &$connectorNode )
    {
        // "@" protects against E_STRICT (Only variables should be assigned by reference)
        @$pluginsInfo = &$connectorNode->getChild("PluginsInfo");
        $imageresize = new QFinder_Connector_Utils_XmlNode("imageresize");
        $pluginsInfo->addChild($imageresize);
        $config = $this->getConfig();
        foreach (array('small', 'medium', 'large') as $size) {
            if (!empty($config[$size.'Thumb'])) {
                $imageresize->addAttribute($size.'Thumb', $config[$size.'Thumb']);
            }
        }
        return true ;
    }

    /**
     * @access public
     */
    function onBeforeExecuteCommand( &$command )
    {
        if ( $command == 'ImageResize' )
        {
            $this->sendResponse();
            return false;
        }

        return true ;
    }
}

class QFinder_Connector_CommandHandler_ImageResizeInfo extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * handle request and build XML
     * @access protected
     *
     */
    function buildXml()
    {
        $this->checkConnector();
        $this->checkRequest();

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!isset($_GET["fileName"])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $fileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["fileName"]);

        if (!QFinder_Connector_Utils_FileSystem::checkFileName($fileName) || $resourceTypeInfo->checkIsHiddenFile($fileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if (!$resourceTypeInfo->checkExtension($fileName, false)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $filePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);

        if (!file_exists($filePath) || !is_file($filePath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        list($width, $height) = getimagesize($filePath);
        $oNode = new Qfinder_Connector_Utils_XmlNode("ImageInfo");
        $oNode->addAttribute("width", $width);
        $oNode->addAttribute("height", $height);
        $this->_connectorNode->addChild($oNode);
    }

    /**
     * @access public
     */
    function onBeforeExecuteCommand( &$command )
    {
        if ( $command == 'ImageResizeInfo' )
        {
            $this->sendResponse();
            return false;
        }

        return true ;
    }
}

if (function_exists('imagecreate')) {
	$CommandHandler_ImageResize = new QFinder_Connector_CommandHandler_ImageResize();
	$CommandHandler_ImageResizeInfo = new QFinder_Connector_CommandHandler_ImageResizeInfo();
	$config['Hooks']['BeforeExecuteCommand'][] = array($CommandHandler_ImageResize, "onBeforeExecuteCommand");
	$config['Hooks']['BeforeExecuteCommand'][] = array($CommandHandler_ImageResizeInfo, "onBeforeExecuteCommand");
	$config['Hooks']['InitCommand'][] = array($CommandHandler_ImageResize, "onInitCommand");
	$config['Plugins'][] = 'imageresize';
}
