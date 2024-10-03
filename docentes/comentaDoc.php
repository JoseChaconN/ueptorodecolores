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
$idEntrega = desencriptar($_POST['id']);

if(!empty($idEntrega))
{
	$comenta_query = mysqli_query($link,"SELECT observDocente FROM entrego_tarea".$tablaPeriodo." WHERE idEntrega='$idEntrega'");
	while($row = mysqli_fetch_array($comenta_query))
  {
  	$comenta=$row['observDocente'];
  }
	$json = ['isSuccessful' => TRUE, 'comenta'=>$comenta] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>