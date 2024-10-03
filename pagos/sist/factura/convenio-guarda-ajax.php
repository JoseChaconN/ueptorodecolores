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
$convenio=$_POST['convenio'];
$grado=$_POST['grado'];
if ($grado<61) {
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET convenio='$convenio' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error());
}else
{
	mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET convenio='$convenio' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error());
}
$json = ['isSuccessful' => TRUE];
echo json_encode($json);
?>