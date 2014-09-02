<?php

if (!defined('IN_QFINDER')) exit;

/**
 * Include base XML command handler
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_FileEditor extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * handle request and build XML
     * @access protected
     */
    function buildXml()
    {
        if (empty($_POST['QFinderCommand']) || $_POST['QFinderCommand'] != 'true') {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $this->checkConnector();
        $this->checkRequest();

        // Saving empty file is equal to deleting a file, that's why FILE_DELETE permissions are required
        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_DELETE)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        if (!isset($_POST["fileName"])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }
        if (!isset($_POST["content"])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $fileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_POST["fileName"]);
        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!$resourceTypeInfo->checkExtension($fileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_EXTENSION);
        }

        if (!QFinder_Connector_Utils_FileSystem::checkFileName($fileName) || $resourceTypeInfo->checkIsHiddenFile($fileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $filePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);

        if (!file_exists($filePath) || !is_file($filePath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        if (!is_writable(dirname($filePath))) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $fp = @fopen($filePath, 'wb');
        if ($fp === false || !flock($fp, LOCK_EX)) {
            $result = false;
        }
        else {
            $result = fwrite($fp, $_POST["content"]);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        if ($result === false) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }
    }

    /**
     * @access public
     */
    function onBeforeExecuteCommand( &$command )
    {
        if ( $command == 'SaveFile' )
        {
            $this->sendResponse();
            return false;
        }

        return true ;
    }
}

$CommandHandler_FileEditor = new QFinder_Connector_CommandHandler_FileEditor();
$config['Hooks']['BeforeExecuteCommand'][] = array($CommandHandler_FileEditor, "onBeforeExecuteCommand");
$config['Plugins'][] = 'fileeditor';
