<?PHP

class MDB_MetaData_oci8
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
        $this->db['name_db'] =$this->mdb->user;
        return $this->db['name_db'];
    }

    function getTableList()
    {
        $i = 0;
        $j = 0;

        //el username tiene que estar en mayuscula
        $username = $this->mdb->user;

        //las variables tienen que estar entre comillas sencillas	
        $sql = "SELECT table_name FROM dba_tables WHERE owner= '$username'";
   
        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: al obtener los nombres de las tablas: ' . $this->mdb->oci8RaiseError($result).'<br>');
            return ' ';
        }
        
        //el metodo fetchRow ya no existe en el driver de oci8
        //solo existe en la clase DB_result de DB.php
        //while ($row =$this->db->fetchRow($result)) { 	

        //el fetchInto no retorna el array con los datos, si no una cadena
        //que me dice si pudo hacer la operacion, el lo coloca en un array
        //llamado $arr pero no lo retorna
        //while ($this->db->fetchInto($result, $row)) { 	

        while (OCIFetchInto($result, $row, OCI_RETURN_NULLS + OCI_RETURN_LOBS)) { 	
            $this->db['tables']['list'][$j] = $row[$i];
            $j++;
        }
        $this->mdb->freeResult($result);

        return $this->db['tables']['list'];
    }
	
    function getColumnList($table)
    {
        $i = 0;
        $j = 0;

        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        //las variables tienen que estar entre comillas sencillas	
 	    //$sql = "SELECT column_name FROM dba_tab_columns WHERE table_name= '$table'";
 	    $sql = "SELECT column_name FROM user_tab_columns WHERE table_name= '$table'";
	    $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: al obtener los nombres de las columnas: ' . $this->mdb->oci8RaiseError($result).'<br>');
            return ' ';
        }

        for ($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result, " ", $i);
            $this->db['tables'][$table]['columns']['list'][$i] = $array[$j];
            
        }

        $this->mdb->freeResult($result);
        return $this->db['tables'][$table]['columns']['list'];
    }


    function getColumnData($table, $column = " ")
    {
        $i = 0;
        $j = 0;
	
	    $this->emptyVar($table,"No se ha definido el nombre de la tabla");
	    
        if ($column == '') {
            $columns = $this->getColumnList($table);
        } else {
            $columns[$i] = $column;
        }

        foreach($columns as $column) {
            //las variables tienen que estar entre comillas sencillas	
            $sql = "select column_name, data_type, data_length, data_precision, data_scale, nullable, data_default from dba_tab_columns where table_name='$table' and column_name='$column'";
            
            $result = $this->mdb->query($sql);

            if (MDB::isError($result))
            {
                print('Error: de Oracle al obtener la metatada de las columnas: ' . $this->mdb->oci8RaiseError($result).'<br>');
                return ' ';
            }

            /*otra forma de sacar los metadatos de una columna
            $ncols = OCINumCols($result);
            for ( $i = 1; $i <= $ncols; $i++ ) {
                $results[0] = $column_name  = OCIColumnName($result, $i);
                $results[1] = $column_type  = OCIColumnType($result, $i);
                $results[2] = $column_size  = OCIColumnSize($result, $i);
            }*/

            //$row = $this->db->fetchRow($result);
            OCIFetchInto($result, $row, OCI_RETURN_NULLS + OCI_RETURN_LOBS);

            $this->db['tables'][$table]['columns'][$column]['name'] = $row[0];
            $this->db['tables'][$table]['columns'][$column]['type'] = $row[1];
            
            if ($row[3] == '') {
                $this->db['tables'][$table]['columns'][$column]['precision'] = $row[2];
                $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['precision'] = $row[3];
                if ($row[4] != '') {
                    $this->db['tables'][$table]['columns'][$column]['scale'] = $row[4];
                }else {
                    $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
                }
            }

            if ($row[5] == 'N') {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'NOT NULL';
            }else {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'NULL';
            }
            
            if ($row[6] == '') {
                $this->db['tables'][$table]['columns'][$column]['default'] = 'null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['default'] = $row[6];
            }
            
            $this->db['tables'][$table]['columns'][$column]['counter'] = 'null';
            
            $this->mdb->freeResult($result);
        }
        return $this->db['tables'][$table]['columns'];
    }

    function getPrimaryKeyTable($table)
    {
        $i = 0;
        $j = 0;
        
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");
         
        //$sql= "SELECT a.table_name, a.constraint_name, a.column_name, a.position column_position
        $sql = "SELECT a.column_name
                FROM user_cons_columns a, user_constraints b
                WHERE a.table_name like '$table'
                AND b.table_name = a.table_name
                AND b.constraint_type = 'P'
                AND a.constraint_name = b.constraint_name
                ORDER BY a.position";

        $result = $this->mdb->query($sql);


        if (MDB::isError($result))
        {
            print('Error: de Oracle al obtener las llaves primarias de la tabla: ' . $this->mdb->oci8RaiseError($result).'<br>');
            return ' ';
        }
   
        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result,"", $i);
            $this->db['tables'][$table]['primary_key'][$i] = $array[$j];
        }

        $this->mdb->freeResult($result);
        
        return $this->db['tables'][$table]['primary_key'];
    }
    
    function getForeignKeyTable($table)
    {
        $i = 0;
        $j = 0;
        $falt = 'FALSE';

        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        $sql = "SELECT a.constraint_name, a.table_name, a.column_name, b.r_constraint_name
                FROM user_cons_columns a, user_constraints b
                WHERE a.table_name like '$table'
                AND b.table_name = a.table_name
                AND b.constraint_type = 'R'
                AND a.constraint_name = b.constraint_name";
			
        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Oracle al obtener las llaves foraneas de la tabla: ' . $this->mdb->oci8RaiseError($result).'<br>');
            return ' ';
        }
        
        $num_results = $this->mdb->numRows($result);
        
        if($num_results == 0) {
            //print ('La tabla '.$table.' no tiene foreign key <br>');
            return ' ';
        }

        for($i=0; $i < $num_results ; $i++) {
            $falt = 'FALSE';
            $local_foreignKey = $this->mdb->fetchInto($result,"", $i);

            if(isset($this->db['tables'][$table]['foreign_key'])) {
                $num_foreignKey = count($this->db['tables'][$table]['foreign_key']);

                for($k=0; $k < $num_foreignKey; $k++) {
                    if($this->db['tables'][$table]['foreign_key'][$k]['name'] == $local_foreignKey[0]) {
                        $num_columns = count($this->db['tables'][$table]['foreign_key'][$k]['local_table_columns']);
                        $this->db['tables'][$table]['foreign_key'][$k]['local_table_columns'][$num_columns] = $local_foreignKey[2];
                        $falt = 'TRUE';
                    }
                }
            }
            
            if($falt == 'FALSE') {
                $num_foreignKey = count($this->db['tables'][$table]['foreign_key']);
                $this->db['tables'][$table]['foreign_key'][$num_foreignKey]['name'] = $local_foreignKey[0];
                $this->db['tables'][$table]['foreign_key'][$num_foreignKey]['local_table'] = $local_foreignKey[1];
                $this->db['tables'][$table]['foreign_key'][$num_foreignKey]['local_table_columns'][0] = $local_foreignKey[2];
                   
                $sql = "SELECT a.table_name, a.column_name
                        FROM user_cons_columns a, user_constraints b
                        WHERE a.constraint_name like '$local_foreignKey[3]'
                        AND b.table_name = a.table_name
                        AND b.constraint_type = 'P'";
			
                $result1 = $this->mdb->query($sql);

                if (MDB::isError($result1))
                {
                    print('Error: de Oracle al obtener las llaves foraneas de la tabla: ' . $this->mdb->oci8RaiseError($result1).'<br>');
                    return ' ';
                }
                    
                for($j=0; $j < ($this->mdb->numRows($result1)); $j++) {
                    $reference_foreignKey = $this->mdb->fetchInto($result1,"", $j);
                    $this->db['tables'][$table]['foreign_key'][$num_foreignKey]['reference_table'] = $reference_foreignKey[0];
                    $this->db['tables'][$table]['foreign_key'][$num_foreignKey]['reference_table_columns'][$j] = $reference_foreignKey[1];
                }
                
                $this->mdb->freeResult($result1);
            }
        }

        $this->mdb->freeResult($result);

        return $this->db['tables'][$table]['foreign_key'];
        
    }
    //imprime todos los indices incluyendo los de los primary key
    //tener encuenta esto ya que mysql y postgres no los tiene encuenta
    //por que seria tener dos indices iguales
    function getIndex( $table )
    {
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        $sql = "SELECT index_name, index_type, uniqueness
	            FROM user_indexes
	            WHERE table_name = '$table'";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Oracle al obtener los indices de la tabla: ' . $this->mdb->oci8RaiseError($result).'<br>');
            return ' ';
        }

        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result,"", $i);
            $this->db['tables'][$table]['index'][$i]['name'] = $array[0];
            
            if(($array[1] == 'NORMAL') && ($array[2] == 'UNIQUE')) {
                $this->db['tables'][$table]['index'][$i]['type'] = $array[2];
            } else {
                if(($array[1] == 'NORMAL') && ($array[2] == 'NONUNIQUE')) {
                    $this->db['tables'][$table]['index'][$i]['type'] = $array[2];
                } else {
                    $this->db['tables'][$table]['index'][$i]['type'] = $array[1];
                }
            }
            //no encontre el atributo que contiene el metodo de acceso de los index
            //por defecto se coloco BTREE
            $this->db['tables'][$table]['index'][$i]['access_method'] = 'BTREE';

            $sql1 = "SELECT column_name, descend FROM user_ind_columns WHERE index_name = '$array[0]'";
	            
            $result1 = $this->mdb->query($sql1);

            if (MDB::isError($result1))
            {
                print('Error: de Oracle al obtener los indices de la tabla: ' . $this->mdb->oci8RaiseError($result1).'<br>');
                return ' ';
            }

            for($j=0; $j < ($this->mdb->numRows($result1)); $j++) {
                $array1 = $this->mdb->fetchInto($result1,"", $j);
                $this->db['tables'][$table]['index'][$i]['collation'] = $array1[1];
                $this->db['tables'][$table]['index'][$i]['columns_name'][$j] = $array1[0];

            }
        }

        $this->mdb->freeResult($result);

        return $this->db['tables'][$table]['index'];
    }

    function  getSequence()
    {
        $sql= "select *from user_sequences";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Oracle al obtener las sequencias: ' . $this->mdb->oci8RaiseError($result).'<br>');
            return ' ';
        }

        $num_rows = $this->mdb->numRows($result);

        if($num_rows > 0) {
            for($i=0; $i < $num_rows; $i++) {
                $array = $this->mdb->fetchInto($result,"", $i);
                $this->db['sequences']['list'][$i] = $array[0];
                //se cambio el nombre del indice 'Last_number' en el arreglo por 'Start'
                //para mantener un estandar en el nombre de los indices
                $this->db['sequences'][$array[0]]['Start']= $array[7];
                $this->db['sequences'][$array[0]]['Increment']= $array[3];
                $this->db['sequences'][$array[0]]['Min_value']= $array[1];
                $this->db['sequences'][$array[0]]['Max_value']= $array[2];
                //se cambio el nombre del indice 'Cycle_flag' en el arreglo por 'Cycle'
                //para mantener un estandar en el nombre de los indices
                //$this->db['sequences'][$array[0]]['Cycle_flag']= $array[4];
                if ($array[4] == 'N') {
                    $this->db['sequences'][$array[0]]['Cycle']= 'NULL';
                }else {
                    $this->db['sequences'][$array[0]]['Cycle']= 'NOT NULL';
                }

                //$this->db['sequences'][$array[0]]['Cycle']= $array[4];
                //se cambio el nombre del indice 'Cache_size' en el arreglo por 'Cache'
                //para mantener un estandar en el nombre de los indices
                //$this->db['sequences'][$array[0]]['Cache_size']= $array[6];
                $this->db['sequences'][$array[0]]['Cache']= $array[6];

                //no encontre equivalencia en los otros SMBD
                //$this->db['sequences'][$array[0]]['Order_flag']= $array[5];
                }
            return $this->db['sequences'];
        } else {
            print('Esta base de datos no tiene sequencias<br>');
            return ' ';
        }
    }

    function emptyVar ($var, $message)
    {
        if (empty($var))
        {
            print('Error: ' . $message.'<br>');
            return ' ';
        }
    }

/********Pruebas**********/
/*
    function prueba ($table)
    {
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        //$sql = "SELECT BLEVEL
	    //        FROM user_indexes
	    //        WHERE table_name = '$table'";

        $sql= "select *from user_sequences";
        
        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            die ('Error de Oracle al obtener los indices de la tabla: ' . $this->mdb->oci8RaiseError($result));
        }
        $count = $this->mdb->numRows($result);
        
        for($i=0; $i < ($count); $i++) {
            $result = $this->mdb->fetchInto($result,"", $i);
            //$result1[$i]= $result;
        }
        return $result;
    }
*/
}

?>
