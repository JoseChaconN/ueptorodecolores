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
$idUser=$_POST['idUser'];
$buscarAlumno= mysqli_query($link,"SELECT activoUser FROM user WHERE idUser = '$idUser'");
while($row=mysqli_fetch_array($buscarAlumno))
{ $status=$row['activoUser']; }
$status = ($status=='1') ? '2' : '1' ;
mysqli_query($link,"UPDATE user SET activoUser='$status' WHERE idUser = '$idUser' ");
$json = ['isSuccessful' => TRUE , 'status' => $status ] ;
echo json_encode($json);
?>