<?php

if (!defined('IN_QFINDER')) exit;

class QFinder_Connector_Core_Connector
{
    /**
     * Registry
     *
     * @var QFinder_Connector_Core_Registry
     * @access private
     */
    private $_registry;

    function __construct()
    {
        $this->_registry =& QFinder_Connector_Core_Factory::getInstance("Core_Registry");
        $this->_registry->set("errorHandler", "ErrorHandler_Base");
    }

    /**
     * Generic handler for invalid commands
     * @access public
     *
     */
    public function handleInvalidCommand()
    {
        $oErrorHandler =& $this->getErrorHandler();
        $oErrorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_COMMAND);
    }

    /**
     * Execute command
     *
     * @param string $command
     * @access public
     */
    public function executeCommand($command)
    {
        if (!QFinder_Connector_Core_Hooks::run('BeforeExecuteCommand', array(&$command))) {
            return;
        }

        switch ($command)
        {
            case 'FileUpload':
            $this->_registry->set("errorHandler", "ErrorHandler_FileUpload");
            $obj =& QFinder_Connector_Core_Factory::getInstance("CommandHandler_".$command);
            $obj->sendResponse();
            break;

            case 'QuickUpload':
            $this->_registry->set("errorHandler", "ErrorHandler_QuickUpload");
            $obj =& QFinder_Connector_Core_Factory::getInstance("CommandHandler_".$command);
            $obj->sendResponse();
            break;

            case 'DownloadFile':
            case 'Thumbnail':
            $this->_registry->set("errorHandler", "ErrorHandler_Http");
            $obj =& QFinder_Connector_Core_Factory::getInstance("CommandHandler_".$command);
            $obj->sendResponse();
            break;

            case 'CopyFiles':
            case 'CreateFolder':
            case 'DeleteFiles':
            case 'DeleteFolder':
            case 'GetFiles':
            case 'GetFolders':
            case 'Init':
            case 'LoadCookies':
            case 'MoveFiles':
            case 'RenameFile':
            case 'RenameFolder':
            $obj =& QFinder_Connector_Core_Factory::getInstance("CommandHandler_".$command);
            $obj->sendResponse();
            break;

            default:
            $this->handleInvalidCommand();
            break;
        }
    }

    /**
     * Get error handler
     *
     * @access public
     * @return QFinder_Connector_ErrorHandler_Base|QFinder_Connector_ErrorHandler_FileUpload|QFinder_Connector_ErrorHandler_Http
     */
    public function &getErrorHandler()
    {
        $_errorHandler = $this->_registry->get("errorHandler");
        $oErrorHandler =& QFinder_Connector_Core_Factory::getInstance($_errorHandler);
        return $oErrorHandler;
    }
}
