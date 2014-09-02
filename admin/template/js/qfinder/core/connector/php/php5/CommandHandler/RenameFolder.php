<?php

if (!defined('IN_QFINDER')) exit;

/**
 * Include base XML command handler
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_RenameFolder extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "RenameFolder";


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

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FOLDER_RENAME)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        if (!isset($_GET["NewFolderName"])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $newFolderName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["NewFolderName"]);
        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        if ($_config->forceAscii()) {
            $newFolderName = QFinder_Connector_Utils_FileSystem::convertToAscii($newFolderName);
        }
        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!QFinder_Connector_Utils_FileSystem::checkFolderName($newFolderName) || $resourceTypeInfo->checkIsHiddenFolder($newFolderName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        // The root folder cannot be deleted.
        if ($this->_currentFolder->getClientPath() == "/") {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $oldFolderPath = $this->_currentFolder->getServerPath();
        $bMoved = false;

        if (!is_dir($oldFolderPath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        //let's calculate new folder name
        $newFolderPath = dirname($oldFolderPath).'/'.$newFolderName.'/';

        if (file_exists(rtrim($newFolderPath, '/'))) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ALREADY_EXIST);
        }

        $bMoved = @rename($oldFolderPath, $newFolderPath);

        if (!$bMoved) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        } else {
            $newThumbsServerPath = dirname($this->_currentFolder->getThumbsServerPath()) . '/' . $newFolderName . '/';
            if (!@rename($this->_currentFolder->getThumbsServerPath(), $newThumbsServerPath)) {
                QFinder_Connector_Utils_FileSystem::unlink($this->_currentFolder->getThumbsServerPath());
            }
        }

        $newFolderPath = preg_replace(",[^/]+/?$,", $newFolderName, $this->_currentFolder->getClientPath()) . '/';
        $newFolderUrl = $resourceTypeInfo->getUrl() . ltrim($newFolderPath, '/');

        $oRenameNode = new Qfinder_Connector_Utils_XmlNode("RenamedFolder");
        $this->_connectorNode->addChild($oRenameNode);

        $oRenameNode->addAttribute("newName", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFolderName));
        $oRenameNode->addAttribute("newPath", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFolderPath));
        $oRenameNode->addAttribute("newUrl", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($newFolderUrl));
    }
}
