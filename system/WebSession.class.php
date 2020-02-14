<?php

    /**
    *   Copyright 2006 - Spyro Solutions
    *   
    *   @author Spyro Solutions - Jose Fernando Mendoza
    *   @date 14-Dic-2006 10:43:34
    *   @location Cali-Colombia
    */  

class WebSession {
	

    public static function init() {
        session_start();
		//WebSession::checkSession();
    }

    public static function setProperty($name="", &$objVar) {
         $_SESSION["_forms"][$name] = $objVar;
    }

    public static function &getProperty($name="") {
        return $_SESSION["_forms"][$name];
    }
    
    public static function unsetProperty($name="") {
        unset($_SESSION["_forms"][$name]);
    }
	
    public static function unsetPropertyForms() {
        unset($_SESSION["_forms"]);
    }
    
	public static function setIAuthProperty($name="", &$objVar) {
        $_SESSION["_iauthSession"][$name] = $objVar;
    }

    public static function &getIAuthProperty($name="") {
        return $_SESSION["_iauthSession"][$name];
    }
    
    public static function unsetPropertyIAuth($name="") {
        unset($_SESSION["_iauthSession"][$name]);
    }
    
    public static function unsetSessionIAuth() {
        unset($_SESSION["_iauthSession"]);
    }
    
    public static function &getParameterList() {
        return array_keys($_SESSION);
    }
    
    public static function issetProperty($name="") {
        if(isset($_SESSION["_forms"][$name])){
            return 1;
        }else{
            return 0;
        }
    }
	
	public static function issetPropertyAuth($name="") {
        if(isset($_SESSION["_iauthSession"][$name])){
            return 1;
        }else{
            return 0;
        }
    }

	public static function issetAuth() {
        if(isset($_SESSION["_iauthSession"])){
            return 1;
        }else{
            return 0;
        }
    }

	/**
	* Metodo para conocer ser el nombre de usuario
	* @author Spyro Solutions
	* @return boolean
	*/
    public static function getCurrentUserName() {
       if(WebSession::issetPropertyAuth("indentify")){
            return WebSession::getIAuthProperty("indentify");
        }else{
            return "";
        }
    }
    
	/**
	* Metodo para conocer ser el Id de usuario
	* @author Spyro Solutions
	* @return boolean
	*/
    public static function getCurrentUserId() {
       if(WebSession::issetPropertyAuth("userid")){
            return WebSession::getIAuthProperty("userid");
        }else{
            return "";
        }
    }
    
	/**
	* Metodo para saber si hay un usuario en session
	* @author Spyro Solutions
	* @return boolean
	*/    
   	public static function isLoged()
	{
		return ( (WebSession::issetPropertyAuth("username") == 1) && (WebSession::issetPropertyAuth("commands") == 1) );
		
	}

    public static function close() {
        session_destroy();
    }

	/**
	* Metodo para validar el tiempo de inactividad de la session no mayor a 30 minutos
	* @author Spyro Solutions JFMI
	* @Date 2015-12-17	
	* @return boolean
	*/  	
	public static function checkSession($time=1800)
	{
		
	  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $time)) {
       // last request was more than 30 minutes ago
       session_unset();     // unset $_SESSION variable for the run-time 
       session_destroy();   // destroy session data in storage
      }
      $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		
	}

}

WebSession::init();

?>
