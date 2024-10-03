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

$id=($_POST['id']);
$concepto=$_POST['concepto'];
$nombreViejo=$_POST['viejo'];
$monto=$_POST['monto'];
$editar=$_POST['edita'];
$articulo=$_POST['arti'];
$monto=number_format($monto,2,'.',',');
if ($concepto!=$nombreViejo) {
	$conceptos_query = mysqli_query($link,"SELECT concepto FROM miscelaneos_conceptos WHERE concepto='$concepto' ");
	if(mysqli_num_rows($conceptos_query) > 0)
	{
		$json = ['isSuccessful' => FALSE, 'problema'=>'Concepto ya existe' ] ;		
	}else
	{
		mysqli_query($link,"UPDATE miscelaneos_conceptos SET concepto='$concepto', monto='$monto',editar='$editar',articulo='$articulo' WHERE id = '$id' ");
		$json = ['isSuccessful' => TRUE, 'conc'=>$concepto, 'monto'=>$monto ] ;
	}
}else
{
	mysqli_query($link,"UPDATE miscelaneos_conceptos SET concepto='$concepto', monto='$monto',editar='$editar',articulo='$articulo' WHERE id = '$id' ");
	$json = ['isSuccessful' => TRUE, 'conc'=>$concepto, 'monto'=>$monto ] ;
}

echo json_encode($json);
?>