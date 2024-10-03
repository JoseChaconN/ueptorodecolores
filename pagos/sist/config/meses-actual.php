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
$mes=$_POST['mes'];
$monto=$_POST['monto'];
$tablaPeriodo=$_POST['perio'];
mysqli_query($link,"UPDATE montos".$tablaPeriodo." SET mes='$mes', monto='$monto', fecha_vence='$fecha_vence' WHERE id_tabla='$id_tabla' ") or die ("NO ACTUALIZO ".mysqli_error());
$monto=number_format($monto,2,'.',',');
$fecha_vence=date("d-m-Y", strtotime($fecha_vence));
$json = ['isSuccessful' => TRUE, 'monto'=>$monto, 'fecha'=>$fecha_vence] ;
echo json_encode($json);
?>