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
$nombre_tipo=$_POST['nombre'];
$status=$_POST['stat'];
$operacion=$_POST['oper'];
if(!empty($nombre_tipo))
{
	mysqli_query($link,"INSERT INTO caja_chica_tipo (nombre_tipo,status,operacion) VALUES ('$nombre_tipo','$status','$operacion')") or die ("NO GUARDO TIPO".mysqli_error($link));	
	$json = ['isSuccessful' => TRUE ] ;
}else
{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>