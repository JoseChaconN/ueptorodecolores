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
$id_concepto=$_POST['id_con'];
$buscar_query= mysqli_query($link,"SELECT status FROM concep_egresos WHERE id_concepto = '$id_concepto'");
while($row=mysqli_fetch_array($buscar_query))
{ $status=$row['status']; }
$status = ($status=='1') ? '2' : '1' ;
mysqli_query($link,"UPDATE concep_egresos SET status='$status' WHERE id_concepto = '$id_concepto' ");	

$json = ['isSuccessful' => TRUE , 'status' => $status ] ;
echo json_encode($json);
?>