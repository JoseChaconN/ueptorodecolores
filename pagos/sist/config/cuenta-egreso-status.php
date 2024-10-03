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
$id_cuenta=$_POST['id_cue'];
$buscar_query= mysqli_query($link,"SELECT status FROM cuentas WHERE id_cuenta = '$id_cuenta'");
while($row=mysqli_fetch_array($buscar_query))
{ $status=$row['status']; }
$status = ($status=='1') ? '2' : '1' ;
mysqli_query($link,"UPDATE cuentas SET status='$status' WHERE id_cuenta = '$id_cuenta' ");	

$json = ['isSuccessful' => TRUE , 'status' => $status ] ;
echo json_encode($json);
?>