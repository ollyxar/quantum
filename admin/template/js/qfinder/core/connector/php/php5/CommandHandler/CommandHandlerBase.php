<?php

if (!defined('IN_QFINDER')) exit;

/**
 * @package QFinder
 * @subpackage CommandHandlers
 */

/**
 * Base commands handler
 *
 * @package QFinder
 * @subpackage CommandHandlers
 * @abstract
 *
 */
class QFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * QFinder_Connector_Core_Connector object
     *
     * @access protected
     * @var QFinder_Connector_Core_Connector
     */
    protected $_connector;
    /**
     * QFinder_Connector_Core_FolderHandler object
     *
     * @access protected
     * @var QFinder_Connector_Core_FolderHandler
     */
    protected $_currentFolder;
    /**
     * Error handler object
     *
     * @access protected
     * @var QFinder_Connector_ErrorHandler_Base|QFinder_Connector_ErrorHandler_FileUpload|QFinder_Connector_ErrorHandler_Http
     */
    protected $_errorHandler;

    function __construct()
    {
        $this->_currentFolder =& QFinder_Connector_Core_Factory::getInstance("Core_FolderHandler");
        $this->_connector =& QFinder_Connector_Core_Factory::getInstance("Core_Connector");
        $this->_errorHandler =&  $this->_connector->getErrorHandler();
    }

    /**
     * Get Folder Handler
     *
     * @access public
     * @return QFinder_Connector_Core_FolderHandler
     */
    public function getFolderHandler()
    {
        if (is_null($this->_currentFolder)) {
            $this->_currentFolder =& QFinder_Connector_Core_Factory::getInstance("Core_FolderHandler");
        }

        return $this->_currentFolder;
    }

    /**
     * Check whether Connector is enabled
     * @access protected
     *
     */
    protected function checkConnector()
    {
        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (!$_config->getIsEnabled()) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_CONNECTOR_DISABLED);
        }
    }

    /**
     * Check request
     * @access protected
     *
     */
    protected function checkRequest()
    {
        if (preg_match(QFINDER_REGEX_INVALID_PATH, $this->_currentFolder->getClientPath())) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $_resourceTypeConfig = $this->_currentFolder->getResourceTypeConfig();

        if (is_null($_resourceTypeConfig)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_TYPE);
        }

        $_clientPath = $this->_currentFolder->getClientPath();
        $_clientPathParts = explode("/", trim($_clientPath, "/"));
        if ($_clientPathParts) {
            foreach ($_clientPathParts as $_part) {
                if ($_resourceTypeConfig->checkIsHiddenFolder($_part)) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }
            }
        }

        if (!is_dir($this->_currentFolder->getServerPath())) {
            if ($_clientPath == "/") {
                if (!QFinder_Connector_Utils_FileSystem::createDirectoryRecursively($this->_currentFolder->getServerPath())) {
                    /**
                     * @todo handle error
                     */
                }
            }
            else {
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND);
            }
        }
    }
}
