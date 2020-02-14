<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {calendar} function plugin
 * Type:     function<br>
 * Name:     calendar<br>
 * Date:     Maz 22, 2005<br>
 * Purpose:  desplegar un calendario en un popup<br>
 * Input:<br>
 *           - name = nombre del calendario (requerido)
 *           - form_name = nombre de la forma que contiene el calendario (requerido)
 *           - size = ancho del text (opcional)
 *           - icon = ruta del directorio donde se encuentran las imagenes (opcional)
 * 			 - first_day = primer dia de la semana ('Mo' o 'Su')(opcional)
 * 			 - time_comp = si incluye componente tiempo ('true' o 'false')(opcional)
 * 
 * Examples:
 * <pre>  
 * {form name="Frm-fecha" method="post"}
 *       {calendar name="MyCalendar" form_name ="Frm-fecha" icon="web/images/aplicacion/" }
 * {/form}
 *</pre>
 *
 * Requisitos: librerias libCalendar.js,libCalendarPopupCode.js
 *
 * @author   Spyro Solutions 
 * @version  2.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_calendar($params, &$smarty)
{

extract($params);

	//tamaño del text
	if(!isset($size)){
	   $size = 17;
	}
	
	//componente tiempo
	if(!isset($time_comp)){
	   $time_comp = "false";
	}
	
	//primer dia
	if(!isset($first_day)){
	   $first_day = "Mo";
	}
	
	$html_result = "";
	
	$html_result .= "<table border='0' cellspacing='0'>";
	$html_result .= "<tr>";
	$html_result .= "<td>";
	
	$html_result .= "<input type=text name='".$name."' size=".$size." readonly='true' class='sizeCatalogoInsumo'";
	
	if($_REQUEST[$name] != ""){
	   $html_result .= "value='".$_REQUEST[$name]."'";
	}else{
	   $html_result .= "value=''";
	}
	
	$html_result .= ">";
	
	$html_result .= "</td>";
	
	$html_result .= "<td>";
	
	$html_result .= "<a href=\"javascript:";
		$html_result .= "var cal".$name." = new calendar(document.".$form_name.".".$name.");";
		$html_result .= "cal".$name.".year_scroll = true;";
		$html_result .= "cal".$name.".time_comp = " . $time_comp . ";";
		$html_result .= "cal".$name.".icon_path = '" . $icon . "';";	
		$html_result .= "cal".$name.".format_date = '" . $format_date . "';";
		$html_result .= "cal".$name.".language = '" . Application::getLanguage() . "';";
		$html_result .= "cal".$name.".first_day = '" . $first_day . "';";
		$html_result .= "cal".$name.".popup();";
	$html_result .= "\">";
	//$html_result .= "&nbsp&nbsp&<img ". (isset($icon) ? "src='".$icon."cal.gif'" : "" ) ." width='16' height='16' border='0' alt='Click Here to Pick up the date'>";
	$html_result .= '&nbsp;&nbsp;<i class="fa fa-calendar fa-lg"></i>';
	$html_result .= "</a>"; 
	
	$html_result .= "</td>";
	$html_result .= "</tr>";
	$html_result .= "</table>";

print $html_result;

}
?>