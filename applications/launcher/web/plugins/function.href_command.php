<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {href_command} plugin
 * Type:     function<br>
 * Name:     href_command<br>
 * Purpose:  crea una href que llama a un comando.<br>
 * Input:<br>
 *           form_name = name of the form that content calendar (required)
 *           cmd = nombre del comando (required)
 *           label = etiqueta del link (required)
 * <br>
 * Examples: {href_command cmd="default" form_name="frmContrato_alquiler" label="Volvel al Menu Princial"}
 *            
 * @author   Spyro Solutions 
 * @version  2.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 * -------------------------------------------------------------
 */

function smarty_function_href_command($params, &$smarty)
{

extract($params);

$html_result = "";

if ((Application::validateProfiles($name)))
{	
  $html_result .= "<a href='#' onClick=\"".$form_name.".action.value='".$cmd."';".$form_name.".submit();\">".$label."</a>";
}  

print $html_result;

}
?>




