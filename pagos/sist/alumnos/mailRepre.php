<?php 
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../includes/PHPMailerMaster/src/Exception.php';
require '../../../includes/PHPMailerMaster/src/PHPMailer.php';
require '../../../includes/PHPMailerMaster/src/SMTP.php';
if(isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
    $asunto=$_POST['asunto'];
    $mimensaje=$_POST['mensaje'];
    $alumno=$_POST['alumn_mail'];
    $representante=$_POST['repre_mail'];
    $correo=$_POST['corre_mail'];
    $grado=$_POST['grado_mail'];
    $seccion=$_POST['secci_mail'];
    $archivo = $_FILES['archivo'];
    
    setlocale(LC_TIME, "spanish");
    date_default_timezone_set("America/Caracas");
    include_once("../../../inicia.php");
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp1.s.ipzmarketing.com';
    $mail->SMTPAuth = true;
    $mail->Username = MAILUSER; 
    $mail->Password = CLAVEMAIL; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom(CORREOM,NKXS.' '.EKKS);
    $mail->addAddress($correo,$representante); //Destinatario
    //$mail->addAddress('sisjch.tlf@gmail.com','SOPORTE');
    $mail->isHTML(true);
    $mail->Subject = utf8_decode($asunto);

    $ekks=utf8_decode(EKKS);
    try {
        $mensaje='
        <html>
            <body>
              <center>
              <table style="width: 40%; background-color: #E0E0E0;">
                <tr style="text-align: center;">
                  <th style="background-color: #283593; "><img src="https://'.DOMINIO.'/imagenes/logo.png" style="width: 30%; height: auto; text-align: center;">
                  </th>
                </tr> 
                <tr>
                  <td style="padding-left: 15px;">Estimado(a) representante<br> '.$representante.'<br>
                  Estudiante:<br>'.$alumno.'<br>Cursante del: '.$grado.' Sección: '.$seccion.'</td>
                </tr>
                <tr style="text-align: center;">
                  <td><h2>'.$asunto.'</h2></td>
                </tr>             
                <tr style="text-align: justify;"><td style="padding: 10px;"><h4>'.$mimensaje.'</h4></td>
                </tr>
                <tr style="text-align: center;">
                  <td><h4>Atentamente</h4></td>
                </tr>
                <tr style="text-align: center;">
                  <td><h4>Dpto.de Administración</h4></td>
                </tr>
                <tr style="text-align: center;"><td><h4>Por favor responder a la cuenta institucional <br><h3>'.SUCORREO.'</h3>  </h4>_____________________________</td>
                </tr>
                <tr style="text-align: center;">
                  <td><h4>'.NKXS.' '.EKKS.'<br>Teléfono.: '.TELEMPM.'</h4></td>
                </tr>
                </table>
                </center>
            </body>
        </html>';
        $mail->Body = $mensaje;
        if($archivo["size"]>0)
        {
          $mail->addAttachment($archivo["tmp_name"],$archivo["name"]);
        }
        $mail->send();
        
    } catch (Exception $e) 
    {
        echo "Error", $mail->ErrorInfo;
    }
    $mail->ClearAddresses(); ?>
    <link rel="shortcut icon" href="../../../imagenes/logo.png?3">
    <title>Pagos <?= $_SESSION['nombreUser'] ?></title>
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 text-center">
                <img src="../img/enviado.jpg" style="width:100%; ">
                <p>Correo Enviado exitosamente!</p>
                <center><button style="color:black;" onclick="javascript:window.close();opener.window.focus();" class="btn btn-warning">Cerrar Ventana</button></center><br>
            </div>
            <div class="col-md-6" style="border-style: solid;">
                <?php echo $mensaje; ?>
            </div>
        </div>
    </div>
    <?php
}