<?php

if (!defined('IN_QFINDER')) exit;

class QFinder_Connector_CommandHandler_FileUpload extends QFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * Command name
     *
     * @access protected
     * @var string
     */
    protected $command = "FileUpload";

    /**
     * send response (save uploaded file, resize if required)
     * @access public
     *
     */
    public function sendResponse()
    {
        $iErrorNumber = QFINDER_CONNECTOR_ERROR_NONE;

        $_config =& QFinder_Connector_Core_Factory::getInstance("Core_Config");
        $oRegistry =& QFinder_Connector_Core_Factory::getInstance("Core_Registry");
        $oRegistry->set("FileUpload_fileName", "unknown file");

        $uploadedFile = array_shift($_FILES);

        if (!isset($uploadedFile['name'])) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_INVALID);
        }

        $sUnsafeFileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding(QFinder_Connector_Utils_Misc::mbBasename($uploadedFile['name']));
        $sFileName = QFinder_Connector_Utils_FileSystem::secureFileName($sUnsafeFileName);

        if ($sFileName != $sUnsafeFileName) {
          $iErrorNumber = QFINDER_CONNECTOR_ERROR_UPLOADED_INVALID_NAME_RENAMED;
        }
        $oRegistry->set("FileUpload_fileName", $sFileName);

        $this->checkConnector();
        $this->checkRequest();

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_UPLOAD)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $_resourceTypeConfig = $this->_currentFolder->getResourceTypeConfig();
        if (!QFinder_Connector_Utils_FileSystem::checkFileName($sFileName) || $_resourceTypeConfig->checkIsHiddenFile($sFileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();
        if (!$resourceTypeInfo->checkExtension($sFileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_EXTENSION);
        }

        $oRegistry->set("FileUpload_fileName", $sFileName);
        $oRegistry->set("FileUpload_url", $this->_currentFolder->getUrl());

        $maxSize = $resourceTypeInfo->getMaxSize();
        if (!$_config->checkSizeAfterScaling() && $maxSize && $uploadedFile['size']>$maxSize) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG);
        }

        $htmlExtensions = $_config->getHtmlExtensions();
        $sExtension = QFinder_Connector_Utils_FileSystem::getExtension($sFileName);

        if ($htmlExtensions
        && !QFinder_Connector_Utils_Misc::inArrayCaseInsensitive($sExtension, $htmlExtensions)
        && ($detectHtml = QFinder_Connector_Utils_FileSystem::detectHtml($uploadedFile['tmp_name'])) === true ) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_WRONG_HTML_FILE);
        }

        $secureImageUploads = $_config->getSecureImageUploads();
        if ($secureImageUploads
        && ($isImageValid = QFinder_Connector_Utils_FileSystem::isImageValid($uploadedFile['tmp_name'], $sExtension)) === false ) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_CORRUPT);
        }

        switch ($uploadedFile['error']) {
            case UPLOAD_ERR_OK:
                break;

            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG);
                break;

            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_CORRUPT);
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_NO_TMP_DIR);
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
                break;

            case UPLOAD_ERR_EXTENSION:
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
                break;
        }

        $sServerDir = $this->_currentFolder->getServerPath();

        while (true)
        {
            $sFilePath = QFinder_Connector_Utils_FileSystem::combinePaths($sServerDir, $sFileName);

            if (file_exists($sFilePath)) {
                $sFileName = QFinder_Connector_Utils_FileSystem::autoRename($sServerDir, $sFileName);
                $oRegistry->set("FileUpload_fileName", $sFileName);

                $iErrorNumber = QFINDER_CONNECTOR_ERROR_UPLOADED_FILE_RENAMED;
            } else {
                if (false === move_uploaded_file($uploadedFile['tmp_name'], $sFilePath)) {
                    $iErrorNumber = QFINDER_CONNECTOR_ERROR_ACCESS_DENIED;
                }
                else {
                    if (isset($detectHtml) && $detectHtml === -1 && QFinder_Connector_Utils_FileSystem::detectHtml($sFilePath) === true) {
                        @unlink($sFilePath);
                        $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_WRONG_HTML_FILE);
                    }
                    else if (isset($isImageValid) && $isImageValid === -1 && QFinder_Connector_Utils_FileSystem::isImageValid($sFilePath, $sExtension) === false) {
                        @unlink($sFilePath);
                        $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_CORRUPT);
                    }
                }
                if (is_file($sFilePath) && ($perms = $_config->getChmodFiles())) {
                    $oldumask = umask(0);
                    chmod($sFilePath, $perms);
                    umask($oldumask);
                }
                break;
            }
        }

        if (!$_config->checkSizeAfterScaling()) {
            $this->_errorHandler->throwError($iErrorNumber, true, false);
        }

        //resize image if required
        require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/Thumbnail.php";
        $_imagesConfig = $_config->getImagesConfig();

        if ($_imagesConfig->getMaxWidth()>0 && $_imagesConfig->getMaxHeight()>0 && $_imagesConfig->getQuality()>0) {
            QFinder_Connector_CommandHandler_Thumbnail::createThumb($sFilePath, $sFilePath, $_imagesConfig->getMaxWidth(), $_imagesConfig->getMaxHeight(), $_imagesConfig->getQuality(), true) ;
        }

        if ($_config->checkSizeAfterScaling()) {
            //check file size after scaling, attempt to delete if too big
            clearstatcache();
            if ($maxSize && filesize($sFilePath)>$maxSize) {
                @unlink($sFilePath);
                $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG);
            }
            else {
                $this->_errorHandler->throwError($iErrorNumber, true, false);
            }
        }

        QFinder_Connector_Core_Hooks::run('AfterFileUpload', array(&$this->_currentFolder, &$uploadedFile, &$sFilePath));
    }
}
