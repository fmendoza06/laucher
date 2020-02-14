<?php

/**
* index.php
*
* standalone receiver
* freestanding code to load the FrontController
* requires the "config.inc.php"
* Implment de Listener Pathern
*/

require_once "config/config.inc.php";
require_once "LitlePHP.class.php";
require_once "FrontController.class.php";

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//Error Manager
//$error = new Error();


FrontController::execute();


?>


