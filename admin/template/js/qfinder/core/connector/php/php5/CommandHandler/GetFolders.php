<?php

if (!defined('IN_QFINDER')) exit;

/**
 * Include base XML command handler
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";


class QFinder_Connector_CommandHandler_GetFolders extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "GetFolders";

    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml()
    {
        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FOLDER_VIEW)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        // Map the virtual path to the local server path.
        $_sServerDir = $this->_currentFolder->getServerPath();

        if (!is_dir($_sServerDir)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND);
        }

        // Create the "Folders" node.
        $oFoldersNode = new Qfinder_Connector_Utils_XmlNode("Folders");
        $this->_connectorNode->addChild($oFoldersNode);

        $files = array();
        if ($dh = @opendir($_sServerDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != ".." && is_dir($_sServerDir . $file)) {
                    $files[] = $file;
                }
            }
            closedir($dh);
        } else {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (sizeof($files)>0) {
            natcasesort($files);
            $i=0;
            foreach ($files as $file) {
                $oAcl = $_config->getAccessControlConfig();
                $folderPath = $this->_currentFolder->getClientPath() . $file . "/";
                $aclMask = $oAcl->getComputedMask($this->_currentFolder->getResourceTypeName(), $folderPath);

                if (($aclMask & QFINDER_CONNECTOR_ACL_FOLDER_VIEW) != QFINDER_CONNECTOR_ACL_FOLDER_VIEW) {
                    continue;
                }
                if ($resourceTypeInfo->checkIsHiddenFolder($file)) {
                    continue;
                }

                // Create the "Folder" node.
                $oFolderNode[$i] = new Qfinder_Connector_Utils_XmlNode("Folder");
                $oFoldersNode->addChild($oFolderNode[$i]);
                $oFolderNode[$i]->addAttribute("name", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($file));
                $oFolderNode[$i]->addAttribute("hasChildren", QFinder_Connector_Utils_FileSystem::hasChildren($folderPath, $resourceTypeInfo) ? "true" : "false");
                $oFolderNode[$i]->addAttribute("acl", $aclMask);

                $i++;
            }
        }
    }
}
