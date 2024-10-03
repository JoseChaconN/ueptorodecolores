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
$usuarioNulo=$_SESSION['idUser'];

$recibo=$_POST['recib'];
$tablaPeriodo=$_POST['tabla'];
$motivo=$_POST['motivo'];
$grado=$_POST['grado'];
$monto_query = mysqli_query($link,"SELECT A.idAlum,A.montoDolar FROM pagos".$tablaPeriodo." A, conceptos B WHERE recibo ='$recibo' and B.afecta='S' and A.id_concepto=B.id ");
$restaPagado=0;
while ($row = mysqli_fetch_array($monto_query))
{
    $idAlum=$row['idAlum'];
    $restaPagado=$restaPagado+$row['montoDolar'];
}
mysqli_query($link,"UPDATE pagos".$tablaPeriodo." SET statusPago='2',comentario='$motivo', fechaNulo='$fechaHoy', usuarioNulo='$usuarioNulo' WHERE recibo='$recibo' ") or die ("NO ACTUALIZO ".mysqli_error());
if($restaPagado>0)
{
	if($grado<61)
	{
		$alumno_query = mysqli_query($link,"SELECT pagado,morosida FROM notaprimaria".$tablaPeriodo." WHERE idAlumno ='$idAlum' ");
		$row2=mysqli_fetch_array($alumno_query);
		$pagado=$row2['pagado']-$restaPagado;
		$morosida=$row2['morosida']+$restaPagado;
		mysqli_query($link,"UPDATE notaprimaria".$tablaPeriodo." SET pagado='$pagado',morosida='$morosida' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error());
	}else
	{
		$alumno_query = mysqli_query($link,"SELECT pagado,morosida FROM matri".$tablaPeriodo." WHERE idAlumno ='$idAlum' ");
		$row2=mysqli_fetch_array($alumno_query);
		$pagado=$row2['pagado']-$restaPagado;
		$morosida=$row2['morosida']+$restaPagado;
		mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET pagado='$pagado',morosida='$morosida' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error());
	}
}
$json = ['isSuccessful' => true ] ;

echo json_encode($json);
?>