<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {message} plugin
 * Type:     function<br>
 * Name:     message<br>
 * Purpose: imprime los mensajes de la retornados por la aplicacion<br>
 * Input:<br>
 *           id = codigo of message (required)
 * <br>
 * Examples:  {message id="5"}
 *
 * @author   Spyro Solutions 
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_messagecitrium($params, &$smarty)
{

//echo "++++++++++ Session ++++++++++++++";
//echo "<pre>"; print_r($_SESSION); echo "</pre>";
//echo "++++++++++ Request ++++++++++++++";
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";
  extract($params);
 //require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Message.lan";
 $gatewayReferencia = Application::getDataGateway('message');
 $registros_tabla = call_user_func(array($gatewayReferencia,'getAllMessageCitrium'));
 
 //print_r($registros_tabla);
 
 if ($registros_tabla)
 {
   
  $html_result .="<table width='900' border='0' cellspacing='0' cellpadding='0' class=icon>";
  $html_result .= "<tr>
                    <td>";
  $html_result .= "<fieldset>";
  $html_result .= "<legend>Noticias & Mensajes / News & Messages</legend>";
  $html_result .="<table width='900' border='0' cellspacing='0' cellpadding='0' class=icon>";
  for($i=0;$i<count($registros_tabla);$i++)
  {
    $html_result .="<tr class='icon'>
                      <td>";
    if ($registros_tabla[$i][messtype]==19) // Static
    {
      $html_result .= "<center><b>".$registros_tabla[$i][messmess]."</b></center><br>";
    }
    else
    {
     $ScrollBehavior = 'scroll';   //slide
     $ScrollDirection = 'left';
     $ScrollHeight = '12';
     $ScrollWidth = '850';
     $ScrollAmount = '2';
     $ScrollDelay = '30';

     $html_result .="<marquee behavior=\"".$ScrollBehavior."\"
                        direction=\"".$ScrollDirection."\"
                        height=\"".$ScrollHeight."\"
                        width=\"".$ScrollWidth."\"
                        scrollamount=\"".$ScrollAmount."\"
                        scrolldelay=\"".$ScrollDelay."\"
                        truespeed=\"true\" onmouseover=\"this.stop()\" onmouseout=\"this.start()\">";
     $html_result .= "<center><b>".$registros_tabla[$i][messmess]."</b></center><br>";
     $html_result .="</marquee>";
    }
    $html_result .="  <td>
                    <tr>";

  }

  $html_result .="</table>";
  $html_result .= "</fieldset>";
  $html_result .=" </td>
                  </tr>
                  </table>";

 }
   print_r($html_result);
}
?>

