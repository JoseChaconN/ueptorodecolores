<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
include_once ("conexion.php");
include_once ("inicia.php");
include_once("includes/funciones.php");
////////////////////DATOS DEL ALUMNO/////////////////////
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$usuario = $_SESSION['usuario'];
$foto_alu = (empty($_FILES['foto_alu']['tmp_name'])) ? '' : addslashes(file_get_contents($_FILES['foto_alu']['tmp_name']));	
$nombrearchivo = $_FILES["foto_alu"]["name"];
$nombreruta = $_FILES["foto_alu"]["tmp_name"];
$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
$formatos = array('.jpg','.jpeg','.png' );
$ruta = "$ced_alu$ext";
$guardaRutaAlu='fotoalu/'.$ruta;	
$link = conectarse();
if(!empty($foto_alu) && in_array($ext, $formatos))
{
	move_uploaded_file($nombreruta, $guardaRutaAlu);
	mysqli_query($link,"UPDATE alumcer SET ruta='$ruta' WHERE cedula = '$usuario'");
}

$_SESSION['fotoAlum']=$ruta;
header("location:boletas.php"); 

?> 
