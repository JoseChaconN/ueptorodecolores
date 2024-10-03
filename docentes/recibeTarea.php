<?php 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$recibio = 'Recibida en fisico por el Docente el '.strftime("%d de %B %Y", strtotime($fechahoy));
$tablaPeriodo=$_SESSION['tablaPeriodo'];
include_once '../conexion.php';
include_once '../includes/funciones.php';
$link = Conectarse();
$idAlumno = desencriptar($_POST['idA']);
$idTarea = desencriptar($_POST['idT']);

if(!empty($idTarea))
{
	mysqli_query($link,"INSERT INTO entrego_tarea".$tablaPeriodo." (idTarea,idAlumno,observDocente,status) VALUES ('$idTarea','$idAlumno','$recibio','1')") or die ("NO GUARDO RECEPCION".mysqli_error()); 
	$json = ['isSuccessful' => TRUE] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>