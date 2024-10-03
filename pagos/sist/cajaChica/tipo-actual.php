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

$id=$_POST['id'];
$nombre_tipo=$_POST['nombre'];
$operacion=$_POST['tipo'];
mysqli_query($link,"UPDATE caja_chica_tipo SET nombre_tipo='$nombre_tipo',operacion='$operacion' WHERE id='$id' ");
$nomOperacion = ($operacion== 1) ? 'SUMAR' : 'RESTAR';
$json = ['isSuccessful' => TRUE, 'nombr'=>$nombre_tipo,'nomTip'=>$nomOperacion ] ;
echo json_encode($json);
?>