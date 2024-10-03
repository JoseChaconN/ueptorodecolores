<?php 
session_start();
include 'conexion.php';
include 'includes/funciones.php';
$link = Conectarse();
$id_tarea = desencriptar($_POST['id_tar']);
$id_alum = desencriptar($_POST['id_al']);
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaVio = date("Y-m-d H:i:s");
if(!empty($id_tarea))
{
	mysqli_query($link,"INSERT INTO vio_tarea (id_tarea,id_alum,fecha) VALUES ('$id_tarea', '$id_alum','$fechaVio')") or die ("NO GUARDO VISTO".mysqli_error());

	$json = ['isSuccessful' => TRUE] ;
}else{ $json = ['isSuccessful' => FALSE ] ; }
echo json_encode($json);
 ?>