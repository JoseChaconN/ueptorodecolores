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
$mailAlum=$_POST['mailAlum'];
$mailRep=$_POST['mailRep'];
$celuRep=$_POST['celuRep'];
$ced_rep=$_POST['ceduRep'];

mysqli_query($link,"UPDATE alumcer SET correo='$mailAlum' WHERE idAlum='$idAlum' ") or die ("NO ACTUALIZO Alumno ".mysqli_error());
mysqli_query($link,"UPDATE represe SET correo='$mailRep',tlf_celu='$celuRep'  WHERE cedula='$ced_rep' ") or die ("NO ACTUALIZO Repre".mysqli_error($link));
if (empty($mailAlum) || empty($mailRep) || empty($celuRep) ) {
	$json = ['isSuccessful' => FALSE];
}else{
	$json = ['isSuccessful' => TRUE];
}
echo json_encode($json);
?>