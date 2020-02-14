<?php

/**
*   Copyright 2006 - Spyro Solutions
*   
*   @author Spyro Solutions - Jose Fernando Mendoza
*   @date 14-Dic-2006 10:43:34
*   @location Cali-Colombia
*/  

class Error {
// haremos nuestra propia manipulaci&oacute;n de errores

  

    var $errorManager;
    //var $applicationname;

    function __construct() {
     error_reporting(E_ALL); //ERROR & FATAL + 
     error_reporting(E_ALL);
     $errorManager = set_error_handler("errorManager");
     ini_set('display_errors', false);
     ini_set('html_errors', false);
     //$this->applicationname=Application::getDescription();

    }


} // End class



// funcion de gestion de errores definida por el usuario
   function errorManager($num_err, $mens_err, $nombre_archivo,$num_linea, $vars)
   {

    $err="";
   //Trace error array
   $errval=debug_backtrace();//var_dump(debug_backtrace());
   if ($num_err==E_NOTICE || $num_err==E_STRICT )
     return;
   //Only show sql warnings, others warnings no show
   //if ($num_err==E_WARNING && $errval[0]['args'][4]['sql']=="")
   //  return;
   
   // for postgress
   if   (preg_match("pg_fetch_array", $mens_err))
       return true;
   
   

    // marca de fecha/hora para el registro de error
    $dt = date("Y-m-d H:i:s ");

    // definir una matriz asociativa de cadenas de error
    // en realidad las unicas entradas que deberiamos
    // considerar son E_WARNING, E_NOTICE, E_USER_ERROR,
    // E_USER_WARNING y E_USER_NOTICE

    $tipo_error = array (
                E_ERROR           => "Error",
                E_WARNING         => "Advertencia",
                E_PARSE           => "Error de Int&eacute;rprete",
                E_NOTICE          => "Anotaci&oacute;n",
                E_CORE_ERROR      => "Error de N&uacute;cleo",
                E_CORE_WARNING    => "Advertencia de N&uacute;cleo",
                E_COMPILE_ERROR   => "Error de Compilaci&oacute;n",
                E_COMPILE_WARNING => "Advertencia de Compilaci&oacute;n",
                E_USER_ERROR      => "Error de Usuario",
                E_USER_WARNING    => "Advertencia de Usuario",
                E_USER_NOTICE     => "Anotaci&oacute;n de Usuario"
                //E_STRICT          => "Anotaci&oacute;n de tiempo de ejecuci&oacute;n"
                );
    // conjunto de errores de los cuales se almacenara un rastreo
    $errores_de_usuario = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    $err .= '<link href="web/css/form.css" rel="stylesheet" type="text/css">';
    $err .='<table border=1 width=50% align="center">
              <tr >
                 <td width=10% height=32 class="detailedViewHeader">
                     <font class="small" size="11" >
                       <b>';
    $err .=              '<center>'.Application::getDescription().' - '.$tipo_error[$num_err].' : MSG-'.$num_err.' DATE '.$dt.'
                          </center>
                       </b>
                     </font>' ;
    $err.=       '</td>
              </tr>
              <tr>
                  <td class1="dvtCellLabel" > MESSAGE :';
    $err .=         $num_err ."-".$mens_err.'<br>FILE : ';
    $err .=         $nombre_archivo;
    $err.=        '</td>
              </tr>
              <tr>
                  <td >';
    $err .=         'Linea = '.$num_linea ;
    $err.=       '</td>
              </tr> ';

    if ($errval[0]['args'][4]['sql']!="")
    {
     $err.='<tr>
                  <td > QUERY : ';

     $err .=($errval[0]['args'][4]['sql']).'<br>FILE : ';
     $err .=$errval[6]['file'];
    $err.=       '</td>';
    }
    if (in_array($num_err, $errores_de_usuario)) {
                    $err .= wddx_serialize_value($vars, "Variables");
    }

    $err.= ' </tr>
            </table>';

    // para efectos de debug
    // echo $err.'<br>';

    // String Error for file or sql
    $errfs = "<errorentry>\n";
    $errfs .= "\t<datetime>" . $dt . "</datetime>\n";
    $errfs .= "\t<user>" .WebSession::getCurrentUserName()."</user>\n";
    $errfs .= "\t<errornum>" . $num_err . "</errornum>\n";
    $errfs .= "\t<errortype>" . $tipo_error[$num_err] . "</errortype>\n";
    $errfs .= "\t<errormsg>" . $mens_err . "</errormsg>\n";
    $errfs .= "\t<scriptname>" . $nombre_archivo . "</scriptname>\n";
    $errfs .= "\t<sql>" .$errval[8]['args'][4]['sql']."</sql>\n";
    $errfs .= "\t<file>" .$errval[6]['file']."</file>\n";
    $errfs .= "\t<scriptlinenum>" . $num_linea . "</scriptlinenum>\n";

    if (in_array($num_err, $errores_de_usuario)) {
        $errfs .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";
    }
    $errfs .= "</errorentry>\n\n";

    echo $err;
    // guardar en el registro de errores, y enviar un correo
    // electr&oacute;nico si hay un error cr&iacute;tico de usuario
	
	//print_r("Error->".Application::getErrorLog());
     switch(Application::getErrorLog()) {
          case 'file':
               error_log($errfs, 3, Application::getErrorLogFile());
          break;
     }//switch

    if ($num_err == E_USER_ERROR) {
        //mail("phpdev@example.com", "Error Cr&iacute;tico de Usuario", $err);
    }
    return true;
   }


?>