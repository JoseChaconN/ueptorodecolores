<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once("../../include/funciones.php");
}
$link = Conectarse();
$idAlumno=desencriptar($_POST['idAlum']);
$tablaPeriodo=$_POST['tabla'];
$exoneraMorosidad=$_POST['fecha'];
$grado=$_POST['grado'];
if($grado<61){
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET exoneraMorosidad='$exoneraMorosidad' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO ".mysqli_error());
}else
{
	mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET exoneraMorosidad='$exoneraMorosidad' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO ".mysqli_error());
}
$json = ['isSuccessful' => true  ] ;
echo json_encode($json);
?>