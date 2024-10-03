<?php
session_start();
include_once ('conexion.php');
include_once ('includes/funciones.php');
$link = Conectarse();
$idVideo=desencriptar($_POST['idVid']);
$idAlum=$_SESSION['idAlum'];

$vistos_query = mysqli_query($link,"SELECT statusVideo FROM vio_video WHERE idVideo='$idVideo' and idAlum='$idAlum' ");
if(mysqli_num_rows($vistos_query) > 0)
{
	/*$row2=mysqli_fetch_array($vistos_query);
	$statusVideo = ($row2['statusVideo']=='1') ? '2' : '1' ;
	
	mysqli_query($link,"UPDATE vio_video SET statusVideo='$statusVideo' WHERE idVideo='$idVideo' ") or die ("NO ACTUALIZO VISTA ".mysqli_error());*/
}else
{
	mysqli_query($link,"INSERT INTO vio_video (idVideo,idAlum,statusVideo) VALUE ('$idVideo','$idAlum','1' ) ") or die ("NO SE CREO ".mysqli_error());
}

$json = ['isSuccessful' => TRUE ] ;
echo json_encode($json);
?>