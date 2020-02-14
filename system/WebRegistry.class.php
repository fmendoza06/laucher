<?php


/**
*   Copyright 2006 - Spyro Solutions
*   
*   @author Spyro Solutions - Jose Fernando Mendoza
*   @date 14-Dic-2006 10:43:34
*   @location Cali-Colombia
*/    
    
require_once "WebSession.class.php";

class WebRegistry
{
  
    public static function setLog()
    {
        $log = Application::generatelog();
        if ($log){
            return $log;
        }
    }
    public static function getWebCommand() 
	{

        // set the action name
        $action_name = isset($_REQUEST["action"]) ?
            $_REQUEST["action"]:
            WebRegistry::getDefaultAction();
            //print_r($action_name);
        // Presenta pantalla Optimizado solo Chrome
            //print_r($_SERVER["HTTP_USER_AGENT"]);
        //if (!strstr($_SERVER["HTTP_USER_AGENT"], "Chrome"))            
           //$action_name = WebRegistry::getDefaultAction_CHROME(); 

        // get the command class name
        $action_class_name = WebRegistry::getCommandClassName($action_name);

        // get the command object
        $filename = Application::getCommandsDirectory(). '/'. $action_class_name . '.class.php';

        //print_r($filename);   
		@include $filename;

        if (!class_exists($action_class_name)) {
            return @PEAR::raiseError($action_name. ': Command not found');
        } else {
            /**
        *   Copyright 2006 - Spyro Solutions
        *
        *   Valid if generated Log Auth
        *   @author Spyro Solutions
        *   @date 14-Dic-2006 10:43:34
        *   @location Cali-Colombia
        */

            if (Application::getlog()!="Nothing")
                 WebRegistry::setLog();

             
            return new $action_class_name();
        }
    }
    
	//Busca la vista que va mostrar de acuerdo al result success o fail
    public static function getWebCommandView($result) {

        // set the action name
        $action_name = isset($_REQUEST["action"]) ?
            $_REQUEST["action"]:
            WebRegistry::getDefaultAction();

        // Presenta pantalla Optimizado solo Chrome
        //if (!strstr($_SERVER["HTTP_USER_AGENT"], "Chrome"))            
           //$action_name = WebRegistry::getDefaultAction_CHROME(); // Presenta pnatalla Optimizado solo Chrome

	    //// get the view name
        $view_name = WebRegistry::getViewName($action_name, $result);

        return $view_name;

    }
	
	
    
    public static function getDefaultAction() {
        //print_r("Entre..".WebRegistry::__getVar('default_action')."<------");
        return WebRegistry::__getVar('default_action');
    }

    public static function getDefaultAction_CHROME() {
        return WebRegistry::__getVar('default_action_CHROME');
    }    

    public static function getErrorView() {
        return WebRegistry::__getVar('error_view');
    }
    
    public static function getLoginView() {
        return WebRegistry::__getVar('login_view');
    }
    
    public static function getCommandClassName($action) {
	
        // get the configuration array
        $config = WebRegistry::getConfig();
		//eval(WebRegistry::getConfig());
        // an error ??
        if (@PEAR::isError($config)) {
            return $config;
        } 
        //print_r("La Claseee".$config['commands'][$action]['class']."<----------".$action);
        return $config['commands'][$action]['class'];
    }
    
    public static function getViewName($action, $result) {

        // get the configuration array
        $config = WebRegistry::getConfig();


        if (@PEAR::isError($config)) {
            return $config;
        }
        
        return $config['commands'][$action]['views'][$result]['view'];
    }
    
    public static function getWebView($view) {
        

        if (@PEAR::isError($config)) {

            return $config;
            
        }

        include 'TemplateView.class.php';
        return new TemplateView($view);
            
    }
    


    public static function &__getVar($nom_var) {

        // get the configuration array
        $config = WebRegistry::getConfig();

        // an error ??
        if (@PEAR::isError($config)) {
            return $config;
        }

        // return the variable
        return $config[$nom_var];
    }

    public static function __setVar($name="", &$objVar) {
        $obj =	 &LITLE::getStaticProperty('Web', 'config');
        $obj[$name] = $objVar;
    }

    public static function getConfig() {
        $config = LITLE::getStaticProperty('Web','config');
        // if configuration data is not set
        if (!isset($config)) {
            // load the configuration data
            $config = Serializer::load(Application::getBaseDirectory().'/config/web.conf.data');
			//print_r(Application::getBaseDirectory().'/config/web.conf.data');
        }
        if (!is_array($config)) {
            return @PEAR::raiseError('cannot load the configuration file');
        }
        return $config;
    }	

}

?>
