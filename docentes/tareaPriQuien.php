<?php 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("../location:index.php?vencio");
}
$tablaPeriodo=$_SESSION['tablaPeriodo'];
include_once '../conexion.php';
include_once '../includes/funciones.php';
$link = Conectarse();
$idTarea = desencriptar($_POST['id']);
if(!empty($idTarea))
{
	$quiene_query=mysqli_query($link,"SELECT B.nombre,B.apellido FROM tarea_indpri_".$tablaPeriodo." A, alumcer B WHERE A.idTarea='$idTarea' and A.idAlum=B.idAlum ");
	if(mysqli_num_rows($quiene_query) > 0)
	{
		$quien=''; $son=0;
		while($row = mysqli_fetch_array($quiene_query)){
			$son++;
			$quien.=$son.') '.$row['nombre'].' '.$row['apellido'].', ';
		}
	}
	$json = ['isSuccessful' => TRUE,'quienes'=>$quien] ;
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>