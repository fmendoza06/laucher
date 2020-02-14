<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {message} plugin
 * Type:     function<br>
 * Name:     message<br>
 * Purpose: imprime los mensajes de la retornados por la aplicacion<br>
 * Input:<br>
 *           id = codigo of message (required)
 * <br>
 * Examples:  {messagebox id="5"}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_messagebox($params, &$smarty)
{

// NO BORRRAR !!!!!!!!!!!! 
//echo "++++++++++ Session ++++++++++++++";
//echo "<pre>"; print_r($_SESSION); echo "</pre>";
//echo "++++++++++ Request ++++++++++++++";
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

 require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Message.lan";


$lang=$Alerts;

extract($params);


$html_result = "";
if ($id > -1)
 {
    $html_result .= $lang[$id];
	/* */
    $html= '<script language="javaScript" >
	           alertify.set("notifier","position", "top-left");
               alertify.success("'.$id.' - '.$html_result.'");
            </script>';


    print $html;
 }

}
?>


