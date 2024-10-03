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
$fechaHoy = date("Y-m-d H:i:s");
$link = Conectarse();
$idAlumno=desencriptar($_POST['idAlum']);
$tablaPeriodo=$_POST['tabla'];
$suma_a_pagado=$_POST['suma'];
$grado=$_POST['grado'];
$pagado=$_POST['pagado'];
$total=$_POST['total'];
$morosida=number_format($total-($pagado+$suma_a_pagado),2,'.',',').' $';
$pago=number_format($pagado+$suma_a_pagado,2,'.',',').' $';

if($grado<61){
	mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET suma_a_pagado='$suma_a_pago' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO1 ".mysqli_error());
}else
{
	mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET suma_a_pagado='$suma_a_pagado' WHERE idAlumno='$idAlumno' ") or die ("NO ACTUALIZO2 ".mysqli_error());
}
$json = ['isSuccessful' => true ] ;
echo json_encode($json);
?>