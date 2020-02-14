<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {form} block plugin
 * Type:     block<br>
 * Name:     form<br>
 * Purpose: Crear un formulario <br>
 * Input:<br>
 *           name = name of the form (optional)
 *           action = value of the action (optional)
 *           method = value of the method (optional)
 *           enctype = value of the enctype (optional)
 *           target = value of the target (optional)
 *
 *
 * Examples :
 * <pre>  
 * {form name="FActualizarUsuario" method="post" action="destino.php"}
 *             
 * {/form}
 * <pre> 
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */
 
//<form id="demo-bvd-notempty" action="forms-validation.html" class="form-horizontal"> 
function smarty_block_form($params, $content, &$smarty)
{
   extract($params);
   $html_result = '';
   if (isset($content)){
    $html_result .= "<form";

    if ($id != ''){
        $html_result .= " id=\"$id\"";
    }
    if ($action != ''){
        $html_result .= " action=\"$action\"";
    }
    if ($method != ''){
        $html_result .= " method=\"$method\"";
    }
    if ($enctype != ''){
        $html_result .= " enctype=\"$enctype\"";
    }
    if ($name != ''){
        $html_result .= " name=\"$name\" ";
    }
    if ($target != ''){
        $html_result .= " target=\"$target\"";
    }
    if ($class != ''){
        $html_result .= " class=\"$class\"";
    }	
    $html_result .= ">";
    $html_result .= $content;
	//$html_result .= '<input type=hidden name="session_EMPRID" value="'.WebSession::setIAuthProperty("emprid").'">';
    $html_result .= "</form>";
    print $html_result;
  }
}

?>
