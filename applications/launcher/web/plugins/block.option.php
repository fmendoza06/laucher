<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {option} block plugin
 * Type:     block<br>
 * Name:     option<br>
 * Purpose: crea las opciones de un componente tipo select<br>
 * Input:<br>
 *           value = value of the option (optional)
 *           id = id of the option (optional)
 *
 *  Examples : 
 * 			<pre>	
 * 			  {select name="nombres" id="nom"}
 *              {option id="cod1"}Hemerson{/option}
 *              {option id="cod2"}Leider{/option}
 *            {/select}
 * 			<pre>
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */
 
function smarty_block_option($params, $content, &$smarty)
{
extract($params);
$html_result = '';
 if(isset($content)){
    $html_result .= "<option";
    if (isset($value)){
        $html_result .= " value=\"$value\"";
    }
    if (isset($id)){
        $html_result .= " id=\"$id\"";
    }
    $html_result .= ">";
    $html_result .= $content;
    $html_result .= "</option>";
    print $html_result;
 }
}
?>
