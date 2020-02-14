<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {hidden} plugin
 * Type:     function<br>
 * Name:     hidden<br>
 * Purpose: crea un componente hidden<br>
 * Input:<br>
 *           name = name of the hidden (optional)
 *           id = id of the hidden (optional)
 *           value = value of the hidden (optional)
 * <br>
 * Examples : {hidden name="hidden" value="SPYRO"}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */
 
function smarty_function_hidden($params, &$smarty)
{
    extract($params);
    $id=$name;
    $html_result = '';
    $html_result .= "<input";
    if (isset($name)){
        $html_result .= " name=\"$name\"";
    }

    $html_result .= " type=\"hidden\"";

    if (isset($id)){
        $html_result .= " id=\"$id\"";
    }
    
    if (isset($value)){
        $html_result .= " value='".$value."'";
    }else{
        $html_result .= " value='".$_REQUEST[$name]."'";
    }
    
    $html_result .= ">";
    
    print $html_result;
}

?>
