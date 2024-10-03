<?php 
session_start();
if(!isset($_SESSION["usuario"]) && $_SESSION['cargo']<2) 
{
  header("../location:index.php?vencio");
}
$tablaPeriodo=$_SESSION['tablaPeriodo'];
include '../conexion.php';
include '../includes/funciones.php';
$link = Conectarse();
$idAlum = desencriptar($_POST['id']);
$literal = $_POST['not'];
if(!empty($idAlum))
{
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET literal='$literal' WHERE idAlumno='$idAlum' ") or die ("NO SE ACTUALIZO LITERAL".mysqli_error());

	$json = ['isSuccessful' => TRUE,] ;
	
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json); ?>