<?php


/**
*   Copyright 2006 - Spyro Solutions
*   
*   @author Spyro Solutions - Jose Fernando Mendoza
*   @date 14-Dic-2006 10:43:34
*   @location Cali-Colombia
*/  

require_once "Smarty.class.php";

class TemplateView {

    var $template;

	function __construct($template) {
        $this->template = $template;
    }
	

    
    /**
    *
    * @access public
    * @param  No params
    * @return display html translate for Smarty Engine. 
    */
    function show() {

        // get a template engine object
        $obj =& TemplateView::getTemplateEngine();

        // assign the variables to the template engine
        $obj->assign(WebRequest::getParameterList());

        // set the plugins directories
        // must use Smarty > 2.6.0
        $obj->plugins_dir = array (
            Application::getBaseDirectory().Application::__getVar('plugins_dir'),
            LITLE::getLITLEDirectory()."/system/plugins",
            'plugins'
            );



	/**
		Copyright 2006  Spyro Solutions
		Load directory for config language
		@author Spyro Solutions
		@date 29-sep-2004 10:33:05
		@location Cali-Colombia Application::__getVar('language_dir') 
	*/
	     
        $obj->config_dir =  Application::getBaseDirectory().Application::__getVar('language_dir').'/'.Application::__getVar('language');
		
        //load the template
        $obj->template_dir = Application::getBaseDirectory().Application::__getVar('templates_dir');
        $obj->display($this->template);

    }

    /**
    *
    * @access public
    * @param  No params
    * @return reference obj to Smarty Class Engine. 
    */

    function &getTemplateEngine(){

        // there is a previously created engine object ??
        $obj =& LITLE::getStaticProperty('Template', 'engine');

        // create the smarty object
        if (!isset($obj) || !is_object($obj)) {
            $obj = new Smarty();
        }
        
        return $obj;
    }

}

?>

