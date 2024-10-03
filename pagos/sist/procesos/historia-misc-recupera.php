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
$recibo=$_POST['recib'];
$motivo=$_POST['motivo'];
$usuarioNulo=$_SESSION['idUser'];
mysqli_query($link,"UPDATE miscelaneos SET statusPago='1',comentario='$motivo' WHERE recibo='$recibo' ") or die ("NO ACTUALIZO ".mysqli_error());
$json = ['isSuccessful' => true, 'motivo'=>$motivo  ] ;
echo json_encode($json);
?>