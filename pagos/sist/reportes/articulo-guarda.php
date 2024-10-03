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
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d");
$concepto=$_POST['cocepNuevo'];
$monto=$_POST['montoNuevo'];
$editar=$_POST['editarNuevo'];
$publicar=$_POST['publicarNuevo'];
$foto_articulo=addslashes(file_get_contents($_FILES['fotoNuevo']['tmp_name']));
$nombrearchivo = $_FILES["fotoNuevo"]["name"];
$nombreruta = $_FILES["fotoNuevo"]["tmp_name"];
$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
$formatos = array('.jpg','.jpeg','.png' );
$ruta = time().mt_rand(0, 999).'_'."$nombrearchivo";
$guardaRutaAlu='../../../fotoArticulos/'.$ruta;
if(!empty($concepto))
{
	$conceptos_query = mysqli_query($link,"SELECT concepto FROM miscelaneos_conceptos WHERE concepto='$concepto' ");
	if(mysqli_num_rows($conceptos_query) > 0)
	{
		echo "<script type='text/javascript'>                                
		      	window.location='inventario-list.php?fail';
		  	  </script>";		
	}else
	{
		mysqli_query($link,"INSERT INTO miscelaneos_conceptos (concepto, monto, editar,articulo,publicar,foto_articulo) VALUES ('$concepto', '$monto','$editar','1','$publicar','$ruta')") or die ("NO GUARDO CONCEPTO".mysqli_error($link));	
		if(!empty($foto_articulo) && in_array($ext, $formatos))
		{
			move_uploaded_file($nombreruta, $guardaRutaAlu);
		}
		echo "<script type='text/javascript'>                                
		      	window.location='inventario-list.php?nuevo';
		  	  </script>";
	}
}else
{
	echo "<script type='text/javascript'>                                
		    window.location='inventario-list.php?fail';
		  </script>";
}
?>