<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {menusnav} plugin
 * Type:     function<br>
 * Name:     message<br>
 * Purpose: Show Menu Tree for appliaction based in user profile<br>
 * Input:<br>
 *           
 * <br>
 * Examples:  {menusnav}
 *
 * @author   Spyro Solutions 
 * @date 06 Jan 2007
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_menusnav($params, &$smarty)
{
  extract($params);

  //Get All menus for Session
  $menus = $_SESSION['_iauthSession']['userAllMenus'];
  //$html_js="<link rel='stylesheet' href='web/css/menu1.css'>";
  $html_js="<link rel='stylesheet' href='web/menu/menunav/css/menu2.css'>";
  $html_js.="<link rel='stylesheet' href='web/menu/menunav/css/menu3.css'>";
  $html_js.="<script language='JavaScript' src='web/menu/menunav/menu.js'></script>";
  $html_js.="<script language='JavaScript' src='web/menu/menunav/menu_tpl2.js'></script>";
  $html_js.="<script language='JavaScript'>";
  print_r($html_js);

  $gateway = Application::getDataGateway("menus");
  //get de root menu

  $v = call_user_func(array($gateway,"existMenus"),1);
  $html_result .= "var MENU_ITEMS =[ ";

  //$html_result .= "['Main Menu',null,null,";
  // Create Childs Menus
  foreach ($menus as $num_menu => $menuid)
  { 
    //Create Childs menus
      $html_result.= findchilds($menuid);
  }
  //$html_result .=  "]"; //End of Root
  $html_result .= "]"; //End of menu
  $html_result .= ";";

  print $html_result;
  print_r("</script>");

  $html_js.="<script language='JavaScript'>new menu (MENU_ITEMS, MENU_POS2);</script>";
}


function findchilds($father)
{
        $j;
        //get the childs
        //Invoke the service
        $servicemenus = Application :: loadServices("spyroAuth");
        $vr=$servicemenus->getMenusChilds($father);
         // Exist many others items
         for($j=0;$j<count($vr);$j++)
         {
          if ($vr[$j]['menutype']==2)
          {
             //get the command name with commid
             //Invoke the service
             $servicecomm = Application :: loadServices("spyroAuth");
             $command = $servicecomm->getCommname($vr[$j]['menucomm']);
             $menu.="['".$vr[$j]['menudesc']."','"."index.php?action=".$command[0]['commname']."',{'tw' : 'center'}]," ;
          }
          else
          {
            //print_r('Paso por->'.$vr[$j]['menuname']);
            $menu.="['".$vr[$j]['menudesc']."',null,null,";
            $menu.=findchilds($vr[$j]['menuid']);
            $menu.="],";
          }
         }
  return $menu;
}


?>

