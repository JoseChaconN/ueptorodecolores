<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once("../../include/funciones.php");
}
$link = Conectarse();
$idAlum=$_POST['idAlu'];
$tablaPeriodo=$_POST['tabla'];
if ($idAlum>0) {
	$alumnos_query = mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido,A.grado,A.ced_rep, B.nombreGrado, C.nombre as nomSec FROM alumcer A, grado".$tablaPeriodo." B, secciones C WHERE A.idAlum='$idAlum' and A.grado=B.grado and A.seccion=C.id ");
	while($row=mysqli_fetch_array($alumnos_query))
	{ 
		$cedula=$row['cedula']; 
		$nombre=$row['nombre']; 
		$apellido=$row['apellido']; 
		$nombreGrado=$row['nombreGrado']; 
		$nomSec=$row['nomSec']; 
		$ced_rep=$row['ced_rep']; 
	}
	$represe_query = mysqli_query($link,"SELECT representante, tlf_celu FROM represe WHERE cedula='$ced_rep' ");
	while($row=mysqli_fetch_array($represe_query))
	{ 
		$represe=$row['representante']; 
		$tlf_celu=$row['tlf_celu']; 
	}
	$json = ['isSuccessful' => TRUE , 'ced' => $cedula, 'nomb'=>$nombre, 'apel'=>$apellido, 'grad'=>$nombreGrado, 'secc'=>$nomSec,'cedR'=>$ced_rep,'tlfRep'=>$tlf_celu,'repre'=>$represe ] ;
}else{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>