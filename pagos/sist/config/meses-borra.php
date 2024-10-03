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
$id_tabla=desencriptar($_POST['id_tabla']);
$tablaPeriodo=$_POST['tablaP'];
mysqli_query($link,"DELETE FROM montos".$tablaPeriodo." WHERE id_tabla = '$id_tabla' ");
$json = ['isSuccessful' => TRUE] ;
echo json_encode($json);
?>