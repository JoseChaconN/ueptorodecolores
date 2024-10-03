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
$idAlum=desencriptar($_POST['idAl']);
$tablaPeriodo=$_POST['tab'];
$nro=$_POST['numero'];
$grado=$_POST['grado'];
$monto=$_POST['monto'];
$desc='desc'.$nro;
//echo 'tabla '.$tablaPeriodo.' nro'.$nro.' grado'.$grado.' monto'.$monto.' desc'.$desc ;
if ($grado<61) {
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET ".$desc."='$monto' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error());
}else
{
	mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET ".$desc."='$monto' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error());
}
$json = ['isSuccessful' => TRUE];
echo json_encode($json);
?>