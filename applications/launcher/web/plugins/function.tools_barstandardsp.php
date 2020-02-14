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
 * @author   Jose Fernando Mendoza 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Soflutions - Agosto 2007 
 */

function smarty_function_tools_barstandardsp($params, &$smarty)
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

    if(isset($images)){
        $image = explode(",",$images);
    } else {
        $image = $v;
    }


    if(isset($accesskey)){
        $access = explode(",",$accesskey);
    } else {
        $access = $v;
    }

    if(isset($titles)){
        $title = explode(",",$titles);
    } else {
        $title = $v;
    }

	$html_result='';
   $html_result .= '<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
                      <tr>
	                     <td class="menudottedline" align="right">
			                <table cellpadding="0" cellspacing="0" border="0" id="toolbar">';

   $html_result .= '          <tr valign="middle" align="center">';

   // Creo los botones

  for($i=0;$i < count($v);$i++)
  {
      if ((Application::validateProfiles($v[$i])))
      {	  
        CrearBoton($html_result,$params,$image[$i],$v[$i],$reference_id,$access[$i],$Toolsbar[$title[$i]]);
	  }	
  }

   $html_result .= "         </tr>";

   $html_result .= "      </table>
                    </tr>
                  </table>";
   //$html_result .= "</fieldset></div>";

   print $html_result;


}

function CrearBoton(&$html_result,$params,$image_button,$command_name,$id,$accesk,$title){
    extract($params);
    $html_result .= '<td>';
    if (isset($href))
     $html_result .= '<a class="toolbar" href="'.$href.'">';


    else
      if ($command_name == "CmdClose")
        $html_result .= '<a class="toolbar" href="JavaScript:window.close()">';

    else
    {
     $html_result .= '<a class="toolbar" href="#"';

     if (isset($onClick)){
			$html_result .= $onClick."\">";
     }
     else {
      $html_result .= '  onClick="'. $form_name. ".action.value = '".$command_name."'; ".$form_name.'.submit();">';
     
     }
    }

    $html_result .= '<img src= "./web/images/menubar/';
    $html_result .= $image_button.'"';

    $html_result .= " name='".$command_name."'";

    if (isset($id)){
        $html_result .= " id='".$id."'";
    }

    if (isset($accesskey)){
        $html_result .= ' accesskey="$accesk"';
    }

    if (isset($titles)){
        $html_result .= ' alt="'.$title.'" ';
    }
    $html_result .=' align="middle" border="0" ><br>'.$title;

    $html_result .= '</a></td>';



    }


?>