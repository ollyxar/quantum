<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_CreateFolder extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "CreateFolder";

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

        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FOLDER_CREATE)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $_resourceTypeConfig = $this->_currentFolder->getResourceTypeConfig();
        $sNewFolderName = isset($_GET["NewFolderName"]) ? $_GET["NewFolderName"] : "";
        $sNewFolderName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($sNewFolderName);
        if ($_config->forceAscii()) {
            $sNewFolderName = QFinder_Connector_Utils_FileSystem::convertToAscii($sNewFolderName);
        }

        if (!QFinder_Connector_Utils_FileSystem::checkFolderName($sNewFolderName) || $_resourceTypeConfig->checkIsHiddenFolder($sNewFolderName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $sServerDir = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $sNewFolderName);
        if (!is_writeable($this->_currentFolder->getServerPath())) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $bCreated = false;

        if (file_exists($sServerDir)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ALREADY_EXIST);
        }

        if ($perms = $_config->getChmodFolders()) {
            $oldUmask = umask(0);
            $bCreated = @mkdir($sServerDir, $perms);
            umask($oldUmask);
        }
        else {
            $bCreated = @mkdir($sServerDir);
        }

        if (!$bCreated) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        } else {
            $oNewFolderNode = new Qfinder_Connector_Utils_XmlNode("NewFolder");
            $this->_connectorNode->addChild($oNewFolderNode);
            $oNewFolderNode->addAttribute("name", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($sNewFolderName));
        }
    }
}
