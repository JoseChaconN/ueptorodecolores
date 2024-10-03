<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once ('../../include/funciones.php');
}
$link = Conectarse();
$id=($_POST['id']);
$concepto=$_POST['conceptoVer'];
$nombreViejo=$_POST['conceptoViejoVer'];
$monto=$_POST['montoVer'];
$editar=$_POST['editarVer'];
$publicar=$_POST['publicarVer'];
$fotoViejaVer='../../../fotoArticulos/'.$_POST['fotoViejaVer'];
$foto_articulo=addslashes(file_get_contents($_FILES['fotoVer']['tmp_name']));
$nombrearchivo = $_FILES["fotoVer"]["name"];
$nombreruta = $_FILES["fotoVer"]["tmp_name"];
$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
$formatos = array('.jpg','.jpeg','.png' );
$ruta = time().mt_rand(0, 999).'_'."$nombrearchivo";
$guardaRutaAlu='../../../fotoArticulos/'.$ruta;
if ($concepto!=$nombreViejo) {
	$conceptos_query = mysqli_query($link,"SELECT concepto FROM miscelaneos_conceptos WHERE concepto='$concepto' ");
	if(mysqli_num_rows($conceptos_query) > 0)
	{
		echo "<script type='text/javascript'>                                
		      	window.location='inventario-list.php?existe';
		  	  </script>";			
	}else
	{
		mysqli_query($link,"UPDATE miscelaneos_conceptos SET concepto='$concepto', monto='$monto',editar='$editar',publicar='$publicar' WHERE id = '$id' ");
		if(!empty($foto_articulo) && in_array($ext, $formatos))
		{
			mysqli_query($link,"UPDATE miscelaneos_conceptos SET foto_articulo='$ruta' WHERE id = '$id' ");
			unlink($fotoViejaVer);
			move_uploaded_file($nombreruta, $guardaRutaAlu);
		}
		echo "<script type='text/javascript'>                                
		      	window.location='inventario-list.php?nuevo';
		  	  </script>";
	}
}else
{
	mysqli_query($link,"UPDATE miscelaneos_conceptos SET concepto='$concepto', monto='$monto',editar='$editar',publicar='$publicar' WHERE id = '$id' ");
	if(!empty($foto_articulo) && in_array($ext, $formatos))
	{
		mysqli_query($link,"UPDATE miscelaneos_conceptos SET foto_articulo='$ruta' WHERE id = '$id' ");
		unlink($fotoViejaVer);
		move_uploaded_file($nombreruta, $guardaRutaAlu);
	}
	echo "<script type='text/javascript'>                                
		    window.location='inventario-list.php?nuevo';
		  </script>";
}?>