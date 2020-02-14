<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {powerby} compiler plugin
 * Type:     compiler<br>
 * Name:     meta<br>
 * Purpose:  print Spyro Power By bar
 *
 * @author   Spyro Solutions
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions
 */
function smarty_function_captcha($tag_arg, &$smarty)
{
         if (isset($_SESSION["captcha"]))
             $html_result =$_SESSION["captchaImage"];
         print $html_result;
}
?>