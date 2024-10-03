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
$fecha_vence=$_POST['fecha_vence'];
$tablaPeriodo=$_POST['perio'];
$fecha_nue=date("Y-m-d",strtotime($fecha_vence."+ 1 year"));
mysqli_query($link,"UPDATE montos".$tablaPeriodo." SET fecha_vence='$fecha_nue' WHERE id_tabla='$id_tabla' ") or die ("NO ACTUALIZO ".mysqli_error());
$monto=number_format($monto,2,'.',',');
$fecha_nue=date("d-m-Y", strtotime($fecha_nue));
$json = ['isSuccessful' => TRUE, 'fecha'=>$fecha_nue] ;
echo json_encode($json);
?>