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
$id_concepto=$_POST['idC'];
$tablaPeriodo=$_POST['tab'];
$monto=$_POST['mont'];
$idAlum=desencriptar($_POST['idA']);
$conce_query = mysqli_query($link,"SELECT concepto,monto FROM miscelaneos_conceptos WHERE id='$id_concepto' ");
$row2=mysqli_fetch_array($conce_query);
$concepto=$row2['concepto'];
$montoConcepto=$row2['monto'];
$concepto='Paga '.$concepto;
$pagar=number_format($montoConcepto,2,'.',',');
$json = ['isSuccessful' => TRUE,'pagar'=>$pagar, 'conceNue'=>$concepto];
echo json_encode($json);
?>