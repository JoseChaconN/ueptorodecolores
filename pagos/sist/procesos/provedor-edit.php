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
$id=$_POST['id'];
$cedula=$_POST['cedula'];
$nombre=($_POST['nombre']);
$apellido=($_POST['apellido']);
$telefono=$_POST['telefono'];
$correo=$_POST['correo'];
$direccion=$_POST['direcc'];
if(!empty($cedula))
{
	mysqli_query($link,"UPDATE alumcer SET cedula='$cedula', nombre='$nombre',apellido='$apellido',telefono='$telefono',correo='$correo',direccion='$direccion' WHERE idAlum = '$id' ");	
	$json = ['isSuccessful' => TRUE, 'nom'=>$nombre, 'ape'=>$apellido  ] ;
}else {$json = ['isSuccessful' => FALSE ] ;}
echo json_encode($json);
?>