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
$cedula=$_POST['cedulaNew'];
$nombre=strtoupper($_POST['nombreNew']);
$apellido=strtoupper($_POST['apellidoNew']);
$telefono=$_POST['telefonoNew'];
$email=$_POST['emailNew'];
$direccNew=$_POST['direccNew'];
$provee_query=mysqli_query($link,"SELECT idAlum FROM alumcer Where cedula = '$cedula'"); 
if(mysqli_num_rows($provee_query)>0)
{
	header("location:provedor-list.php?msj=2");
}else
{
	if(!empty($cedula))
	{
		mysqli_query($link,"INSERT INTO alumcer (cedula,nombre,apellido,telefono,correo,statusAlum,cargo,direccion) VALUES ('$cedula','$nombre','$apellido','$telefono','$email','1','6','$direccNew')" ) or die ("NO GUARDO EL PROVEEDOR".mysqli_error($link));
		header("location:provedor-list.php?msj=1");
	}
}
?>
