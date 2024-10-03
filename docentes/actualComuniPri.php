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
$idComunica = desencriptar($_POST['id']);
$fechaPublica = $_POST['fechaPublica'];
$fechaMaxima = $_POST['fechaMaxima'];
$tituloTarea = $_POST['titulo'];

if(!empty($idComunica))
{
	mysqli_query($link,"UPDATE comunica_docen SET fechaPublica = '$fechaPublica', fechaMaxima='$fechaMaxima', tituloComunica='$tituloTarea' WHERE idComunica = '$idComunica'");
	

	$fechaP=date("d-m-Y", strtotime($fechaPublica));
	$fechaM=date("d-m-Y", strtotime($fechaMaxima));
	$json = ['isSuccessful' => TRUE, 'fechaP'=>$fechaP, 'fechaM'=>$fechaM ] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>