<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/ErrorHandler/Base.php";

class QFinder_Connector_ErrorHandler_FileUpload extends QFinder_Connector_ErrorHandler_Base {
    /**
     * Throw file upload error, return true if error has been thrown, false if error has been catched
     *
     * @param int $number
     * @param string $text
     * @access public
     */
    public function throwError($number, $uploaded = false, $exit = true) {
        if ($this->_catchAllErrors || in_array($number, $this->_skipErrorsArray)) {
            return false;
        }

        $oRegistry = & QFinder_Connector_Core_Factory :: getInstance("Core_Registry");
        $sFileName = $oRegistry->get("FileUpload_fileName");
        $sFileUrl = $oRegistry->get("FileUpload_url");
        $sEncodedFileName = QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($sFileName);

        header('Content-Type: text/html; charset=utf-8');

        $errorMessage = QFinder_Connector_Utils_Misc::getErrorMessage($number, $sEncodedFileName);
        if (!$uploaded) {
            $sFileName = "";
            $sEncodedFileName = "";
        }
        if (!empty($_GET['response_type']) && $_GET['response_type'] == 'txt') {
            echo $sFileName."|".$errorMessage;
        }
        else {
            echo "<script type=\"text/javascript\">";
            if (!empty($_GET['QFinderFuncNum'])) {

                if (!$uploaded) {
                    $sFileUrl = "";
                    $sFileName = "";
                }

                $funcNum = preg_replace("/[^0-9]/", "", $_GET['QFinderFuncNum']);
                echo "window.parent.QFinder.tools.callFunction($funcNum, '" . str_replace("'", "\\'", $sFileUrl . $sFileName) . "', '" .str_replace("'", "\\'", $errorMessage). "');";
            }
            else {
                echo "window.parent.OnUploadCompleted('" . str_replace("'", "\\'", $sEncodedFileName) . "', '" . str_replace("'", "\\'", $errorMessage) . "') ;";
            }
            echo "</script>";
        }

        if ($exit) {
            exit;
        }
    }
}
