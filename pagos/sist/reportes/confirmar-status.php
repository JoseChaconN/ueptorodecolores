<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once("../../include/funciones.php");
}
$link = Conectarse();
$idPago=$_POST['idSta'];
$buscarPago= mysqli_query($link,"SELECT statusPago FROM pagos WHERE id = '$idPago'");
while($row=mysqli_fetch_array($buscarPago))
{ $status=$row['statusPago']; }
$status = ($status=='1') ? '2' : '1' ;
mysqli_query($link,"UPDATE pagos SET statusPago='$status' WHERE id = '$idPago' ");
$json = ['isSuccessful' => TRUE , 'status' => $status ] ;
echo json_encode($json);
?>