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
$idComunica = desencriptar($_POST['id']);
$nombreArchivo = $_POST['archivo'];

if(!empty($idComunica))
{
	mysqli_query($link,"DELETE FROM comunica_docen WHERE idComunica = '$idComunica'");
	unlink($nombreArchivo);
	$json = ['isSuccessful' => TRUE] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>