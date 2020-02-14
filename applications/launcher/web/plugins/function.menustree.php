<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {menus} plugin
 * Type:     function<br>
 * Name:     message<br>
 * Purpose: Show Menu Tree for appliaction based in user profile<br>
 * Input:<br>
 *           id = codigo of message (required)
 * <br>
 * Examples:  {menus}
 *
 * @author   Spyro Solutions 
 * @date 06 Jan 2007
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_menustree($params, &$smarty)
{
  extract($params);

  //Get All menus for Session
  $menus = $_SESSION['_iauthSession']['userAllMenus'];

  $html_js="<script language='JavaScript'>";
  print_r($html_js);
  $gateway = Application::getDataGateway("menus");
  //get de root menu
  $v = call_user_func(array($gateway,"existMenus"),1);
  $html_result .= "var TREE_ITEMS =[ ";
  $html_result .= "['Main Menu', '',"; /*Root menu*/

  foreach ($menus as $num_menu => $menuid)
  {
    //Create Childs menus
      $html_result.= findchilds($menuid);
  }

  //$html_result .= findchilds(0);
  $html_result .=  "]"; //End of Root
  $html_result .= "]"; //End of menu
  $html_result .= ";";
  print $html_result;
  print_r("</script>");
  $html_js="<script language='JavaScript' src='web/menu/menutree/tree.js'></script>";
  $html_js.="<script language='JavaScript' src='web/menu/menutree/tree_tpl.js'></script>";
  $html_js.="<script language='JavaScript'>new tree (TREE_ITEMS, tree_tpl);</script>";
  print_r($html_js);
}
function findchilds($father)
{
        $j;
        //get the childs
        //Invoke the service
        $servicemenus = Application :: loadServices("spyroAuth");
        $vr=$servicemenus->getMenusChilds($father);         // Exist many others items
         for($j=0;$j<count($vr);$j++)
         {
          if ($vr[$j]['menutype']==2)
          {
             //get the command name with commid
             //Invoke the service
             $servicecomm = Application :: loadServices("spyroAuth");
             $command = $servicecomm->getCommname($vr[$j]['menucomm']);
             $menu.="['".$vr[$j]['menudesc']."','"."index.php?action=".$command[0]['commname']."']," ;
          }
          else
          {
            $menu.="['".$vr[$j]['menudesc']."','',";
            $menu.=findchilds($vr[$j]['menuid']);
            $menu.="],";
          }
         }
  return $menu;
}


?>

