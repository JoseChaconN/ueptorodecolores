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
$id_grado=$_POST['grado'];
$tablaPeriodo=$_POST['tablaPeriodo'];
for ($i=1; $i < 14; $i++) 
{  
	$mes=$_POST['mes'.$i];
	$fecha_vence=$_POST['fecha'.$i];
	$monto=$_POST['monto'.$i];
	$insc = ($i==1) ? 'X' : '' ;
	if($monto>0 && !empty($fecha_vence))
	{
		mysqli_query($link,"INSERT INTO montos".$tablaPeriodo." (id_grado, mes, monto, fecha_vence,insc) VALUES ('$id_grado', '$mes', '$monto','$fecha_vence','$insc')") or die ("NO GUARDO MES".mysqli_error($link));	
	}
}
echo 'ok';
?>