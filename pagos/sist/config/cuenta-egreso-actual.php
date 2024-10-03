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

$id_cuenta=($_POST['id']);
$nombre_cuenta=$_POST['nombre'];
mysqli_query($link,"UPDATE cuentas SET nombre_cuenta='$nombre_cuenta' WHERE id_cuenta = '$id_cuenta' ");

$json = ['isSuccessful' => TRUE, 'conc'=>$nombre_cuenta ] ;
echo json_encode($json);
?>