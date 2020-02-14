<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {menusez} plugin
 * Type:     function<br>
 * Name:     message<br>
 * Purpose: Show Menu for appliaction based in user profile<br>
 * Input:<br>
 *           Nothing
 * <br>
 * Examples:  {menusez}
 *
 * @author   Spyro Solutions
 * @date 06 Jan 2007
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions
 */

function smarty_function_menusez($params, &$smarty)
{
  extract($params);

  //Get All menus for Session
  $menus = $_SESSION['_iauthSession']['userAllMenus'];

  $html_js="<script language='JavaScript'>";
  print_r($html_js);
  if (count($menus)> 1)
  {
    //Create Main Menu
    $html_result2.="Type:Vertical,Verdana,8,FFFFFF,333333,Arial,8,FFFF86,4080be,FFCC00,007FEE,333333,1,1,3,,web/images/arrow.gif,web/images/arrow.gif,10,100,10,50"."~";
    $html_result2.="Panel=Main~";
    foreach ($menus as $num_menu => $menuid)
    {

     //Invoke the service
     $servicecomm = Application :: loadServices("spyroAuth");
     $menu = $servicecomm->getByIdMenus($menuid);
     if (!empty($menu[0]['menuicon']))
     {
       $template= Application::getTemplate("template");
       $html_result2.="<img src='web/images/".$template."/tools_bar_page/".$menu[0]['menuicon']."' width=16 height=16>".$menu[0]['menudesc']."^^"."Root".$num_menu.'~';
     }
     else
       $html_result2.=$menu[0]['menudesc']."^^"."Root".$num_menu.'~';
   }
  }

  // Create Childs Menus
  foreach ($menus as $num_menu => $menuid)
  { //Create Childs menus
      $html_result2.= findchilds2($menuid,"Root".$num_menu);
      //print_r($html_result2);
  }

  $html_result2 =explode('~',$html_result2);
  $html_result2=menuArrays($html_result2);
  print_r("</script>");
  $html_js="<script language=\"JavaScript\"> ez_codePath = 'web/menu/menuez/' </script>";
  $html_js.="<script language=\"JavaScript\"> document.write(\"<SCR\" + \"IPT SRC='\" + ez_codePath + \"ezloader.js'><\/SCR\" + \"IPT>\"); </script>";
  $html_js.="<script language=\"JavaScript\">menusGo()</script>";
  $html_js.="<script > showPermPanel('Main', 10, 350) </script>";
  print_r($html_js);

}

	/**
		Copyright 2007  Spyro Solutions

		Build Menu Items Childs
		@return integer
		@author jmendoza <spyrosolutions@yahoo.com>
		@date 06 Jan 2007
		@location Cali-Colombia
	*/

function findchilds2($father,$title)
{

        $j;
        $menu="";
        //$menu="Type:Vertical,Arial,9,333333,0000FF,Arial,9,111111,dddddd,FFFFFF,AAAAAA,987891,1,1,3,,web/images/arrow.gif,web/images/arrow.gif,10,5,5,50"."~";
        $menu="Type:Vertical,Verdana,8,FFFFFF,333333,Arial,8,FFFF86,4080be,FFCC00,007FEE,333333,1,1,3,,web/images/arrow.gif,web/images/arrow.gif,10,100,10,50"."~";
        $menu.="Panel=".$title."~";

        //Invoke the service
        $servicemenus = Application :: loadServices("spyroAuth");
        $vr=$servicemenus->getMenusChilds($father);

         // Exist many others items
         for($j=0;$j<count($vr);$j++)
         {
          if ($vr[$j]['menutype']==2)
          {
             //Invoke the service
             $servicecomm = Application :: loadServices("spyroAuth");
             $command = $servicecomm->getCommname($vr[$j]['menucomm']);

             //get the command name with commid
             if (!empty($vr[$j]['menuicon']))
             {
              $template= Application::getTemplate("template");
              $menu.="<img src='web/images/".$template."/tools_bar_page/".$vr[$j]['menuicon']."' width=16 height=16>".$vr[$j]['menudesc']."^index.php?action=".$command[0]['commname'].",center" ."~";
             }
             else
              $menu.=$vr[$j]['menudesc']."^index.php?action=".$command[0]['commname'].",center" ."~";

          }
          else
          {
            if (!empty($vr[$j]['menuicon']))
            {
             $template= Application::getTemplate("template");
             $menu.="<img src='web/images/".$template."/tools_bar_page/".$vr[$j]['menuicon']."' width=16 height=16>".$vr[$j]['menudesc']."^^".$vr[$j]['menuname'].'~';
            }
            else
             $menu.=$vr[$j]['menudesc']."^^".$vr[$j]['menuname'].'~';

          }
         }

         //Create submenus
         for($j=0;$j<count($vr);$j++)
         {
          if (($vr[$j]['menutype']==1))
          {
            $menu.=findchilds2($vr[$j]['menuid'],$vr[$j]['menuname']);
          }
         }
        return $menu;
}


	/**
		Copyright 2007  Spyro Solutions

		Generate Java Scrip Vars
		@return integer
		@author jmendoza <spyrosolutions@yahoo.com>
		@date 06 Jan 2007
		@location Cali-Colombia
	*/

function menuArrays($lineItems) {
        $id = "ez_";
        $panelopt=Array ('paneltype','fonttype','fontsize','fontcol','fontcolhi','tfonttype','tfontsize','tfontcol','bgcol','bgcolhi','tbgcol','borcol', 'outerborsize', 'innerborsize', 'textpad', 'bgimg','arrow', 'arrowhi', 'arrowsz', 'xover', 'yover', 'horizspc');
	$entry = "";//new Array();
	$entry=readEntries($lineItems);
	$MenuNum = -1;
	$ItemNum = -1;
	$menu = "";//new Array();
	$root = "";// new Array();
	$menuID = "";// new Array();
	$rootProp = "";// new Array();
	$rootNum = -1;
	$bar=false;
	$isBar = "";
	$panelname = "";

	for ($i=0; $i< count($entry); $i++) {

		if (substr($entry[$i],0,5) == "Type:") { //new Panel
			$rootNum++;
			$root[$rootNum] = $MenuNum+1 ;
			$rootProp[$rootNum] = $entry[$i];
			if (substr($entry[$i],0,8) == "Type:Bar")
                          $bar=true;
                        else
                          $bar=false;
		}

		else if (substr($entry[$i],0,6) == "Panel=") { //new Panel
			$MenuNum++;
			$menu[$MenuNum] = "";
			$menuID[$MenuNum] = substr($entry[$i],6,strlen($entry[$i]));
			$ItemNum = -1;
			$isBar[$MenuNum] = $bar;
			$panelname[$MenuNum] = substr($entry[$i],6);
		}

		else {
			$ItemNum++;
			$menu[$MenuNum][$ItemNum] = $entry[$i];
		}

	}

         //replacing all the menu IDs in the menu entries with the appropriate menu index
         for ($i=0; $i<count($menu) ; $i++) {  //

		for ($j =0; $j<count($menu[$i]); $j++) {
			$contents = explode('^',$menu[$i][$j]); //
			if (count($contents) >= 3) { //i.e. there is a child menu
				$childIndex = -1;
				for ($k=0; $k< count($menu); $k++)
                                   if ($menuID[$k] == $contents[2])
                                      $childIndex = $k;

				$menu[$i][$j] = $contents[0]."^".$contents[1]."^".$childIndex;
			}
		}
	}



	$output = "var ".$id."Menu = new Array()".chr(13); //\n

	for ($i=0; $i< count($menu); $i++) {
		$output .= $id.'Menu['.$i.']= new Array(';

		for ($j =0; $j < count($menu[$i]); $j++) {
			$output .= '"' .$menu[$i][$j];
			if ($j < (count($menu[$i])-1))
                          $output .= '", ';
                        else
                          $output.= '")';
		}

		$output .= chr(13); //"\n";
	}

	$ez_fontInfo = "var ez_fontInfo = new Array(";
	$ez_tfontInfo = "var ez_tfontInfo = new Array(";
	$ez_colInfo = "var ez_colInfo = new Array(";
	$ez_borSize = "var ez_borSize = new Array(";
	$ez_txtPad = "var ez_txtPad = new Array(";
	$ez_arrow = "var ez_arrow = new Array(";
	$ez_barSpc = "var ez_barSpc = new Array(";
	$ez_root = "var ez_root = new Array(";
	$ez_xover = "var ez_xover = new Array(";
	$ez_yover = "var ez_yover = new Array(";
	$ez_bg = "var ez_bg = new Array(";


	for ($i=0; $i <= $rootNum; $i++) {
		$panelprop = explode(",",$rootProp[$i]);
		if ($i == $rootNum)
                  $term = ")".chr(13) ; //$term = ")\n" ;
                else
                  $term = ", ";
		$prop="";
		for ($j=0; $j<count($panelopt); $j++){
			$prop[$panelopt[$j]]= $panelprop[$j];
		}
		$ez_fontInfo .= '"' . $prop['fonttype'] . ',' . $prop['fontsize'] . 'pt,#' . $prop['fontcol'] . ',#' . $prop['fontcolhi'] . '"' . $term;
		$ez_tfontInfo .= '"' . $prop['tfonttype'] . ',' . $prop['tfontsize'] . 'pt,#' . $prop['tfontcol'] . '"' . $term;
		$ez_colInfo .= '"#' . $prop['bgcol'] . ',#' . $prop['bgcolhi'] . ',#' . $prop['tbgcol'] . ',#' . $prop['borcol'] . '"' . $term;
		$ez_borSize .= '"' . $prop['outerborsize'] . ',' . $prop['innerborsize'] . '"' . $term;
		$ez_txtPad .= '"' . $prop['textpad'] . '"' . $term;
		$ez_arrow .= '"' . $prop['arrow'] . ',' . $prop['arrowhi'] . ',' . $prop['arrowsz'] . '"' . $term;
		$ez_barSpc .= '"' . $prop['horizspc'] . '"' . $term;
		$ez_root .= '"' . $root[$i] . '"' . $term;
		$ez_xover .= '"' . $prop['xover'] . '"' . $term;
		$ez_yover .= '"' . $prop['yover'] . '"' . $term;
		$ez_bg .= '"' . $prop['bgimg'] . '"' . $term;

	}

        
	$ez_isBar = "var ez_isBar = new Array(" ;
	$ez_pname = "var ez_pname = new Array(";
	for ($i=0; $i< count($menu); $i++) {
		$ez_pname .= "'" . $panelname[$i] . "'";
		if ($isBar[$i])
                  $ez_isBar .= "true"; 
                else 
                   $ez_isBar .= "false";
		if ($i == (count($menu)-1))
                   $term = ')'.chr(13); //$term = ')\n'
                else
                   $term = ', ';
		$ez_isBar.=$term;
		$ez_pname.=$term;
	}

        print_r($output.$ez_fontInfo.$ez_tfontInfo.$ez_colInfo.$ez_borSize.$ez_txtPad.$ez_arrow.$ez_barSpc.$ez_root.$ez_xover.$ez_yover.$ez_bg.$ez_pname.$ez_isBar);
 }

function readEntries($menuitems) {
  $lines = $menuitems;
  $numLines = 0;
  foreach ($lines as $linea_num => $line) {
    //echo htmlspecialchars($linea) ;
    if (($line != '\r') && (trim($line) != ''))
    {
      $numLines ++;
      $entry[$numLines-1] = trim($line);
    }
  }
  return  $entry;
}



?>

