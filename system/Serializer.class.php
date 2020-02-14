<?php

/**
*   Copyright 2006 - Spyro Solutions
*   
*   @author Spyro Solutions - Jose Fernando Mendoza
*   @date 14-Dic-2006 10:43:34
*   @location Cali-Colombia
*/  

global $saveconfiguration;

if ($saveconfiguration != "S")
{
      require_once "./../../lib/PEAR/PEAR.php";
}
else
{
 require_once "../../../lib/PEAR/PEAR.php";
}




class Serializer {

    /**
    *
    * @access public
    * @param  string $obj  asocciative array to save into file
    * @return true or PEAR:raiseError. 
    */
    public static function save($obj, $filename) {

        if (!is_object($obj) && !is_array($obj)) {
            return PEAR::raiseError("trying to serialize a non-object");
        } else {

            $SerializedObj = serialize($obj);

            $fp = fopen($filename, "w");
            if (!$fp) {
                return PEAR::raiseError("cannot open file $filename");
            }

            $write =  fwrite ( $fp, $SerializedObj );
            if (!$write) {
                return PEAR::raiseError("error writing serialized data to $filename");
            }

            $close = fclose ( $fp);
            if(!$close) {
                return PEAR::raiseError("error closing the serialisation file $filename");
            }
            
            return true;
        }
    }


    /**
    *
    * @access public
    * @param  string $filename  serialized file name 
    * @return asocciative array of serilized file or PEAR:raiseError. 
    */

    public static function load($filename) {
        $fp = @fopen($filename, "r");
        if (!$fp) {
            return PEAR::raiseError("cannot open file $filename");
        }

        $read =  fread ( $fp, filesize ($filename));
        if(!$read) {
            return PEAR::raiseError("error reading file $filename");
        }

        $obj = unserialize ($read);
        if(!$obj) {
            return PEAR::raiseError("error deserialising file $filename");
        }

        $close = fclose ( $fp);
        if(!$close) {
            return PEAR::raiseError("error closing the serialisation file $filename");
        }
		//print_r($obj);
        return $obj;
    }

}

?>
