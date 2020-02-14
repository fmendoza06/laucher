<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {meta} compiler plugin
 * Type:     compiler<br>
 * Name:     meta<br>
 * Purpose:  definir el charset que se utilizara en el template<br>
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */


function smarty_compiler_meta($tag_arg, &$smarty)
{
	$html_result = "";
	$html_result .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . Application::getCharSet() . "\">";
	//print_r($html_result);
	return $html_result;
}

?>