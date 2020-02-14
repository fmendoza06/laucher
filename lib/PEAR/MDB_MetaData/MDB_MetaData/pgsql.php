<?PHP
class MDB_MetaData_pgsql
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
            /*$sql = "SELECT pg_attribute.attname,
                    pg_type.typname,
                    pg_attribute.attlen,
                    pg_attribute.attnotnull
                    FROM  pg_attribute
                    INNER JOIN pg_class ON pg_attribute.attrelid = pg_class.oid
                    INNER JOIN pg_type ON pg_attribute.atttypid = pg_type.oid
                    WHERE pg_class.relname = '$table' and pg_attribute.attname = '$column'";
            */
            $sql = "SELECT pg_attribute.attname,
                    pg_type.typname,
                    pg_attribute.attlen,
                    pg_attribute.attnotnull,
		            pg_attribute.atthasdef,
		            pg_attrdef.adsrc
                    FROM  pg_attribute
                    INNER JOIN pg_class ON pg_attribute.attrelid = pg_class.oid
                    INNER JOIN pg_type ON pg_attribute.atttypid = pg_type.oid
		            LEFT JOIN pg_attrdef ON pg_attribute.attrelid=pg_attrdef.adrelid AND pg_attribute.attnum=pg_attrdef.adnum
                    WHERE pg_class.relname = '$table' and pg_attribute.attname = '$column'";
                    
            $result = $this->mdb->query($sql);

            if (MDB::isError($result))
            {
                print('Error: de Postgresql al obtener la metatada de las columnas: ' . $this->mdb->pgsqlRaiseError($result).'<br>');
                return ' ';
            }

            $row = pg_fetch_row($result);

            $this->db['tables'][$table]['columns'][$column]['name'] = $row[0];
            $this->db['tables'][$table]['columns'][$column]['type'] = $row[1];
            $this->db['tables'][$table]['columns'][$column]['precision'] = $row[2];

            if ($this->db['tables'][$table]['columns'][$column]['precision'] == '-1') {
                $sql = "SELECT
					    format_type(a.atttypid, a.atttypmod) as type
					    FROM
					    pg_attribute a LEFT JOIN pg_attrdef adef
					    ON a.attrelid=adef.adrelid AND a.attnum=adef.adnum
				        WHERE
					    a.attrelid = (SELECT oid FROM pg_class WHERE relname='{$table}')
					    AND a.attname = '{$column}'
				        ORDER BY a.attnum";
			
		        $result = $this->mdb->query($sql);

                if (MDB::isError($result))
                {
                    print('Error: de Postgresql al obtener la metatada de las columnas: ' . $this->mdb->pgsqlRaiseError($result).'<br>');
                    return ' ';
                }

                $row1 = pg_fetch_row($result);

                if(strstr($row1[0],'(')) {
                    $data_type = split('[(,)]', $row1[0]);
                    $this->db['tables'][$table]['columns'][$column]['precision'] = $data_type[1];
                    if($data_type[2]!= '') {
                        $this->db['tables'][$table]['columns'][$column]['scale'] = $data_type[2];
                    }else {
                        $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
                    }
                }else {
                    $this->db['tables'][$table]['columns'][$column]['precision'] = 'null';
                    $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
                }
             }else {
                $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
             }
             
             
             if ($row[3] == 't') {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'not null';
             }else {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'null';
             }
             
             if ($row[5] == '') {
                $this->db['tables'][$table]['columns'][$column]['default'] = 'null';
             }else {
                $this->db['tables'][$table]['columns'][$column]['default'] = $row[5];
             }

             if ($row[4] == 't') {
                $this->db['tables'][$table]['columns'][$column]['counter'] = 'serial';
                $this->db['tables'][$table]['columns'][$column]['type'] = 'serial';
             }else {
                $this->db['tables'][$table]['columns'][$column]['counter'] = 'null';
             }
            $this->mdb->freeResult($result);
        }

        return $this->db['tables'][$table]['columns'];
    }

    function getPrimaryKeyTable($table)
    {
        $i = 0;
        $j = 0;
        
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");
        
        $sql = "SELECT indkey
                FROM pg_index
                WHERE indisprimary AND indrelid=(SELECT oid
                                                 FROM pg_class
                                                 WHERE relname='{$table}')";
        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Postgresql al obtener las llaves primarias de la tabla: ' . $this->mdb->pgsqlRaiseError($result).'<br>');
            return ' ';
        }

        $row = pg_fetch_row($result);

        foreach($row as $obj) {
            $indKey = explode(' ', $obj);
        }

        $this->mdb->freeResult($result);

        $sql = "SELECT attname FROM pg_attribute
		      	WHERE attnum IN ('" . join("','", $indKey) . "')
			    AND attrelid = (SELECT oid FROM pg_class WHERE relname='{$table}')";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Postgresql al obtener las llaves primarias de la tabla: ' . $this->mdb->pgsqlRaiseError($result).'<br>');
            return ' ';
        }

        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = pg_fetch_row($result,$i);
            $this->db['tables'][$table]['primary_key'][$i] = $array[0];
        }

        $this->mdb->freeResult($result);
        
        return $this->db['tables'][$table]['primary_key'];
    }
    
    function getForeignKeyTable($table)
    {
        $i = 0;
        $j = 0;

        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        $sql = "SELECT t.tgargs as arg
                FROM pg_trigger t, pg_class c, pg_proc p
                WHERE t.tgrelid=c.oid
                AND t.tgfoid=p.oid
                AND p.proname='RI_FKey_check_ins'			
                AND c.relname='{$table}'";
			
        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Postgresql al obtener las llaves foraneas de la tabla: ' . $this->mdb->pgsqlRaiseError($result).'<br>');
            return ' ';
        }

        $num_results = $this->mdb->numRows($result);
        
        if($num_results == 0) {
            //die( 'La tabla '.$table.' no tiene foreign key');
            //print('La tabla '.$table.' no tiene foreign key <br>');
            return ' ';
        }
        
        for($i=0; $i < $num_results; $i++) {
            $row = pg_fetch_row($result);
            $pieces = explode('UNSPECIFIED\000', $row[0]);
            $fk_tables[$i] = explode('\000', $pieces[0]);
            $fk_columns[$i] = explode('\000', $pieces[1]);

            $this->db['tables'][$table]['foreign_key'][$i]['name'] = $fk_tables[$i][0];
            $this->db['tables'][$table]['foreign_key'][$i]['local_table'] = $fk_tables[$i][1];

            $num_columns =  count($fk_columns[$i])-1;

            for($j=0,$k=1,$l=0; $j < $num_columns; $j+=2,$k+=2,$l++)
            {
                $this->db['tables'][$table]['foreign_key'][$i]['local_table_columns'][$l] =  $fk_columns[$i][$j];
                $this->db['tables'][$table]['foreign_key'][$i]['reference_table'] = $fk_tables[$i][2];
                $this->db['tables'][$table]['foreign_key'][$i]['reference_table_columns'][$l] =  $fk_columns[$i][$k];
            }

            $this->mdb->nextResult($result);
        }

        $this->mdb->freeResult($result);

        return $this->db['tables'][$table]['foreign_key'];
    }
    
    function getIndex( $table )
    {
        $sql= "SELECT c2.relname, i.indisprimary, i.indisunique, pg_get_indexdef(i.indexrelid)
			FROM pg_class c, pg_class c2, pg_index i
			WHERE c.relname = '{$table}' AND c.oid = i.indrelid AND i.indexrelid = c2.oid
			AND NOT i.indisprimary AND NOT i.indisunique
			ORDER BY c2.relname";

        $result = $this->mdb->query($sql);

        if (MDB::isError($result))
        {
            print('Error: de Postgres al obtener los indices de la tabla: ' . $this->mdb->pgsqlRaiseError($result).'<br>');
            return ' ';
        }

        for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
            $array = $this->mdb->fetchInto($result,"", $i);
            $this->db['tables'][$table]['index'][$i]['name'] = $array[0];

            if ($array[2] == 't') {
                $this->db['tables'][$table]['index'][$i]['type'] = 'unique';
            }else {
                $this->db['tables'][$table]['index'][$i]['type'] = 'nonunique';
            }

            $this->db['tables'][$table]['index'][$i]['access_method'] = preg_replace("/.*USING(.*)\((.*)\)/", "\\1",$array[3]);
            //no soporta collation
            //no encontre el atributo que contiene el collation de los index
            //por defecto se coloco ASC
            $this->db['tables'][$table]['index'][$i]['collation'] = 'ASC';
            $this->db['tables'][$table]['index'][$i]['columns_name'] = explode(',',preg_replace("/.*USING(.*)\((.*)\)/", "\\2",$array[3]));
        }

        $this->mdb->freeResult($result);
        return $this->db['tables'][$table]['index'];
    }

    function  getSequence()
    {
        $j = 0;
        $list_sequences = $this->mdb->listSequences( );
        if (MDB::isError($list_sequences)) {
            print('Error: al obtener las sequencias: ' . MDB::errorMessage($list_sequences).'<br>');
            return ' ';
        }
        $count_array = count($list_sequences);

        if($count_array > 0) {
            
            foreach($list_sequences as $sequence) {

                $num_sub_string = substr_count ( $sequence, '_seq' );

                if($num_sub_string != 1) {
                    
                    $sql= "SELECT *FROM {$sequence}";

                    $result = $this->mdb->query($sql);

                    if (MDB::isError($result)) {
                        print('Error: de Postgresql al obtener las sequencias: ' . $this->mdb->pgsqlRaiseError($result).'<br>');
                        return ' ';
                    }

                    for($i=0; $i < ($this->mdb->numRows($result)); $i++) {
                        $array = $this->mdb->fetchInto($result,"", $i);
                    
                        $this->db['sequences']['list'][$j] = $sequence;
                        $j++;
                        //se cambio el nombre del indice 'Last_number' en el arreglo por 'Start'
                        //para mantener un estandar en el nombre de los indices
                        $this->db['sequences'][$array[0]]['Start']= $array[1];
                        $this->db['sequences'][$array[0]]['Increment']= $array[2];
                        $this->db['sequences'][$array[0]]['Min_value']= $array[4];
                        $this->db['sequences'][$array[0]]['Max_value']= $array[3];

                        //se cambio el nombre del indice 'Is_cycled' en el arreglo por 'Cycle'
                        //para mantener un estandar en el nombre de los indices
                        //$this->db['sequences'][$array[0]]['Cycle']= $array[7];
                        if ($array[7] == 't') {
                            $this->db['sequences'][$array[0]]['Cycle']= 'not null';
                        }else {
                            $this->db['sequences'][$array[0]]['Cycle']= 'null';
                        }
                    
                        //se cambio el nombre del indice 'Cache_size' en el arreglo por 'Cache'
                        $this->db['sequences'][$array[0]]['Cache']= $array[5];

                        //no encontre equivalencia en los otros SMBD
                        //$this->db['sequences'][$array[0]]['Log_cnt']= $array[6];
                        //$this->db['sequences'][$array[0]]['Is_called']= $array[8];

                    }
                //genera un error
                //$this->db['sequences'][$sequence] = $this->mdb->getSequenceDefinition($sequence);
                //$this->db['sequences'][$sequence] = $this->mdb->getSequenceName($sequence);
                }
            }
            $this->mdb->freeResult($result);
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
    function prueba ()
    {
      $result = $this->mdb->getTableFieldDefinition('otros_atributos', 'cedula');
      return $result;
    }
*/
}

?>
