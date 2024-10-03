<?php 
session_start();
include_once 'conexion.php';
include_once 'includes/funciones.php';
$link = Conectarse();
$idEntrega = desencriptar($_POST['idEnt']);
$periodoAlum=$_SESSION['periodoAlum'];
if(!empty($idEntrega))
{
	mysqli_query($link,"UPDATE entrego_tarea".$periodoAlum." SET status = '2' WHERE idEntrega = '$idEntrega'");
		
	$json = ['isSuccessful' => TRUE ] ;
}else{ $json = ['isSuccessful' => TRUE ] ; }
echo json_encode($json);
 ?>