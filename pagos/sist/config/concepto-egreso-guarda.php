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

$concepto=$_POST['concepto'];
$monto=$_POST['monto'];
$tipo_egreso=$_POST['tipo'];
$ordena=$_POST['orden'];
if(!empty($concepto))
{
	mysqli_query($link,"INSERT INTO concep_egresos (concepto, monto, status, tipo_egreso,ordena) VALUES ('$concepto', '$monto','1', '$tipo_egreso','$ordena')") or die ("NO GUARDO CONCEPTO".mysqli_error($link));	
	$json = ['isSuccessful' => TRUE ] ;
}else
{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>