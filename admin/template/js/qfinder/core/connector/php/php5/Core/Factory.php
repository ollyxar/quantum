<?php

if (!defined('IN_QFINDER')) exit;

class QFinder_Connector_Core_Factory
{
    static $instances = array();

    /**
     * Initiate factory
     * @static
     */
    static function initFactory()
    {
    }

    /**
     * Get instance of specified class
     * Short and Long class names are possible
     * <code>
     * $obj1 =& QFinder_Connector_Core_Factory::getInstance("Qfinder_Connector_Core_Xml");
     * $obj2 =& QFinder_Connector_Core_Factory::getInstance("Core_Xml");
     * </code>
     *
     * @param string $className class name
     * @static
     * @access public
     * @return object
     */
    public static function &getInstance($className)
    {
        $namespace = "QFinder_Connector_";

        $baseName = str_replace($namespace,"",$className);

        $className = $namespace.$baseName;

        if (!isset(QFinder_Connector_Core_Factory::$instances[$className])) {
            require_once QFINDER_CONNECTOR_LIB_DIR . "/" . str_replace("_","/",$baseName).".php";
            QFinder_Connector_Core_Factory::$instances[$className] = new $className;
        }

        return QFinder_Connector_Core_Factory::$instances[$className];
    }
}
