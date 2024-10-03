<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
$link = Conectarse();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d");
$concepto=$_POST['concepto'];
$monto=$_POST['monto'];
$editar=$_POST['edita'];
$articulo=$_POST['arti'];
$emitidoPor=$_SESSION['idUser'];
if(!empty($concepto))
{
	$conceptos_query = mysqli_query($link,"SELECT concepto FROM miscelaneos_conceptos WHERE concepto='$concepto' ");
	if(mysqli_num_rows($conceptos_query) > 0)
	{
		$json = ['isSuccessful' => FALSE, 'problema'=>'Concepto ya existe' ] ;		
	}else
	{
		mysqli_query($link,"INSERT INTO miscelaneos_conceptos (concepto, monto, editar,articulo) VALUES ('$concepto', '$monto','$editar','$articulo')") or die ("NO GUARDO CONCEPTO".mysqli_error($link));	
		$json = ['isSuccessful' => TRUE ] ;
	}
}else
{
	$json = ['isSuccessful' => FALSE,'problema'=>'Ingrese un nombre de concepto valido' ] ;
}
echo json_encode($json);
?>