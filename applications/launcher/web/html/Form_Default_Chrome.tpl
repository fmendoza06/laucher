{config_load file="Templates.lan" section="chrome"}
<!DOCTYPE html>
<html>
<head>
    <link href="web/template/AdminLTE/dist/css/styles.css" rel="stylesheet" type="text/css" />
    <!-- Contiene el head de la pagina -->
    <meta charset="UTF-8">
<title>Chrome Optimized</title>
<link rel="shortcut icon" href="./web/template/pos/themes/default/assets/images/logo.png" />

<script type="text/javascript">
    if (parent.frames.length !== 0) {
        top.location = './index.php';
    }
</script>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel1="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ruda:400,700,900">
    <style type="text/css">
    body { margin: 0; padding: 10px; background-color: #3C8DBC; font-family: "Ruda",sans-serif; color: #FFF; }
    .middle-box { margin: 0 auto; max-width: 400px; padding-top: 30px; z-index: 100; text-align: center; }
    .middle-box h1 { font-size: 170px; color:white; margin:0; }
    </style>
</head>

<body class="bg-theme03">
    <div class="middle-box">
        <h1>:(</h1>
        <h3 style="font-weight:bold;">Copyright &copy; {#TITLE#}. <br>{#RIGHT#}</h3>
        <div style="color:#F5F5F5;">
            {#TITLE2#}
        </div>
        <div style="color:#F5F5F5;font-weight:bold;font-size:40px;">
            Chrome
        </div>        
         
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

</html>


