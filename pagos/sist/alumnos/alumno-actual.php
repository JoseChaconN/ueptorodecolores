<?php
session_start();
#error_reporting(E_ALL);
#ini_set('display_errors', '1');

if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once("../../include/funciones.php");
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../includes/PHPMailerMaster/src/Exception.php';
require '../../../includes/PHPMailerMaster/src/PHPMailer.php';
require '../../../includes/PHPMailerMaster/src/SMTP.php';
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$link = Conectarse();
$idAlum=desencriptar($_POST['idAlum']);
$periodo=$_POST["nomPeriodo"];
$periodo_query = mysqli_query($link,"SELECT nombre_periodo FROM periodos WHERE activoPeriodo='1' ");
$row2=mysqli_fetch_array($periodo_query);
$periActivo=$row2['nombre_periodo'];
$tablaPeriodo=$_POST["tablaPeriodo"];
$editable = (isset($_POST['editable'])) ? $_POST['editable'] : '' ;
$gra_alu=$_POST["gra_alu"];
$sec_alu=$_POST["sec_alu"];
$nac_alu=$_POST["nacion"];
$ced_alu=$_POST["cedula"];
$miUsuario=$_POST["miUsuario"];
$cla_alu=$_POST["clave"];
$fna_alu=$_POST["fna_alu"];
$ape_alu=sanear_string($_POST['apellido']);
$ape_alu=strtoupper($ape_alu);
$nom_alu=sanear_string($_POST['nombre']);
$nom_alu=strtoupper($nom_alu);
$edo_alu=$_POST["edo_alu"];
$loc_alu=$_POST["loc_alu"];
$muni_alu=$_POST["muni_alu"];
$pai_alu=strtoupper($_POST["pai_alu"]);
$sex_alu=$_POST["sex_alu"];
$tlf_alu=$_POST["tlf_alu"];
$mai_alu=$_POST["mai_alu"];
$dir_alu=$_POST["dir_alu"];
$pagado=$_POST['pagado'];
$retiraPagos=$_POST['retiraPagos'];
$enviar = (isset($_POST["enviar"])) ? $_POST["enviar"] : '' ;
$montos_query = mysqli_query($link,"SELECT monto FROM montos".$tablaPeriodo." WHERE id_grado ='$gra_alu' ");
$totalPeriodo=0;
while ($row = mysqli_fetch_array($montos_query))
{
    $totalPeriodo=$totalPeriodo+$row['monto'];
}
if(!empty($_FILES['foto_alu']["name"]))
{
	$foto_alu=addslashes(file_get_contents($_FILES['foto_alu']['tmp_name']));
	$nombrearchivo = $_FILES["foto_alu"]["name"];
	$nombreruta = $_FILES["foto_alu"]["tmp_name"];
	$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
	$formatos = array('.jpg','.jpeg','.png' );
	$ruta = "$ced_alu$ext";
	$guardaRutaAlu='../../../fotoalu/'.$ruta;
}
$meses=$_POST['meses'];
for ($i=1; $i <=$meses ; $i++) { 
	${'desc'.$i}=$_POST['desc'.$i];
	$totalPeriodo=$totalPeriodo-$_POST['desc'.$i];
}
////////////////////DATOS DEL REPRESENTANTE/////////////////////
$ced_rep_1=$_POST["ced_rep_vie"];
$ced_rep=$_POST["ced_rep"];
$nom_rep=$_POST["nom_rep"];
$par_rep = $_POST["par_rep"];	
$mai_rep=$_POST["mai_rep"];
$dir_rep=$_POST["dir_rep"];
$tlf_hab_rep=$_POST["tlf_hab_rep"];
$tlf_cel_rep=$_POST["tlf_cel_rep"];
if(!empty($_FILES['foto_rep']["name"])) 
{
	$foto_rep = addslashes(file_get_contents($_FILES['foto_rep']['tmp_name']));	
	$nombrearchivo1 = $_FILES["foto_rep"]["name"];
	$nombreruta1 = $_FILES["foto_rep"]["tmp_name"];
	$ext1 = substr($nombrearchivo1, strrpos($nombrearchivo1, '.'));
	$formatos1 = array('.jpg','.jpeg','.png' );
	$ruta1 = "$ced_rep$ext1";
	$guardaRutaRep='../../../fotorep/'.$ruta1;
}
////////QUIEN PAGA ////////
$id_quienPaga=$_POST['id_quienPaga'];

if($id_quienPaga<1)
{
	$ced_reci=$_POST['ced_rep'];
	$nom_reci=$_POST['nom_rep'];
	$dir_reci=$_POST['dir_rep'];
}else
{
	$ced_reci=$_POST['ced_reci'];
	$nom_reci=$_POST['nom_reci'];
	$dir_reci=$_POST['dir_reci'];
}

$grado_query = mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." WHERE grado='$gra_alu'");
while($row=mysqli_fetch_array($grado_query))
{ $nombreGrado=$row['nombreGrado']; }

if(!empty($_FILES['foto_alu']["name"]))
{
	mysqli_query($link,"UPDATE alumcer SET ruta='$ruta' WHERE idAlum = '$idAlum'");
	move_uploaded_file($nombreruta, $guardaRutaAlu);
}
mysqli_query($link,"UPDATE alumcer SET nacion='$nac_alu', miUsuario='$miUsuario', apellido='$ape_alu', nombre='$nom_alu', sexo='$sex_alu', FechaNac='$fna_alu', locali='$loc_alu', estado='$edo_alu', pais='$pai_alu', direccion='$dir_alu', telefono='$tlf_alu', correo='$mai_alu', clave='$cla_alu', parentesco='$par_rep', editable='$editable', municip='$muni_alu',id_quienPaga='$id_quienPaga' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO SSSS ".mysqli_error($link));
	if($periodo==$periActivo)
	{
		mysqli_query($link,"UPDATE alumcer SET grado='$gra_alu', seccion='$sec_alu', Periodo='$periodo' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO2".mysqli_error($link));
	}
if($id_quienPaga>0)
{
	mysqli_query($link,"UPDATE emite_pago SET ced_reci='$ced_reci', nom_reci='$nom_reci', dir_reci='$dir_reci' WHERE id = '$id_quienPaga'") or die ("NO ACTUALIZO QUIEN PAGA".mysqli_error($link));
}else
{
	mysqli_query($link,"INSERT INTO emite_pago (ced_reci,nom_reci,dir_reci) VALUES ('$ced_reci', '$nom_reci', '$dir_reci')") or die ("NO GUARDO ".mysqli_error($link));
	$nuevo_quienPaga_query = mysqli_query($link,"SELECT LAST_INSERT_ID(id) as nuevoQuien FROM emite_pago order by id desc limit 0,1  ");
	$row=mysqli_fetch_array($nuevo_quienPaga_query);
	$id_quienPaga=$row['nuevoQuien'];
	mysqli_query($link,"UPDATE alumcer SET id_quienPaga='$id_quienPaga' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO SSSS ".mysqli_error($link));
}

if ($ced_rep <> $ced_rep_1)
{
	$repnuevo=mysqli_query($link,"SELECT cedula FROM represe WHERE cedula='$ced_rep'");
	if(mysqli_num_rows($repnuevo) == 0 )
	{
		mysqli_query($link,"INSERT INTO represe (cedula,representante,correo,direccion,telefono,tlf_celu) VALUES ('$ced_rep', '$nom_rep', '$mai_rep', '$dir_rep', '$tlf_hab_rep', '$tlf_cel_rep')") or die ("NO GUARDO REPRESENTANTE".mysqli_error($link));
		if(!empty($_FILES['foto_rep']["name"]))
		{
			mysqli_query($link,"UPDATE represe SET  ruta='$ruta1' WHERE cedula = '$ced_rep'");
			move_uploaded_file($nombreruta1, $guardaRutaRep);
		}
	}
	else
	{
		mysqli_query($link,"UPDATE represe SET representante='$nom_rep', correo='$mai_rep', direccion='$dir_rep', telefono='$tlf_hab_rep', tlf_celu='$tlf_cel_rep' WHERE cedula = '$ced_rep'") or die ("NO ACTUALIZO EL REPRESENTANTE".mysqli_error($link));
		if (!empty($_FILES['foto_rep']["name"]))
		{
			move_uploaded_file($nombreruta1, $guardaRutaRep);
			mysqli_query($link,"UPDATE represe SET  ruta='$ruta1' WHERE cedula = '$ced_rep'");
		}
	}
	mysqli_query($link,"UPDATE alumcer SET ced_rep='$ced_rep' WHERE idAlum = '$idAlum'");
}
else
{
	mysqli_query($link,"UPDATE represe SET representante='$nom_rep', correo='$mai_rep', direccion='$dir_rep', telefono='$tlf_hab_rep', tlf_celu='$tlf_cel_rep' WHERE cedula = '$ced_rep'") or die ("NO ACTUALIZO EL REPRESENTANTE".mysqli_error($link));
	if (!empty($_FILES['foto_rep']["name"]))
	{
		mysqli_query($link,"UPDATE represe SET ruta='$ruta1' WHERE cedula = '$ced_rep'");
		move_uploaded_file($nombreruta1, $guardaRutaRep);
	}											
}
if ($gra_alu>60) {
	$matri_query = mysqli_query($link,"SELECT idMatri FROM matri".$tablaPeriodo." WHERE idAlumno='$idAlum'");
	if(mysqli_num_rows($matri_query) > 0)
	{
		$row2=mysqli_fetch_array($matri_query);
		$id=$row2['idMatri'];
		mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET grado='$gra_alu', idSeccion='$sec_alu', actualizado='$hoy', desc1='$desc1', desc2='$desc2', desc3='$desc3', desc4='$desc4', desc5='$desc5', desc6='$desc6', desc7='$desc7', desc8='$desc8', desc9='$desc9', desc10='$desc10', desc11='$desc11', desc12='$desc12', desc13='$desc13',totalPeriodo='$totalPeriodo', pagado='$pagado',retiraPagos='$retiraPagos' WHERE idMatri='$id'");
	}else
	{
		mysqli_query($link,"INSERT INTO matri".$tablaPeriodo." (ced_alu,idAlumno,grado,idSeccion,creado,statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado ) VALUE ('$ced_alu','$idAlum','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado' ) ") or die ("NO SE CREO ".mysqli_error());
	}
}else
{
	$matri_query = mysqli_query($link,"SELECT id_notas FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlum'");
	if(mysqli_num_rows($matri_query) > 0)
	{
		$row2=mysqli_fetch_array($matri_query);
		$id=$row2['id_notas'];
		mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET grado='$gra_alu', idSeccion='$sec_alu', actualizado='$hoy', desc1='$desc1', desc2='$desc2', desc3='$desc3', desc4='$desc4', desc5='$desc5', desc6='$desc6', desc7='$desc7', desc8='$desc8', desc9='$desc9', desc10='$desc10', desc11='$desc11', desc12='$desc12', desc13='$desc13',totalPeriodo='$totalPeriodo', pagado='$pagado',retiraPagos='$retiraPagos' WHERE id_notas='$id'");
	}else
	{
		mysqli_query($link,"INSERT INTO notaprimaria".$tablaPeriodo." (ced_alu,idAlumno,grado,idSeccion,creado,statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado ) VALUE ('$ced_alu','$idAlum','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado' ) ") or die ("NO SE CREO ".mysqli_error());
	}
}

if($enviar=='1' && !empty($mai_alu))
{
	include("../../../inicia.php");
	$usuario = ($miUsuario=='') ? $ced_alu : $miUsuario ;
	$ekks=EKKS;
	$mail = new PHPMailer(true);
  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = 'smtp1.s.ipzmarketing.com';
  $mail->SMTPAuth = true;
  $mail->Username = MAILUSER; 
  $mail->Password = CLAVEMAIL; 
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;
  $mail->setFrom(CORREOM,NKXS.' '.utf8_decode(EKKS));
  $mail->addAddress($mai_alu,$nom_alu); //Destinatario
  //$mail->addAddress('sisjch.tlf@gmail.com','SOPORTE');
  $mail->isHTML(true);
  $mail->Subject = 'Acceso a Pagina Web '.NKXS.' '.utf8_decode(EKKS).' de '.$ape_alu.' '.$nom_alu;
	try {
  	$mensaje='
		<html>
      <body>
        <center>
        <table style="width: 40%; background-color: #E0E0E0;">
          <tr style="text-align: center;">
            <th style="background-color: #283593; "><img src="https://'.DOMINIO.'/imagenes/logo.png?1" style="width: 30%; height: auto; text-align: center;">
            </th>
          </tr> 
          <tr style="text-align: center;">
            <td><h2>Datos de acceso a pagina web</h2></td>
          </tr>             
          <tr>
            <td style="font-weight: bold; padding-left: 15px;">Estimado(a) '.$nom_rep.'<br><br>Reciba un cordial saludo de parte de todo el equipo que labora en la '.NKXS.' '.EKKS.' <br><br>
            Estudiante: '.$ape_alu.' '.$nom_alu.'<br>Cedula: '.$ced_alu.'<br>Cursante del: '.$nombreGrado.'<br>Periodo Escolar: '.$periodo.'<br>Link: https://'.DOMINIO.'<br>Usuario: '.$usuario.'<br>Contraseña: '.$cla_alu.'</td>
          </tr>
          <tr style="text-align: justify;"><td style="padding: 10px;"><h4>Lo invitamos antes de iniciar sesión en nuestra página web, a leer el manual de usuario https://'.DOMINIO.'/manual.php para que pueda darle el mejor uso a las funciones que ofrece nuestra página web</h4></td>
          </tr>
          <tr style="text-align: center;"><td><h4>Este correo fue enviado automáticamente desde la pagina<br>https://'.DOMINIO.'<br><h2>por favor NO responder</h2>  </h4>_____________________________</td>
          </tr>
          <tr style="text-align: center;">
            <td><h4>'.NKXS.' '.EKKS.'<br>Teléfono.: '.TELEMPM.'</h4></td>
          </tr>
          </table>
          </center>
      </body>
    </html>';
    $mail->Body = $mensaje;
    $mail->send();
	} catch (Exception $e) 
	{
		echo "Error", $mail->ErrorInfo;
	}
    $mail->ClearAddresses();
}
//////////////////////////////
/*$quien_query = mysqli_query($link,"SELECT A.idAlum,B.cedula,B.representante,B.direccion FROM alumcer A, represe B WHERE A.cargo is NULL and A.ced_rep=B.cedula ");
while ($row = mysqli_fetch_array($quien_query))
{
	$idAlum2=$row['idAlum'];
	$cedula=$row['cedula'];
	$representante=$row['representante'];
	$direccion=$row['direccion'];

	mysqli_query($link,"INSERT INTO emite_pago (ced_reci,nom_reci,dir_reci ) VALUES ('$cedula', '$representante', '$direccion')") or die ("NO GUARDO QUIEN PAGA".mysqli_error($link));

	$nuevo_quienPaga_query = mysqli_query($link,"SELECT LAST_INSERT_ID(id) as nuevoQuien FROM emite_pago order by id desc limit 0,1  ");
	$row=mysqli_fetch_array($nuevo_quienPaga_query);
	$id_quienPaga=$row['nuevoQuien'];
	mysqli_query($link,"UPDATE alumcer SET id_quienPaga='$id_quienPaga' WHERE idAlum='$idAlum2'");
}*/
/////////////////////////////
$idAlum=encriptar($idAlum);
if($gra_alu<61)
{
	echo "<script type='text/javascript'>  
		opener.document.location.reload();                              
      	window.location='perfil-pri-alumno.php?id=$idAlum&guar=1&peri=$tablaPeriodo&gra=$gra_alu&sec=$sec_alu&nomP=$periodo';
  		</script>";
}else
{
	echo "<script type='text/javascript'>  
		opener.document.location.reload();                              
      	window.location='perfil-alumno.php?id=$idAlum&guar=1&peri=$tablaPeriodo&gra=$gra_alu&sec=$sec_alu&nomP=$periodo';
  		</script>";
}

?>