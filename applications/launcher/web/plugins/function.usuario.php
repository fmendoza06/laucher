<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {hidden} plugin
 * Type:     function<br>
 * Name:     hidden<br>
 * Purpose: crea un componente label<br>
 * Input:<br>
 *           name = name of the hidden (optional)
 *           id = id of the hidden (optional)
 *           value = value of the hidden (optional)
 * <br>
 * Examples : {usuario}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */
 
function smarty_function_usuario($params, &$smarty)
{
    extract($params);
    
    $html_result = '';

        $html_result .= $_SESSION["_iauthSession"]["username"];

    print $html_result;
}

?>
