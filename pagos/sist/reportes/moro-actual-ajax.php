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
$monto=$_POST['monto'];
$tablaPeriodo=$_POST['tabla'];
$grado=$_POST['grad'];
if($grado<61){
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET morosida='$monto' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO ".mysqli_error());
}else
{
	mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET morosida='$monto' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO ".mysqli_error());
}
$json = ['isSuccessful' => true  ] ;
echo json_encode($json);
?>