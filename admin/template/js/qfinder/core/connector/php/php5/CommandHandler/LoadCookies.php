<?php

if (!defined('IN_QFINDER')) exit;

/**
 * Include base XML command handler
 */
require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class QFinder_Connector_CommandHandler_LoadCookies extends QFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "LoadCookies";


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

        if (!$this->_currentFolder->checkAcl(QFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(QFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $oCookiesNode = new Qfinder_Connector_Utils_XmlNode("Cookies");
        $this->_connectorNode->addChild($oCookiesNode);
        $i = 0;
        foreach ($_COOKIE as $name => $value) {
            if (!is_array($value) && strpos($name, "QFinder_") !== 0) {
                $oCookieNode[$i] = new Qfinder_Connector_Utils_XmlNode("Cookie");
                $oCookiesNode->addChild($oCookieNode[$i]);
                $oCookieNode[$i]->addAttribute("name", $name);
                $oCookieNode[$i]->addAttribute("value", $value);
                $i++;
            }
        }
    }
}
