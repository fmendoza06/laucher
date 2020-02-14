<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {btn_command} plugin
 * Type:     function<br>
 * Name:     btn_command<br>
 * Purpose:  crea un boton<br>
 * Input:<br>
 *           name = name of btn_command (optional)
 *           id = id of btn_command (optional)
 *           type = define the type of the btn_command ('button'|'submit')(required)
 *           disabled = disable the btn_command (optional)
 *           onClick = To introduce code javascript (optional)
 *           value = define the label of the btn_command (optional)
 *           form_name = nombre de la forma que contiene el btn_command
 *           
 *<pre>
 * Examples : {btn_command type="button" form_name="frmPais" value="Modificar" name="CmdUpdatePais" onClick="alert('click al button');"}
 *            {btn_command type="submit" value="Adicionar" name="CmdAddPais" onClick="alert('click al submit');"}
 *</pre>
 *
 * @author   Spyro Solutions
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions
 */

function smarty_function_btn_command($params, &$smarty)
{
    extract($params);

    //Valid this command have auth and allowed
    //$security = Application::validateProfiles($name);
    
  if ((Application::validateProfiles($name)))
  {
    $html_result = '';
    $html_result .= "<input ";

    if (isset($name)){
        $html_result .= " name='".$name."'";
    }
    if (isset($type)){
        $html_result .= " type='".$type."'";
    }
    if (isset($src)){
        $html_result .= " src='".$src."'";
    }
    if (isset($id)){
        $html_result .= " id='".$id."'";
    }
    if (isset($value)){
        $html_result .= " value='".$value."'";
    }
    if (isset($class)){
        $html_result .= " class='".$class."'"; 
    } else {
        $html_result .= " class='boton'";
	}
    if (isset($title)){
        $html_result .= " title='$title'";
    }
    if (isset($disabled)){
        $html_result .= " disabled='".$disabled."'";
    }
    if (isset($onMouseover)){ 
        $html_result .= " onMouseover=\"".$onMouseover."\"";
    }
    if (isset($onMouseDown)){ 
        $html_result .= " onMouseDown='".$onMouseDown."'";
    }
    if (isset($onMouseout)){ 
        $html_result .= " onMouseout=".$onMouseout;
    }
    if (isset($label)){ 
        $html_result .= " label='".$label."'";
    }


    $onclikc1 ='';
    if (isset($onClick)){
        $onclikc1 = $onClick;
    }

	//Esta variable me permite desactivar las funciones javascript que vienen por defecto con el plugin.
	if(!isset($action_no)) { 
		$html_result .= " onClick=\"disableButtons();".$onclikc1;
		if (($type == "Button")||($type == "button") || ($type == "image")){
			$html_result .= "action.value = '".$name."'; ".$form_name.".submit();";
		}
		if (($type == "Submit")||($type == "submit")){
			$html_result .= "action.value = '".$name."';";
		}
	} else {
		$html_result .= " onClick=\"";
		if (isset($onClick)){
			$html_result .= $onClick;
		}
	}
	//cierra la doble comilla del onClick
	$html_result .= "\"";
	$html_result .= ">";
    print $html_result;

 } // End of validate permission

}

?>
