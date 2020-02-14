<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {textfield} function plugin 
 * Type:     function
 * Name:     textfield
 * Input:
 *           - name = name of the textfield (optional)
 *           - type = define the type of the textfield (required)
 *           - id = id of the textfield (optional)
 *           - value = puts text inside the textfield (optional)
 *           - size = Long of the textfield (optional)
 *           - typeData = define the type of data that you can introduce (optional)
 *           - readonly = readonly ('true'|'false') (optional)
 *           - disabled = disabled the textfield (optional)
 *           - onClick =  introduce code javascript (optional)
 *           - maxlength = Maximum of characters of the textfield (optional)
 *           - placeholder = Init Text into TextBox
 *
 *
 * Examples : {textfield name="textfield" type="text" size="60" value="LIDIS"}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */
 function smarty_function_textfield($params, &$smarty)
{
	
	/*
										    <div class="form-group">
												<label class="col-lg-3 control-label">Full name</label>
												<div class="col-lg-7">
													<input type="text" class="form-control" name="fullName" placeholder="Full name">
												</div>
											</div>
    */											
    extract($params);
	$contid = WebSession::getIAuthProperty("contid");
    $html_result = '

	';
	$id=$name;
    $html_result .= "<input";
    if (isset($name)){
        $html_result .= " name='".$name."'";
    }
    if (isset($type))
        $html_result .= " type='".$type."'";
    else
        $html_result .= " type='text'";

    if (isset($id)){
        $html_result .= " id='".$id."'";
    }
    else
        $html_result .= " id='".$name."'";

    if (isset($value)){
        $html_result .= " value='".$value."'";
    }else{
        $html_result .= " value='".$_REQUEST[$name]."'";
    }

    if (isset($class)){
        $html_result .= " class='".$class."'";
    }else
		$html_result .= "' class='form-control'"; 

    if (isset($size)){ 
        $html_result .= " size='".$size."'";
    }

    if (isset($maxlength)){
        $html_result .= " maxlength='".$min."'";
    }

    if (isset($max)){
        $html_result .= " max='".$max."'";
    }    

    if (isset($min)){
        $html_result .= " min='".$maxlength."'";
    }    

    if (isset($placeholder)){
        $html_result .= " placeholder='".$placeholder."'";
    }    

    
    if (isset($disabled)){
        $html_result .= " disabled='".$disabled."'";
    }
    
    if (isset($readonly)){
        if (($readonly == "true")||($readonly == "True")){
           $html_result .= " readonly";
        }
    }

    if (isset($required)){
        if (($required == "true")||($required == "True")){
           $html_result .= " required";
        }
    }	
	
    if (isset($onClick)){
        $html_result .= " onClick='".$onClick."'";
    }

    if (isset($onBlur)){
        $html_result .= " onBlur='".$onBlur."'";
    }	
	
	
   
    ////////////////////// INTERNET EXPLORER / OPERA ////////////////////////////
    
    if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") or strstr($_SERVER["HTTP_USER_AGENT"], "Opera")) {
     if (isset($typeData)){
        if ($typeData == 'int' || $typeData == 'number'){
         $html_result .= " onkeypress=\"if (!((event.keyCode>=48) && (event.keyCode<=57)))";
         $html_result .= " event.returnValue = false;\"";
        }
        if ($typeData == 'double'){
         $html_result .= " onkeypress=\"if(event.keyCode != 45){";
         $html_result .= " if(event.keyCode != 46){";
         $html_result .= " if(!((event.keyCode>=48) && (event.keyCode<=57)))";
         $html_result .= " event.returnValue = false;";
         $html_result .= " }else{if((value.indexOf('.') != -1) || (value == '-') || (value.length == 0))";
		 $html_result .= " event.returnValue = false;}";
         $html_result .= " }else{if(value.length != 0)event.returnValue = false;}\"";
        }
        if ($typeData == 'string'){
         $html_result .= " onkeypress=\"if (!(((event.keyCode>=97) && (event.keyCode<=122)) ||";
         $html_result .= " ((event.keyCode>=65) && (event.keyCode<=90)) || (event.keyCode==32)";
         $html_result .= " || (event.keyCode==241) || (event.keyCode==209)))";
         $html_result .= " event.returnValue = false;\"";
        }
     }
    }

    ////////////////////////////NETSCAPE/////////////////////////////*

    else{
     if (isset($typeData)){
        if ($typeData == 'int' || $typeData == 'number'){
         $html_result .= " onkeypress=\"if (!((event.charCode>=48) && (event.charCode<=57) ||";
         $html_result .= " (event.charCode == 0) || (event.charCode == 8)))";
		 $html_result .= " event.preventDefault();\"";
		}
        if ($typeData == 'double'){
         $html_result .= " onkeypress=\"if(event.charCode != 45){";
         $html_result .= " if(event.charCode != 46){";
         $html_result .= " if(!((event.charCode>=48) && (event.charCode<=57) || (event.charCode == 0) ||";
         $html_result .= " (event.charCode == 8)))";
         $html_result .= " event.preventDefault();";
         $html_result .= " }else{if((value.indexOf('.') != -1) || (value == '-') || (value.length == 0))";
		 $html_result .= " event.preventDefault();}";
         $html_result .= " }else{if(value.length != 0)event.preventDefault();}\"";
        }
        if ($typeData == 'string'){
         $html_result .= " onkeypress=\"if (!(((event.charCode>=97) && (event.charCode<=122)) ||";
         $html_result .= " ((event.charCode>=65) && (event.charCode<=90)) || (event.charCode==32)";
         $html_result .= " || (event.charCode==241) || (event.charCode==209) ||";
         $html_result .= " (event.charCode == 0) || (event.charCode == 8)))";
         $html_result .= " event.preventDefault();\"";
        }
     }
    }
	
    $html_result .= ">";
    
    print $html_result;

}

?>
