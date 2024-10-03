<?php
session_start();
$url_actual =  $_SERVER["SERVER_NAME"];
if(isset($_SESSION["usuario"]) && $url_actual!='localhost') 
{
  if ($url_actual=="http://www.jesistemas.com/pagos" || $url_actual=="www.jesistemas.com/pagos" || !isset($_SERVER['HTTPS']) )
  {header("Location:https://jesistemas.com/pagos");}    
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$diaHoy = date("d");
$mesHoy = date("s");
$otr=$diaHoy+$mesHoy;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>JE Sistemas</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo.png?2">

</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Recuperar su clave</h1>
                                        <p class="mb-4">Ingrese su numero de cedula  y la enviaremos a su correo</p>
                                    </div>
                                    <form role="form" method="POST" enctype="multipart/form-data" action="login.php" >
                                        <div class="form-group">
                                            <input type="text" required class="form-control form-control-user" name="cedulaOlvida" 
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Ingrese cedula de usuario...">
                                        </div>
                                        <div class="form-group" style="margin-top: 2%; margin-bottom: 2%;">
                                            <h3 id="suma" class="text-center"></h3>
                                            <input type="text" placeholder="Resultado" onkeyup="activa()" id="resultado" name="resultado" class="form-control">  
                                        </div> 
                                        <input type="hidden" id="n1" name="n1" >
                                        <input type="hidden" id="n2" name="n2" >
                                        <button class="btn btn-primary btn-user btn-block" type="submit" id="btnEnvia" disabled>Enviar Contraseña</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                    	<a class="small" href="login.php">Ya recorde mi clave!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
<script type="text/javascript">
    $(document).ready(function () 
    {
        calcula()
    });
    function activa() 
    {
        res=parseFloat($('#resultado').val())
        n1=parseFloat($('#n1').val())
        n2=parseFloat($('#n2').val())
        if(n1>n2)
        {tot=n1-n2}else{tot=n1+n2}
        if(res==tot)
        { 
          document.getElementById("btnEnvia").disabled = false;
        }else
        {
          document.getElementById("btnEnvia").disabled = true;
        }
    }
    function calcula() 
    {
        hoy=new Date();
        n1=hoy.getMinutes();
        n2=hoy.getSeconds();
        if(n1>n2)
        {calcu=n1+' - '+n2+' = ?';}else{calcu=n1+' + '+n2+' = ?';}
        
        document.getElementById("suma").innerHTML = calcu;
        $('#n1').val(n1)
        $('#n2').val(n2)
    }
</script>

</html>