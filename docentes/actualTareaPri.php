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
$fechaPublica = $_POST['fechaPublica'];
$fechaMaxima = $_POST['fechaMaxima'];
$tituloTarea = $_POST['tituloTarea'];
$descriTarea = $_POST['descriTarea'];
if(!empty($idTarea))
{
	mysqli_query($link,"UPDATE tareaspri".$tablaPeriodo." SET fechaPublica = '$fechaPublica', fechaMaxima='$fechaMaxima', tituloTarea='$tituloTarea', descriTarea='$descriTarea' WHERE idTarea = '$idTarea'");
	

	$fechaP=date("d-m-Y", strtotime($fechaPublica));
	$fechaM=date("d-m-Y", strtotime($fechaMaxima));
	$json = ['isSuccessful' => TRUE, 'fechaP'=>$fechaP, 'fechaM'=>$fechaM ] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>