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
 *           btn_class = Clase del Boton
 *           icon_class = Clase para mostrar imagen en el boton
 *           
 *<pre>
 * Examples : {tools_barstandard type="Button" reference_id =100 
                        form_name="frmWp_commentmeta"
                        commands="seresCmdAddWp_commentmeta,seresCmdShowListWp_commentmeta,seresCmdHelpWp_commentmeta"
                        icon_class="fa-check,fa-reply,fa-ambulance" 
						btn_class ="btn-success,btn-warning,btn-mint" 
                        labels="Guardar,Regresar,Ayuda"  
              }
 *            {btn_command type="submit" value="Adicionar" name="CmdAddPais" onClick="alert('click al submit');"}
 *</pre>
 *
 * @author   Joseé Fernando Mendoza <fmendoza06@gmail.com>
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Softlutions - 02-Jul-2015 
 */

function smarty_function_tools_barstandard($params, &$smarty)
{

    extract($params);
    require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Toolsbar.lan";
	
    if (isset($reference_id)){
        $html_help = $reference_id;
    }
    if(isset($commands)) {
    	$v = explode(",",$commands);
    } else {
    	$v = "";
    }

    if(isset($btn_class)){
        $btn_class = explode(",",$btn_class);
    } else {
        $btn_class = $v;
    }
	
    if(isset($icon_class)){
        $icon_class = explode(",",$icon_class);
    } else {
        $icon_class = $v;
    }	


    if(isset($accesskey)){
        $access = explode(",",$accesskey);
    } else {
        $access = $v;
    }

    if(isset($labels)){
        $labels = explode(",",$labels);
    } else {
        $labels = $v;
    }

   $html_result = '';
   $html_result .= '
						<div class="col-sm-12">
							<div class="panel">
								  <div class="panel-heading">
									<h3 class="panel-title"></h3>
								  </div>					
					              <div class="panel-body demo-nifty-btn">';		

   $html_result = '
					              <div class="panel-body demo-nifty-btn">';		


   // Creo los botones
  //print_r("Aqui Voy :) ->".count($v)); 
  for($i=0;$i < count($v);$i++)
  {
	if ((Application::validateProfiles($v[$i])))
    {	
      CrearBoton($html_result,$params,$icon_class[$i],$btn_class[$i],$v[$i],$reference_id,$access[$i],$Toolsbar[$labels[$i]]); 
	}  
  }


   /* 
   $html_result .= "    
                                  </div>									
							</div>
						</div>";
    */						
						
   $html_result .= "    
                                  </div>";						

   print $html_result;


}

function CrearBoton(&$html_result,$params,$icon_class_icon,$btn_class_btn,$command_name,$id,$accesk,$label){
    extract($params);
    //print_r("<br>Aqui Voy :) ->".$label); 
    if (isset($id)){
        $html_result_id .= " id='".$id."'";
    }
	else
		$html_result_id .= " id='".$command_name."'";
	
    if (isset($accesk2)){
        $html_result .= " accesskey='$accesk'";
    }
    
    if (isset($disabled)){
        $html_result_disabled .= " disabled='".$disabled."'";
    }

    if (isset($width)){
        $html_result_width .= " width='".$width."'";
    }
    if (isset($heigth))
	{
        $html_result_heigth .= " ".$heigth." ";
    }
	else
		$html_result_heigth = " fa-lg " ;
	

	
    if (isset($value)){
        $html_result .= " value='".$value."'";
    }
    //print_r($html_result);
    if (isset($btn_class_btn)){
        $html_result_class = " class='btn ".$btn_class_btn." btn-rounded'"; 
    } else {
        $html_result_class .= " class='btn btn-primary btn-rounded'"; //
	}


    $html_result .= '<a '.$html_result_class.' href="#" '.$html_result_id.' ';    

	//Esta variable me permite desactivar las funciones javascript que vienen por defecto con el plugin.
	if(!isset($action_no)) 
	{
		//$html_result .= " onClick=\"disableButtons();";
		if (($type == "Button")||($type == "button") || ($type == "image") )
		{
			//$html_result .= "action.value = '".$command_name."'; ".$form_name.".submit();";
            $html_result .= '  onClick="'. $form_name. ".action.value = '".$command_name."'; ".$form_name.'.submit();'; //">';			
		}
		if (($type == "Submit")||($type == "submit")){
			$html_result .= "action.value = '".$name."';";
		}
	} 
	else 
	{
		$html_result .= " onClick=\"";
		if (isset($onClick))
		{
			$html_result .= $onClick;
		}
	}
	//cierra la doble comilla del onClick
	$html_result .= "\"";
	$html_result .= ">"; 
	
	$html_result .= '<i class="fa '.$icon_class_icon.' '.$html_result_heigth.'"></i> '.$label.'&nbsp';
	
	$html_result .= '</a>&nbsp';
}
?>