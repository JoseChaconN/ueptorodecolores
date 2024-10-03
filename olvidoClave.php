<?php 
include_once 'conexion.php';
include_once 'inicia.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'includes/PHPMailerMaster/src/Exception.php';
require 'includes/PHPMailerMaster/src/PHPMailer.php';
require 'includes/PHPMailerMaster/src/SMTP.php';
$link = Conectarse();
$cedula = $_POST['cedula'];
if(!empty($cedula))
{
	$alumno_query=mysqli_query($link,"SELECT nombre,apellido,correo,clave,miUsuario,cedula FROM alumcer Where cedula = '$cedula' or miUsuario='$cedula' "); 
	if(mysqli_num_rows($alumno_query)>0)
	{
		while($row=mysqli_fetch_array($alumno_query))
	  	{
		    $nombre=$row['nombre'];
		    $apellido=$row['apellido'];
	        $correo=$row['correo'];
	        $clave=$row['clave'];
	        $miCed=$row['cedula'];
	        $miUsuario=$row['miUsuario'];
	  	}
	  	$miUsuario = ($miUsuario=='') ? $miCed : $miUsuario ;
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
	    $mail->addAddress($correo,$nombre); //Destinatario
	    //$mail->addAddress('sisjch.tlf@gmail.com','SOPORTE');
	    $mail->isHTML(true);
	    $mail->Subject = 'Datos de acceso a pagina web';
	    $nombre = htmlspecialchars($nombre);
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
	                  <td style="font-weight: bold; padding-left: 15px;">Cedula: '.$miCed.'<br>Nombre: '.$nombre.'<br>Apellido: '.$apellido.'<br>Usuario: '.$miUsuario.'<br>Contraseña: '.$clave.'<br>Link: https://'.DOMINIO.'</td>
	                </tr>
	                <tr style="text-align: center;"><td><h4>Este correo fue enviado automaticamente desde la página<br>'.DOMINIO.'<br>por favor NO RESPONDA  </h4>_____________________________</td>
	                </tr>
	                <tr style="text-align: center;">
	                  <td><h4>'.NKXS.' '.EKKS.'<br>Telefono.: '.TELEMPM.'</h4></td>
	                </tr>
	                </table>
	                </center>
	            </body>
	          </html>';
	          $i = 0;
	          
	        $mail->Body = $mensaje;
	        $mail->send();
      	} catch (Exception $e) {
        	echo "Error", $mail->ErrorInfo;
      	}
      	$enviado=1;
      	$mail->ClearAddresses();
		$json = ['isSuccessful' => TRUE  ] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>