<html>
<head>
<head>
       <title>Save Navigation Configuration File</title>
</head>
<body>
<h2>Save Navigation Configuration File</h2>
<hr>

<?php

//Esto es para independizar librerias del include_path de PHP
//Se afecta el Application.class y el Serializer.php
global $saveconfiguration;
$saveconfiguration = "S";

require_once "config.inc.php";
require_once "Serializer.class.php";

$Navigation_config = array(
    'default_action' => 'CmdDefaultLogin', 
    'default_action_CHROME' => 'default_CHROME',
    'error_view' => 'CmdDefaultError',
    'commands' => array(

    	//Comando por defecto de la aplicacion sino se invoca el action en el REQUEST
        'default' => array(
            'class' => 'DefaultCommand',
            'validated' => 'false',
            'views' => array(
                'success' => array(
                    'view' => 'Form_Wellcome.tpl',
                    'redirect' => 0
                )
            )
        ),
        //Comando por defecto para lanzar la pagina de autenticacion
        'CmdDefaultLogin' => array(
            'class' => 'CmdDefaultLogin',
            'validated' => 'false', // No se utilizará por el momento
            'views' => array(
                'success' => array(
                    'view' => 'Form_Login.tpl', // Pantalla Login
                    'redirect' => 0
                )
            )
        ),

        //Comando para realizar la autenticacion
        'CmdLogin' => array(
            'class' => 'CmdLogin',
            'validated' => 'false', // No se utilizará por el momento
            'views' => array(
                'success' => array(
                    'view' => 'Form_HomeAdmin.tpl', // Pantalla Home de Louncher Admin 
                    'redirect' => 0
                ),
                'fail' => array(
                    'view' => 'Form_Login.tpl', // Pantalla Login
                    'redirect' => 0
                )                

            )
        ),          

        //Comando por defecto para lanzar la pagina Hola Mundo
        'CmdDefaultHolaMundo' => array(
            'class' => 'CmdDefaultHolaMundo',
            'validated' => 'false', // No se utilizará por el momento
            'views' => array(
                'success' => array(
                    'view' => 'Form_HolaMundo.tpl', // Pantalla Hola Mundo
                    'redirect' => 0
                )
            )
        ),        

        //Comando por defecto ejemplo de uso de Smarty Engine
        'CmdDefaultSample' => array(
            'class' => 'CmdDefaultSample',
            'validated' => 'false',
            'views' => array(
                'success' => array(
                    'view' => 'Form_Default_Sample.tpl',
                    'redirect' => 0
                )
            )
        ),

        
        'CmdDefaultError' => array(
            'class' => 'CmdDefaultError',
            'validated' => 'false',
            'desc' => 'Cargar Forma Error',
            'views' => array(
                'success' => array(
                    'view' => 'Form_Error.tpl',
                    'redirect' => 0
                )
            )
        ),		





	) // Fin arreglo de comandos	

); //Fin Arreglo Navigation_config

echo "<pre>";
print_r($Navigation_config);
echo "</pre>";

$result = Serializer::save($Navigation_config, 'web.conf.data');

if (@PEAR::isError($result)) {
    echo "Error creating configuration file";
}

?>
</body>
</html>
