<?php

if (!defined('IN_QFINDER')) exit;


/**
 * Include base XML command handler
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";


class QFinder_Connector_CommandHandler_GetFiles extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "GetFiles";

    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml()
    {
        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        // Map the virtual path to the local server path.
        $_sServerDir = $this->_currentFolder->getServerPath();

        // Create the "Files" node.
        $oFilesNode = new Qfinder_Connector_Utils_XmlNode("Files");
        $this->_connectorNode->addChild($oFilesNode);

        if (!is_dir($_sServerDir)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND);
        }

        $files = array();
        $thumbFiles = array();

        if ($dh = @opendir($_sServerDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != ".." && !is_dir($_sServerDir . $file)) {
                    $files[] = $file;
                }
            }
            closedir($dh);
        } else {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (sizeof($files)>0) {
            $_thumbnailsConfig = $_config->getThumbnailsConfig();
            $_thumbServerPath = '';
            $_showThumbs = (!empty($_GET['showThumbs']) && $_GET['showThumbs'] == 1);
            if ($_thumbnailsConfig->getIsEnabled() && ($_thumbnailsConfig->getDirectAccess() || $_showThumbs)) {
                $_thumbServerPath = $this->_currentFolder->getThumbsServerPath();
            }

            natcasesort($files);
            $i=0;
            foreach ($files as $file) {
                $filemtime = @filemtime($_sServerDir . $file);

                //otherwise file doesn't exist or we can't get it's filename properly
                if ($filemtime !== false) {
                    $filename = QFinder_Connector_Utils_Misc::mbBasename($file);
                    if (!$resourceTypeInfo->checkExtension($filename, false)) {
                        continue;
                    }
                    if ($resourceTypeInfo->checkIsHiddenFile($filename)) {
                        continue;
                    }
                    $oFileNode[$i] = new Qfinder_Connector_Utils_XmlNode("File");
                    $oFilesNode->addChild($oFileNode[$i]);
                    $oFileNode[$i]->addAttribute("name", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding(QFinder_Connector_Utils_Misc::mbBasename($file)));
                    $oFileNode[$i]->addAttribute("date", @date("YmdHi", $filemtime));
                    if (!empty($_thumbServerPath) && preg_match(QFINDER_REGEX_IMAGES_EXT, $filename)) {
                        if (file_exists($_thumbServerPath . $filename)) {
                            $oFileNode[$i]->addAttribute("thumb", $filename);
                        }
                        elseif ($_showThumbs) {
                            $oFileNode[$i]->addAttribute("thumb", "?" . $filename);
                        }
                    }
                    $size = filesize($_sServerDir . $file);
                    if ($size && $size<1024) {
                        $size = 1;
                    }
                    else {
                        $size = (int)round($size / 1024);
                    }
                    $oFileNode[$i]->addAttribute("size", $size);
                    $i++;
                }
            }
        }
    }
}
