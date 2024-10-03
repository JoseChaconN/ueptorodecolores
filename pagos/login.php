<?php
session_start();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaYa = date( "d-m-Y H:i");
$fechaAcceso = date( "Y-m-d H:i:s");
$fecha_hoy = date( "Y-m-d");
include_once "../inicia.php";
if((isset($_SESSION['usuario']) || isset($_SESSION['password'])) && $_SESSION['cargo']>0  && $_SESSION['impresora']>0)
{   
    header("location:sist/alumnos/buscar-alumno.php");
}
if( $url_actual!='localhost') 
{
  if ($url_actual=="http://".DOMINIO."/pagos" || $url_actual=="http://www.".DOMINIO."/pagos" || $url_actual=="www.".DOMINIO."/pagos" || !isset($_SERVER['HTTPS']) )
  {header("Location:https://".DOMINIO."/pagos");}    
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../includes/PHPMailerMaster/src/Exception.php';
require '../includes/PHPMailerMaster/src/PHPMailer.php';
require '../includes/PHPMailerMaster/src/SMTP.php';
include_once "../conexion.php";
include_once "include/funciones.php";
$link3 = Conectarse3();
$chat_query = mysqli_query($link3,"SELECT enlinea FROM chat_online WHERE id='1' ");  
while($row=mysqli_fetch_array($chat_query))
{
  $_SESSION['activoChat']=$row['enlinea'];

}
$link = Conectarse(); 
if(isset($_POST['cedulaOlvida']))
{
    $cedulaOlvida=$_POST['cedulaOlvida'];
    $n1=$_POST['n1'];
    $n2=$_POST['n2'];
    if($n1>$n2)
    {$tot=$n1-$n2;}else{$tot=$n1+$n2;}
    $resultado=$_POST['resultado'];
    if($tot==$resultado)
    {
        $envia_query = mysqli_query($link,"SELECT cedulaUser, emailUser, claveUser, nombreUser, apellidoUser FROM user WHERE cedulaUser = '$cedulaOlvida' and activoUser='1' ");
        if(mysqli_num_rows($envia_query) > 0)
        {
            while ($row = mysqli_fetch_array($envia_query))
            {
                $cedulaUser=$row['cedulaUser'];
                $emailUser=$row['emailUser'];
                $claveUser=$row['claveUser'];
                $nombreUser=$row['nombreUser'];
                $apellidoUser=$row['apellidoUser'];
            }
            $nombre = htmlspecialchars($nombreUser);
            $apellido = htmlspecialchars($apellidoUser);
            $cedula =htmlspecialchars($cedulaOlvida);
            $email = htmlspecialchars($emailUser);
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp1.s.ipzmarketing.com';
            $mail->SMTPAuth = true;
            $mail->Username = MAILUSER; 
            $mail->Password = CLAVEMAIL; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom(CORREOM,'jesistemas.com.ve');   //de donde envia
            $mail->addAddress($email,$nombre); //Destinatario
            //$mail->addAddress('sisjch.tlf@gmail.com','SOPORTE');
            $mail->isHTML(true);
            $mail->Subject =  'Datos de acceso a FacilPag';
            try {
                $mensaje =  "<div style='float:left'><img src='https://jesistemas.com/pagina/img/logo.png' width='100' height='100'></div>";
                $mensaje .= "<div>Desarrollos Web<BR>";
                $mensaje .=  "<div style='font-size: 20px;'>Jesistemas.com</div>";
                $mensaje .=  "Tlf.: 0412-457.80.84 - Maracay - Aragua<br><BR></div>";
                $mensaje .= "<DIV align='right'>Fecha: ".strftime("%d de %B de %Y")."<br><BR></div>";
                $mensaje .= "Sr(a): ".$apellidoUser."<br>";
                $mensaje .= $nombreUser."<br>";
                $mensaje .= "Cedula: " . $cedulaUser . "<br>Link: https://".DOMINIO."/pagos<br><br>";
                $mensaje .= "Estimado(a) usuario la contraseña solicitada para el ingreso a nuestro sistema web es: ".$claveUser."<br><br>";
                $mensaje .= "Dpto.de Sistemas<br><br>";
                $mail->Body = $mensaje;
                $mail->send();
                $enviado++;
            } catch (Exception $e) 
            {
                echo "Error", $mail->ErrorInfo;
            }
            $enviando++;
            $mail->ClearAddresses(); ?>
            <script type="text/javascript">
                alert('jesistemas.com a enviado un correo con sus datos de acceso, espere unos segundos y revise su correo')
            </script><?php
        }else
        { ?>
            <script type="text/javascript">
                alert('Disculpe cedula no registrada')
            </script><?php
        } 
    }else
    { ?>
        <script type="text/javascript">
            alert('Disculpe resultado incorrecto')
        </script><?php
    } 
}
if(isset($_POST['usuario']) && isset($_POST['clave']))
{
    $usuario=$_POST['usuario'];
    $contrasena=$_POST['clave'];
    $result = mysqli_query($link,"SELECT * FROM user WHERE cedulaUser = '$usuario' and claveUser = '$contrasena' and activoUser='1'and '$fecha_hoy'<=activo_hasta ");  
    $count = 0; 
    while ($row = mysqli_fetch_array($result))
    { 
        $_SESSION['idUser'] = $row['idUser']; 
        $_SESSION['nombreUser'] = $row['nombreUser']; 
        $_SESSION['emailUser'] = $row['emailUser']; 
        $_SESSION['cargo'] = $row['cargoUser'];
        $_SESSION['usuario'] = $row['nombreUser'];
        $_SESSION['password'] = $row['claveUser'];
        $_SESSION['impresora']=$row['impresora'];
        $clave=encriptar($row['claveUser']);
        $idEntra=$row['idUser'];
        $nombreUser = $row['nombreUser']; 
        $emailUser = $row['emailUser']; 
        $cedula = $row['cedulaUser']; 
        $apellido = $row['apellidoUser']; 
        $count=10; 
    }
    if($count > 0) 
    {
        setcookie("usuario",$usuario,time()+(60*60*12) ); 
        setcookie("password",$clave,time()+(60*60*12));  
        $ip_acceso = $_SERVER[REMOTE_ADDR]; 
        if ($idEntra>1) {
            mysqli_query($link,"INSERT INTO accesos (idUser,emailUser,nombreUser,ip_acceso,sistema,fechaAcceso) VALUE ('$idEntra','$emailUser','$nombreUser','$ip_acceso','FacilPag','$fechaAcceso') ") or die ("NO SE CREO ".mysqli_error());    
        }
        
        $periodo_query = mysqli_query($link,"SELECT * FROM periodos WHERE activoPeriodo='1' "); 
        while ($row = mysqli_fetch_array($periodo_query))
        {
            $_SESSION['nomPeriAct'] = $row['nombre_periodo']; 
            $_SESSION['tablaPeriodo'] = $row['tablaPeriodo']; 
            $_SESSION['id_periodo']=$row['id_periodo'];
        }
        //print_r($_COOKIE);
        //echo $_COOKIE['usuario'].'<br>'.$_COOKIE['password'].'<br>'.$_SESSION['nomPeriAct'];
        
        /*$actualiza_query = mysqli_query($link,"SELECT tablaPeriodo FROM periodos WHERE pagos='S' ORDER BY id_periodo ");
        while($row = mysqli_fetch_array($actualiza_query))
        {
            $tablaBusca=$row['tablaPeriodo'];
            //Bachillerato
            $bachi_query = mysqli_query($link,"SELECT idAlumno,grado FROM matri".$tablaBusca." ORDER BY idAlumno ");
            while($row2 = mysqli_fetch_array($bachi_query))
            {
                $idBusca=$row2['idAlumno'];
                $gradoCur=$row2['grado'];
                $pagos_query = mysqli_query($link,"SELECT A.montoDolar,A.statusPago, D.afecta, D.agosto FROM pagos".$tablaBusca." A, conceptos D WHERE A.idAlum = '$idBusca' and A.recibo <> '' and A.id_concepto=D.id ORDER BY A.id ");
                $pagado=0; 
                while ($row3= mysqli_fetch_array($pagos_query))
                {
                    if($row3['statusPago']=='1' and ($row3['afecta']=='S' || $row3['agosto']=='S') )
                    {
                        $pagado=$pagado+$row3['montoDolar'];
                    }
                }
                mysqli_query($link,"UPDATE matri".$tablaBusca." SET pagado='$pagado' WHERE idAlumno='$idBusca' ") or die ("NO ACTUALIZO ".mysqli_error());
            }
            //Primaria
            $prima_query = mysqli_query($link,"SELECT idAlumno FROM notaprimaria".$tablaBusca." ORDER BY idAlumno ");
            while($rowp2 = mysqli_fetch_array($prima_query))
            {
                $idBusca=$rowp2['idAlumno'];
                $pagos_query = mysqli_query($link,"SELECT A.montoDolar,A.statusPago, D.afecta, D.agosto FROM pagos".$tablaBusca." A, conceptos D WHERE A.idAlum = '$idBusca' and A.recibo <> '' and A.id_concepto=D.id ORDER BY A.id ");
                $pagado=0; 
                while ($rowp3= mysqli_fetch_array($pagos_query))
                {
                    if($rowp3['statusPago']=='1' and ($rowp3['afecta']=='S' || $rowp3['agosto']=='S') )
                    {
                        $pagado=$pagado+$rowp3['montoDolar'];
                    }
                }
                mysqli_query($link,"UPDATE notaprimaria".$tablaBusca." SET pagado='$pagado' WHERE idAlumno='$idBusca' ") or die ("NO ACTUALIZO ".mysqli_error());
            }

        }*/
        $margen_query = mysqli_query($link,"SELECT margen_izq, margen_sup, margen_cop FROM preinscripcion WHERE id = '1' ");
        while($row2 = mysqli_fetch_array($margen_query))
        {
            $_SESSION['margen_izq']=$row2['margen_izq'];
            $_SESSION['margen_sup']=$row2['margen_sup'];
            $_SESSION['margen_cop']=$row2['margen_cop'];
        }
        /*ENVIAR CORREO DE VISITA*/
        /*$IP_ADDRESS = $_SERVER[REMOTE_ADDR];
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp1.s.ipzmarketing.com';
        $mail->SMTPAuth = true;
        $mail->Username = MAILUSER; 
        $mail->Password = CLAVEMAIL; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom(CORREOM,'jesistemas.com.ve');   //de donde envia
        //$mail->addAddress($email,$nombre); //Destinatario
        $mail->addAddress('sisjch.tlf@gmail.com','SOPORTE');
        $mail->isHTML(true);
        $mail->Subject =  'Ingreso a FacilPag '.DOMINIO;
        try {
            $mensaje='Ingreso al sistema: <br>Cedula: '.$cedula.'<br>Nombre: '.$nombreUser.' '.$apellido.'<br>Email: '.$emailUser.'<br>Desde la IP: '.$IP_ADDRESS.'<br>dia y hora: '.$fechaYa.'<br>Clave: '.$_SESSION['password'];
            $mail->Body = $mensaje;
            //$mail->send();
            //$enviado++;
        } catch (Exception $e) 
        {
            echo "Error", $mail->ErrorInfo;
        }
        $mail->ClearAddresses(); */
        mysqli_free_result($envia_query);
        mysqli_free_result($result);
        mysqli_free_result($periodo_query);
        mysqli_free_result($actualiza_query);
        mysqli_free_result($bachi_query);
        mysqli_free_result($prima_query);
        mysqli_free_result($pagos_query);
        mysqli_free_result($margen_query);
        header("location:sist/alumnos/buscar-alumno.php");
    } else 
    { ?>
        <script src="js/jquery.min.js"></script>
        <script src="js/sweetalert.min.js"></script>
        <script type="text/javascript">
        jQuery(document).ready(function(){ 
          swal({
            title: "INFORMACION!",
            text: "Disculpe Usuarios o Contraseña incorrecta",
            icon: "info",
            button: "Entendido",
          });
         });
        </script><?php
    }
}?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Factura <?= DOMINIO ?></title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!--link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"-->

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css?6" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo.png?5">
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
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido!<br>FacilFact<br> <?= 'U.E.P. '.EKKS ?> </h1>
                                    </div>
                                    <form class="user" method='POST'>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="usuario" required onkeypress="return ValCed(event)" name="usuario" aria-describedby="emailHelp"
                                                placeholder="Ingrese su Cedula...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="clave" required name="clave" placeholder="Ingrese contraseña">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Ingresar</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="recupera-clave.php">Olvidó su clave?</a>
                                    </div>
                                    <div class="col-md-12" style="text-align: justify; background-color: #FFCDD2 ; color: black;">
                                        
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
    <script type="text/javascript">
        function ValCed(e)
        {
          tecla = (document.all) ? e.keyCode : e.which;
          if (tecla==8)
          {
            return true;
          }
          patron =/[0-9]/;
          tecla_final = String.fromCharCode(tecla);
          return patron.test(tecla_final);
        }
    </script>
</body>
</html>