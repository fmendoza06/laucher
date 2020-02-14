<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {typusers} plugin
 * Type:     function<br>
 * Name:     displaysize<br>
 * Purpose:  componente que define el tamaño de la pantalla<br>
 * Input:<br>
 *      name = is the name of the select (required)
 *      table_name = name of table in data base (required)
 *      value = is the name of the row in the tabla that specifies the value of select (required)
 *      label = is the name of the row in the tabla that specifies the laber of select(optional)
 *      size = this sets the number of visible choices(optional)
 *      is_null = especifica si el 'select_row_table' debe tener el valor nulo.  'true|false' (optional)
 *
 *<pre>
 * Examples: 
 *       {displaysize size=1024}
 *</pre>
 *
 * @author   Spyro Solutions
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions
 */


function smarty_function_displaysize($params, &$smarty)
{
  extract($params);
  
  $sizedefault= 980;

  //$displaysize= Application :: loadDisplaySize("spyroAuth");
  $displaysize= $sizedefault;

  if (isset($size))
     $html_result = $size;
  else
     $html_result = $displaysize;

  print $html_result;

}




?>
