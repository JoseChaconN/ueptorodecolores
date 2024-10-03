<?php 
session_start();
include_once 'conexion.php';
include_once 'includes/funciones.php';
$link = Conectarse();
$usuario = $_POST['usua'];
if(!empty($usuario))
{
	$alumno_query=mysqli_query($link,"SELECT idAlum, miUsuario FROM alumcer Where miUsuario = '$usuario'"); 
	if(mysqli_num_rows($alumno_query)>0)
	{
		while($row=mysqli_fetch_array($alumno_query))
	  	{
		    $idAlum=$row['idAlum'];
	  	}
	  	if ($_SESSION['idAlum']!=$idAlum) {
	  		$json = ['isSuccessful' => TRUE ] ;
	  	}else{
	  		$json = ['isSuccessful' => FALSE];	
	  	}
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
 ?>