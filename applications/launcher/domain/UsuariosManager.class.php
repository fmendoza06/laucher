<?php	
define("EXITO_OPERACION_REALIZADA", 103);
define("ERROR_AUTH", 106);
class UsuariosManager
{
    /**
     * objeto para la instancia del modelo
     */
    var $gateway;

    function __construct()
    {
        $this->gateway = Application::getDataGateway("usuarios");
    }

    function getExisteUSUARIO($USUAEMAI, $USUAPASS)
    {
        $usuario = $this->gateway->existeUSUARIO($USUAEMAI, $USUAPASS);
        // se Valida que se traigan datos
        if ($usuario) {
                //Se incorporan lo datos en la session para tenerlos disponibles
                WebSession::setIAuthProperty("usuaid", $usuario[0]['USUAID']);
                WebSession::setIAuthProperty("usuanomb", $usuario[0]['USUANOMB']);
                WebSession::setIAuthProperty("usuaapel", $usuario[0]['USUAAPEL']);
                WebSession::setIAuthProperty("usuatele", $usuario[0]['USUATELE']);
                WebSession::setIAuthProperty("usuaident", $usuario[0]['USUAIDENT']);
                WebSession::setIAuthProperty("usuaemai", $usuario[0]['USUAEMAI']);
            return EXITO_OPERACION_REALIZADA;
        } else {
            return ERROR_AUTH;
        }
    }
}
?>	