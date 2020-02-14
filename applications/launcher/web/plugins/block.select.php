<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {select} block plugin
 *
 * Type:     block<br>
 * Name:     select<br>
 * Purpose:  crea las etiquetas para el componente select en html<br>
 * Input:<br>
 *           name = name of select (optional)
 *           id = id of select (optional)
 *           options = allow insert diferents types of options (optional)
 *           
 * <pre>
 * Examples 1: {select name="nombres" id="nom" options="Leider,Hemerson,Jaime"}{/select}
 *
 * Examples 2: Require the tag {option}
 *
 *             {select name="nombres" id="nom"}
 *               {option id="cod1"}Hemerson{/option}
 *               {option id="cod2"}Leider{/option}
 *             {/select}
 *</pre>
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */
function smarty_block_select($params, $content, &$smarty)
{

extract($params);

if(strstr($content,$_REQUEST[$name])){
   $content = str_replace("<option>".$_REQUEST[$name],"<option selected>".$_REQUEST[$name],$content);
}

$html_result = '';
if(isset($content)){
    $html_result .= "<select";
    if (isset($name)){
        $html_result .= " name=\"$name\"";
    }
    if (isset($id)){
        $html_result .= " id=\"$id\"";
    }

    if(isset($class)){
       $html_result .= " class='".$class."'";
    }
    
    $html_result .= ">";
    //****************************************************************
    if (isset($options)){
        $tipo = gettype($options);
     if ($tipo == 'string'){

          for ($i=0 ; $i <= strlen($options); $i++){
              $resto = strstr($options, ',');
              if ($resto != ''){
                 $cant = strlen($resto);
                 $option = substr($options, 0, -$cant);
                 $html_result .="<option value=\"$option\" id=\"$option\">$option</option>";
                 $options = substr($resto, 1);
                 $i=0;
             }
             else{
                 $html_result .="<option value=\"$options\" id=\"$options\">$options</option>";
                 $options = '';
             }
         }
      }else{
         for ($i=0 ; $i<= sizeof($options)-1;$i++){
             $html_result .="<option value=\"$options[$i]\" id=\"$options[$i]\">$options[$i]</option>";
         }
     }

    }
    //*******************************************************************
    
    $html_result .= $content;
    $html_result .= "</select>";
    print $html_result;
}
}

?>
