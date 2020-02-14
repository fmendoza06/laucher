<?php
/* Smarty version 3.1.30, created on 2018-10-21 06:14:23
  from "D:\webservers\xampp\htdocs\LitlePHP2\applications\launcher\web\html\Form_HomeAdmin.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5bcbfd1f0b26f5_70159674',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c2a714ea08891f6f6fbf0e34dfe4f9b1464ef792' => 
    array (
      0 => 'D:\\webservers\\xampp\\htdocs\\LitlePHP2\\applications\\launcher\\web\\html\\Form_HomeAdmin.tpl',
      1 => 1540093445,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5bcbfd1f0b26f5_70159674 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_messagebox')) require_once 'D:\\webservers\\xampp\\htdocs\\LitlePHP2\\applications\\launcher\\web\\plugins\\function.messagebox.php';
?>
<!-- This Template use AdminLTE css styles -->
<!-- Complete AdminLTE theme repository is in ./web/template/AdminLTE -->

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "Templates.lan", "index", 0);
?>

<!DOCTYPE html>
<html>
<head>
    <link href="web/template/AdminLTE/dist/css/styles.css" rel="stylesheet" type="text/css" />
    <!-- Contiene el head de la pagina -->
    <meta charset="UTF-8">
    <title><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'TITLE');?>
</title>
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
  <?php echo '<script'; ?>
 src="./web/template/AdminLTE/plugins/alertify/alertify.min.js"><?php echo '</script'; ?>
>
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.css" />
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.core.css" />            
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.default.css" />    
      
</head>

<body class="bg-theme03">
    <div class="middle-box animated fadeInDown">
        <h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'WELLCOME');?>
</h1>
        <h3 style="font-weight:bold;">Copyright &copy; <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'TITLE');?>
. <br><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'RIGHT');?>
</h3>
        <div style="color:#F5F5F5;">
           <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'WELLCOMETITLE');?>
 
        </div>

        <div style="color:#F5F5F5;">
           <h2 style="font-weight:bold;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ALLISOK');?>
</h2>
        </div>


         <!-- --> 
            <div class="">
                <p>&nbsp;</p>
                <p>
                    <span class="text-danger">
                        <a href="./index.php?action=CmdDefaultLogin" class="btn btn-primary btn-block btn-flat" ><i class="glyphicon glyphicon-log-in"></i>&nbsp;<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'BACKLOGIN');?>
</a>
                    </span>
                </p>
            </div>
               
    </div>
    
</body>
<!-- Plugin de mensajes emergentes -->
<?php echo smarty_function_messagebox(array('id'=>$_smarty_tpl->tpl_vars['cod_message']->value),$_smarty_tpl);?>

</html>


<?php }
}
