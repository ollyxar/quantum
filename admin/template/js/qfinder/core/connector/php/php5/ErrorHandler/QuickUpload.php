<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/ErrorHandler/Base.php";

class QFinder_Connector_ErrorHandler_QuickUpload extends QFinder_Connector_ErrorHandler_Base {
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

		/**
		 * echo <script> is not called before QFinder_Connector_Utils_Misc::getErrorMessage
		 * because PHP has problems with including files that contain BOM character.
		 * Having BOM character after <script> tag causes a javascript error.
		 */
        echo "<script type=\"text/javascript\">";
        if (!empty($_GET['CKEditor'])) {
            $errorMessage = QFinder_Connector_Utils_Misc::getErrorMessage($number, $sEncodedFileName);

            if (!$uploaded) {
                $sFileUrl = "";
                $sFileName = "";
                $sEncodedFileName = "";
            }

            $funcNum = preg_replace("/[^0-9]/", "", $_GET['CKEditorFuncNum']);
            echo "window.parent.CKEDITOR.tools.callFunction($funcNum, '" . str_replace("'", "\\'", $sFileUrl . QFinder_Connector_Utils_Misc::encodeURIComponent($sEncodedFileName)) . "', '" .str_replace("'", "\\'", $errorMessage). "');";
        }
        else {
            if (!$uploaded) {
                echo "window.parent.OnUploadCompleted(" . $number . ", '', '', '') ;";
            } else {
                echo "window.parent.OnUploadCompleted(" . $number . ", '" . str_replace("'", "\\'", $sFileUrl . QFinder_Connector_Utils_Misc::encodeURIComponent($sEncodedFileName)) . "', '" . str_replace("'", "\\'", $sEncodedFileName) . "', '') ;";
            }
        }
        echo "</script>";

        if ($exit) {
            exit;
        }
    }
}
