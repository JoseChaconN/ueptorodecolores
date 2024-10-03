<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../include/conectar.php');
	include_once ('../../include/funciones.php');
}
$link = Conectarse();

$id=($_POST['id']);

mysqli_query($link,"DELETE FROM conceptos WHERE id = '$id' ");

$json = ['isSuccessful' => TRUE] ;
echo json_encode($json);
?>