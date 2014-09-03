<?php

if (!defined('IN_QFINDER')) exit;

class Qfinder_Connector_Utils_XmlNode
{
    /**
     * Array that stores XML attributes
     *
     * @access private
     * @var array
     */
    private $_attributes = array();
    /**
     * Array that stores child nodes
     *
     * @access private
     * @var array
     */
    private $_childNodes = array();
    /**
     * Node name
     *
     * @access private
     * @var string
     */
    private $_name;
    /**
     * Node value
     *
     * @access private
     * @var string
     */
    private $_value;

    /**
     * Create new node
     *
     * @param string $nodeName node name
     * @param string $nodeValue node value
     * @return Qfinder_Connector_Utils_XmlNode
     */
    function __construct($nodeName, $nodeValue = null)
    {
        $this->_name = $nodeName;
        if (!is_null($nodeValue)) {
            $this->_value = $nodeValue;
        }
    }

    function getChild($name)
    {
        foreach ($this->_childNodes as $i => $node) {
            if ($node->_name == $name) {
                return $this->_childNodes[$i];
            }
        }
        return null;
    }

    /**
     * Add attribute
     *
     * @param string $name
     * @param string $value
     * @access public
     */
    public function addAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * Get attribute value
     *
     * @param string $name
     * @access public
     */
    public function getAttribute($name)
    {
        return $this->_attributes[$name];
    }

    /**
     * Set element value
     *
     * @param string $name
     * @param string $value
     * @access public
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * Get element value
     *
     * @param string $name
     * @param string $value
     * @access public
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Adds new child at the end of the children
     *
     * @param Qfinder_Connector_Utils_XmlNode $node
     * @access public
     */
    public function addChild(&$node)
    {
        $this->_childNodes[] =& $node;
    }

    /**
     * Checks whether the string is valid UTF8
     * @param string $string
     */
    public function asUTF8($string)
    {
        if (QFinder_Connector_Utils_Misc::isValidUTF8($string)) {
            return $string;
        }

        $ret = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $ret .= QFinder_Connector_Utils_Misc::isValidUTF8($string[$i]) ? $string[$i] : "\xEF\xBF\xBD";
        }

        return $ret;
    }

    /**
     * Return a well-formed XML string based on Qfinder_Connector_Utils_XmlNode element
     *
     * @return string
     * @access public
     */
    public function asXML()
    {
        $ret = "<" . $this->_name;

        //print Attributes
        if (sizeof($this->_attributes)>0) {
            foreach ($this->_attributes as $_name => $_value) {
                $ret .= " " . $_name . '="' . htmlspecialchars($this->asUTF8($_value)) . '"';
            }
        }

        //if there is nothing more todo, close empty tag and exit
        if (is_null($this->_value) && !sizeof($this->_childNodes)) {
            $ret .= " />";
            return $ret;
        }

        //close opening tag
        $ret .= ">";

        //print value
        if (!is_null($this->_value)) {
            $ret .= htmlspecialchars($this->asUTF8($this->_value));
        }

        //print child nodes
        if (sizeof($this->_childNodes)>0) {
            foreach ($this->_childNodes as $_node) {
                $ret .= $_node->asXml();
            }
        }

        $ret .= "</" . $this->_name . ">";

        return $ret;
    }
}