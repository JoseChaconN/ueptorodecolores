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
$tipo_egreso=$_POST['tipo'];
$ordena=$_POST['orden'];
mysqli_query($link,"UPDATE concep_egresos SET concepto='$concepto', monto='$monto',tipo_egreso='$tipo_egreso',ordena='$ordena' WHERE id_concepto = '$id' ");

$tipo = ($tipo_egreso==1) ? 'Devenga' : 'Deducción' ;
$monto=number_format($monto,2,'.',',');
$json = ['isSuccessful' => TRUE, 'conc'=>$concepto, 'monto'=>$monto, 'tipo'=>$tipo ] ;
echo json_encode($json);
?>