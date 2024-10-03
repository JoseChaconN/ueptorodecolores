<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../includes/PHPMailerMaster/src/Exception.php';
require '../includes/PHPMailerMaster/src/PHPMailer.php';
require '../includes/PHPMailerMaster/src/SMTP.php';
if(isset($_SESSION['usuario']) && $_SESSION['cargo']>2)
{
    include("../inicia.php");
    include("../conexion.php");
    include_once "header.php";
    $link = conectarse();
    $docente=$_SESSION["usuario"];
    $nomDocente=$_SESSION['nomuser'].' '.$_SESSION['apelluser'];
    $mimensaje=$_POST['mensaje'];  
    $asunto=$_POST['asunto'];
    $archivo = $_FILES['archivo'];
    $nombre_archivos = $archivo['name'];
    $ruta_archivos = $archivo['tmp_name'];
    $idAlumno=desencriptar($_POST['idAlumn_mail']);
    $destino_query=mysqli_query($link,"SELECT A.cedula,A.nombre,A.apellido, B.correo, B.representante, C.nombreGrado, D.nombre as nomSec FROM alumcer A, represe B, grado".$tablaPeriodo." C, secciones D WHERE A.idAlum='$idAlumno' and A.ced_rep=B.cedula and A.grado=C.grado and A.seccion=D.id ");
    while($row = mysqli_fetch_array($destino_query))
    {
      $cedula=$row['cedula'];
      $correo=$row['correo'];
      $alumno=$row['nombre'].' '.$row['apellido'];
      $representante=$row['representante'];
      $nombreGrado=$row['nombreGrado'];
      $nomSec=$row['nomSec'];
    }
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
    $mail->Subject = $asunto;
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
              Estudiante:<br>'.$alumno.'<br>Cursante del: '.$nombreGrado.' Sección: '.$nomSec.'<br><br></td>
            </tr>
            <tr style="text-align: center;">
              <td><h2>'.$asunto.'</h2></td>
            </tr>             
            <tr style="text-align: justify;"><td style="padding: 10px;"><h4>'.$mimensaje.'</h4><br><br></td>
            </tr>
            <tr style="text-align: center;">
              <td><h4>Atentamente</h4></td>
            </tr>
            <tr style="text-align: center;">
              <td><h4>Docente '.$nomDocente.'<br><br></h4></td>
            </tr>
            <tr style="text-align: center;"><td><h4>Por favor responder a la cuenta <br><h3>'.$_SESSION['correo'].'</h3>  </h4>_____________________________</td>
            </tr>
            <tr style="text-align: center;">
              <td><h4>'.NKXS.' '.EKKS.'<br>Teléfono.: '.TELEMPM.'</h4></td>
            </tr>
            </table>
            </center>
        </body>
      </html>';
      $i = 0;
      if($archivo["size"]>0)
      {
        $mail->addAttachment($archivo["tmp_name"],$archivo["name"]);
      }
      $mail->Body = $mensaje;
      //$mail->send();
      //echo "Mensaje Enviado";
    } catch (Exception $e) {
      echo "Error", $mail->ErrorInfo;
    }
    $enviado=1;
    $mail->ClearAddresses();
    ?>
    <link rel="shortcut icon" href="../img/logo.png?3">
    <title>Pagina <?= $_SESSION['nombreUser'].' '.$_SESSION['ekks'] ?></title>
    <div class="container-fluid" style="margin-top: 7%;">
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