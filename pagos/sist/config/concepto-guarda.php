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
$nro_pagos=$_POST['nroPag'];
$abonos=$_POST['abono'];
$editar=$_POST['edita'];
if(!empty($concepto))
{
	mysqli_query($link,"INSERT INTO conceptos (concepto, monto, afecta,agosto,nro_pagos,abonos,editar) VALUES ('$concepto', '$monto','N','N','$nro_pagos','$abonos','$editar')") or die ("NO GUARDO CONCEPTO".mysqli_error($link));	
	$json = ['isSuccessful' => TRUE ] ;
}else
{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>