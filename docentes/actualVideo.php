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
$idVideo = desencriptar($_POST['id']);
$fechaPublica = $_POST['fechaPublica'];
$tituloVideo = $_POST['tituloVideo'];
$descriVideo = $_POST['descriVideo'];
$videoLink=$_POST['linkNue'];
if(!empty($idVideo))
{
	mysqli_query($link,"UPDATE video".$tablaPeriodo." SET fechaPublica = '$fechaPublica', tituloVideo='$tituloVideo', descriVideo='$descriVideo',videoLink='$videoLink' WHERE idVideo = '$idVideo'");
	$fechaP=date("d-m-Y", strtotime($fechaPublica));
	$json = ['isSuccessful' => TRUE, 'fechaP'=>$fechaP ] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>