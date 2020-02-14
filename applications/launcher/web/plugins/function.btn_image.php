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
 * Examples : {btn_image   name="CmdUpdatePais" images="xx.gif" alt=""}
 *
 *</pre>
 *
 * @author   Spyro Solutions
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions
 */

function smarty_function_btn_image($params, &$smarty)
{
    extract($params);
  require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Toolsbar.lan";
  //if (isset($SessionClear)){
    //WebSession::unsetSessionIAuth();
  //}
  //print_r("Validacion->".Application::validateProfiles($name));
  //Valid this command have auth and allowed
  if ((Application::validateProfiles($name)) == true)
  {
    $html_result = '';
    
   

    
   if (isset($target)){ 
        $targetwindow = " target='".$target."'";
    }

   if (isset($ur)){

    $html_result .='<div style="float:left;">
                        <div class="icon">
			  <a href="'.$ur. '"  '.$targetwindow.'>';

   }
   else 
     if (isset($onClick))
	 {
       $html_result .='<div style="float:left;">
                        <div class="icon">
		  	     <a href="#"  onClick="'.$onClick.'">';	 
	 
	 }
     else
     {
       $html_result .='<div style="float:left;">
                        <div class="icon">
		  	     <a href="index.php?action='.$name.'" '.$targetwindow.'>';
     }
    $html_result .='        <img src="'.$image.'" alt="'.$alt.'" align="top" border="0" />';
    $html_result .='        <span>'.$Toolsbar[$label].'</span>  
			  </a>
                       </div>
		    </div>';
    print $html_result;

  } // End of validate permission

}

?>
