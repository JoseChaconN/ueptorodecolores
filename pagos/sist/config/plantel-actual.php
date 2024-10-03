<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once("../../include/funciones.php");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$link = Conectarse();
$nkxs=encriptar($_POST['nkxs']);
$ckls=encriptar($_POST['ckls']);
$ekks=encriptar($_POST['ekks']);
$rifcolm=encriptar($_POST['rifcolm']);
$ciudadm=encriptar($_POST['ciudadm']);
$estadom=encriptar($_POST['estadom']);
$direccm=encriptar($_POST['direccm']);
$sucorreo=encriptar($_POST['sucorreo']);
$correom=encriptar($_POST['correom']);
$clavemail=encriptar($_POST['clavemail']);
$telefono=encriptar($_POST['telefono']);
$dominio=encriptar($_POST['dominio']);
$tasa=$_POST['tasa'];
$foto=$_POST['ckls'];
$administra=$_POST['administra'];
$ced_admin=$_POST['ced_admin'];
//$=encriptar($_POST['']);
if(!empty($_FILES['logoPlantel']["name"]))
{
	$logoPlantel=addslashes(file_get_contents($_FILES['logoPlantel']['tmp_name']));
	$nombrearchivo = $_FILES["logoPlantel"]["name"];
	$nombreruta = $_FILES["logoPlantel"]["tmp_name"];
	$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
	$formatos = array('.jpg','.jpeg','.png' );
	$ruta = "$foto$ext";
	$guardaRutaAlu='../img/'.$ruta;
}
if(!empty($_FILES['logoPlantel']["name"]))
{
	mysqli_query($link,"UPDATE colegio SET logoPlantel='$ruta' WHERE id = '1'");
	move_uploaded_file($nombreruta, $guardaRutaAlu);
}
mysqli_query($link,"UPDATE colegio SET nkxs='$nkxs', ckls='$ckls', ekks='$ekks', rifcolm='$rifcolm', ciudadm='$ciudadm', estadom='$estadom', direccm='$direccm', sucorreo='$sucorreo', correom='$correom', clavemail='$clavemail', telefono='$telefono', dominio='$dominio', tasa='$tasa', administra='$administra', ced_admin='$ced_admin' WHERE id = '1'") or die ("NO ACTUALIZO PLANTEL ".mysqli_error($link));
header("location:plantel-perfil.php"); 
/*echo "<script type='text/javascript'>  
		window.location='plantel-perfil.php';
  		</script>";*/
?>