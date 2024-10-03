<?php 
include_once 'conexion.php';
include_once 'includes/funciones.php';
$link = Conectarse();
$miUsuario = $_POST['usua'];
if(!empty($miUsuario))
{
	$alumno_query=mysqli_query($link,"SELECT miUsuario FROM alumcer Where miUsuario='$miUsuario'"); 
	if(mysqli_num_rows($alumno_query)>0)
	{
		$json = ['isSuccessful' => TRUE ] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}else{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>