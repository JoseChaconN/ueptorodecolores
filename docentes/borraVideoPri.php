<?php 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2 ) 
{
  header("location:../index.php?vencio");
}
$tablaPeriodo=$_SESSION['tablaPeriodo'];
include_once '../conexion.php';
include_once '../includes/funciones.php';
$link = Conectarse();
$idVideo = desencriptar($_POST['id']);

if(!empty($idVideo))
{
	mysqli_query($link,"DELETE FROM videopri".$tablaPeriodo." WHERE idVideo = '$idVideo'");
	$json = ['isSuccessful' => TRUE] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>