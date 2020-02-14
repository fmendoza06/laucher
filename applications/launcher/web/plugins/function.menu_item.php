<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {menu_item} plugin
 * Type:     function<br>
 * Name:     menu_item<br>
 * Purpose:  Item en Menu de Navegacion<br>
 * Input:<br>
 *           name = name of btn_command (obligatorio)
 *           id = id of btn_command (optional)
             label= label into Toolsbar.lan
			 divider = divider line
			 class   = icon class ionto font-awesome font
			 icon_size = tamaño del icono valores validos lg,2x,3x,4x,5x

 *           
 *<pre>
 * Examples : {menu_item   name="CmdUpdatePais"}
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

function smarty_function_menu_item($params, &$smarty)
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

	if (isset($icon_size))

       $icon_class_size .='fa-'.$icon_size; 		
	else 
       $icon_class_size .='fa-lg';
	  
	if (isset($class))

       $icon_class_name .='fa '.$class; 		
	else 
       $icon_class_name .='fa-th';
	
	
   
    $html_result = '
									<!--Menu list item-->  
									<li> 
										<a href="index.php?action='.$name.'">
											<i class="'.$icon_class_name.' '.$icon_class_size.'"></i>
											<span class="menu-title">
												<strong>'.$Toolsbar[$label].'</strong>
											</span>

										</a>
                                    </li> 
	
	';

	if (isset($divider)){

    
    $html_result .='               
	                                 <li class="list-divider"></li>';

   }
    
    print $html_result;

  } // End of validate permission

}

?>
