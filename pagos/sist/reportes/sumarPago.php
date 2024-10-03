<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
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
$idAlumno=($_POST['idAlum']);
$tablaPeriodo=$_POST['tabla'];
$suma_a_pagado=$_POST['suma'];
$grado=$_POST['grado'];
$pagado=$_POST['pagado'];
//$total=$_POST['total'];
$moros=$_POST['moros'];
//$=$_POST[''];
$morosida=number_format($moros-($pagado+$suma_a_pagado),2,'.',',');
$pago=number_format($pagado+$suma_a_pagado,2,'.',',').' $';
$idUser=$_SESSION['idUser'];
$nomUser=$_SESSION['nombreUser'];
$modificado='suma_a_pagado= '.$suma_a_pagado;
if($grado<61){
	$busca_query = mysqli_query($link,"SELECT suma_a_pagado FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlumno' ");
	while($row=mysqli_fetch_array($busca_query)) 
	{
		$suma_viejo=$row['suma_a_pagado'];
		$original='suma_a_pagado= '.$suma_viejo;
	}
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET suma_a_pagado='$suma_a_pagado' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO1 ".mysqli_error());
}else
{
	$busca_query = mysqli_query($link,"SELECT suma_a_pagado FROM matri".$tablaPeriodo." WHERE idAlumno='$idAlumno' ");
	while($row=mysqli_fetch_array($busca_query)) 
	{
		$suma_viejo=$row['suma_a_pagado'];
		$original='suma_a_pagado= '.$suma_viejo;
	}
	mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET suma_a_pagado='$suma_a_pagado' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO2 ".mysqli_error());
}
if($idUser>1){
	mysqli_query($link,"INSERT INTO bitacora (id_user,nombre_usuario,campo_original, campo_modificado,fecha,idAlum,tabla,grado ) VALUE ('$idUser','$nomUser','$original','$modificado','$hoy','$idAlumno','$tablaPeriodo','$grado' ) ") or die ("NO SE CREO ".mysqli_error($link));
}
$json = ['isSuccessful' => true, 'id'=>$idAlumno, 'tabla'=>$tablaPeriodo, 'grado'=>$grado,'moro'=>$morosida,'pago'=>$pago  ] ;

echo json_encode($json);
?>