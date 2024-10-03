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
$tasa=$_POST['tasa'];
$montoDiv=$_POST['mes'];
$montoXmes=number_format(($montoDiv*$tasa),2,'.',',');
mysqli_query($link,"UPDATE tasa_dia SET monto='$tasa' WHERE idTasa='1' ") or die ("NO ACTUALIZO ".mysqli_error());
$json = ['isSuccessful' => TRUE,'montoBs'=>$montoXmes];
echo json_encode($json);
?>