<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
include_once "../../include/funciones.php";
$link = Conectarse();
$banco=$_POST['banco'];
$referencia=$_POST['oper'];
$buscar_query = mysqli_query($link,"SELECT A.fecha,A.recibo,B.apellido,B.nombre,C.nombreUser,C.apellidoUser FROM operaciones A, alumcer B, user C WHERE A.referencia='$referencia' and A.banco='$banco' and A.idAlum=B.idAlum and A.usuario=C.idUser and A.tipo='5' ");
if(mysqli_num_rows($buscar_query) > 0)
{
	while ($row = mysqli_fetch_array($buscar_query))
	{
		$fecha=date("d-m-Y", strtotime($row['fecha']));
		$alumno=$row['apellido'].' '.$row['nombre'];
		$usuario=$row['nombreUser'].' '.$row['apellidoUser'];
		$recibo=$row['recibo'];
	}
	$json = ['isSuccessful' => TRUE,'fecha'=>$fecha,'alumno'=>$alumno,'usuario'=>$usuario,'recibo'=>$recibo ];
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
?>