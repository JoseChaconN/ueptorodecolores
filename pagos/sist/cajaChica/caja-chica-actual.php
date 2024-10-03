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

$id=($_POST['id']);
$nombre_caja_chica=$_POST['nombre'];

mysqli_query($link,"UPDATE cajas_chicas SET nombre_caja_chica='$nombre_caja_chica' WHERE id='$id' ");

$json = ['isSuccessful' => TRUE, 'nombr'=>$nombre_caja_chica ] ;
echo json_encode($json);
?>