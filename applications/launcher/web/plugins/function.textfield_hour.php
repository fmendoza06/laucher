<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {textfield_hour} plugin
 * Type:     function<br>
 * Name:     textfield_hour<br>
 * Purpose:  componente para ingresar la hora<br>
 * Input:<br>
 *           name = name of the textfield (optional)
 *           id = id of the textfield (optional)
 *           value = puts text inside the textfield (optional)
 *           size = Long of the textfield (optional)
 *           readonly = readonly ('true'|'false') (optional)
 *           disabled = disabled the textfield (optional)
 *
 *<br>
 * Examples : {textfield name="textfield" type="text" size="60" value="LIDIS"}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
*/

function smarty_function_textfield_hour($params, & $smarty) {
	extract($params);
	$html_result = '';
	$html_result .= "<input";
	if (isset ($name)) {
		$html_result .= " name='".$name."'";
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
	$html_result .= " maxlength='8'";

	if (isset ($disabled)) {
		$html_result .= " disabled='".$disabled."'";
	}
	if (isset ($readonly)) {
		if (($readonly == "true") || ($readonly == "True")) {
			$html_result .= " readonly";
		}
	}


	////////////////////// INTERNET EXPLORER / OPERA ////////////////////////////

	if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") or strstr($_SERVER["HTTP_USER_AGENT"], "Opera")) {
	  
	$html_result .= "onKeyPress=\"if((event.keyCode >= 48)&&(event.keyCode <= 57)&&(this.value.length < 8)){
									var cantidad = this.value.length;
									switch(cantidad){
											case 0: if(!((event.keyCode >= 48)&&(event.keyCode <= 50)))
													   event.returnValue = false;   
													break;
											case 2: this.value += ':';
													if(!((event.keyCode >= 48)&&(event.keyCode <= 53)))
													   event.returnValue = false; 
													break;
											case 3: if(!((event.keyCode >= 48)&&(event.keyCode <= 53)))
													   event.returnValue = false; 
													break;
											case 5: this.value += ':';
													if(!((event.keyCode >= 48)&&(event.keyCode <= 53)))
													   event.returnValue = false; 
													break;
											case 6: if(!((event.keyCode >= 48)&&(event.keyCode <= 53)))
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
	$html_result .= "onKeyPress=\"if((event.charCode >= 48)&&(event.charCode <= 57)&&(this.value.length < 8)){
									var cantidad = this.value.length;
									switch(cantidad){
											case 0: if(!((event.charCode >= 48)&&(event.charCode <= 50)))
													   event.preventDefault();   
													break;
											case 2: this.value += ':';
													if(!((event.charCode >= 48)&&(event.charCode <= 53)))
													   event.preventDefault(); 
													break;
											case 3: if(!((event.charCode >= 48)&&(event.charCode <= 53)))
													   event.preventDefault(); 
													break;
											case 5: this.value += ':';
													if(!((event.charCode >= 48)&&(event.charCode <= 53)))
													   event.preventDefault(); 
													break;
											case 6: if(!((event.charCode >= 48)&&(event.charCode <= 53)))
													   event.preventDefault(); 
													break;								
									}
								  }else{
									 if(event.charCode != 0)
										event.preventDefault();			
								  }\"
					 ";
	}

	$html_result .= "onBlur=\" 
			//Calcula el tamaño de la cadena
			var cantidad = this.value.length;
			
			if((cantidad > 0) && (cantidad < 8)){
			   if(cantidad == 1){
			      n = this.value.charAt(cantidad - 1);
				  if(n > 2)
				     this.value = this.value = '0'; 
			   }

			   if((cantidad == 4)||(cantidad == 7)){
			      n = this.value.charAt(cantidad - 1);
				  if(n > 5)
				     this.value = this.value.substring(0,cantidad - 1) + '0';
			   }

  		       while(cantidad < 8){
					if(cantidad == 2 || cantidad == 5)
					   this.value += ':'; 	
					else
					   this.value += '0';
					cantidad++;
			   }
			}\"
					";
	
	$html_result .= ">";

	print $html_result;
}
?>
