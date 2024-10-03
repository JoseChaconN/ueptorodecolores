<?php 
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once ('../../include/funciones.php');
}
$link = Conectarse();
$cedula = $_POST['cedula'];
if(!empty($cedula))
{
	$provee_query=mysqli_query($link,"SELECT idAlum,cedula,nombre,apellido,telefono,correo,direccion FROM alumcer Where cedula = '$cedula'"); 
	if(mysqli_num_rows($provee_query)>0){
		while($row=mysqli_fetch_array($provee_query))
	  {
	  	$idAlum=$row['idAlum'];
	    $cedula=$row['cedula'];
	    $nombre=$row['nombre'];
	    $apellido=$row['apellido'];
	    $telefono=$row['telefono'];
	    $correo = $row['correo'];
	    $direccion = $row['direccion'];
  	}
		$json = ['isSuccessful' => TRUE ,'id'=>$idAlum, 'nombre' => $nombre, 'apellido' => $apellido, 'correo' => $correo, 'telefono' => $telefono, 'direcc'=>$direccion ];
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
?>