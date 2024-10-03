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
$paga_desde=$_POST['fecha'];
$link=Conectarse();
mysqli_query($link,"UPDATE tasa_dia SET paga_desde='$paga_desde' WHERE idTasa='1' ") or die ("NO ACTUALIZO DESDE".mysqli_error());
$json = ['isSuccessful' => true ] ;
echo json_encode($json);
?>