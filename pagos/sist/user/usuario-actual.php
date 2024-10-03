<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../includes/PHPMailerMaster/src/Exception.php';
require '../../../includes/PHPMailerMaster/src/PHPMailer.php';
require '../../../includes/PHPMailerMaster/src/SMTP.php';
include_once ('../../../conexion.php');
include_once ('../../include/funciones.php');
include_once ('../../../inicia.php');
$link = Conectarse();
$nacionUser=$_POST['nacionUser'];
$cedulaUser=$_POST['cedulaUser'];
$claveUser=$_POST['claveUser'];
$fechaNacUser=$_POST['fechaNacUser'];
$cargoUser=$_POST['cargoUser'];
$nombreUser=$_POST['nombreUser'];
$apellidoUser=$_POST['apellidoUser'];
$telefonoUser=$_POST['telefonoUser'];
$emailUser=$_POST['emailUser'];
$direccionUser=$_POST['direccionUser'];
$idUser=desencriptar($_POST['idUser']);
$activo_hasta=$_POST['activo_hasta'];
$impresora=$_POST['impresora'];
$enviarInfo = (isset($_POST['enviarInfo'])) ? $_POST['enviarInfo'] : '' ;
mysqli_query($link,"UPDATE user SET nacionUser='$nacionUser', cedulaUser='$cedulaUser', claveUser='$claveUser', fechaNacUser='$fechaNacUser', cargoUser='$cargoUser', nombreUser='$nombreUser', apellidoUser='$apellidoUser', telefonoUser='$telefonoUser', emailUser='$emailUser', direccionUser='$direccionUser',impresora='$impresora' WHERE idUser = '$idUser' ");
if ($_SESSION['idUser']==1) {
	mysqli_query($link,"UPDATE user SET activo_hasta='$activo_hasta' WHERE idUser = '$idUser' ");
}
if(!empty($emailUser) && $enviarInfo=='1')
{
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
    $mail->addAddress($emailUser,$nombreUser); //Destinatario
    //$mail->addAddress('sisjch.tlf@gmail.com','SOPORTE');
    $mail->isHTML(true);
    $mail->Subject = 'Acceso a FacilFact '.NKXS.' '.EKKS.' de '.$nombreUser;

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
                <tr style="text-align: center;">
                  <td><h2>Datos de acceso a pagina web</h2></td>
                </tr>             
                <tr>
                  <td style="font-weight: bold; padding-left: 15px;">Estimado(a) '.$nombreUser.'<br><br>Reciba un cordial saludo de parte de todo el equipo que labora en la '.NKXS.' '.EKKS.' <br><br>
                  A continuación se detallan los datos para poder ingresar al modulo de administración FacilFact, Bienvenido... <br>Link: https://'.DOMINIO.'/pagos<br>Usuario: '.$cedulaUser.'<br>Contraseña: '.$claveUser.'</td>
                </tr>
                
                <tr style="text-align: center;"><td><h4>Este correo fue enviado automáticamente desde la pagina<br>https://'.DOMINIO.'<br><h2>por favor NO responder</h2>  </h4>_____________________________</td>
                </tr>
                <tr style="text-align: center;">
		            <td><h3>Telefono de Contacto (0412) 457.80.84 Maracay <br> https://jesistemas.com - Jose Chacon</td>
		        </tr>
                </table>
                </center>
            </body>
        </html>';
		$mail->Body = $mensaje;
		$mail->send();
		//echo "Mensaje Enviado";
	} catch (Exception $e) {
		echo "Error", $mail->ErrorInfo;
	}
	$mail->ClearAddresses();
}
echo 'ok';
?>