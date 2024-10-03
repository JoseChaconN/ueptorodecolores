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

$id=($_POST['id']);
$concepto=$_POST['concepto'];
$monto=$_POST['monto'];
$nro_pagos=$_POST['nroPag'];
$abonos=$_POST['abono'];
$editar=$_POST['edita'];

mysqli_query($link,"UPDATE conceptos SET concepto='$concepto', monto='$monto',nro_pagos='$nro_pagos',abonos='$abonos',editar='$editar' WHERE id = '$id' ");
$monto=number_format($monto,2,'.',',');
$json = ['isSuccessful' => TRUE, 'conc'=>$concepto, 'monto'=>$monto ] ;
echo json_encode($json);
?>