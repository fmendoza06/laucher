<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     checkbox
 * Purpose:  checkbox
 * -------------------------------------------------------------
 */
function smarty_function_checkbox($params, &$smarty)
{

    extract($params);
    
    $id=$name;
    $html_result = '';
    $html_result .= "<input";
    if (isset($name)){
        $html_result .= " name=\"$name\"";
    }
    $html_result .= " type=\"checkbox\"";
    if (isset($id)){
        $html_result .= " id=\"$id\"";
    }
    if (isset($value)){
        $html_result .= " value=\"$value\"";
    }
    if (isset($disabled)){
        $html_result .= " disabled=\"$disabled\"";
    }
    if (isset($checked)){
        $html_result .= " $checked";
    }
    if (isset($onKeyUp)){
        $html_result .= " onKeyUp=\"$onKeyUp\"";
    }
     
	
     if(isset($_REQUEST[$name])){
        $html_result .= " value=\"$_REQUEST[$name]\"";
    	$html_result .= " checked";
	}
	elseif (isset($value)){
        $html_result .= " value=\"$value\"";
        $html_result=str_replace("checked","",$html_result);
    } 
    $html_result .= ">";
    return $html_result;

}

?>
