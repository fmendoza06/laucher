<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {consult_table} plugin
 * Type:     function<br>
 * Name:     consult_table<br>
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
 *           cantidad_registros = es el numero de registros que cargara la consulta como maximo.(optional)
 *           commnad = nombre del comando que se invocar cuando el usuario desea cambiar a la pagina siguiente
 *
 *<pre>
 * Examples: 
 *           {consult_table table_name="ciudad"
 *                          llaves="Codigo_Ciudad,Codigo_Pais"
 *                          form_name="FrmCiudad"
 *                          titulos="Codigo Ciudad,Nombre Ciudad,Pais"
 *                          cambiar_valor="Codigo_Pais,pais,Codigo_Pais,Nombre_Pais"
 *                          cantidad_registros = 30
 *                          command = "CmdShowListCiudad"
 *           }
 *<pre>
 * Nota: Necesita ser parte de una forma!
 *
 * @author   Spyro Solutions 
 * @version  1.0.1
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions 
 */

function smarty_function_consult_table($params, &$smarty)
{
 	extract($params);

  	$registros_tabla = ConsultarRegistro($table_name);

	// asigna el valor por defecto a la cantidad de registros
	$cantidad_registros = ( isset($cantidad_registros) ? $cantidad_registros : 20 );

	$cantidad_paginas = CalcularCantidadPaginas($registros_tabla,$cantidad_registros);

	$numero_pagina = getPaginaActual($table_name,$cantidad_paginas);

	//inicializa la cadena.
  	$html_tabla = '';

	$html_tabla .= CrearTablaRegistros($registros_tabla,$titulos,$table_name,$llaves,$cambiar_valor,$cantidad_registros,$numero_pagina,$command_showbyid,$form_name,$cambiar_enumeracion);
  
  	$html_tabla .= CrearVariablesOcultas($table_name,$llaves);
  
  	if($cantidad_paginas > 1)
  		 $html_tabla .= CrearMenuPaginasSiguientes($table_name,$form_name,$numero_pagina,$cantidad_paginas,$command);
  	  	
	print $html_tabla;
}

/**
 * Metodo que determina la consulta que se realiza en la base de datos
 * */
function ConsultarRegistro($table_name)
{
	//instancia la compuerta de fila	
  	$gateway = Application::getDataGateway(ucfirst($table_name));
  
  	//consulta
    return call_user_func(array($gateway,"getAll".ucfirst($table_name)));
}

/**
 * 	calcula la cantidad de paginas
 * */
function CalcularCantidadPaginas($registros_tabla,$cantidad_registros){

	return ceil(count($registros_tabla)/$cantidad_registros);	
}

/**
 * Obtiene el numero de la pagina actual
 **/
function getPaginaActual($table_name,$cantidad_paginas){

  	//obtiene el numero de la pagina actual
  	if(!isset($_REQUEST[$table_name."__pagina_consult"])){
     	return 1;
  	}else{
     	if($_REQUEST[$table_name."__pagina_consult"] > $cantidad_paginas ){
       		return $cantidad_paginas;
     	}else{
          	if($_REQUEST[$table_name."__pagina_consult"] < 1 ){
             	return 1;
          	}else{
             	return $_REQUEST[$table_name."__pagina_consult"];
          	}
     	}
  	}
}

/**
 * 
 */
function CrearTablaRegistros($registros_tabla,$titulos,$table_name,$llaves,$cambiar_valor,$cantidad_registros,$numero_pagina,$command_showbyid,$form_name,$cambiar_enumeracion)
{
  	$html_tabla .= "<table border='0' align='center'>";

  	if( isset($titulos) || is_array($registros_tabla) ){
     	$html_tabla .= CrearEncabezadoTabla($registros_tabla,$titulos);
  	}

  	if( is_array($registros_tabla) ){
     	$html_tabla .= CrearCuerpoTabla($registros_tabla,$table_name,$llaves,$cambiar_valor,$cantidad_registros,$numero_pagina,$command_showbyid,$form_name,$cambiar_enumeracion);
  	}

  	$html_tabla .= "</table>";
  	
  	return $html_tabla;
}


/**
* Crea el encabezado de la tabla, con el valor de la variable $ titulos,
* si titulos no esta setiado por defecto el encabezado de la tabla
* son los nombre de los campos de la tabla en la base de datos
*/
function CrearEncabezadoTabla($registros_tabla,$titulos)
{
	//inicializa cadena.
	$html_tabla = "";

    //si la variable titulos esta setiada crea un vector con los titulos
    //sino crea un vector con los indices del vector $registros_tabla
     if(isset($titulos)){
        $m = explode(",",$titulos);
     }else{
        //pasa los titulos a un vector
        $m = array_keys($registros_tabla[0]);
     }

    // crea el encabezado de la tabla
    $html_tabla .= "<tr>";
    $html_tabla .= "<th></th>";
    for($i=0;$i < count($m);$i++){
        $html_tabla .= "<th class=\"titulofila\">";
        $html_tabla.= $m[$i];
        $html_tabla .= "</th>";
    }
    $html_tabla .= "</tr>";

	return $html_tabla;

}

/**
* Crea una tabla en html con los datos de la tabla de la base de datos
*/
function CrearCuerpoTabla($registros_tabla,$table_name,$llaves,$cambiar_valor,$cantidad_registros,$numero_pagina,$command_showbyid,$form_name,$cambiar_enumeracion)
{
	//inicializa cadena.
	$html_tabla = "";

   	//obtener las llaves de la tabla y pasarlas a un vector
   	$keys = explode(",",$llaves);

   	for($i=(($numero_pagina-1)*$cantidad_registros);($i < $numero_pagina*$cantidad_registros )&&($i < count($registros_tabla));$i++){

      	$html_tabla .= "<tr>";

      	CrearRadioButton($html_tabla,$table_name,$registros_tabla,$keys,$i,$command_showbyid,$form_name);

      	if(isset($cambiar_valor))
         	CambiarValorTabla($registros_tabla,$cambiar_valor,$i);
      	
      	if(isset($cambiar_enumeracion))
      		CambiarEnumeracion($registros_tabla,$i,$cambiar_enumeracion);
      		
      	//define el estilo de la fila
      	if($i%2 == 0)
         	$valor_estilo ="celda";
      	else
         	$valor_estilo ="celda2";

       	//obtener una fila completa de la tabla de la base de datos.
       	$m = array_values($registros_tabla[$i]);

        for($j=0;$j < count($m);$j++){
           	$html_tabla .= "<td class=\"".$valor_estilo."\">";
           	if($m[$j] != ""){
               	$html_tabla .= $m[$j];
           	}else{
               	$html_tabla .= "&nbsp;";
           	}
           	$html_tabla .= "</td>";
        }
      $html_tabla .= "</tr>";

   	}

	return $html_tabla;
}

/**
* Cambia los valores de del vector '$registros_tabla' que son indices
* de otras tablas en la base de datos
*/
function CambiarValorTabla(&$registros_tabla,$cambiar_valor,$indice){
 //convierte la cadena a un vector
 $parametros_cambiar = explode(",",$cambiar_valor);

  for($i=0;$i < count($parametros_cambiar);$i+=4){
    //llama la clase de la tabla
     $gateway = Application::getDataGateway($parametros_cambiar[$i+1]);
    //optiene todos los datos de la tabla
     $datos = call_user_func(array($gateway,"getAll".$parametros_cambiar[$i+1]));

     for($z=0;$z < count($datos);$z++){
         //cambia el valor del vector por el nombre si los codigos son iguales
         if($registros_tabla[$indice][$parametros_cambiar[$i]] == $datos[$z][$parametros_cambiar[$i+2]] ){
            $registros_tabla[$indice][$parametros_cambiar[$i]] = $datos[$z][$parametros_cambiar[$i+3]];
            break;
         }
     }

  }
}


/**
 * cambia los valores de registros que son enumeraciones.
 */
function CambiarEnumeracion(&$registros_tabla,$indice,$enumeracion){
	
	$enumeracion = explode('~',$enumeracion);
	for($pos = 0; $pos < count($enumeracion); $pos++){
		
		//se extrae el campo al cual se le cambia el valor. 
		$campo = explode('-',$enumeracion[$pos]);
		//extrae la enumeración.
		$enum_aux = explode(',',$campo[1]);
		
		//se cambia el correspondiente valor.
		for($i = 0; $i < count($enum_aux); $i+=2){
			
			if($registros_tabla[$indice][$campo[0]] == $enum_aux[$i]){
				
				$registros_tabla[$indice][$campo[0]] = $enum_aux[$i+1];
				break;
			}
		}
	}
	
}


/**
* Crea un Radio Buton en la primera columna de la tabla en html que se esta generando,
* Por cada Radio Button se genera un codigo en JavaScript para en la propiedad
* 'Onclick' para asignar los vales de la llaves de la tabla en la base de datos
* en los campos ocultos. (Ver CrearVariablesOcultas)
*/
function CrearRadioButton(&$html_tabla,$table_name,$registros_tabla,$keys,$i,$command_showbyid,$form_name){

  $html_tabla .= "<td class=\"titulofila\">";
  $html_tabla .= "<input type='radio'";
  $html_tabla .= " name='".$table_name."__keys' onClick=\"";
  for($z=0;$z < count($keys);$z++){
      if($z == 0){
         $html_tabla .= $table_name."__".$keys[$z].".value = '".$registros_tabla[$i][$keys[$z]]."'";
      }else{
         $html_tabla .= ";".$table_name."__".$keys[$z].".value = '".$registros_tabla[$i][$keys[$z]]."'";
      }
  }
  $html_tabla .= ";disableButtons();".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\">";
  $html_tabla .= "</td>";
}


/**
* Crea campos ocultos segun la cantidad de llaves tenga la tabla en la base de
* datos.
*/
function CrearVariablesOcultas($table_name,$llaves)
{
 	$html_hidden = '';
 	$keys = explode(",",$llaves);
 	for($i=0;$i < count($keys);$i++){
    	$html_hidden .= "<input type='hidden' name='".$table_name."__".$keys[$i]."'>";
 	}
	return $html_hidden;
}

/**
* Genera el codigo para hacer un menu que el usuario utilizara cuando va a cambiar de pagina
* o va a pasar a la pagina siguiente.
**/								 
function  CrearMenuPaginasSiguientes($table_name,$form_name,$numero_pagina,$cantidad_paginas,$command)
{
	//inicializa la cadena
	$html_tabla = "";
	$html_tabla .= "<table border='0' align='right'>";
    $html_tabla .= "<tr>";
    $html_tabla .= "<td align='right'>";
       //etiqueta menu pagina siguiente
        $html_tabla .= "<font class='labelPaginador'>Saltar a :";
            //textfield
            $html_tabla .= "<input type='text' name='".$table_name."__pagina_consult' class='textPaginador' maxlength='4' size='3' value='".$numero_pagina."' ";
            $html_tabla .= "onKeyPress=\"if((event.keyCode == 13) && (".$form_name.".".$table_name."__pagina_consult.value != '')){";
		      $html_tabla .= $form_name.".action.value='".$command."';";
            $html_tabla .= $form_name.".submit();}\"";
            $html_tabla .= ">";
            //boton
            $html_tabla .= "<input type='button' value='Ir' class='botonPaginador' ";
            $html_tabla .= "onClick=\"if((".$form_name.".".$table_name."__pagina_consult.value < 1) || (".$form_name.".".$table_name."__pagina_consult.value > ".$cantidad_paginas.")){";
            $html_tabla .= "alert('Error: Debe ingresar un numero entre 1 y ".$cantidad_paginas."');";
            $html_tabla .= "}else{";
            $html_tabla .= $form_name.".action.value='".$command."';";
            $html_tabla .= $form_name.".submit();";
            $html_tabla .= "}\">";
            //salto de linea
            $html_tabla .= "<br>";
            //enlace pagina anterior
            if($numero_pagina != 1){
               $html_tabla .= "<a href='#' onClick=\"".$form_name.".".$table_name."__pagina_consult.value = parseInt(".$form_name.".".$table_name."__pagina_consult.value)-1;".$form_name.".action.value='".$command."';".$form_name.".submit();\">&lt;&lt;Ant</a>";
            }else{
               $html_tabla .= "&lt;&lt;Ant";
            }
            // pagina actual / cantidad de paginas
            $html_tabla .= " ".$numero_pagina."/".$cantidad_paginas." ";
            //enlace pagina siguiente
            if($numero_pagina < $cantidad_paginas){
               $html_tabla .= "<a href='#' onClick=\"".$form_name.".".$table_name."__pagina_consult.value = parseInt(".$form_name.".".$table_name."__pagina_consult.value)+1;".$form_name.".action.value='".$command."';".$form_name.".submit();\">Sig&gt;&gt;</a>";
            }else{
               $html_tabla .= "Sig&gt;&gt;";
            }
        $html_tabla .= "</font>";
     $html_tabla .= "</td>";
    $html_tabla .= "</tr>";
$html_tabla .= "</table>";

return $html_tabla;
}

?>