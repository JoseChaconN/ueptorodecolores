<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once ('../../include/funciones.php');
}
$link = Conectarse();
$id_grado=$_POST['id_grado'];
$fecha_vence=$_POST['fecha_vence'];
$mes=$_POST['mes'];
$monto=$_POST['monto'];
$tablaPeriodo=$_POST['periodo'];
mysqli_query($link,"INSERT INTO montos".$tablaPeriodo." (id_grado,mes,monto,fecha_vence ) VALUE ('$id_grado','$mes','$monto','$fecha_vence' ) ") or die ("NO SE CREO ".mysqli_error());
$json = ['isSuccessful' => TRUE ] ;
echo json_encode($json);
?>