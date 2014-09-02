<?php

if (!defined('IN_QFINDER')) exit;

class QFinder_Connector_CommandHandler_DownloadFile extends QFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "DownloadFile";

    /**
     * send response (file)
     * @access public
     *
     */
    public function sendResponse()
    {
        if (!function_exists('ob_list_handlers') || ob_list_handlers()) {
            @ob_end_clean();
        }
        header("Content-Encoding: none");

        $this->checkConnector();
        $this->checkRequest();

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $fileName = QFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["FileName"]);
        $_resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!QFinder_Connector_Utils_FileSystem::checkFileName($fileName)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        if (!$_resourceTypeInfo->checkExtension($fileName, false)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $filePath = QFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);
        if ($_resourceTypeInfo->checkIsHiddenFile($fileName) || !file_exists($filePath) || !is_file($filePath)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        $fileName = QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($fileName);

        header("Cache-Control: cache, must-revalidate");
        header("Pragma: public");
        header("Expires: 0");
        if (!empty($_GET['format']) && $_GET['format'] == 'text') {
            header("Content-Type: text/plain; charset=utf-8");
        }
        else {
            $user_agent = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
            $encodedName = str_replace("\"", "\\\"", $fileName);
            if (strpos($user_agent, "MSIE") !== false) {
                $encodedName = str_replace(array("+", "%2E"), array(" ", "."), urlencode($encodedName));
            }
            header("Content-type: application/octet-stream; name=\"" . $fileName . "\"");
            header("Content-Disposition: attachment; filename=\"" . $encodedName. "\"");
        }
        header("Content-Length: " . filesize($filePath));
        QFinder_Connector_Utils_FileSystem::sendFile($filePath);
        exit;
    }
}
