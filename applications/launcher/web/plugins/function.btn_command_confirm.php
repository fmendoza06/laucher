<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {btn_command_confirm} plugin
 * Type:     function<br>
 * Name:     btn_command_confirm<br>
 * Purpose:  crea un boton con un mensaje de confirmacion<br>
 * Input:<br>
 *           name = name of btn_command (optional)
 *           id = id of btn_command (optional)
 *           type = define the type of the btn_command ('button'|'submit')(required)
 *           disabled = disable the btn_command (optional)
 *           message = mensaje que se muestra en la confirmacion (optional)
 *           value = define the label of the btn_command (optional)
 *           form_name = nombre de la forma que contiene el btn_command
 *           
 *<pre>
 * Examples : {btn_command_confirm type="button" value="Eliminar" name="GsCatCmdDeleteColeccion" form_name="frmColeccion" message="Si elimina la colección también eliminara todas las referencias asociadas a está colección ¿Desea continuar?"}
 *</pre>
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_btn_command_confirm($params, &$smarty)
{
    extract($params);
    $html_result = '';
    $html_result .= "<input class='boton'";

    if (isset($name)){
        $html_result .= " name='".$name."'";
    }
    if (isset($type)){
        $html_result .= " type='".$type."'";
    }
    if (isset($id)){
        $html_result .= " id='".$id."'";
    }
    if (isset($value)){
        $html_result .= " value='".$value."'";
    }
    if (isset($disabled)){
        $html_result .= " disabled='".$disabled."'";
    }

    $html_result .= " onClick=\"var result_confirm = confirm('" . $message . "');
			  			if(result_confirm == true){
							disableButtons();
							action.value = '" . $name . "';
							" . $form_name . ".submit();
						}";

    //cierra la doble comilla del onClick
    $html_result .= "\"";

    $html_result .= ">";
    
    print $html_result;
}

?>