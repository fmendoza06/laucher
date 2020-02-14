<?php


/**
*   Copyright 2006 - Spyro Solutions
*   
*   @author Spyro Solutions - Jose Fernando Mendoza
*   @date 14-Dic-2006 10:43:34
*   @location Cali-Colombia
*/  


require_once "Serializer.class.php";

class LITLE
{

   
    /*
	* Return de httdocs directory
	*/
    public static function getLITLEDirectory() {
        return realpath(dirname(__FILE__)."/../../.");
    }
    
    function getPluginsDirectory() {
        // must be set by configuration
        return LITLE::getLITLEDirectory()."/system/plugins";
    }
    
    /**
    * If you need static properties, you can use this method
    * to simulate them. Eg. in your method(s)
    * do this: $myVar = &LITLE::getStaticProperty('myVar');
    * You MUST use a reference, or they will not persist!
    *
    * @access public
    * @param  string $class  The calling classname, to prevent clashes
    * @param  string $var    The variable to retrieve.
    * @return mixed   A reference to the variable. If not set it will be
    *                 auto initialised to NULL.
    */
     public static function &getStaticProperty($class, $var)
    {
		static $properties;

		return $properties[$class][$var];
    }
	
	
    /**
    *
    * @access public
    * @param  string $nom_var  config var n saveconfiguration file serialized into system.conf.data
    * @return mixed   A reference to the attribute into saveconfigurationfile. 
    */
     public static function __getVar($nom_var) {

        $config = LITLE::getStaticProperty('LITLE','config');
        
        // if configuration data is not set
        if (!isset($config)) {
		     //print_r("<br>No esta Seteada __getVar	 jj ->".(dirname(dirname(__FILE__)))."<br>");
            // load the configuration data
            // filename = <LITLE-APP-dir>/config/system.conf.data  getLITLEDirectory
            $config = Serializer::load(dirname(__FILE__).'./../../..'. '/config/system.conf.data');
        }
        if (!is_array($config)) {
            return PEAR::raiseError('cannot load the configuration file');
        }

        return $config[$nom_var];
    }
    
}

?>
