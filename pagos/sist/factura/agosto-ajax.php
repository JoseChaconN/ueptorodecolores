<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
include_once "../../include/funciones.php";
$link = Conectarse();
$id_concepto=$_POST['id'];
$tablaPeriodo=$_POST['tabl'];
$grado=$_POST['grado'];
$idAlum=desencriptar($_POST['idAlu']);
if ($grado<61) {
	$matri_query = mysqli_query($link,"SELECT desc13,grado FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlum' ");	
}else
{
	$matri_query = mysqli_query($link,"SELECT desc13,grado FROM matri".$tablaPeriodo." WHERE idAlumno='$idAlum' ");
}
$descAgosto=0;
if(mysqli_num_rows($matri_query) > 0)
{
	$row2=mysqli_fetch_array($matri_query);
	$descAgosto=$row2['desc13'];
	$grado=$row2['grado'];

	$montos_query = mysqli_query($link,"SELECT monto FROM montos".$tablaPeriodo." WHERE id_grado='$grado' and id_concepto='$id_concepto' ");
	$pagaAgosto=0;
	if(mysqli_num_rows($montos_query) > 0)
	{
		$row2=mysqli_fetch_array($montos_query);
		$pagaAgosto=$row2['monto'];
	}
	$pagos_query = mysqli_query($link,"SELECT SUM(montoDolar) as montoD FROM pagos".$tablaPeriodo." WHERE idAlum='$idAlum' and id_concepto='$id_concepto' and statusPago='1' ");
	$pagoAgosto=0;
	if(mysqli_num_rows($pagos_query) > 0)
	{
		$row2=mysqli_fetch_array($pagos_query);
		$pagoAgosto=$row2['montoD'];
	}
	$debeAgosto=number_format((($pagaAgosto-$descAgosto)-$pagoAgosto),2,'.',',');
	$json = ['isSuccessful' => TRUE,'pagAgo'=>$pagoAgosto,'debeAgo'=>$debeAgosto];
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
?>