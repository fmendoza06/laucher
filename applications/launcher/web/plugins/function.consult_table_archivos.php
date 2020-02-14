<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {consult_table_referencia} plugin
 * Type:     function<br>
 * Name:     consult_table_referencia<br>
 * Purpose:  Crea una tabla en html, la cual es llenada con los datos de
 *           una tabla en la base de datos.<br>
 * Input:<br>
 *           table_name = nombre de la tabla en la base de datos (required)
 *           llaves = nombre de los identificadores de la tabla (required) si los identificadores son mas de uno deben ser separados por ','
 *           form_name = nombre de la forma que contiene el consult_table (required)
 *           titulos = encabezados de la tabla que se crea(optional)
 *           cambiar_valor = son los campos de la tabla que seran reemplazados por otros valores en otras tablas. (optional)
 *                           la cadena debe tener el siguiente formato :
 *                           1. Nombre del campo que va a cambiar de la tabla 'table_name'
 *                           2. Nombre Tabla de donde se sacara el valor nuevo
 *                           3. Nombre del indice de la tabla de donde se sacara el valor nuevo
 *                           4. Nombre del campo de la tabla expesificada en numeral (2), este sera el nuevo valor.
 *           DataGateway = Compuerta de fila que se utilizara sino se esta presente se utiliza la pordefeco
 *           cantidad_registros = es el numero de registros que cargara la consulta como maximo.(optional)
 *           commnad = nombre del comando que se invocar cuando el usuario desea cambiar a la pagina siguiente
 *           filter  = si se quiere hacer algun filtro campodetabla,campoveusuario(sedebe contruir en la compuerta el metodo getallBykeyword )
 *                     sino se esta presente se utiliza el metodo de la compuerta por defecto sin filtro
 *           title   = Titulo que se le muestra al usuario en la pantalla
 * 
 *
 *<pre>
 * Examples: 
 *           {consult_table_referencia table_name="ciudad"
 *                          llaves="Codigo_Ciudad,Codigo_Pais"
 *                          form_name="FrmCiudad"
 *                          titulos="Codigo Ciudad,Nombre Ciudad,Pais"
 *                          cambiar_valor="Codigo_Pais,pais,Codigo_Pais,Nombre_Pais"
 *                          cantidad_registros = 30
 *                          command = "CmdShowListCiudad"
 *                          type ="LOV" list of values
 *                          filter=userlogin,Login,username,Name,userlana,LastName
 *                          title= " Titulo de la consulta"
                            parameter = parametro para la funcion gateway
 *           }
 *<pre>
 * Nota: Necesita ser parte de una forma!
 *
 * @author   José Feranndo Mendoza
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions - 28 - jul - 2006
 */

function smarty_function_consult_table_archivos($params, &$smarty)
{    

  extract($params);
  extract($_REQUEST);

  require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Toolsbar.lan";
  $report_dir = Application::getReportDirectory()."/".$_REQUEST["folder"]."/generados/";
  
  //print_r("Ruta->".dirname(__FILE__));
  if ($_REQUEST["folder"] !='')
    $registros_tabla=CargarArchivo($report_dir);
  /*
  print_r("<pre>");
  print_r($registros_tabla);  
  print_r("</pre>");  
  */
  // asigna el valor por defecto a la cantidad de registros
  if(!isset($cantidad_registros))
  {
     $cantidad_registros = 100000;
  }

  //calcula la cantidad de paginas
  $cantidad_paginas = ceil(count($registros_tabla)/$cantidad_registros);

  //obtiene el numero de la pagina actual
  if(!isset($_REQUEST[$table_name."__pagina_consult"])){
     $numero_pagina = 1;
  }else{
     if($_REQUEST[$table_name."__pagina_consult"] > $cantidad_paginas ){
       $numero_pagina = $cantidad_paginas;
     }else{
          if($_REQUEST[$table_name."__pagina_consult"] < 1 ){
             $numero_pagina = 1;
          }else{
             $numero_pagina = $_REQUEST[$table_name."__pagina_consult"];
          }
     }
  }

  $html_tabla = '';
  
  if( (is_array($registros_tabla)) &&  (count($registros_tabla)>0))
  {
     if(isset($filter)) {
        CrearMecanismoBusqueda($html_tabla,$form_name,$command,$cantidad_paginas,$table_name,$filter,$Toolsbar);
     }

   if($cantidad_paginas > 1){
     CrearMenuPaginasSiguientes($html_tabla,$table_name,$form_name,$numero_pagina,$cantidad_paginas,$command);
   }
  
  
  $html_tabla .= "<table border='0' align='center' class=adminform>";

     if(!isset($title)) {
         $title = $form_name;
     }
  }	 

  // SE tiene en cuenta si esta una lista de valores o una consulta
  $typenew ='LIST';

  if( (is_array($registros_tabla)) &&  (count($registros_tabla)>0)){

    //$result=GenerarArchivo($titulos,$registros_tabla,$report_dir,$archivo);
    
    if( isset($titulos) && is_array($registros_tabla) && count($registros_tabla)>0)
	{
       CrearEncabezadoTabla($html_tabla,$registros_tabla,$titulos);
    }
     if(!isset($type)) {
         $typenew = 'LIST';
     }
     else
     {
      $typenew=$type;
     }


     CrearCuerpoTabla($html_tabla,$registros_tabla,$table_name,$llaves,$change_value,$cantidad_registros,$numero_pagina,$typenew,$command_showbyid,$form_name);
     
  }
  else
  {
     $html_tabla .= "<tr> <td colspan = 3 align='center'> NO SE ENCONTRARON DATOS </td></tr>";
    
  }

 
  CrearVariablesOcultas($html_tabla,$table_name,$llaves);
  


  $html_result = $html_tabla;

  print $html_result;

}


function CrearMecanismoBusqueda(&$html_tabla,$form_name,$command,$cantidad_paginas,$table_name,$filter,$Toolsbar)
{
    
    $html_tabla .= "<table border='0' class ='detailedViewTextBox' width=50%>";
    $html_tabla .= "<tr colspan=2 >";
    $html_tabla .=       "<td width=5% class='detailedViewHeader' >".$Toolsbar[Filterby]."</td>
                          <td width=5% class='detailedViewHeader' >".$Toolsbar[condiction]."</td>
                          <td width=5% class='detailedViewHeader' >".$Toolsbar[KeyWord]."</td>
                          <td width=5% class='detailedViewHeader' ></td>
                          
                    </tr>
                    <tr colspan=2>";
    //Filed to search
    $html_tabla .=  "<td  class='dvtCellLabel'>";

                       $html_tabla .= selectfield($filter);
                       $html_tabla .= 
                    "</td>";

    $html_tabla .=  "<td width=5% class='dvtCellLabel'>";

                       $html_tabla .= select($Toolsbar);
    $html_tabla .=  "</td>";

 $html_tabla .=    "<td class='dvtCellLabel'><input class='detailedViewTextBox' type='text' name='key_word' id='key_wordid'";
                         if(isset($_REQUEST["key_word"])){
                              $html_tabla .= "value='".$_REQUEST["key_word"]."' ";
                          }

                          if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") or strstr($_SERVER["HTTP_USER_AGENT"], "Opera")){
                               $html_tabla .= "onKeyPress=\"if(event.keyCode == 13){";
		                   $html_tabla .= "action.value='".$command."';";
                               $html_tabla .= $form_name.".submit();}\">";
                          }else{
                                 $html_tabla .= "onKeyPress=\"if(event.keyCode == 13){";
                                $html_tabla .= "action.value='".$command."';}\">";
                           }

    $html_tabla .=  "</td>";

    $html_tabla .=  "<td width=3%>";
                       $html_tabla .= "<input type='button' class='boton' value='".$Toolsbar[find]."' Onclick=\"disableButtons();action.value='".$command."';";
                       if($cantidad_paginas > 1 ){
                           $html_tabla .= $table_name."__pagina_consult.value = '1';";
                       }
                       $html_tabla .= $form_name.".submit();\">";
    $html_tabla .=  "</td>";

   //$html_tabla .= "<td width=15% class='dvtCellLabel' align=right><input type='button' class='boton' value='Buscar' Onclick=\"disableButtons();action.value='".$command."';";
   //if($cantidad_paginas > 1 ){
   //    $html_tabla .= $table_name."__pagina_consult.value = '1';";
   //}
   //$html_tabla .= $form_name.".submit();\"></td>";
   $html_tabla .= "</tr>";
   $html_tabla .= "</table><br>";
}


/*
* Crea el select con la info de la tabla 'tipo_insumo'
*/
/*
* Crea el select con la info de la tabla 'tipo_insumo'
*/
function selectfield($filter)
{     
      require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tableheader.lan";

  $html_result = '';
  $html_result .= "<select name='sel_condicionfield' class='detailedViewTextBox'>";

  //$html_result .= "<option value=''></optional>";

 //convierte la cadena a un vector
 $filter_fields = explode(",",$filter);

  for($i=0;$i < count($filter_fields);$i+=2){
       $html_result .= "<option value='".$filter_fields[$i]."'";
       if (isset($_REQUEST[sel_condicionfield]))
           if($_REQUEST["sel_condicionfield"] ==$filter_fields[$i]) 
            $html_result .= " selected ";
            $html_result .= ">".$Tableheaders[$filter_fields[$i+1]]."</option>";
       //$html_result .= ">".$filter_fields[$i+1]."</option>";
      
     }
  $html_result .= "</select>";

  return $html_result;
}


/*
* Crea el select con la info de la tabla 'tipo_insumo'
*/
function select($Toolsbar)
{
  $html_result = '';
  $html_result .= "<select name='sel_condiction' class='detailedViewTextBox'>";

  $html_result .= "<option value='='".sel_condiction("=").">".$Toolsbar[ExactPhrase]."</option>";
  $html_result .= "<option value='!='".sel_condiction("!=")." >".$Toolsbar[Diff]."</option>";
  $html_result .= "<option value='_%'".sel_condiction("_%").">".$Toolsbar[BeginWith]."</option>";
  $html_result .= "<option value='%_'".sel_condiction("%_").">".$Toolsbar[EndWith]."</option>";
  $html_result .= "<option value='%'".sel_condiction("%").">".$Toolsbar[Content]."</option>";

  $html_result .= "</select>";

  return $html_result;
}

function sel_condiction ($condiction)
{
    if (isset($_REQUEST["sel_condiction"]))
     if ($_REQUEST["sel_condiction"] == $condiction)
      return "selected";
     else
      return "";
}
/*
* Crea el encabezado de la tabla, con el valor de la variable $ titulos,
* si titulos no esta setiado por defecto el encabezado de la tabla
* son los nombre de los campos de la tabla en la base de datos
*/
function CrearEncabezadoTabla(&$html_tabla,$registros_tabla,$titulos){
    require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tableheader.lan";
    //si la variable titulos esta setiada crea un vector con los titulos
    //sino crea un vector con los indices del vector $registros_tabla
     if(isset($titulos) && $titulos !=''){
        $m = explode(",",$titulos);
     }else{
        //pasa los titulos a un vector
        $m = array_keys($registros_tabla[0]);
     }

    // crea el encabezado de la tabla
    $html_tabla .= "<tr>";
    //$html_tabla .= "<td>";
    for($i=0;$i < count($m);$i++){
        $html_tabla .= "<th>";
        //$html_tabla.= $m[$i];
        $html_tabla.=$Tableheaders[$m[$i]];
        //$html_tabla .= "</td>";
    }
	
    $html_tabla .= "<th>Descargar</th></tr>";

}

/*
* Crea una tabla en html con los datos de la tabla de la base de datos
*/
function CrearCuerpoTabla(&$html_tabla,$registros_tabla,$table_name,$llaves,$change_value,$cantidad_registros,$numero_pagina,$type,$command_showbyid,$form_name){

   $gatewaypersona_orden = Application::getDataGateway("ordepers");

   $usuario=$_SESSION["auth"]["username"];
   $contid = WebSession::getIAuthProperty("contid");
   //obtener las llav,s de la tabla y pasarlas a un vector
   $keys = explode(",",$llaves);

   for($i=(($numero_pagina-1)*$cantidad_registros);($i < $numero_pagina*$cantidad_registros )&&($i < count($registros_tabla));$i++){

      //$html_tabla .= "<tr>";
	  
	  //define el estilo de la fila
      if($i%2 == 0)
         $valor_estilo ="row0";
      else
         $valor_estilo ="row1";
     
      $html_tabla .= "<tr class='".$valor_estilo."'>";
       //obtener una fila completa de la tabla de la base de datos.
        $m = array_values($registros_tabla[$i]);
        if ($type !='LOV') {
           //$html_tabla .= "<th></th>";
        }
        for($j=0;$j < count($m);$j++){

        	
           $html_tabla .= "<td>";
           //$html_tabla .= "<DIV id='CELDA".$i.$j."'>";
		   if ($j==0)
		     $html_tabla .= "<img src='web/images/page.gif'>&nbsp;";
		   if ($j==0)
              $html_tabla .= "<a  href='./".$m[4]."'>".$m[$j]."</a>";
           else		   
             $html_tabla .= $m[$j];


           
           $html_tabla .= "</td>";
        }
		
           /**/
           //$html_tabla .= "<td>";
           //$html_tabla .= "<input type=button name=".$table_name."__".$keys[0]."bodega_btn".$m[0]." value='Descargar' onClick=\"asignar(".$m[0].",'".$table_name."__".$keys[0]."bodega_".$m[0]."','".$usuario."')\">";
           //$html_tabla .= "</td>";
		   
		   
      $html_tabla .= "</tr>";
   }
   
}

/*
* Cambia los valores de del vector '$registros_tabla' que son indices
* de otras tablas en la base de datos
*/
function CambiarValorTabla(&$registros_tabla,$change_value,$indice){
 //convierte la cadena a un vector
 $parametros_cambiar = explode(",",$change_value);

  for($i=0;$i < count($parametros_cambiar);$i+=4){
    //llama la clase de la tabla
     $gateway = Application::getDataGateway($parametros_cambiar[$i+1]);
    //optiene todos los datos de la tabla
     $datos = call_user_func(array($gateway,"getAll".$parametros_cambiar[$i+1]));
     for($z=0;$z < count($datos);$z++){
         //cambia el valor del vector por el nombre si los codigos son iguales
         $data='';
         if($registros_tabla[$indice][$parametros_cambiar[$i]] == $datos[$z][$parametros_cambiar[$i+2]] ){
            $param=explode('/',$parametros_cambiar[$i+3]);
            for($j=0;$j<count($param);$j++)
            {
             //$registros_tabla[$indice][$parametros_cambiar[$i]] = $datos[$z][$parametros_cambiar[$i+3]];
             $data.=$datos[$z][$param[$j]]." ";
            }
            //$registros_tabla[$indice][$parametros_cambiar[$i]] = $datos[$z][$parametros_cambiar[$i+3]];
            $registros_tabla[$indice][$parametros_cambiar[$i]] = $data;
            break;
         }
         //$z++;
     }

  }
}



/*
* Crea un Radio Buton en la primera columna de la tabla en html que se esta generando,
* Por cada Radio Button se genera un codigo en JavaScript para en la propiedad
* 'Onclick' para asignar los vales de la llaves de la tabla en la base de datos
* en los campos ocultos. (Ver CrearVariablesOcultas)
*/
function CrearRadioButtonsp(&$html_tabla,$table_name,$registros_tabla,$keys,$i,$valor_estilo,$command_showbyid,$form_name){
  $html_tabla .= "<td class='".$valor_estilo."'>";
  $html_tabla .= "<input type='checkbox'";
  $html_tabla .= " name='".$table_name."__keys' onClick=\"";
  for($z=0;$z < count($keys);$z++){
      if($z == 0 ){
         $html_tabla .= $table_name."__".$keys[$z].".value = '".$registros_tabla[$i][$keys[$z]]."'";
      }else{
         $html_tabla .= ";".$table_name."__".strtoupper($keys[$z]).".value = '".$registros_tabla[$i][$keys[$z]]."'";
      }
  }
  $html_tabla .= "\">";
//  $html_tabla .= ";disableButtons();".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\">";

  $html_tabla .= "</td>";
}


function CrearRadioButtonlov(&$html_tabla,$table_name,$registros_tabla,$keys,$i,$valor_estilo){

	$html_tabla .= "<td class='detailedViewHeader'>";
	$html_tabla .= "<input type='radio' ";
	$html_tabla .= " name='Keys' value=\"";
	for($z=0;$z < count($keys);$z++){
		$html_tabla .= $i;
	}

      //  $html_tabla .= ";disableButtons();".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\">";

	$html_tabla .= "\">";
	$html_tabla .= "</td>";
}



/*
* Crea campos ocultos segun la cantidad de llaves tenga la tabla en la base de
* datos.
*/
function CrearVariablesOcultas(&$html_tabla,$table_name,$llaves)
{
 $keys = explode(",",$llaves);

 for($i=0;$i < count($keys);$i++){
    $html_tabla .= "<input type='hidden' name='".$table_name."__".$keys[$i]."'>";
 }
}


/*
*
*
*/
function  CrearMenuPaginasSiguientes(&$html_tabla,$table_name,$form_name,$numero_pagina,$cantidad_paginas,$command)
{
 $html_tabla .= "<table border='0' align='left' width=10% class='dvtCellLabel' >";
 
 $html_tabla .= "<tr height=30 width=10%>
                 
                 <td width=10% hieght=10 class='detailedViewHeader'><div align='center'><font class='small' size='2'>Pag :".$numero_pagina."/".$cantidad_paginas."</font></div></td>
                 
                 ";
//</tr>
// $html_tabla .= "<tr>";

 if($numero_pagina != 1){
    $html_tabla .= "<td width=10% height=10 class='detailedViewHeader'><div align='center'><a href='#' onClick=\"".$form_name.".".$table_name."__pagina_consult.value = parseInt(".$form_name.".".$table_name."__pagina_consult.value)-1;".$form_name.".action.value='".$command."';".$form_name.".submit();\"><IMG class='imgDoc' src='web/images/rewind.png'></a></div></td>";
    
 }else{
    $html_tabla .= "<td width=10% height=10 class='detailedViewHeader'><div align='center'><IMG class='imgDoc' src='web/images/rewinddark.png'></div></td>";
 }

 $html_tabla .= "<td width=10% hieght=10 class='detailedViewHeader'><div align='center'><input class='class='detailedViewTextBox'' type='text' name='".$table_name."__pagina_consult' maxlength='3' size='2' value='".$numero_pagina."' ";

 if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") or strstr($_SERVER["HTTP_USER_AGENT"], "Opera")){
        $html_tabla .= "onKeyPress=\"if((event.keyCode == 13) && (".$form_name.".".$table_name."__pagina_consult.value == '')){";
		$html_tabla .= "event.returnValue = false;";
		$html_tabla .= "}else{";	
		$html_tabla .= "if((event.keyCode == 13) && (".$form_name.".".$table_name."__pagina_consult.value != '')){";
		$html_tabla .= $form_name.".action.value='".$command."';";
        $html_tabla .= $form_name.".submit();}}";
        
        $html_tabla .= "if (!((event.keyCode>=48) && (event.keyCode<=57))){event.returnValue = false;}\"";
        
        
 }else{
        $html_tabla .= "onKeyPress=\"if(event.keyCode == 13){";
        $html_tabla .= $form_name.".action.value='".$command."';";
        $html_tabla .= "if(".$form_name.".".$table_name."__pagina_consult.value == ''){";
        $html_tabla .= $form_name.".".$table_name."__pagina_consult.value = '1';";
	    $html_tabla .= "}}";
	    $html_tabla .= "if (!((event.charCode>=48) && (event.charCode<=57) ||(event.charCode == 0) || (event.charCode == 8))){event.preventDefault();}\"";
 }

 $html_tabla .= "></div></td>";

 if($numero_pagina < $cantidad_paginas){
    $html_tabla .= "<td width=10%  height=10 class='detailedViewHeader'><div align='center'><a href='#' onClick=\"".$form_name.".".$table_name."__pagina_consult.value = parseInt(".$form_name.".".$table_name."__pagina_consult.value)+1;".$form_name.".action.value='".$command."';".$form_name.".submit();\"><IMG class='imgDoc' src='web/images/forward.png'></a></div></td>";
    
 }else{
    $html_tabla .= "<td width=10% heightt=10 class='detailedViewHeader'><div align='center'><IMG class='imgDoc' src='web/images/forwarddark.png'></div></td>";
 }
 //$html_tabla .= "</tr>";
 
// $html_tabla .= "<tr>";

 $html_tabla .= "<td width=10% height=10 colspan=3 class='detailedViewHeader'><div align='center'><input class='boton' type='button' value='  Ir  ' onClick=\"if((".$form_name.".".$table_name."__pagina_consult.value < 1) || (".$form_name.".".$table_name."__pagina_consult.value > ".$cantidad_paginas.")){";
 $html_tabla .= "alert('Error: Debe ingresar un numero entre 1 y ".$cantidad_paginas."');";
 $html_tabla .= $form_name.".".$table_name."__pagina_consult.value = ''";
 $html_tabla .= "}else{";
 $html_tabla .= $form_name.".action.value='".$command."';";
 $html_tabla .= $form_name.".submit();";
 $html_tabla .= "}\"></div></td>";

 $html_tabla .= "</tr>";

 $html_tabla .= "</table>";

}


/*
* Crea Archivo con Arreglo de datos
* datos.
*/
function CargarArchivo($ruta)
{
      $data=array();
      require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tableheader.lan";
	  
     //$fd = fopen ("/usr/local/apache2/htdocs".$ruta."/".$archivo.".xls", "w");
	  //$fd = fopen ($ruta."/".$archivo.".xls", "w");
	  
      $dir=dirname($ruta);
      $directory=opendir($ruta); 

      $i=0;
      while ($archivo = readdir($directory)) 
	  { 
	    if ($archivo=="." || $archivo==".."){ 
		  echo " "; 

	    }else{
		   $data[$i]["FILENAME"]=$archivo;
		   $data[$i]["FILEPATH"]=$ruta."/".$archivo;
		   $data[$i]["FILESIZE"]=formatSizeUnits(filesize($ruta."/".$archivo));
		   $last_modified = filemtime($ruta."/".$archivo);
		   $data[$i]["FILETIME"]=date("Y-m-d h:i:s", $last_modified);
		   $data[$i]["RUTA"]    ="report/".$_REQUEST["folder"]."/generados/".$archivo;
		   $i++;
	  } 
	  
     } 
	 //arsort ($data);
	 return $data;

}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
  
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
  
    $bytes /= pow(1024, $pow);
  
    return round($bytes, $precision) . ' ' . $units[$pow];
}

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

?>