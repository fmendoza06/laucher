<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {textarea} block plugin
 * Type:     block<br>
 * Name:     textarea<br>
 * Purpose: crea un componente de area de texto en html<br>
 * Input:<br>
 *           name = name of the textarea (optional)
 *           cols = cols of the textarea (optional)
 *           rows = rows of the textarea (optional)
 *           wrap = wrap of the textarea (optional)
 *           id = id of the textarea (optional)
 *           disabled = disabled the textarea (optional)
 *           readonly = only read (optional)
 *
 *<pre>
 * Examples : {textarea name="textarea" cols="12" rows="3" wrap="OFF" value="lidis"}
 *             USB
 *            {/textarea}
 *</pre>
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 * --------------------------------------------------------------------
 */
function smarty_block_textarea($params, $content, &$smarty)
{
    extract($params);
	$id=$name;
    $html_result = '';
  if(isset($content)) {
    $html_result .= "<textarea";
    if (isset($name)){
        $html_result .= " name='".$name."'";
    }
    if (isset($class)){
        $html_result .= " class='".$class."'";
    }
    if (isset($cols)){
        $html_result .= " cols='".$cols."'";
    }
    if (isset($rows)){
        $html_result .= " rows='".$rows."'";
    }
    if (isset($wrap)){
        $html_result .= " wrap='".$wrap."'";
    }
    if (isset($id)){
        $html_result .= " id='".$id."'";
    }
    if (isset($disabled)){
        $html_result .= " disabled='".$disabled."'";
    }
    if (isset($readonly)){
        $html_result .= " readonly='".$readonly."'";
    }
    $html_result .= ">";
    
    if ($content != ''){
        $html_result .= $content;
    }else
        $html_result .= $_REQUEST[$name];
    
    $html_result .= "</textarea>";
    print $html_result;
  }
}

?>
