<?php
session_start();
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
$link = Conectarse();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$periodo=$_POST["nomPeriodo"];
$periodo_query = mysqli_query($link,"SELECT nombre_periodo FROM periodos WHERE activoPeriodo='1' ");
$row2=mysqli_fetch_array($periodo_query);
$periActivo=$row2['nombre_periodo'];
$tablaPeriodo=$_POST["tablaPeriodo"];
$editable = (isset($_POST["editable"])) ? $_POST["editable"] : '' ;
$gra_alu=$_POST["gra_alu"];
$sec_alu=$_POST["sec_alu"];
$enviar = (isset($_POST["enviar"])) ? $_POST["enviar"] : '' ;
$pagado=$_POST['pagado'];
$debe=$_POST['debe'];
$montos_query = mysqli_query($link,"SELECT monto FROM montos".$tablaPeriodo." WHERE id_grado ='$gra_alu' "); 
$totalPeriodo=0;
while ($row = mysqli_fetch_array($montos_query))
{
    $totalPeriodo=$totalPeriodo+$row['monto'];
}
$nacion=$_POST["nacion"];
$cedula=$_POST["cedula"];
$miUsuario=$_POST["miUsuario"];
$clave=$_POST["clave"];
$fechaNac=$_POST["fna_alu"];
$ape_alu=sanear_string($_POST['apellido']);
$apellido=strtoupper($ape_alu);
$nom_alu=sanear_string($_POST['nombre']);
$nombre=strtoupper($nom_alu);
$estado=$_POST["edo_alu"];
$locali=$_POST["loc_alu"];
$municip=$_POST["muni_alu"];
$pais=strtoupper($_POST["pai_alu"]);
$sexo=$_POST["sex_alu"];
$telefono=$_POST["tlf_alu"];
$correo=$_POST["mai_alu"];
$direccion=$_POST["dir_alu"];
////////////////////DATOS DEL REPRESENTANTE/////////////////////
$ced_rep=$_POST["ced_rep"];
$nom_rep=$_POST["nom_rep"];
$par_rep = (isset($_POST["par_rep"])) ? $_POST["par_rep"] : '1' ;
$mai_rep=$_POST["mai_rep"];
$dir_rep=$_POST["dir_rep"];
$tlf_hab_rep=$_POST["tlf_hab_rep"];
$tlf_cel_rep=$_POST["tlf_cel_rep"];
if(!empty($_FILES['foto_alu']["name"]))
{
	$foto_alu=addslashes(file_get_contents($_FILES['foto_alu']['tmp_name']));
	$nombrearchivo = $_FILES["foto_alu"]["name"];
	$nombreruta = $_FILES["foto_alu"]["tmp_name"];
	$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
	$formatos = array('.jpg','.jpeg','.png' );
	$ruta = "$cedula$ext";
	$guardaRutaAlu='../../../fotoalu/'.$ruta;
}
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
$ced_reci=$_POST['ced_reci'];
$nom_reci=$_POST['nom_reci'];
$dir_reci=$_POST['dir_reci'];	
if($debe<=0)
{
	$existe_quienPaga_query=mysqli_query($link,"SELECT id FROM emite_pago where ced_reci='$ced_reci' "); 
	if(mysqli_num_rows($existe_quienPaga_query) == 0)
	{
		mysqli_query($link,"INSERT INTO emite_pago (ced_reci,nom_reci,dir_reci ) VALUES ('$ced_reci', '$nom_reci', '$dir_reci')") or die ("NO GUARDO QUIEN PAGA".mysqli_error($link));

		$nuevo_quienPaga_query = mysqli_query($link,"SELECT LAST_INSERT_ID(id) as nuevoQuien FROM emite_pago order by id desc limit 0,1  ");
		$row=mysqli_fetch_array($nuevo_quienPaga_query);
		$id_quienPaga=$row['nuevoQuien'];
	}else
	{
		$row2=mysqli_fetch_array($existe_quienPaga_query);
		$id_quienPaga=$row2['id'];
	}
	$meses=$_POST['meses'];
	for ($i=1; $i <=$meses ; $i++) { 
		${'desc'.$i}=$_POST['desc'.$i];
		$totalPeriodo=$totalPeriodo-$_POST['desc'.$i];
	}
	// El alumno es nuevo ingreso
	$existe_query=mysqli_query($link,"SELECT idAlum FROM alumcer where cedula='$cedula' "); 
	if(mysqli_num_rows($existe_query) == 0)
	{
		$grado_query = mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." WHERE grado='$gra_alu'");
		while($row=mysqli_fetch_array($grado_query))
		{ $nombreGrado=$row['nombreGrado']; }
		mysqli_query($link,"INSERT INTO alumcer (nacion, cedula, miUsuario, apellido, nombre, sexo, FechaNac, locali, estado, municip, pais, direccion, telefono, correo, clave, parentesco, editable, ced_rep, id_quienPaga,Periodo,grado,seccion ) VALUES ('$nacion', '$cedula', '$miUsuario', '$apellido', '$nombre', '$sexo', '$fechaNac', '$locali', '$estado', '$municip', '$pais', '$direccion', '$telefono', '$correo', '$clave', '$par_rep', '$editable', '$ced_rep','$id_quienPaga','$periodo','$gra_alu','$sec_alu')") or die ("NO GUARDO ALUMNO".mysqli_error($link));
		$nuevo_query = mysqli_query($link,"SELECT LAST_INSERT_ID(idAlum) as nuevoCodigo FROM alumcer order by idAlum desc limit 0,1  ");
		$row=mysqli_fetch_array($nuevo_query);
		$idNuevo=$row['nuevoCodigo'];
		$idAlum=encriptar($row['nuevoCodigo']);
		if(!empty($_FILES['foto_alu']["name"]))
		{
			move_uploaded_file($nombreruta, $guardaRutaAlu);
			mysqli_query($link,"UPDATE alumcer SET ruta='$ruta' WHERE idAlum = '$idNuevo'");
		}
		if($gra_alu>60)
		{
			mysqli_query($link,"INSERT INTO matri".$tablaPeriodo." (ced_alu,idAlumno,grado,idSeccion,creado,statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,escola ) VALUE ('$cedula','$idNuevo','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado','$hoy','1' ) ") or die ("NO SE CREO1 ".mysqli_error());
		}else
		{
			mysqli_query($link,"INSERT INTO notaprimaria".$tablaPeriodo." (ced_alu,idAlumno,grado,idSeccion,creado,statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,escola ) VALUE ('$cedula','$idNuevo','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado','$hoy','1' ) ") or die ("NO SE CREO2 ".mysqli_error());
		}
	}else // el alumno estaba inscrito en alumcer
	{
		$row2=mysqli_fetch_array($existe_query);
		$idAlum=$row2['idAlum'];
		mysqli_query($link,"UPDATE alumcer SET nacion='$nacion', miUsuario='$miUsuario', apellido='$apellido', nombre='$nombre', sexo='$sexo', FechaNac='$fechaNac', locali='$locali', estado='$estado', pais='$pais', direccion='$direccion', telefono='$telefono', correo='$correo', clave='$clave', parentesco='$par_rep', editable='$editable', municip='$municip' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO SSSS ".mysqli_error($link));
		if($periodo==$periActivo)
		{
			mysqli_query($link,"UPDATE alumcer SET grado='$gra_alu', seccion='$sec_alu', Periodo='$periodo' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO2".mysqli_error($link));
		}
		if(!empty($_FILES['foto_alu']["name"]))
		{
			move_uploaded_file($nombreruta, $guardaRutaAlu);
			mysqli_query($link,"UPDATE alumcer SET ruta='$ruta' WHERE idAlum = '$idAlum'");
		}
		if($gra_alu>60)
		{
			$matri_query = mysqli_query($link,"SELECT idMatri FROM matri".$tablaPeriodo." WHERE idAlumno='$idAlum'");
			if(mysqli_num_rows($matri_query) > 0)
			{
				$row2=mysqli_fetch_array($matri_query);
				$id=$row2['idMatri'];
				mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET grado='$gra_alu', idSeccion='$sec_alu', actualizado='$hoy', desc1='$desc1', desc2='$desc2', desc3='$desc3', desc4='$desc4', desc5='$desc5', desc6='$desc6', desc7='$desc7', desc8='$desc8', desc9='$desc9', desc10='$desc10', desc11='$desc11', desc12='$desc12', desc13='$desc13', totalPeriodo='$totalPeriodo',pagado='$pagado',fechaIngreso='$hoy',escola='1' WHERE idMatri='$id'");
			}else
			{
				mysqli_query($link,"INSERT INTO matri".$tablaPeriodo." (ced_alu,idAlumno,grado,idSeccion,creado,statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,escola ) VALUE ('$cedula','$idAlum','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado','$hoy','1' ) ") or die ("NO SE CREO3 ".mysqli_error());
			}
		}else
		{
			$matri_query = mysqli_query($link,"SELECT id_notas FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlum'");
			if(mysqli_num_rows($matri_query) > 0)
			{
				$row2=mysqli_fetch_array($matri_query);
				$id=$row2['id_notas'];
				mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET grado='$gra_alu', idSeccion='$sec_alu', actualizado='$hoy', desc1='$desc1', desc2='$desc2', desc3='$desc3', desc4='$desc4', desc5='$desc5', desc6='$desc6', desc7='$desc7', desc8='$desc8', desc9='$desc9', desc10='$desc10', desc11='$desc11', desc12='$desc12', desc13='$desc13', totalPeriodo='$totalPeriodo',pagado='$pagado',fechaIngreso='$hoy',escola='1' WHERE id_notas='$id'");
			}else
			{
				mysqli_query($link,"INSERT INTO notaprimaria".$tablaPeriodo." (ced_alu,idAlumno,grado,idSeccion,creado,statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,escola ) VALUE ('$cedula','$idAlum','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado','$hoy','1' ) ") or die ("NO SE CREO4 ".mysqli_error());
			}
		}
		$idAlum=encriptar($idAlum);
	}
	//REPRESENTANTE//////////
	$repre_query=mysqli_query($link,"SELECT cedula FROM represe WHERE cedula='$ced_rep'");
	if(mysqli_num_rows($repre_query) == 0)
	{
		mysqli_query($link,"INSERT INTO represe (cedula, representante, correo, direccion, telefono, tlf_celu) VALUES ('$ced_rep', '$nom_rep', '$mai_rep', '$dir_rep', '$tlf_hab_rep', '$tlf_cel_rep')") or die ("NO GUARDO REPRESENTANTE".mysqli_error($link));
	}else
	{
		mysqli_query($link,"UPDATE represe SET representante='$nom_rep', correo='$mai_rep', direccion='$dir_rep', telefono='$tlf_hab_rep', tlf_celu='$tlf_cel_rep' WHERE cedula = '$ced_rep'") or die ("NO ACTUALIZO EL REPRESENTANTE".mysqli_error($link));
	}
	if(!empty($_FILES['foto_rep']["name"]))
	{
		move_uploaded_file($nombreruta1, $guardaRutaRep);
		mysqli_query($link,"UPDATE represe SET ruta='$ruta1' WHERE cedula = '$ced_rep'");
	}
	if($enviar=='1' && !empty($correo))
	{
		$usuario = ($miUsuario=='') ? $cedula : $miUsuario ;
		include("../../../inicia.php");
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
	    $mail->addAddress($correo,$nombre); //Destinatario
	    //$mail->addAddress('sisjch.tlf@gmail.com','SOPORTE');
	    $mail->isHTML(true);
	    $mail->Subject = 'Acceso a Pagina Web '.NKXS.' '.utf8_decode(EKKS).' de '.$apellido.' '.$nombre;
		$ekks=EKKS;
		$mai_alu = htmlspecialchars($correo);
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
	                  Estudiante: '.$apellido.' '.$nombre.'<br>Cedula: '.$cedula.'<br>Cursante del: '.$nombreGrado.'<br>Periodo Escolar: '.$periodo.'<br>Link: https://'.DOMINIO.' <br>Usuario: '.$usuario.'<br>Contraseña: '.$clave.'<br><br><br></td>
	                </tr>
	                <tr style="text-align: justify;"><td style="padding: 10px;"><h4>Lo invitamos antes de iniciar sesión en nuestra página web, a leer el manual de usuario https://'.DOMINIO.'/manual.php para que pueda darle el mejor uso a las funciones que ofrece nuestra página web<br><br><br></h4></td>
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
			$enviado++;
		} catch (Exception $e) 
		{
			echo "Error", $mail->ErrorInfo;
		}
	    $mail->ClearAddresses();
	}
}
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
} ?>