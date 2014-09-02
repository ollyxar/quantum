<?php

if (!defined('IN_QFINDER')) exit;

/**
 * Include file upload command handler
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/FileUpload.php";

class QFinder_Connector_CommandHandler_QuickUpload extends QFinder_Connector_CommandHandler_FileUpload
{
    /**
     * Command name
     *
     * @access protected
     * @var string
     */
    protected $command = "QuickUpload";

    function sendResponse()
    {
        $oRegistry =& QFinder_Connector_Core_Factory::getInstance("Core_Registry");
        $oRegistry->set("FileUpload_url", $this->_currentFolder->getUrl());

        return parent::sendResponse();
    }
}
