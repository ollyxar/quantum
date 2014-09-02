<?php

if (!defined('IN_QFINDER')) exit;

require_once QFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/CommandHandlerBase.php";

require_once QFINDER_CONNECTOR_LIB_DIR . "/Core/Xml.php";

abstract class QFinder_Connector_CommandHandler_XmlCommandHandlerBase extends QFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * Connector node - Qfinder_Connector_Utils_XmlNode object
     *
     * @var Qfinder_Connector_Utils_XmlNode
     * @access protected
     */
    protected $_connectorNode;

    /**
     * send response
     * @access public
     *
     */
    public function sendResponse()
    {
        $xml =& QFinder_Connector_Core_Factory::getInstance("Core_Xml");
        $this->_connectorNode =& $xml->getConnectorNode();

        $this->checkConnector();
        if ($this->mustCheckRequest()) {
            $this->checkRequest();
        }

        $resourceTypeName = $this->_currentFolder->getResourceTypeName();
        if (!empty($resourceTypeName)) {
            $this->_connectorNode->addAttribute("resourceType", $this->_currentFolder->getResourceTypeName());
        }

        if ($this->mustAddCurrentFolderNode()) {
            $_currentFolder = new Qfinder_Connector_Utils_XmlNode("CurrentFolder");
            $this->_connectorNode->addChild($_currentFolder);
            $_currentFolder->addAttribute("path", QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($this->_currentFolder->getClientPath()));

            $this->_errorHandler->setCatchAllErros(true);
            $_url = $this->_currentFolder->getUrl();
            $_currentFolder->addAttribute("url", is_null($_url) ? "" : QFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($_url));
            $this->_errorHandler->setCatchAllErros(false);

            $_currentFolder->addAttribute("acl", $this->_currentFolder->getAclMask());
        }

        $this->buildXml();

        $_oErrorNode =& $xml->getErrorNode();
        $_oErrorNode->addAttribute("number", "0");

        echo $this->_connectorNode->asXML();
        exit;
    }

    /**
     * Must check request?
     *
     * @return boolean
     * @access protected
     */
    protected function mustCheckRequest()
    {
        return true;
    }

    /**
     * Must add CurrentFolder node?
     *
     * @return boolean
     * @access protected
     */
    protected function mustAddCurrentFolderNode()
    {
        return true;
    }

    /**
     * @access protected
     * @abstract
     * @return void
     */
    abstract protected function buildXml();
}
