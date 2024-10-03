<?php 
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
include_once 'conexion.php';
include_once 'includes/funciones.php';
$link = Conectarse();
$idEleccion = $_POST['idEl'];
$idParticipan=$_POST['id'];
$cedula=$_SESSION['usuario'];
if(!empty($idEleccion))
{
	$votante_query=mysqli_query($link,"SELECT idVotos FROM votos Where idEleccion = '$idEleccion' and cedulaAlumno='$cedula' ");
	if(mysqli_num_rows($votante_query)==0)
	{
		mysqli_query($link,"INSERT INTO votos (idEleccion,idParticipan,cedulaAlumno,fechaHora ) VALUE ('$idEleccion','$idParticipan','$cedula','$hoy' ) ") or die ("NO SE GUARDO ".mysqli_error());

		$json = ['isSuccessful' => TRUE ] ;
	}else{ $json = ['isSuccessful' => FALSE]; }
	
}else{ $json = ['isSuccessful' => FALSE]; }
echo json_encode($json);
 ?>