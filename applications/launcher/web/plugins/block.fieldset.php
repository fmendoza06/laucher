<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {fieldset} block plugin
 * Type:     block<br>
 * Name:     fieldset<br>
 * Purpose:  fieldset for HTML<br>
 * Input:<br>
 *           legend = etiqueta del fieldset
 *
 *
 * Examples: {fieldset legend = "Note"}
 *            {$message}
 *           {/fieldset}
 * Output:  <fieldset >
 *	           <legend>Note</legend>
 *	           Hello Word!!
 *	        </fieldset>
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param content
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 * */
 
 
function smarty_block_fieldset($params, $content, &$smarty)
{
    extract($params);

    if(isset($content)){
       $result = '';
       $result .= "<fieldset>";
       if($legend != ''){
           $result .= "<legend>$legend</legend>";
       }

       $result .= "&nbsp;&nbsp;";
       $result .= $content;
       $result .= "</fieldset>";

      print $result;
   }
}
?>

