<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {hidden} plugin
 * Type:     function<br>
 * Name:     hidden<br>
 * Purpose:  mostrar la version del sistema<br>
 * Input:
 * Examples : {version}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */
 
function smarty_function_version($params, &$smarty)
{
    extract($params);
    echo Application::getVersion();
}

?>
