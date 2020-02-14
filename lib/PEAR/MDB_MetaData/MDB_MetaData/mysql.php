<?PHP

class MDB_MetaData_mysql
{
    var $mdb;
    var $db;
    
    function setConnectionDataBase($mdb)
    {
        $this->mdb = $mdb;
    }

    function getConnectionDataBase()
    {
        return $this->mdb;
    }

    function setDataBase($db)
    {
        $this->db = $db;
    }

    function getDataBase()
    {
        return $this->db;
    }
    
    function getDataBaseName()
    {
        $this->db['name_db'] =$this->mdb->database_name;
        return $this->db['name_db'];
    }

    function getTableList()
    {
        $this->db['tables']['list'] = $this->mdb->listTables();
        if (MDB::isError($this->db['tables']['list']))
        {
            print('Error: al obtener los nombres de las tablas: ' . MDB::errorMessage($this->db['tables']['list']).'<br>');
            return ' ';
        }
        return $this->db['tables']['list'];
    }
	
    function getColumnList($table)
    {
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");
            
        $this->db['tables'][$table]['columns']['list'] = $this->mdb->listTableFields($table);
        if (MDB::isError($this->db['tables'][$table]['columns']['list']))
        {
            print('Error: al obtener los nombres de las columnas: ' . MDB::errorMessage($this->db['tables'][$table]['columns']['list']).'<br>');
            return ' ';
        }
        return $this->db['tables'][$table]['columns']['list'];
    }

    /*otra forma de obtener la metadata de las columnas de una tabla, el problema
    es que no obtiene la escala de un tipo de dato
    /*function getColumnData($table, $column = " ")
    {
        $i = 0;
        $j = 0;
	
	    $this->emptyVar($table,"No se ha definido el nombre de la tabla");
        
        if (!isset($column)) {
            $columns = $this->getColumnList($table);
        } else {
            $columns[$i] = $column;
        }

        foreach($columns as $column) {

            $sql = "SELECT $column FROM $table";
            
            $result = $this->mdb->query($sql);

            if (MDB::isError($result))
            {
                die ('Error de Mysql al obtener la metatada de las columnas: ' . $this->mdb->mysqlRaiseError($result));
            }

            $name  = mysql_field_name($result, $i);
            $type  = mysql_field_type($result, $i);
            $len   = mysql_field_len($result, $i);
            $flags = mysql_field_flags($result, $i);

            // @@ jaime
            // pieces no siempre contiene 3 elementos, a veces contiene más !!
            $pieces = explode(" ", $flags);

            $this->db['tables'][$table]['columns'][$column]['name'] = $name;
            $this->db['tables'][$table]['columns'][$column]['data_type'] = $type;
            $this->db['tables'][$table]['columns'][$column]['precision'] = $len;
            $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';

            if ($pieces[0] == 'not_null') {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'not null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'null';
            }

            $this->mdb->freeResult($result);
        }
        return $this->db['tables'][$table]['columns'];
    }*/

    function getColumnData($table, $name_column = '')
    {
        $i = 0;
        $j = 0;
				
	    $this->emptyVar($table,"No se ha definido el nombre de la tabla");

        //$tables = $this->getTableList();
        //$count_array = count($tables);

        $sql = "SHOW columns FROM $table";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Mysql al obtener la metatada de las columnas: ' . $this->mdb->mysqlRaiseError($result).'<br>');
            return ' ';
        }
        
        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result,"", $i);
            $column = $array[0];
            $this->db['tables'][$table]['columns'][$column]['name'] = $array[0];
            $type = $array[1];
            
            if(strstr($type,'(')) {
                $data_type = split('[(,)]', $type);
                $this->db['tables'][$table]['columns'][$column]['type'] = $data_type[0];
                if(($data_type[0] == 'enum') || ($data_type[0] == 'set')) {
                    $this->db['tables'][$table]['columns'][$column]['precision'] =
                    preg_replace("/.*\((.*)\)/", "\\1",$type);
                    $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
                }else {
                    $this->db['tables'][$table]['columns'][$column]['precision'] = $data_type[1];
                    if($data_type[2]!= '') {
                        $this->db['tables'][$table]['columns'][$column]['scale'] = $data_type[2];
                    }else {
                        $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
                    }
                }
            }else {
                $this->db['tables'][$table]['columns'][$column]['type'] = $type;
                $this->db['tables'][$table]['columns'][$column]['precision'] = 'null';
                $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
            }

            if ($array[2] == '') {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'not null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'null';
            }

            if ($array[4] == '') {
                $this->db['tables'][$table]['columns'][$column]['default'] = 'null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['default'] = $array[4];
            }
            
            if ($array[5] == '') {
                $this->db['tables'][$table]['columns'][$column]['counter'] = 'null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['counter'] = 'auto_increment';
            }

        }

        if ($name_column == '') {
            return $this->db['tables'][$table]['columns'];
        } else {
            return $this->db['tables'][$table]['columns'][$name_column];
        }
        $this->mdb->freeResult($result);
    }
    
    /*function getPrimaryKeyTable($table)
    {
        $i = 0;
        $j = 0;
  	
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");
        
        $columns = $this->getColumnList($table);

        foreach($columns as $column) {

            $sql = "SELECT $column FROM $table";

            $result = $this->mdb->query($sql);

            if (MDB::isError($result))
            {
                print('Error: de Mysql al obtener las llaves primarias de la tabla: ' . $this->mdb->mysqlRaiseError($result).'<br>');
                return ' ';
            }

            $column  = mysql_field_name($result, $i);
            $flags = mysql_field_flags($result, $i);
            $pieces = explode(" ", $flags);

            foreach($pieces as $obj) {
                if($obj == "primary_key") {
                    $this->db['tables'][$table]['primary_key'][$j] = $column;
                    $j++;
                }
            }
        }
        $this->mdb->freeResult($result);
        return $this->db['tables'][$table]['primary_key'];
    }*/
            
    function getPrimaryKeyTable($table)
    {
        $i = 0;
  	
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        $sql = "SHOW KEYS FROM $table";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Mysql al obtener las llaves primarias de la tabla: ' . $this->mdb->mysqlRaiseError($result).'<br>');
            return ' ';
        }

        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result,"", $i);
            $index_type = $array[2];
            if($index_type == 'PRIMARY') {
                $column_key = $array[4];
                $this->db['tables'][$table]['primary_key'][$i] = $column_key;
            }
        }

        $this->mdb->freeResult($result);
        return $this->db['tables'][$table]['primary_key'];
    }
        
    function getForeignKeyTable($table)
    {
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");
        
        $sql = "SHOW TABLE STATUS LIKE '$table'";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Mysql al obtener las llaves foraneas de la tabla: ' . $this->mdb->mysqlRaiseError($result).'<br>');
            return ' ';
        }

        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result,"", $i);
            $db_type = $array[1];
            if($db_type == 'InnoDB') {
                $pieces = explode(';', $array[14]);
                $num_pieces = count($pieces)-1;
                
                if($num_pieces == 0) {
                    //print ('La tabla '.$table.' no tiene foreign key <br>');
                    return ' ';
                }

                for($j=1; $j <= ($num_pieces); $j++) {
                    //el nombre del constraint que es el mismo que el del foreign key /
                    //se obtiene de la consulta 'show create table name_table'
                    if( $j == 1 ) {
                        $this->db['tables'][$table]['foreign_key'][$j-1]['name'] = $table.'_A';
                    }else {
                        $k = $j-1;
                        $this->db['tables'][$table]['foreign_key'][$j-1]['name'] = $table.'_A'.$k;
                    }
                    
                    $this->db['tables'][$table]['foreign_key'][$j-1]['local_table'] = $table;
                    $this->db['tables'][$table]['foreign_key'][$j-1]['local_table_columns'] = explode(' ',preg_replace("/.*\((.*)\).*\/(.*)\((.*)\)/", "\\1",$pieces[$j]));
                    $this->db['tables'][$table]['foreign_key'][$j-1]['reference_table'] = preg_replace("/.*\((.*)\).*\/(.*)\((.*)\)/", "\\2",$pieces[$j]);
                    $this->db['tables'][$table]['foreign_key'][$j-1]['reference_table_columns'] = explode(' ',preg_replace("/.*\((.*)\).*\/(.*)\((.*)\)/", "\\3",$pieces[$j]));
                }
                return $this->db['tables'][$table]['foreign_key'];
            }else {
                //print('Error: los Foreign Key para la tabla '.$table.' no son soportados el SMBD<br>');
                return ' ';
            }

        }
    }

    function getIndex($table)
    {
        $j = 0;
        $falt = 'FALSE';
        
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        $sql = "SHOW INDEX FROM $table";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Mysql al obtener los indices de la tabla: ' . $this->mdb->mysqlRaiseError($result).'<br>');
            return ' ';
        }

        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result,"", $i);
            $falt = 'FALSE';

            if($array[2] != 'PRIMARY') {
                
                if(isset($this->db['tables'][$table]['index'])) {
                    $count_array = count($this->db['tables'][$table]['index']);
                    for($k=0; $k <= ($count_array); $k++) {
                        if (($this->db['tables'][$table]['index'][$k]['name']) == ($array[2])) {
                            $count_column = count($this->db['tables'][$table]['index'][$k]['column_name']);
                            $this->db['tables'][$table]['index'][$k]['columns_name'][$count_column+1] = $array[4];
                            $falt = 'TRUE';
                        } 
                    }
                }

                if($falt == 'FALSE') {

                    $this->db['tables'][$table]['index'][$j]['name'] = $array[2];

                    if ($array[1] == 0) {
                        $this->db['tables'][$table]['index'][$j]['type'] = 'unique';
                    }else {
                        $this->db['tables'][$table]['index'][$j]['type'] = 'nonunique';
                    }

                    $this->db['tables'][$table]['index'][$j]['access_method'] = $array[10];

                    if ($array[5] == 'A') {
                        $this->db['tables'][$table]['index'][$j]['collation'] = 'asc';
                    }else {
                        $this->db['tables'][$table]['index'][$j]['collation'] = 'null';
                    }

                    $this->db['tables'][$table]['index'][$j]['columns_name'][0] = $array[4];
                    $j++;
                        
                }
            }
        }

        return $this->db['tables'][$table]['index'];
    }

    function getSequence( )
    {
        // print('Error: Las sequencias no son soportados por el SMBD<br>');
         return ' ';
    }
    
    function emptyVar ($var, $message)
    {
        if (empty($var))
        {
            print('Error: ' . $message);
            return ' ';
        }
    }

/*********Pruebas**********/
/*
    function prueba ()
    {
      //$result = $this->mdb->getTableFieldDefinition('otros_atributos', 'Cedula');
      //return $result;
      
      $cadena ='InnoDB free: 11264 kB; (Codigo_Condominio) REFER raizmysqlinnodb/condominio(Codigo_Condominio); (Codigo_Ciudad Codigo_Pais) REFER raizmysqlinnodb/ciudad(Codigo_Ciudad Codigo_Pais)';
      echo preg_replace("/kB.*\;(.*)/", "\\1",$cadena);
      //$data1 = preg_replace("/.*\('(.*)'\)/", "\\1",$row[1]);
                        // enum('a','b','c')
                        //     a','b','c
                        //   'a','b','c'\((.*)\)
    }
*/
/*
function mysql_enum_values($table, $field)
{
    $sql = "SHOW COLUMNS FROM $table LIKE '$field';";
    
    $result = $this->mdb->query($sql);

    if (MDB::isError($result))
    {
        die ('Error de Mysql al obtener las llaves foraneas de la tabla: ' . $this->mdb->mysqlRaiseError($result));
    }

    $row = $this->mdb->fetchInto($result,"", 0);
    echo $row[1].'<br>';
    //$data1 = preg_replace("/.*\('(.*)'\)/", "\\1",$row[1]);
    $data1 = preg_replace("/.*\((.*)\)/", "\\1",$row[1]);
                        // enum('a','b','c')
                       //     a','b','c
                        //   'a','b','c'

    echo $data1.'<br>';
    //return(explode("','",preg_replace("/.*\('(.*)'\)/", "\\1",$row[1])));
    $data2 = preg_replace(",", "-",$data1);
    echo $data2.'<br>';
    $data3 = str_replace ("','", ",", $data1);
    echo $data3.'<br>';
}
*/
}

?>
