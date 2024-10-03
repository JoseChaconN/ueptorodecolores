<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
$link = Conectarse();

$nombre_cuenta=$_POST['nombre'];
if(!empty($nombre_cuenta))
{
	mysqli_query($link,"INSERT INTO cuentas (nombre_cuenta,status) VALUES ('$nombre_cuenta','1')") or die ("NO GUARDO CUENTA".mysqli_error($link));	
	$json = ['isSuccessful' => TRUE ] ;
}else
{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>