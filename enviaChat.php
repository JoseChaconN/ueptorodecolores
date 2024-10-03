<?php 
session_start();
include_once 'conexion.php';
include_once 'includes/funciones.php';
$link = Conectarse();
$id_docente = desencriptar($_POST['idDoc']);
$idAlum = desencriptar($_POST['idAlu']);
$id_materia = desencriptar($_POST['idMate']);
$texto = $_POST['mensaje'];

if(!empty($id_docente))
{
	mysqli_query($link,"INSERT INTO chat (texto,envia,idAlum,id_docente,id_materia) VALUES ('$texto', '2', '$idAlum', '$id_docente', '$id_materia')") or die ("NO GUARDO MENSAJE".mysqli_error());

	$json = ['isSuccessful' => TRUE] ;
}else{ $json = ['isSuccessful' => TRUE ] ; }
echo json_encode($json);
 ?>