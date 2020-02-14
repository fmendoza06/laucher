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
 *       {textfield_timestamp name="MyCalendar" form_name ="Frm-fecha" icon="web/images/aplicacion/" }
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

function smarty_function_textfield_timestamp($params, & $smarty) {
	extract($params);
	$html_result = '';
	$html_result .= "<input";
	if (isset ($name)) {
		$html_result .= " name='".$name."'"." id='".$name."'" ;
	}
	$html_result .= " type='text'";

	if (isset ($id)) {
		$html_result .= " id='".$id."'";
	}
	if (isset ($value)) {
		$html_result .= " value='".$value."'";
	} else {
		$cadStrip = stripslashes(htmlentities($_REQUEST[$name]));
		$html_result .= " value=\"$cadStrip\"";
	}
	if (isset ($size)) {
		$html_result .= " size='".$size."'";
	}
	$html_result .= " maxlength='19'";

	if (isset ($disabled)) {
		$html_result .= " disabled='".$disabled."'";
	}
	if (isset ($readonly)) {
		if (($readonly == "true") || ($readonly == "True")) {
			$html_result .= " readonly";
		}
	}

	if (isset ($onChange)) {
		$html_result .= " onChange='".$onChange."'";
	}


	////////////////////// INTERNET EXPLORER / OPERA ////////////////////////////

	if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") or strstr($_SERVER["HTTP_USER_AGENT"], "Opera")) {
	  
	$html_result .= "onKeyPress=\"if((event.keyCode >= 48)&&(event.keyCode <= 57)&&(this.value.length <= 20)){
									var cantidad = this.value.length;
									switch(cantidad){
											case 0: if(!((event.keyCode >49)&&(event.keyCode <51)))
													   event.returnValue = false;
													break;
											case 1: if(!((event.keyCode > 47)&&(event.keyCode < 49))) //0
													   event.returnValue = false;   
													break;
											case 2: if(!((event.keyCode > 48)&&(event.keyCode < 53))) //1,2,3,4
													   event.returnValue = false; 
													break;
											case 3: if(!((event.keyCode >= 48)&&(event.keyCode <= 57))) //1-9
													   event.returnValue = false; 
													break;
											case 4: this.value += '-';
													if(!((event.keyCode >= 48)&&(event.keyCode <= 51))) //0,1,2,3
													   event.returnValue = false; 
													break;
											case 5: if(!((event.keyCode >= 48)&&(event.keyCode <= 49))) //0,1
													   event.returnValue = false; 
													break;
											case 7: this.value += '-';
                                                                                                if(!((event.keyCode >= 48)&&(event.keyCode <= 51))) //0,1,2,3
													   event.returnValue = false; 
													break;
											case 8: if(!((event.keyCode >= 48)&&(event.keyCode <= 51)))
													   event.returnValue = false; 
													break;
											case 9: if(!((event.keyCode >= 48)&&(event.keyCode <= 57)))
													   event.returnValue = false; 
													break;
											case 10: this.value += ' ';
                                                                                                       if(!((event.keyCode >= 48)&&(event.keyCode <= 50)))
													   event.returnValue = false;   
													break;
											case 11: if(!((event.keyCode >= 48)&&(event.keyCode <= 50))) //0-2
													   event.returnValue = false; 
													break;
											case 12: if(!((event.keyCode >= 48)&&(event.keyCode <= 57))) //0-9
													   event.returnValue = false; 
													break;
											case 13: this.value += ':';
                                                                                                 if(!((event.keyCode >= 48)&&(event.keyCode <= 53))) //0-5
													   event.returnValue = false; 
													break;
											case 14:if(!((event.keyCode >= 48)&&(event.keyCode <= 53))) //0-5
													   event.returnValue = false;
													break;
											case 15: if(!((event.keyCode >= 48)&&(event.keyCode <= 57))) //0-9
													   event.returnValue = false; 
													break;
											case 16: this.value += ':';
													if(!((event.keyCode >= 48)&&(event.keyCode <= 53))) //0-5
													   event.returnValue = false; 
													break;
											case 17: if(!((event.keyCode >= 48)&&(event.keyCode <= 53))) //0-5
													   event.returnValue = false;
													break;
											case 18: if(!((event.keyCode >= 48)&&(event.keyCode <= 57))) //0-9
													   event.returnValue = false; 
													break;



									}
								}else{
									event.returnValue = false;			
								}\"
					";
	}
	else {
			////////////////////////////NETSCAPE/////////////////////////////
	$html_result .= "onKeyPress=\"if((event.charCode >= 48)&&(event.charCode <= 57)&&(this.value.length < 16)){
									var cantidad = this.value.length;
									switch(cantidad){
                                                                          
											case 0: if(!((event.charCode >49)&&(event.charCode <51)))
													   event.preventDefault = false;
													break;
											case 1: if(!((event.charCode > 47)&&(event.charCode < 49))) //0
													   event.preventDefault = false;
													break;
											case 2: if(!((event.charCode > 48)&&(event.charCode < 53))) //1,2,3,4
													   event.preventDefault = false;
													break;
											case 3: if(!((event.charCode >= 48)&&(event.charCode <= 57))) //1-9
													   event.preventDefault = false;
													break;
											case 4: this.value += '-';
													if(!((event.charCode >= 48)&&(event.charCode <= 51))) //0,1,2,3
													   event.preventDefault = false;
													break;
											case 5: if(!((event.charCode >= 48)&&(event.charCode <= 49))) //0,1
													   event.preventDefault = false;
													break;
											case 7: this.value += '-';
                                                                                                if(!((event.keyCode >= 48)&&(event.keyCode <= 51))) //0,1,2,3
													   event.preventDefault = false;
													break;
											case 8: if(!((event.charCode >= 48)&&(event.charCode <= 51)))
													   event.preventDefault = false;
													break;
											case 9: if(!((event.charCode >= 48)&&(event.charCode <= 57)))
													   event.preventDefault = false;
													break;
											case 10: this.value += ' ';
                                                                                                       if(!((event.charCode >= 48)&&(event.charCode <= 50)))
													   event.preventDefault = false;
													break;
											case 11: if(!((event.charCode >= 48)&&(event.charCode <= 50))) //0-2
													   event.preventDefault = false;
													break;
											case 12: if(!((event.charCode >= 48)&&(event.charCode <= 57))) //0-9
													   event.preventDefault = false;
													break;
											case 13: this.value += ':';
                                                                                                 if(!((event.charCode >= 48)&&(event.charCode <= 53))) //0-5
													   event.preventDefault = false;
													break;
											case 14:if(!((event.charCode >= 48)&&(event.charCode <= 53))) //0-5
													   event.preventDefault = false;
													break;
											case 15: if(!((event.charCode >= 48)&&(event.charCode <= 57))) //0-9
													   event.preventDefault = false;
													break;
											case 16: this.value += ':';
													if(!((event.charCode >= 48)&&(event.charCode <= 53))) //0-5
													   event.preventDefault = false;
													break;
											case 17: if(!((event.charCode >= 48)&&(event.charCode <= 53))) //0-5
													   event.preventDefault = false;
													break;
											case 18: if(!((event.charCode >= 48)&&(event.charCode <= 57))) //0-9
													   event.preventDefault = false;
													break;


									}
								  }else{
									 if(event.charCode != 0)
										event.preventDefault();			
								  }\"
					 ";
	}

        /*
	$html_result .= "onBlur=\"
			//Calcula el tamaño de la cadena
			var cantidad = this.value.length;

			if((cantidad > 0) && (cantidad < 11)){
			   if(cantidad == 1){
			      n = this.value.charAt(cantidad - 1);
				  if(n > 4)
				     this.value = this.value = '2';
			   }

			   if((cantidad == 4)||(cantidad == 7)){
			      n = this.value.charAt(cantidad - 1);
				  if(n > 5)
				     this.value = this.value.substring(0,cantidad - 1) + '0';
			   }

  		       while(cantidad < 11){
					if(cantidad == 2 || cantidad == 5)
					   this.value += ':';
					else
					   this.value += '0';
					cantidad++;
			   }
			}\"
					";
                */
	$html_result .= ">&nbsp;&nbsp;";
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
	$html_result .= "<img ". (isset($icon) ? "src='".$icon."cal.gif'" : "" ) ." width='16' height='16' border='0' alt='Click Here to Pick up the date'>";
	$html_result .= "</a>";

	print $html_result;
}
?>
