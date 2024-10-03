<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once ('../../include/funciones.php');
}
$link = Conectarse();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d");
$id=($_POST['id']);
$cantidad=$_POST['stoc'];
$monto=$_POST['monto'];
$emitidoPor=$_SESSION['idUser'];
mysqli_query($link,"INSERT INTO miscelaneos (id_concepto,cantidad,proceso,fecha,montoDolar,emitidoPor,statusPago ) VALUE ( '$id','$cantidad','1','$hoy','$monto','$emitidoPor','1' ) ") or die ("NO SE CREO ".mysqli_error());
mysqli_query($link,"UPDATE miscelaneos_conceptos SET monto='$monto' WHERE id='$id' ") or die ("NO ACTUALIZO ".mysqli_error());
$json = ['isSuccessful' => TRUE ] ;
echo json_encode($json);
?>