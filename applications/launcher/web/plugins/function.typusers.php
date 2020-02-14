<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {typusers} plugin
 * Type:     function<br>
 * Name:     stypusers<br>
 * Purpose:  componente que crea un select con los tipos de usuario<br>
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
 *       {typusers name="Mycombo" table_name="dept" value="deptno"}
 *       {typusers name="Mycombo" table_name="dept" value="deptno" label="dname" size="5" is_null="true"}
 *</pre>
 *
 * @author   Spyro Solutions
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions
 */


function smarty_function_typusers($params, &$smarty)
{
  extract($params);

  //$gateway = Application::getDataGateway("$table_name");
  $usertype = Websession::getIAuthProperty("usertype");
  $service= Application :: loadServices("spyroAuth");
  $gateway =$service->getDataGateway("$table_name");

  $data = call_user_func(array($gateway,"getPaopdesc"),"1");
  $data1=explode(",",$data[0]['paopdesc']); //1~admin
  //print_r($usertype);
  $pos=0;
  for($i=0;$i<count($data1);$i++)
  {
    $user=explode("~",$data1[$i]);
    if ($usertype <=2)
    {
     $v[$i]['typeuserid']= $user[0];
     $v[$i]['typeuserdesc']= $user[1];
    }
    else
    {
       if ($user[0] > 3   )
       {
         $v[$pos]['typeuserid']= $user[0];
         $v[$pos]['typeuserdesc']= $user[1];
         $pos++;
       }
    }
  }

  $v = BorrarValoresDuplicados($v,$value);

  if(!isset($label)){
      $label[0] = $value;
  } else {
      $label = explode(",",$label);
  }

  if(!isset($size)){
      $size = 1;
  }

  if(isset($class)){
     $style = "class='".$class."'";
  }

  if(isset($onchange)){
     $event = "onchange='".$onchange.";'";
  }

  $html_result = '';
  $html_result .= "<select name='$name'  size='$size' $event >";
  
  $html_result .= "<option value=''> -- Select -- </optional>";
  
  for($i=0;$i < count($v);$i++)
  {
    $html_result .= "<option value='";
    $html_result .= $v[$i][$value];
    if(($_REQUEST[$name] == $v[$i][$value])&&($_REQUEST[$name] != ""))
	{
       $html_result .= "' selected>";
    } else {
       $html_result .= "'>";
    }
    $j = 0;
	do {
		if($j != 0) $html_result .= $glue;
		$html_result .= $v[$i][$label[$j]]; $j++;
	} while($j < count($label));
    $html_result .= "</option>";
  }
  $html_result .= "</select>";

  print $html_result;

}




?>
