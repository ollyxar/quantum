<?php

if (!defined('IN_QFINDER')) exit;

/**
 * Include base XML command handler
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_RenameFile extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "RenameFile";


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

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_RENAME)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        if (!isset($_GET["fileName"])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }
        if (!isset($_GET["newFileName"])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        $fileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["fileName"]);
        $newFileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["newFileName"]);

        $oRenamedFileNode = new Qfinder_Connector_Utils_XmlNode("RenamedFile");
        $this->_connectorNode->addChild($oRenamedFileNode);
        $oRenamedFileNode->addAttribute("name", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($fileName));

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();
        if (!$resourceTypeInfo->checkExtension($newFileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_EXTENSION);
        }

        if (!QFinder_Connector_Utils_FileSystem::checkFileName($fileName) || $resourceTypeInfo->checkIsHiddenFile($fileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if (!QFinder_Connector_Utils_FileSystem::checkFileName($newFileName) || $resourceTypeInfo->checkIsHiddenFile($newFileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        if (!$resourceTypeInfo->checkExtension($fileName, false)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if ($_config->forceAscii()) {
            $newFileName = QFinder_Connector_Utils_FileSystem::convertToAscii($newFileName);
        }

        $filePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);
        $newFilePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $newFileName);

        $bMoved = false;

        if (!file_exists($filePath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        if (!is_writable(dirname($newFilePath))) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        if (!is_writable($filePath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        if (file_exists($newFilePath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ALREADY_EXIST);
        }

        $bMoved = @rename($filePath, $newFilePath);

        if (!$bMoved) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNKNOWN, "File " . QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($fileName) . "has not been renamed");
        } else {
            $oRenamedFileNode->addAttribute("newName", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFileName));

            $thumbPath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getThumbsServerPath(), $fileName);
            QFinder_Connector_Utils_FileSystem::unlink($thumbPath);
        }
    }
}
