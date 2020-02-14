<?php
class Usuarios
{
	/**
	 * conexion de bases de datos.
	 */
	var $connection;
	/**
	 * recurso de base de datos
	 */
	var $consult;

	/**
	 * Metodo constructor de la clase
	 */
	function __construct()
	{
		$this->connection = Application::getDatabaseConnection();
		$this->connection->SetFetchMode(2);
	}

	/**
	 * Metodo para consultar por el correo y clave de los usuarios. 
	 */
	function existeUSUARIO($USUAEMAI, $USUAPASS)
	{
		$sql = "SELECT * FROM usuarios WHERE USUAEMAI='$USUAEMAI' AND USUAPASS='$USUAPASS'";
		return $this->connection->GetAll($sql);

	}

}
?>