<?php
/**
 * Smarty plugin
 *
 * Smarty {tooltip} function plugin 
 * Type:     function
 * Name:     tooltip
 * Input:
 *           - code = id of help tip (mandatory)
 *
 *
 * Examples : {tooltip code="??"}
 *
 * @author   Jose Fernando Mendoza
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions - Oct 2003  
 */
 function smarty_function_tooltip($params, &$smarty)
{
    require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tooltips.lan";
	extract($params);

	//print_r($Tooltips[$code]);
	$message = $Tooltips[$code];
	//$arrHelp = $ToolTips;
		$html_result = "<span class='fa-stack fa-lg' ";
        $html_result .= "onMouseover=\"return overlib('".$message."');\" ";		
	    if(!isset($onMouseOut))
		   $html_result .= "onMouseOut='return nd();' ";
	    else
		   $html_result .= "onMouseOut=\"".$onMouseOut."\" ";
		
	    $html_result .= " >		
                           <i class='fa fa-question-circle fa-stack'></i>
                         </span>";	
		
	//print $html_result;

}

?>
