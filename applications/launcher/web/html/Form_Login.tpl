{config_load file="Templates.lan" section="login"}
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="web/template/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="web/template/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="web/template/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="web/template/AdminLTE/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="web/template/AdminLTE/plugins/iCheck/square/blue.css">

  <!-- Alertify -->  
  <script src="./web/template/AdminLTE/plugins/alertify/alertify.min.js"></script>
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.css" />
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.core.css" />      
  <link rel="stylesheet" href="./web/template/AdminLTE/plugins/alertify/alertify.default.css" />  

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="web/template/AdminLTE/index2.html">{#TITLE#}</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">{#TITLE2#}</p>

    <form action="index.php" method="post">
      <input type="hidden" name="action" value="CmdLogin">
      <div class="form-group has-feedback">
        <input type="email" name="email" value="" class="form-control" placeholder="{#USERNAME#}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" name="password" value="12345" class="form-control" placeholder="{#PASSWORD#}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">

          <!-- /.col -->
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">{#BTNSINGIN#}</button>
          </div>
          <!-- /.col -->
      </div>
    </form>

    <a href="#">{#REMEMBERPASS#}</a><br>
    <a href="register.html" class="text-center">{#NEWUSER#}</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="web/template/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="web/template/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="web/template/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
<!-- Plugin de mensajes emergentes -->
{messagebox id=$cod_message}
</body>
</html>