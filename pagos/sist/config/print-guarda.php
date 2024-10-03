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
$nombre=$_POST['nombre'];
$superior_FIS=$_POST['sup_fis'];
$izquierdo_FIS=$_POST['izq_fis'];
$copia_FIS=$_POST['cop_fis'];
$superior_HB=$_POST['sup_hb'];
$izquierdo_HB=$_POST['izq_hb'];
$copia_HB=$_POST['cop_hb'];
if(!empty($nombre))
{
	mysqli_query($link,"INSERT INTO impresora (nombre, superior_FIS, izquierdo_FIS, copia_FIS, superior_HB, izquierdo_HB, copia_HB, status) VALUES ('$nombre', '$superior_FIS','$izquierdo_FIS','$copia_FIS','$superior_HB','$izquierdo_HB','$copia_HB','1')") or die ("NO GUARDO IMPRESORA".mysqli_error($link));	
	$json = ['isSuccessful' => TRUE ] ;
}else
{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>