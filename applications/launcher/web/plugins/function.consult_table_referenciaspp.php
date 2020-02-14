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

function smarty_function_consult_table_referenciaspp($params, &$smarty)
{

    extract($params);
    require Application::getLanguageDirectory() . '/' . Application::getLanguage() . "/Toolsbar.lan";
    $gatewayReferencia = Application::getDataGateway($DataGateway);
 //$ruta='varios';
    $report_dir = Application::getReportDirectory() . "/" . $ruta . "/generados";
 //$genfile='on';
 //$archivo='varios';

    if (!isset($loadTable)) {
        $loadTable = "getAll" . ucfirst($table_name);
    }



    if (isset($filter)) {
        if ($_REQUEST['key_word'] == '') {
     
   //consulta todos los registros
            $registros_tabla = call_user_func(array($gatewayReferencia, $loadTable));

        } else {
          //$registros_tabla = call_user_func(array($gatewayReferencia,$loadTable));
            $registros_tabla = call_user_func(array($gatewayReferencia, "getallBykeyword"), $_REQUEST["key_word"], $_REQUEST["sel_condiction"], $_REQUEST["sel_condicionfield"]); 
          //unset($_REQUEST["key_word"]);
          //unset($filter);


        }
    } else {
        if (isset($parameters)) {

            if (isset($_REQUEST[$parameters]))
                $parameters = $_REQUEST[$parameters];

            $registros_tabla = call_user_func(array($gatewayReferencia, "$loadTable"), $parameters);
        } else
            $registros_tabla = call_user_func(array($gatewayReferencia, $loadTable));



    }

  // asigna el valor por defecto a la cantidad de registros
    if (!isset($cantidad_registros)) {
        $cantidad_registros = 25;
    }

  //calcula la cantidad de paginas
    $cantidad_paginas = ceil(count($registros_tabla) / $cantidad_registros);

  //obtiene el numero de la pagina actual
    if (!isset($_REQUEST[$table_name . "__pagina_consult"])) {
        $numero_pagina = 1;
    } else {
        if ($_REQUEST[$table_name . "__pagina_consult"] > $cantidad_paginas) {
            $numero_pagina = $cantidad_paginas;
        } else {
            if ($_REQUEST[$table_name . "__pagina_consult"] < 1) {
                $numero_pagina = 1;
            } else {
                $numero_pagina = $_REQUEST[$table_name . "__pagina_consult"];
            }
        }
    }

    $html_tabla = '';
  
    /*
     if(isset($filter)) {
        CrearMecanismoBusqueda($html_tabla,$form_name,$command,$cantidad_paginas,$table_name,$filter,$Toolsbar);
     }
     */

    if ($cantidad_paginas > 1) {
        CrearMenuPaginasSiguientes($html_tabla, $table_name, $form_name, $numero_pagina, $cantidad_paginas, $command);
    }

    $html_tabla .= '
          <div class="panel">
              <!--
            <div class="panel-heading">
              <h3 class="panel-title">Sample Toolbar</h3>
            </div>
              -->
            <!--Data Table-->
            <!--===================================================-->  
            <div class="panel-body">

                            <!-- Menu Table -->
              <div class="pad-btm form-inline">
                <div class="row">
                  <!--
                  <div class="col-sm-6 table-toolbar-left">
                    <button id="demo-btn-addrow" class="btn btn-purple btn-labeled fa fa-plus">Add</button>
                    <button class="btn btn-default"><i class="fa fa-print"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default"><i class="fa fa-exclamation-circle"></i></button>
                      <button class="btn btn-default"><i class="fa fa-trash"></i></button>
                    </div>
                  </div>
                  <div class="col-sm-6 table-toolbar-right">
                    <div class="form-group">
                      <input id="demo-input-search2" type="text" placeholder="Search" class="form-control" autocomplete="off">
                    </div>
                    <div class="btn-group">
                      <button class="btn btn-default"><i class="fa fa fa-cloud-download"></i></button>
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                          <i class="fa fa-cog"></i>
                          <span class="caret"></span>
                        </button>
                        <ul role="menu" class="dropdown-menu dropdown-menu-right">
                          <li><a href="#">Action</a></li>
                          <li><a href="#">Another action</a></li>
                          <li><a href="#">Something else here</a></li>
                          <li class="divider"></li>
                          <li><a href="#">Separated link</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                -->
                                <!--- Mecanismo de Busqueda -->';
    if (isset($filter)) {
        CrearMecanismoBusqueda($html_tabla, $form_name, $command, $cantidad_paginas, $table_name, $filter, $Toolsbar);
    }
    $html_tabla .= '
 
                <!---   --->
              </div>              
              <!-- En Menu Table-->
            
              <div class="table-responsive">
                <table class="table table-striped">  
  ';

    if (!isset($title)) {
        $title = $form_name;
    }


    if ((isset($titulos) || is_array($registros_tabla)) && (count($registros_tabla) > 0)) {
        CrearEncabezadoTabla($html_tabla, $registros_tabla, $titulos);
    }

  // SE tiene en cuenta si esta una lista de valores o una consulta
    $typenew = 'LIST';

    if ((is_array($registros_tabla)) && (count($registros_tabla) > 0)) {


        if (isset($genfile) && $genfile == "on")
            $result = GenerarArchivo($titulos, $registros_tabla, $report_dir, $archivo, $ruta);


        if (!isset($type)) {
            $typenew = 'LIST';
        } else {
            $typenew = $type;
        }


        CrearCuerpoTabla($html_tabla, $registros_tabla, $table_name, $llaves, $cambiar_valor, $cantidad_registros, $numero_pagina, $typenew, $command_showbyid, $form_name);

    } else {
     //$html_tabla .= "<tr> <td colspan = 3 align='center'> NO SE ENCONTRARON DATOS </td></tr>";
        $html_tabla .= '
                    <div id="demo-preview-alert-1" class="demo-preview-alert">
          
                      <!-- Alert layout example -->
                      <div class="alert alert-warning media fade in">
                        <div class="media-left">
                          <span class="icon-wrap icon-wrap-xs icon-circle alert-icon">
                            <i class="fa fa-exclamation fa-lg"></i>
                          </span>
                        </div>                      
                        <div class="media-body">
                          <h4 class="alert-title">Atenci&oacute;n</h4>
                          <p class="alert-message">No se Encontraron Datos, Intente con Otra Consulta !!!</p>
                        </div>
                      </div>
                      <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
          
                    </div>';

    }


    CrearVariablesOcultas($html_tabla, $table_name, $llaves);
  
  //Fin de Tabla
    $html_tabla .= '
                </table>
              </div>
            </div>
            <!--===================================================-->
            <!--End Data Table-->
          
          </div>            
  ';


    $html_result = $html_tabla;

    print $html_result;

}


function CrearMecanismoBusqueda(&$html_tabla, $form_name, $command, $cantidad_paginas, $table_name, $filter, $Toolsbar)
{


    $html_tabla .= '  
                <div class="row1">
                  <div class="col-sm-12 table-toolbar-center">';
    //Filter by Combo Box
    $html_tabla .= '               <div class="form-inline" role="form">
                    <div class="form-group">
                        
                      <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-list fa-fw"></i></span>
                 
                      
                         <div>
                           ' . selectfield($filter) . '
                         </div>
                      </div>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';  
                  
  

    //Filter Condition
    $html_tabla .= '  
                    <div class="form-group">
                      <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-filter fa-fw"></i></span>                    
                                               <div>
                          ' . select($Toolsbar) . '
                         </div>
                      </div>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';  
                  

           
    //Filter Key Frase
    $html_tabla .= "  
                    <div class='form-group'>
                      <div class='input-group'>
                           <span class='input-group-addon'><i class='fa fa-file-word-o fa-fw'></i></span>
                           <input  type='text' class='form-control' size='20' placeholder='" . $Toolsbar[KeyWord] . "' name='key_word' id='key_wordid'";
    if (isset($_REQUEST["key_word"])) {
        $html_tabla .= "value='" . $_REQUEST["key_word"] . "' ";
    }

    if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") or strstr($_SERVER["HTTP_USER_AGENT"], "Opera")) {
        $html_tabla .= "onKeyPress=\"if(event.keyCode == 13){";
        $html_tabla .= $form_name . ".action.value='" . $command . "';";
        $html_tabla .= $form_name . ".submit();}\">";
    } else {
        $html_tabla .= "onKeyPress=\"if(event.keyCode == 13){";
        $html_tabla .= $form_name . ".action.value='" . $command . "';";
        $html_tabla .= $form_name . ".submit();}\">";
    }
    $html_tabla .= '
                                          </div>  
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';  
                  
           

    //Bottom Search
    $html_tabla .= '  
                    <div class="form-group">';
   //                   
    $html_tabla .= "                     <div class='input-group'> 
                                        <a  class='btn btn-mint btn-rounded' Onclick=\"action.value='" . $command . "';";
    if ($cantidad_paginas > 1) {
        $html_tabla .= $table_name . "__pagina_consult.value = '1';";
    }
    $html_tabla .= $form_name . ".submit();\">";
    $html_tabla .= "
                                             <i class='fa fa-refresh  fa-lg '></i> " . $Toolsbar[refresh] . "
                        </a>                        
                     </div> 
                    </div>";



    $html_tabla .= '                 </div>
                  </div>                
                </div>
                   ';




}


/*
 * Crea el select con la info de la tabla 'tipo_insumo'
 */
/*
 * Crea el select con la info de la tabla 'tipo_insumo'  fa-filter
 */
function selectfield($filter)
{
    require Application::getLanguageDirectory() . '/' . Application::getLanguage() . "/Tableheader.lan";

    $html_result = '';
    $html_result .= "<select name='sel_condicionfield' class='form-control select2' style='width: 100%;'>";

  //$html_result .= "<option value=''></optional>";
  
 //convierte la cadena a un vector
    $filter_fields = explode(",", $filter);

    for ($i = 0; $i < count($filter_fields); $i++) {
        $html_result .= "<option value='" . $filter_fields[$i] . "'";
        if (isset($_REQUEST['sel_condicionfield']))
            if ($_REQUEST['sel_condicionfield'] == $filter_fields[$i])
            $html_result .= " selected ";
        $html_result .= ">" . $Tableheaders[$filter_fields[$i]] . "</option>";
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
    $html_result .= "<select name='sel_condiction' class='form-control select2' style='width: 100%;'>";

    $html_result .= "<option value='='" . sel_condiction("=") . ">" . $Toolsbar[ExactPhrase] . "</option>";
    $html_result .= "<option value='!='" . sel_condiction("!=") . " >" . $Toolsbar[Diff] . "</option>";
    $html_result .= "<option value='_%'" . sel_condiction("_%") . ">" . $Toolsbar[BeginWith] . "</option>";
    $html_result .= "<option value='%_'" . sel_condiction("%_") . ">" . $Toolsbar[EndWith] . "</option>";
    $html_result .= "<option value='%'" . sel_condiction("%") . ">" . $Toolsbar[Content] . "</option>";
  /* */
    $html_result .= "<option value='>'" . sel_condiction(">") . ">" . $Toolsbar[Morethan] . "</option>";
    $html_result .= "<option value='>='" . sel_condiction(">=") . ">" . $Toolsbar[MoreEqualthan] . "</option>";
    $html_result .= "<option value='<'" . sel_condiction("<") . ">" . $Toolsbar[Minusthan] . "</option>";
    $html_result .= "<option value='<='" . sel_condiction("<=") . ">" . $Toolsbar[MinusEqualthan] . "</option>";

    $html_result .= "</select>";

    return $html_result;
}

function sel_condiction($condiction)
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
function CrearEncabezadoTabla(&$html_tabla, $registros_tabla, $titulos)
{
    require Application::getLanguageDirectory() . '/' . Application::getLanguage() . "/Tableheader.lan";
    //si la variable titulos esta setiada crea un vector con los titulos
    //sino crea un vector con los indices del vector $registros_tabla
    if (isset($titulos)) {
        $m = explode(",", $titulos);
    } else {
        //pasa los titulos a un vector
        $m = array_keys($registros_tabla[0]);
    }

    // crea el encabezado de la tabla
    $html_tabla .= "
                  <thead> 
                   ";

    $html_tabla .= "
                    <tr>
                   ";
    for ($i = 0; $i < count($m); $i++) {
        $html_tabla .= "
                      <th class='class='text-thin'>" . $Tableheaders[$m[$i]] . "</th>
             ";
        //$html_tabla.= $m[$i];
    }
    $html_tabla .= "
                    </tr>
                   ";
    $html_tabla .= "
                  </thead>  
                   ";
}

/*
 * Crea una tabla en html con los datos de la tabla de la base de datos
 */
function CrearCuerpoTabla(&$html_tabla, $registros_tabla, $table_name, $llaves, $cambiar_valor, $cantidad_registros, $numero_pagina, $type, $command_showbyid, $form_name)
{

   //obtener las llav,s de la tabla y pasarlas a un vector
    $keys = explode(",", $llaves);

    $html_tabla .= "
                  <tbody>";

    for ($i = (($numero_pagina - 1) * $cantidad_registros); ($i < $numero_pagina * $cantidad_registros) && ($i < count($registros_tabla)); $i++) {



        $html_tabla .= "
                    <tr>";

    
      //print_r($valor_estilo);
      
       //Si lo que se esta pidiendo es una lista de valores
        if ($type == 'LOV') {

            CrearRadioButtonlov($html_tabla, $table_name, $registros_tabla, $keys, $i, $valor_estilo);
        } else // LIST - BROWSE
        {
            CrearRadioButton($html_tabla, $table_name, $registros_tabla, $keys, $i, $valor_estilo, $command_showbyid, $form_name);

        }

        if (isset($cambiar_valor)) {

            CambiarValorTabla($registros_tabla, $cambiar_valor, $i);

        }

       //obtener una fila completa de la tabla de la base de datos.
        $m = array_values($registros_tabla[$i]);

        for ($j = 0; $j < count($m); $j++) {



            $html_tabla .= "<td>";
           //$html_tabla .= "<DIV id='CELDA".$i.$j."'>";
            if ($m[$j] != "") {
                if (($j == 0 || $j == 1) && ($type != 'LOV')) {
                    $html_tabla .= "<a href='#' onClick=\"";

                    $html_tabla .= $form_name . "." . $table_name . "__" . strtoupper($keys[0]) . ".value = '" . $m[0] . "'";


                    $html_tabla .= ";" . $form_name . ".action.value='" . $command_showbyid . "';" . $form_name . ".submit();\">" . $m[$j] . "</a>";

                } else
                    $html_tabla .= $m[$j];

            } else {
                $html_tabla .= "&nbsp;";
            }
           //$html_tabla .= "</DIV>";
            $html_tabla .= "</td>";

        }
        if ($table_name == 'ordeserv') {
            $html_tabla .= "<td>";
            $printicon = '<a href="#" onclick="popUpShowShowByIdFormkey(\'sptosgcoCmdShowByIdOrdeservPreview\',\'ordeserv__ORDID\',' . $m[0] . ',500,500); "><img src= "web/images/docprint.jpg" alt="Preview Orden"  align="middle" border="0" title="Preview Orden"></a>&nbsp;&nbsp;';

            $html_tabla .= $printicon;
            $html_tabla .= "</td>";

        }
        $html_tabla .= "<td><div align='center'><input id='cantidad".$m[0]."' name='cantidad".$m[0]."'class='form-control' style='width: 60%' type='number' placeholder='Cantidad'></div></td>";
        $html_tabla .= "<td><div align='center'><input type='button' onclick=\"AddProducto(".$m[0].",'".$m[1]."','".$m[2]."',frmProductosLista.cantidad".$m[0].".value,'".$m[3]."');frmProductosLista.cantidad".$m[0].".value='NULL'\"  id='producto".$m[0]."'  class='btn btn-primary' value='+'></div></td>";
        $html_tabla .= "</tr>";

    }
    $html_tabla .= "
                  </tbody>";
}

/*
 * Cambia los valores de del vector '$registros_tabla' que son indices
 * de otras tablas en la base de datos
 */
function CambiarValorTabla(&$registros_tabla, $change_value, $indice)
{
 //convierte la cadena a un vector
    $parametros_cambiar = explode(",", $change_value);

    for ($i = 0; $i < count($parametros_cambiar); $i += 4) {
    //llama la clase de la tabla
        $gateway = Application::getDataGateway($parametros_cambiar[$i + 1]);
    //optiene todos los datos de la tabla
        $datos = call_user_func(array($gateway, "getAll" . $parametros_cambiar[$i + 1]));
        for ($z = 0; $z < count($datos); $z++) {
         //cambia el valor del vector por el nombre si los codigos son iguales
            $data = '';
            if ($registros_tabla[$indice][$parametros_cambiar[$i]] == $datos[$z][$parametros_cambiar[$i + 2]]) {
                $param = explode('/', $parametros_cambiar[$i + 3]);
                for ($j = 0; $j < count($param); $j++) {
             //$registros_tabla[$indice][$parametros_cambiar[$i]] = $datos[$z][$parametros_cambiar[$i+3]];
                    $data .= $datos[$z][$param[$j]] . " ";
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
function CrearRadioButton(&$html_tabla, $table_name, $registros_tabla, $keys, $i, $valor_estilo, $command_showbyid, $form_name)
{
    $html_tabla .= "<td>";
    $html_tabla .= "<input type='radio'";
    $html_tabla .= " name='" . $table_name . "__keys' onClick=\"";
    for ($z = 0; $z < count($keys); $z++) {
        if ($z == 0) {
            $html_tabla .= $form_name . "." . $table_name . "__" . strtoupper($keys[$z]) . ".value = '" . $registros_tabla[$i][strtoupper($keys[$z])] . "'";
        } else {
            $html_tabla .= ";" . $form_name . "." . $table_name . "__" . strtoupper($keys[$z]) . ".value = '" . $registros_tabla[$i][strtoupper($keys[$z])] . "'";
        }
    }
    $html_tabla .= "\">";
//  $html_tabla .= ";disableButtons();".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\">";

    $html_tabla .= "</td>";
}


function CrearRadioButtonlov(&$html_tabla, $table_name, $registros_tabla, $keys, $i, $valor_estilo)
{

    $html_tabla .= "<td class='detailedViewHeader'>";
    $html_tabla .= "<input type='radio' ";
    $html_tabla .= " name='Keys' value=\"";
    for ($z = 0; $z < count($keys); $z++) {
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
function CrearVariablesOcultas(&$html_tabla, $table_name, $llaves)
{
    $keys = explode(",", $llaves);

    for ($i = 0; $i < count($keys); $i++) {
        $html_tabla .= "<input type='hidden' name='" . $table_name . "__" . strtoupper($keys[$i]) . "'>";
    }
}


/*
 *
 *
 */
function CrearMenuPaginasSiguientes(&$html_tabla, $table_name, $form_name, $numero_pagina, $cantidad_paginas, $command)
{


    $html_tabla .= '
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped" width=10%> 
                  <tbody>';



    $html_tabla .= "<tr>
                 
                 <td ><div align='center'><font class='small' size='2'>Pag :" . $numero_pagina . "/" . $cantidad_paginas . "</font></div></td>
                 
                 ";
//</tr>
// $html_tabla .= "<tr>";

    if ($numero_pagina != 1) {
        $html_tabla .= "<td><div align='center'><a href='#' onClick=\"" . $form_name . "." . $table_name . "__pagina_consult.value = parseInt(" . $form_name . "." . $table_name . "__pagina_consult.value)-1;" . $form_name . ".action.value='" . $command . "';" . $form_name . ".submit();\"><i class='fa fa-backward fa-lg'></i></a></div></td>";

    } else {
        $html_tabla .= "<td><div align='center'><span class='fa-stack fa-lg'><i class='fa fa-backward'><i class='fa fa-ban fa-stack-1x text-danger'></span></div></td>";
    }

    $html_tabla .= "<td><div align='center'><input type='text' name='" . $table_name . "__pagina_consult' maxlength='3' size='2' value='" . $numero_pagina . "' ";

    if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") or strstr($_SERVER["HTTP_USER_AGENT"], "Opera")) {
        $html_tabla .= "onKeyPress=\"if((event.keyCode == 13) && (" . $form_name . "." . $table_name . "__pagina_consult.value == '')){";
        $html_tabla .= "event.returnValue = false;";
        $html_tabla .= "}else{";
        $html_tabla .= "if((event.keyCode == 13) && (" . $form_name . "." . $table_name . "__pagina_consult.value != '')){";
        $html_tabla .= $form_name . ".action.value='" . $command . "';";
        $html_tabla .= $form_name . ".submit();}}";

        $html_tabla .= "if (!((event.keyCode>=48) && (event.keyCode<=57))){event.returnValue = false;}\"";


    } else {
        $html_tabla .= "onKeyPress=\"if(event.keyCode == 13){";
        $html_tabla .= $form_name . ".action.value='" . $command . "';";
        $html_tabla .= "if(" . $form_name . "." . $table_name . "__pagina_consult.value == ''){";
        $html_tabla .= $form_name . "." . $table_name . "__pagina_consult.value = '1';";
        $html_tabla .= "}}";
        $html_tabla .= "if (!((event.charCode>=48) && (event.charCode<=57) ||(event.charCode == 0) || (event.charCode == 8))){event.preventDefault();}\"";
    }

    $html_tabla .= "></div></td>";

    if ($numero_pagina < $cantidad_paginas) {
        $html_tabla .= "<td><div align='center'><a href='#' onClick=\"" . $form_name . "." . $table_name . "__pagina_consult.value = parseInt(" . $form_name . "." . $table_name . "__pagina_consult.value)+1;" . $form_name . ".action.value='" . $command . "';" . $form_name . ".submit();\"><i class='fa fa-forward fa-lg'></i></a></div></td>";

    } else {
        $html_tabla .= "<td><div align='center'><span class='fa-stack fa-lg'><i class='fa fa-forward'><i class='fa fa-ban fa-stack-1x text-danger'></span></div></td>";
    }
 //$html_tabla .= "</tr>";
 
// $html_tabla .= "<tr>";

    $html_tabla .= "<td><div align='center'><input type='button' class='btn btn-primary btn-rounded' value='  Ir  ' onClick=\"if((" . $form_name . "." . $table_name . "__pagina_consult.value < 1) || (" . $form_name . "." . $table_name . "__pagina_consult.value > " . $cantidad_paginas . ")){";
 //$html_tabla .= "alert('Error: Debe ingresar un numero entre 1 y ".$cantidad_paginas."');"; 
    $html_tabla .= "alertify.alert('Error: Debe ingresar un numero entre 1 y " . $cantidad_paginas . "');";

    $html_tabla .= $form_name . "." . $table_name . "__pagina_consult.value = ''";
    $html_tabla .= "}else{";
    $html_tabla .= $form_name . ".action.value='" . $command . "';";
    $html_tabla .= $form_name . ".submit();";
    $html_tabla .= "}\"></div></td>";

    $html_tabla .= "</tr>
                 </tbody>
                </table>
              </div>
            </div>         ";



}

/*
 * Crea Archivo con Arreglo de datos
 * datos.
 */
function GenerarArchivo($titulos, $datos, $ruta, $archivo = "data", $path)
{

    require Application::getLanguageDirectory() . '/' . Application::getLanguage() . "/Tableheader.lan";
    $archivo = $archivo . date(" Y-m-d-m-s");
     //$fd = fopen ("/usr/local/apache2/htdocs".$ruta."/".$archivo.".xls", "w");
    $fd = fopen($ruta . "/" . $archivo . ".xls", "w");

    if (!$fd) {
        echo "No se pudo crear los archivos";
        return 100;
    }

    if (isset($titulos) && $titulos != '') {
        $m = explode(",", $titulos);
    } else {
        //pasa los titulos a un vector
        $m = array_keys($datos[0]);
    }
    //print_r("total->".sizeof($datos[0]));
    if (sizeof($m) >= sizeof($datos[0])) {
                   
    //$data[$i]["RUTA"]    ="report/".$_REQUEST["folder"]."/generados/".$archivo;
        //$html_tabla .= "<a  href='./".$m[4]."'>".$m[$j]."</a>";   
        $html_tabla = "<a href='./report/" . $path . "/generados/" . $archivo . ".xls'>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-files-o fa-mg'></i>&nbsp;&nbsp;Descargar reporte " . $archivo . ".xls<a><p>";
        for ($i = 0; $i < sizeof($m); $i++) {
            if ($m[$i] != '.')
                $cadena .= trim($Tableheaders[$m[$i]]) . chr(9);

        }
        $cadena .= "\r\n";
        fwrite($fd, $cadena);
    } else {
        return "Hay diferencia de campos entre la consulta y el encabezado";
    }


    if (count($datos) > 0) {
        for ($j = 0; $j < sizeof($datos); $j++) {
            $cadena = "";

       /*
       for ($z=0;$z<count($datos[$j]);$z++)
       {

         list($key, $value) = $datos[$j];
          $cadena.=str_replace(chr(10), '',trim($value)).chr(9);
       }
             */
            foreach ($datos[$j] as $k => $v) {
                $cadena .= str_replace(chr(10), '', trim($v)) . chr(9);
            }

            $cadena .= "\r\n";
            fwrite($fd, $cadena);
        }

    }
    fclose($fd);
    echo $html_tabla;

}


?>