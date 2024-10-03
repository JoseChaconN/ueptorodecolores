<?php 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$lap = $_SESSION['lapsoActivo'];
include_once '../conexion.php';
include_once '../includes/funciones.php';
$link = Conectarse();
$idAlum = desencriptar($_POST['id']);
$campo = $_POST['campo'];
$nota = $_POST['not'];
$ced_alu = $_POST['ced'];
$grado = $_POST['gra'];
$seccion = $_POST['sec'];
if ($lap==1) {
  $fecha_query=mysqli_query($link,"SELECT iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=3 ");  
}
if ($lap==2) {
  $fecha_query=mysqli_query($link,"SELECT iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=4 ");  
}
if ($lap==3) {
  $fecha_query=mysqli_query($link,"SELECT iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=5 ");  
}
while($row=mysqli_fetch_array($fecha_query)) 
{
  $iniciaCarga=$row['iniciaMaestro'];
  $terminaCarga=$row['terminaMaestro'];
}
$permite = ($iniciaCarga<=$fechahoy && $terminaCarga>=$fechahoy ) ? 'S' : 'N' ;
if(!empty($ced_alu) && $permite=='S')
{
	$notas_query=mysqli_query($link,"SELECT id_notas FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlum' ");
	if(mysqli_num_rows($notas_query) > 0)
	{
		$row2=mysqli_fetch_array($notas_query);
		$id_notas=$row2['id_notas'];
		mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET $campo='$nota' WHERE id_notas='$id_notas'") or die ("NO SE ACTUALIZO".mysqli_error());	
	}else
	{
		mysqli_query($link,"INSERT INTO notaprimaria".$tablaPeriodo." (ced_alu, idAlumno, grado, idSeccion, $campo, statusAlum,escola) VALUES ('$ced_alu', '$idAlum', '$grado', '$seccion','$nota','1','1')") or die ("NO SE GUARDO".mysqli_error());
	}

	$json = ['isSuccessful' => TRUE,] ;
	
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json); ?>