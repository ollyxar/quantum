<?php

if (!defined('IN_QFINDER')) exit;

class QFinder_Connector_Core_Registry
{
    /**
     * Arrat that stores all values
     *
     * @var array
     * @access private
     */
    private $_store = array();

    /**
     * Chacke if value has been set
     *
     * @param string $key
     * @return boolean
     * @access private
     */
    private function isValid($key)
    {
        return array_key_exists($key, $this->_store);
    }

    /**
     * Set value
     *
     * @param string $key
     * @param mixed $obj
     * @access public
     */
    public function set($key, $obj)
    {
        $this->_store[$key] = $obj;
    }

    /**
     * Get value
     *
     * @param string $key
     * @return mixed
     * @access public
     */
    public function get($key)
    {
    	if ($this->isValid($key)) {
    	    return $this->_store[$key];
    	}
    }
}
