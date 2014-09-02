<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_DeleteFolder extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "DeleteFolder";


    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml()
    {
        if (empty($_POST['QFinderCommand']) || $_POST['QFinderCommand'] != 'true') {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FOLDER_DELETE)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        // The root folder cannot be deleted.
        if ($this->_currentFolder->getClientPath() == "/") {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $folderServerPath = $this->_currentFolder->getServerPath();
        if (!file_exists($folderServerPath) || !is_dir($folderServerPath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND);
        }

        if (!QFinder_Connector_Utils_FileSystem::unlink($folderServerPath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        QFinder_Connector_Utils_FileSystem::unlink($this->_currentFolder->getThumbsServerPath());
    }
}
