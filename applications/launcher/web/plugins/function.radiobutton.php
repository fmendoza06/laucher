<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     radiobutton
 * Purpose:  radiobutton
 * -------------------------------------------------------------
 */
function smarty_function_radiobutton($params, &$smarty)
{
    extract($params);
    
    $html_result = '';
	$i=$name;
	$id=$name;
    $html_result .= "<input";
    if (isset($name)){
        $html_result .= " name=\"$name\"";
    }
    $html_result .= " type=\"radio\"";
    if (isset($value)){
        $html_result .= " value=\"$value\"";
    }
    if (isset($id)){
        $html_result .= " id=\"$id\"";
    }
    if (isset($tabindex)){
        $html_result .= " tabindex=\"$tabindex\"";
    }
    if (isset($disabled)){
        $html_result .= " disabled";
    }
    if (isset($_REQUEST[$name])){
    	if ($_REQUEST[$name]==$value){
        	$html_result .= " checked";
    	}else
    	    $html_result .= " unchecked";
    }elseif (isset($checked)){
        	$html_result .= " $checked";
    }
    if (isset($onClick)){
          $html_result .= " onClick=\"action.value = '$comando';$onClick\"";
      }
      
    $html_result .= ">";
    return $html_result;

}

?>
