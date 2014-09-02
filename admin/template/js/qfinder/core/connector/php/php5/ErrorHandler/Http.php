<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/ErrorHandler/Base.php";

class QFinder_Connector_ErrorHandler_Http extends QFinder_Connector_ErrorHandler_Base
{
    /**
     * Throw file upload error, return true if error has been thrown, false if error has been catched
     *
     * @param int $number
     * @param string $text
     * @access public
     */
    public function throwError($number, $text = false, $exit = true)
    {
        if ($this->_catchAllErrors || in_array($number, $this->_skipErrorsArray)) {
            return false;
        }

        switch ($number)
        {
            case QFINDER_CONNECTOR_ERROR_INVALID_REQUEST:
            case QFINDER_CONNECTOR_ERROR_INVALID_NAME:
            case QFINDER_CONNECTOR_ERROR_THUMBNAILS_DISABLED:
            case QFINDER_CONNECTOR_ERROR_UNAUTHORIZED:
                header("HTTP/1.0 403 Forbidden");
                header("X-QFinder-Error: ". $number);
                break;

            case QFINDER_CONNECTOR_ERROR_ACCESS_DENIED:
                header("HTTP/1.0 500 Internal Server Error");
                header("X-QFinder-Error: ".$number);
                break;

            default:
                header("HTTP/1.0 404 Not Found");
                header("X-QFinder-Error: ". $number);
                break;
        }

        if ($exit) {
            exit;
        }
    }
}
