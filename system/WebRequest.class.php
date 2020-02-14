<?php

class WebRequest {
	
  	/**
	*	Copyright 2006 - Spyro Solutions
	*	
	*	@author Spyro Solutions - Jose Fernando Mendoza
	*	@date 14-Dic-2006 10:43:34
	*	@location Cali-Colombia
	*/	

    public static function setProperty($name="", &$objVar) {
        $params =& LITLE::getStaticProperty('Request', 'parameters');
        if (!isset($params)) {
            $params = array();
        }
        //$params[$name] = $objVar;
        //clear a inject o garbage data
		$params[$name] = WebRequest::clear_input($objVar);
    }

    public static function &getProperty($name="") {
        $params =& LITLE::getStaticProperty('Request', 'parameters');
        if (!isset($params)) {
            $params = array();
        }
        return $params[$name];
    }

    public static function &getParameterList() {
        $params =& LITLE::getStaticProperty('Request', 'parameters');
        if (!isset($params)) {
            $params = array();
        }
        return $params;
    }
	
    public static function clear_input($data) {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       $data =  filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);       
       return $data;
    }	

}

?>
