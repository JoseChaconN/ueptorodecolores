<?php 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
$tablaPeriodo=$_SESSION['tablaPeriodo'];
include_once '../conexion.php';
include_once '../includes/funciones.php';
$link = Conectarse();
$idEntrega = desencriptar($_POST['id']);
$comenta = $_POST['comenta'];

if(!empty($idEntrega))
{
	mysqli_query($link,"UPDATE entrego_tarea".$tablaPeriodo." SET observDocente='$comenta', status='1' WHERE idEntrega = '$idEntrega'");
	$json = ['isSuccessful' => TRUE] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>