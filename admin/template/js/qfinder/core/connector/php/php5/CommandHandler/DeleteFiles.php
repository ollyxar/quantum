<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_DeleteFiles extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "DeleteFiles";


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

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_DELETE)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $oErrorsNode = new QFinder_Connector_Utils_XmlNode("Errors");
        $errorCode = QFINDER_CONNECTOR_ERROR_NONE;
        $deleted = 0;
        $oDeleteFilesNode = new Qfinder_Connector_Utils_XmlNode("DeleteFiles");

        $currentResourceTypeConfig = $this->_currentFolder->getResourceTypeConfig();
        $_config = & QFinder_Connector_Core_Factory::getInstance("Core_Config");
        $_aclConfig = $_config->getAccessControlConfig();
        $aclMasks = array();
        $_resourceTypeConfig = array();
        $checkedPaths = array();

        if (!empty($_POST['files']) && is_array($_POST['files'])) {
            foreach ($_POST['files'] as $arr) {
                if (empty($arr['name'])) {
                    continue;
                }
                if (!isset($arr['type'], $arr['folder'])) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                // file name
                $name = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($arr['name']);
                // resource type
                $type = $arr['type'];
                // client path
                $path = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($arr['folder']);

                if (!isset($_resourceTypeConfig[$type])) {
                    $_resourceTypeConfig[$type] = $_config->getResourceTypeConfig($type);
                }

                if (is_null($_resourceTypeConfig[$type]) || !QFinder_Connector_Utils_FileSystem::checkFileName($name) || preg_match(QFINDER_REGEX_INVALID_PATH, $path)) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                if (!$_resourceTypeConfig[$type]->checkExtension($name, false)) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                if (empty($checkedPaths[$path])) {
                    $checkedPaths[$path] = true;

                    if ($_resourceTypeConfig[$type]->checkIsHiddenPath($path)) {
                        $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                    }
                }

                if ($currentResourceTypeConfig->checkIsHiddenFile($name)) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                if (!isset($aclMasks[$type . "@" . $path])) {
                    $aclMasks[$type . "@" . $path] = $_aclConfig->getComputedMask($type, $path);
                }

                $isAuthorized = (($aclMasks[$type . "@" . $path] & QFINDER_CONNECTOR_ACL_FILE_DELETE) == QFINDER_CONNECTOR_ACL_FILE_DELETE);
                if (!$isAuthorized) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
                }

                $filePath = $_resourceTypeConfig[$type]->getDirectory() . $path . $name;

                if (!file_exists($filePath) || !is_file($filePath)) {
                    $errorCode = QFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND;
                    $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                    continue;
                }

                if (!QFinder_Connector_Utils_FileSystem::unlink($filePath)) {
                    $errorCode = QFINDER_CONNECTOR_ERROR_ACCESS_DENIED;
                    $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                    continue;
                } else {
                    $deleted++;

                    $thumbPath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getThumbsServerPath(), $name);

                    @unlink($thumbPath);
                }
            }
        }

        $this->_connectorNode->addChild($oDeleteFilesNode);
        if ($errorCode != QFINDER_CONNECTOR_ERROR_NONE) {
            $this->_connectorNode->addChild($oErrorsNode);
        }
        $oDeleteFilesNode->addAttribute("deleted", $deleted);

        if ($errorCode != QFINDER_CONNECTOR_ERROR_NONE) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_DELETE_FAILED);
        }
    }

    private function appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path)
    {
        $oErrorNode = new QFinder_Connector_Utils_XmlNode("Error");
        $oErrorNode->addAttribute("code", $errorCode);
        $oErrorNode->addAttribute("name", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($name));
        $oErrorNode->addAttribute("type", $type);
        $oErrorNode->addAttribute("folder", $path);
        $oErrorsNode->addChild($oErrorNode);
    }
}
