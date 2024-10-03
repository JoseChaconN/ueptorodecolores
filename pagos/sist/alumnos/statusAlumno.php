<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once ('../../include/funciones.php');
}
$link = Conectarse();
$idAlumno=$_POST['idAlu'];
$tablaPeriodo=$_POST['tabPer'];
$grado=$_POST['grado'];
if ($grado<60) {
	$buscarAlumno= mysqli_query($link,"SELECT statusAlum FROM notaprimaria".$tablaPeriodo." WHERE idAlumno = '$idAlumno'");
	while($row=mysqli_fetch_array($buscarAlumno))
	{ $status=$row['statusAlum']; }
	$status = ($status=='1') ? '2' : '1' ;
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET statusAlum='$status' WHERE idAlumno = '$idAlumno' ");	
}else
{
	$buscarAlumno= mysqli_query($link,"SELECT statusAlum FROM matri".$tablaPeriodo." WHERE idAlumno = '$idAlumno'");
	while($row=mysqli_fetch_array($buscarAlumno))
	{ $status=$row['statusAlum']; }
	$status = ($status=='1') ? '2' : '1' ;
	mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET statusAlum='$status' WHERE idAlumno = '$idAlumno' ");
}


$json = ['isSuccessful' => TRUE , 'status' => $status ] ;
echo json_encode($json);
?>