<!-- This Template use AdminLTE css styles -->
<!-- Complete AdminLTE theme repository is in ./web/template/AdminLTE -->

{config_load file="Templates.lan" section="index"}
<!DOCTYPE html>
<html>
<head>
    <link href="web/template/AdminLTE/dist/css/styles.css" rel="stylesheet" type="text/css" />
    <!-- Contiene el head de la pagina -->
    <meta charset="UTF-8">
    <title>{#TITLE#}</title>
    <link rel="shortcut icon" href="./web/images/launcherico.png" />

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel1="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ruda:400,700,900">
    <style type="text/css">
    body { margin: 0; padding: 10px; background-color: #3C8DBC; font-family: "Ruda",sans-serif; color: #FFF; }
    .middle-box { margin: 0 auto; max-width: 400px; padding-top: 30px; z-index: 100; text-align: center; }
    .middle-box h1 { font-size: 170px; color:white; margin:0; }
    </style>

  <!-- Alertify -->  
  <script src="./web/template/AdminLTE/plugins/alertify/alertify.min.js"></script>
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.css" />
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.core.css" />            
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.default.css" />    
      
</head>

<body class="bg-theme03">
    <div class="middle-box animated fadeInDown">
        <h1>{#WELLCOME#}</h1>
        <h3 style="font-weight:bold;">Copyright &copy; {#TITLE#}. <br>{#RIGHT#}</h3>
        <div style="color:#F5F5F5;">
           {#WELLCOMETITLE#} 
        </div>

        <div style="color:#F5F5F5;">
           <h2 style="font-weight:bold;">{#ALLISOK#}</h2>
        </div>


         <!-- --> 
            <div class="">
                <p>&nbsp;</p>
                <p>
                    <span class="text-danger">
                        <a href="./index.php?action=CmdDefaultLogin" class="btn btn-primary btn-block btn-flat" ><i class="glyphicon glyphicon-log-in"></i>&nbsp;{#BACKLOGIN#}</a>
                    </span>
                </p>
            </div>
               
    </div>
    
</body>
<!-- Plugin de mensajes emergentes -->
{messagebox id=$cod_message}
</html>


