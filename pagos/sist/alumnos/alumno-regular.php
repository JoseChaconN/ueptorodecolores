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
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$hoyCorta = date("Y-m-d");
$link = Conectarse();
$idAlum=desencriptar($_GET['id']);
$periodo=$_GET["nomP"];
$tablaPeriodo=$_GET["peri"];
$gra_alu=$_GET["gra"];
$sec_alu=$_GET["sec"];

$datos_query = mysqli_query($link,"SELECT cedula FROM alumcer WHERE idAlum='$idAlum'");
$row1=mysqli_fetch_array($datos_query);
$cedula=$row1['cedula'];

mysqli_query($link,"UPDATE alumcer SET grado='$gra_alu', seccion='$sec_alu', Periodo='$periodo' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO SSSS ".mysqli_error($link));

if ($gra_alu>60) {
	$matri_query = mysqli_query($link,"SELECT idMatri FROM matri".$tablaPeriodo." WHERE idAlumno='$idAlum'");
	if(mysqli_num_rows($matri_query) > 0)
	{
		$row2=mysqli_fetch_array($matri_query);
		$id=$row2['idMatri'];
		mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET grado='$gra_alu', idSeccion='$sec_alu', actualizado='$hoy' WHERE idMatri='$id'");
	}else
	{
		mysqli_query($link,"INSERT INTO matri".$tablaPeriodo." (idAlumno,grado,idSeccion,creado,statusAlum,ced_alu,escola,fechaIngreso ) VALUE ('$idAlum','$gra_alu','$sec_alu','$hoy','1', '$cedula','1','$hoyCorta' ) ") or die ("NO SE CREO ".mysqli_error());
	}
}else
{
	$matri_query = mysqli_query($link,"SELECT id_notas FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlum'");
	if(mysqli_num_rows($matri_query) > 0)
	{
		$row2=mysqli_fetch_array($matri_query);
		$id=$row2['id_notas'];
		mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET grado='$gra_alu', idSeccion='$sec_alu', actualizado='$hoy' WHERE id_notas='$id'");
	}else
	{
		mysqli_query($link,"INSERT INTO notaprimaria".$tablaPeriodo." (idAlumno,grado,idSeccion,creado,statusAlum,ced_alu,escola,fechaIngreso ) VALUE ('$idAlum','$gra_alu','$sec_alu','$hoy','1', '$cedula','1','$hoyCorta' ) ") or die ("NO SE CREO ".mysqli_error());
	}
}

$idAlum=encriptar($idAlum);?>
<script type="text/javascript">
	opener.document.location.reload();
</script><?php
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