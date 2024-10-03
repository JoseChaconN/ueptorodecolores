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
$idTarea = desencriptar($_POST['id']);
$nombreArchivo = $_POST['archivo'];
if(!empty($idTarea))
{
	mysqli_query($link,"DELETE FROM tareaspri".$tablaPeriodo." WHERE idTarea = '$idTarea'");
	unlink('../tareas/'.$nombreArchivo);
	mysqli_query($link,"DELETE FROM tarea_indpri_".$tablaPeriodo." WHERE idTarea = '$idTarea'");
	$json = ['isSuccessful' => TRUE] ;
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json); ?>