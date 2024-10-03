<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$link = Conectarse();
$nombre_caja_chica=$_POST['nombre'];
$status=$_POST['stat'];
if(!empty($nombre_caja_chica))
{
	mysqli_query($link,"INSERT INTO cajas_chicas (nombre_caja_chica,status,creacion) VALUES ('$nombre_caja_chica','$status','$hoy')") or die ("NO GUARDO CAJA CHICA".mysqli_error($link));	
	$json = ['isSuccessful' => TRUE ] ;
}else
{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>