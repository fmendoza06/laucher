<?php

/**
*   Copyright 2006 - Spyro Solutions
*   
*   @author Spyro Solutions - Jose Fernando Mendoza
*   @date 14-Dic-2006 10:43:34
*   @location Cali-Colombia
*/  

require_once "WebRegistry.class.php";
require_once "WebSession.class.php";

set_time_limit(0);

class FrontController {

    public static function execute() {
        
        WebSession::checkSession(1800); //30 minutos
        $command =WebRegistry::getWebCommand();
            
        if (@PEAR::isError($command)) {
            echo "command not found :".$_REQUEST["action"];
        }

        $result = $command->execute();
        /*BAV 20170707: se adiciono RSQUEST_AJAX, para retornar json cuando la peticion sea un ajax */
        if ($result == "REQUEST_AJAX") {
            //Realiza el print del json desde el comando
        } 
		else 
		{		
			if (@PEAR::isError($result)) {
				echo $result->getMessage();
			} 
			else 
			{
				
				$view_name = WebRegistry::getWebCommandView($result);
				if (@PEAR::isError($view_name)) 
				{
					echo $view_name->getMessage();
					
				} 
				else 
				{
					$view = WebRegistry::getWebView($view_name);
					
					if (@PEAR::isError($view))
					{
						echo $view->getMessage();
					} 
					else 
					{
						//WebRequest::setProperty("template",Application::getTemplate());
											
						$view->show();
					}
				}
			}
		
		}

    }

}


/*
 Ejemplo del llamado al comando por ajax
 
         $.ajax({
            method: "POST",
            url: "index.php",
            data: {
                action: "CmdAddCar", 
				PRODUID: 1,
                PRODCANT: 20,
				PRODPREC : 1000
            }
            , cache: false
        }).success(function (data) {

            var content = JSON.parse(data);

            if (content != null) {
                if (content[0] != null) {
                    $.each(content, function (i, reg) {
                        if (res.status == 'success') {
                           alert('Producto Adicionado');   
                        } else {

                            alert('La transaccion no fue posible');
                       

                        }
                        return false;
                    });
                }
            }

        }).error(function (data) {
            alert(data.responseText);
            var append = '<option value="">-- Select --</option>';
            $("#select__ROWID_GESTION").append(append);
        });

*/

?>

