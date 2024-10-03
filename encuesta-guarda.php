<?php
session_start();
include_once ("conexion.php");
include_once ("inicia.php");
include_once("includes/funciones.php");
setlocale(LC_TIME, "spanish");
	date_default_timezone_set("America/Caracas");
$fecha_res = date("Y-m-d H:i:s");
$link = Conectarse();
$id_encuesta=desencriptar($_POST["idEnc"]);
$grado=$_SESSION['grado'];
$seccion=$_SESSION['seccion'];
$id_alum=$_SESSION['idAlum'];
$periodo=$_SESSION['nombre_periodo'];
$van=$_POST["van"];




for ($i=1; $i <=$van ; $i++) { 
	$simple = (isset($_POST['simple'.$i])) ? desencriptar($_POST['simple'.$i]) : '' ;
	$id_pregunta=(isset($_POST['id_pregunta'.$i])) ? desencriptar($_POST['id_pregunta'.$i]) : '' ;
	$comentario=(isset($_POST['comenta'.$i])) ? $_POST['comenta'.$i] : '' ;

	$respuestas_query=mysqli_query($link,"SELECT id_respuesta FROM encuesta_respuesta WHERE id_pregunta='$id_pregunta' and id_encuesta='$id_encuesta' and id_alum='$id_alum' ");
	if(mysqli_num_rows($respuestas_query) > 0)
	{$pasa=2;}else{$pasa=1;}
	if($simple>0 and $pasa==1){
		mysqli_query($link,"INSERT INTO encuesta_respuesta (id_encuesta,id_pregunta,id_alum,fecha_res,respuesta,comentario,grado,seccion,periodo) VALUES ('$id_encuesta', '$id_pregunta', '$id_alum','$fecha_res','$simple', '$comentario', '$grado','$seccion','$periodo' )") or die ("NO GUARDO ".mysqli_error());
	}
	for ($x=1; $x <=10 ; $x++) { 
		$multi = (isset($_POST['multi'.$i.$x])) ? desencriptar($_POST['multi'.$i.$x]) : '' ;
		if($multi>0 and $pasa==1){
			mysqli_query($link,"INSERT INTO encuesta_respuesta (id_encuesta,id_pregunta,id_alum,fecha_res,respuesta,comentario,grado,seccion,periodo) VALUES ('$id_encuesta', '$id_pregunta', '$id_alum','$fecha_res','$multi', '$comentario', '$grado','$seccion','$periodo' )") or die ("NO GUARDO ".mysqli_error());
		}	
	}
	$texto = (isset($_POST['texto'.$i])) ? $_POST['texto'.$i] : '' ;
	if (!empty($texto) and $pasa==1) {
		mysqli_query($link,"INSERT INTO encuesta_respuesta (id_encuesta,id_pregunta,id_alum,fecha_res,respuesta,comentario,grado,seccion,periodo) VALUES ('$id_encuesta', '$id_pregunta', '$id_alum','$fecha_res','$texto', '$comentario', '$grado','$seccion','$periodo' )") or die ("NO GUARDO ".mysqli_error());
	}
}
?>
<script language="Javascript">  
	window.location='encuesta-lista.php'; 
</script>

