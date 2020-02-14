<?PHP
require_once "MDB2.php";
require_once "PEAR.php";

class MDB_MetaData
{

    function &connect($dsn)
    {
        //The MDB::connect() creates an object of the driver class to access the database and initializes some variables.
        $mdb = &MDB2::connect($dsn);

        if (MDB2::isError($mdb)) {
            die ('Error de conexion: ' . MDB2::errorMessage($mdb));
        }
         	
        $type = $mdb->phptype;
        include_once "MDB_MetaData/${type}.php";
        $classname = "MDB_MetaData_${type}";
    
        if (!class_exists($classname)) {
            //return PEAR::raiseError(null, MDB_ERROR_NOT_FOUND, null, null, null, 'MDB_Error', true);
            die ('Error al cargar el manejador: la clase manejadora no existe: ' . MDB2::errorMessage($classname));
        }

        $obj  = &new $classname;
        $obj->setConnectionDataBase($mdb);
  	    return $obj;
    }
}
?>
