<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_CopyFiles extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "CopyFiles";


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

        $clientPath = $this->_currentFolder->getClientPath();
        $sServerDir = $this->_currentFolder->getServerPath();
        $currentResourceTypeConfig = $this->_currentFolder->getResourceTypeConfig();
        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        $_aclConfig = $_config->getAccessControlConfig();
        $aclMasks = array();
        $_resourceTypeConfig = array();

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_RENAME | QFINDER_CONNECTOR_ACL_FILE_UPLOAD | QFINDER_CONNECTOR_ACL_FILE_DELETE)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        // Create the "Errors" node.
        $oErrorsNode = new QFinder_Connector_Utils_XmlNode("Errors");
        $errorCode = QFINDER_CONNECTOR_ERROR_NONE;
        $copied = 0;
        $copiedAll = 0;
        if (!empty($_POST['copied'])) {
            $copiedAll = intval($_POST['copied']);
        }
        $checkedPaths = array();

        $oCopyFilesNode = new Qfinder_Connector_Utils_XmlNode("CopyFiles");

        if (!empty($_POST['files']) && is_array($_POST['files'])) {
            foreach ($_POST['files'] as $index => $arr) {
                if (empty($arr['name'])) {
                    continue;
                }
                if (!isset($arr['name'], $arr['type'], $arr['folder'])) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                // file name
                $name = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($arr['name']);
                // resource type
                $type = $arr['type'];
                // client path
                $path = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($arr['folder']);
                // options
                $options = (!empty($arr['options'])) ? $arr['options'] : '';

                $destinationFilePath = $sServerDir.$name;

                // check #1 (path)
                if (!QFinder_Connector_Utils_FileSystem::checkFileName($name) || preg_match(QFINDER_REGEX_INVALID_PATH, $path)) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                // get resource type config for current file
                if (!isset($_resourceTypeConfig[$type])) {
                    $_resourceTypeConfig[$type] = $_config->getResourceTypeConfig($type);
                }

                // check #2 (resource type)
                if (is_null($_resourceTypeConfig[$type])) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                // check #3 (extension)
                if (!$_resourceTypeConfig[$type]->checkExtension($name, false)) {
                    $errorCode = QFINDER_CONNECTOR_ERROR_INVALID_EXTENSION;
                    $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                    continue;
                }

                // check #4 (extension) - when moving to another resource type, double check extension
                if ($currentResourceTypeConfig->getName() != $type) {
                    if (!$currentResourceTypeConfig->checkExtension($name, false)) {
                        $errorCode = QFINDER_CONNECTOR_ERROR_INVALID_EXTENSION;
                        $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                        continue;
                    }
                }

                // check #5 (hidden folders)
                // cache results
                if (empty($checkedPaths[$path])) {
                    $checkedPaths[$path] = true;

                    if ($_resourceTypeConfig[$type]->checkIsHiddenPath($path)) {
                        $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                    }
                }

                $sourceFilePath = $_resourceTypeConfig[$type]->getDirectory().$path.$name;

                // check #6 (hidden file name)
                if ($currentResourceTypeConfig->checkIsHiddenFile($name)) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
                }

                // check #7 (Access Control, need file view permission to source files)
                if (!isset($aclMasks[$type."@".$path])) {
                    $aclMasks[$type."@".$path] = $_aclConfig->getComputedMask($type, $path);
                }

                $isAuthorized = (($aclMasks[$type."@".$path] & QFINDER_CONNECTOR_ACL_FILE_VIEW) == QFINDER_CONNECTOR_ACL_FILE_VIEW);
                if (!$isAuthorized) {
                    $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
                }

                // check #8 (invalid file name)
                if (!file_exists($sourceFilePath) || !is_file($sourceFilePath)) {
                    $errorCode = QFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND;
                    $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                    continue;
                }

                // check #9 (max size)
                if ($currentResourceTypeConfig->getName() != $type) {
                    $maxSize = $currentResourceTypeConfig->getMaxSize();
                    $fileSize = filesize($sourceFilePath);
                    if ($maxSize && $fileSize>$maxSize) {
                        $errorCode = QFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG;
                        $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                        continue;
                    }
                }

                //$overwrite
                // finally, no errors so far, we may attempt to copy a file
                // protection against copying files to itself
                if ($sourceFilePath == $destinationFilePath) {
                    $errorCode = QFINDER_CONNECTOR_ERROR_SOURCE_AND_TARGET_PATH_EQUAL;
                    $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                    continue;
                }
                // check if file exists if we don't force overwriting
                else if (file_exists($destinationFilePath) && strpos($options, "overwrite") === false) {
                    if (strpos($options, "autorename") !== false) {
                        $fileName = QFinder_Connector_Utils_FileSystem::autoRename($sServerDir, $name);
                        $destinationFilePath = $sServerDir.$fileName;
                        if (!@copy($sourceFilePath, $destinationFilePath)) {
                            $errorCode = QFINDER_CONNECTOR_ERROR_ACCESS_DENIED;
                            $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                            continue;
                        }
                        else {
                            $copied++;
                        }
                    }
                    else {
                        $errorCode = QFINDER_CONNECTOR_ERROR_ALREADY_EXIST;
                        $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                        continue;
                    }
                }
                // copy() overwrites without warning
                else {
                    if (!@copy($sourceFilePath, $destinationFilePath)) {
                        $errorCode = QFINDER_CONNECTOR_ERROR_ACCESS_DENIED;
                        $this->appendErrorNode($oErrorsNode, $errorCode, $name, $type, $path);
                        continue;
                    }
                    else {
                        $copied++;
                    }
                }
            }
        }

        $this->_connectorNode->addChild($oCopyFilesNode);
        if ($errorCode != QFINDER_CONNECTOR_ERROR_NONE) {
            $this->_connectorNode->addChild($oErrorsNode);
        }
        $oCopyFilesNode->addAttribute("copied", $copied);
        $oCopyFilesNode->addAttribute("copiedTotal", $copiedAll + $copied);

        /**
         * Note: actually we could have more than one error.
         * This is just a flag for QFinder interface telling it to check all errors.
         */
        if ($errorCode != QFINDER_CONNECTOR_ERROR_NONE) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_COPY_FAILED);
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
