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
$fechaHoy = date("Y-m-d H:i:s");
$link = Conectarse();
$usuarioNulo=$_SESSION['idUser'];
$recibo=$_POST['recib'];
$motivo=$_POST['motivo'];
mysqli_query($link,"UPDATE miscelaneos SET statusPago='2',comentario='$motivo', fechaNulo='$fechaHoy', usuarioNulo='$usuarioNulo' WHERE recibo='$recibo' ") or die ("NO ACTUALIZO ".mysqli_error());
$json = ['isSuccessful' => true ] ;
echo json_encode($json);
?>