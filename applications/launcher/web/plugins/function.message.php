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
 * Examples:  {message id="5"}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_message($params, &$smarty)
{

// NO BORRRAR !!!!!!!!!!!! Att:(Hemerson.)
//echo "++++++++++ Session ++++++++++++++";
//echo "<pre>"; print_r($_SESSION); echo "</pre>";
//echo "++++++++++ Request ++++++++++++++";
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

$lang=Array(
    0=>"Error: Los campos con (*) son obligatorios",
    1=>"Error: El registro ya existe",
	2=>"Error: El registro no existe",
	3=>"Operaci&oacute;n realizada con &eacute;xito",
	4=>"La entrada no es valida para el tipo de dato",
	5=>"No se pudo realizar la operaci&oacute;n"
);

extract($params);

$html_result = "";

$html_result .= $lang[$id];

print $html_result;

}
?>

