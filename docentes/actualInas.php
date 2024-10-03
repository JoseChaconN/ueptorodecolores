<?php 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$lap = $_SESSION['lapsoActivo'];
include_once '../conexion.php';
include_once '../includes/funciones.php';
$link = Conectarse();
$ced_alu = $_POST['ced'];
$cod_materia = $_POST['mate'];
$inas = $_POST['inas'];
$corte = $_POST['corte'];

if(!empty($ced_alu))
{
	$cortes_query=mysqli_query($link,"SELECT id FROM cortes".$tablaPeriodo." WHERE ced_alu='$ced_alu' and cod_materia='$cod_materia' ");
	if(mysqli_num_rows($cortes_query) > 0)
	{
		$row2=mysqli_fetch_array($cortes_query);
		$id=$row2['id'];
		mysqli_query($link,"UPDATE cortes".$tablaPeriodo." SET inas$corte$lap='$inas' WHERE id='$id'") or die ("NO SE ACTUALIZO".mysqli_error());	
	}else
	{
		mysqli_query($link,"INSERT INTO cortes".$tablaPeriodo." (ced_alu,inas$corte$lap, cod_materia) VALUES ('$ced_alu','$inas','$cod_materia')") or die ("NO SE GUARDO".mysqli_error());
	}

	$json = ['isSuccessful' => TRUE,] ;
	
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>