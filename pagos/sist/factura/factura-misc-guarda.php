<?php
session_start();

if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
$totalReciboDolar=$_POST['totalReciboDolar'];

if($totalReciboDolar==0)
{
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
}
$link = Conectarse();
include_once("../../include/funciones.php");
include_once("../../../inicia.php");
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaHoy = date("Y-m-d H:i:s");
$reciboNombre=$_POST['reciboNombre'];
$reciboRif=$_POST['reciboRif'];
$reciboDire=$_POST['reciboDire'];
$operador=$_SESSION['nombreUser'];
$montoTasa=$_POST['tasaDolar'];
$totalReciboBs=$_POST['totalReciboBs'];
$bancoTransf=$_POST['bancoTransf'];
$montoTransf=$_POST['montoTransf'];
$nroTransf=$_POST['nroTransf'];
$fechaTransf=$_POST['fechaTransf'];
$bancoDebito=$_POST['bancoDebito'];
$montoDebito=$_POST['montoDebito'];
$nroDebito=$_POST['nroDebito'];
$fechaDebito=$_POST['fechaDebito'];
$bancoPagMov=$_POST['bancoPagMov'];
$montoPagMovil=$_POST['montoPagMovil'];
$nroPagMovil=$_POST['nroPagMovil'];
$fechaPagMovil=$_POST['fechaPagMovil'];
$emitidoPor=$_SESSION['idUser'];
$comentario=$_POST['comentario'];
$idAlum=desencriptar($_POST['idAlum']);
$alumno=$_POST['alumno'];
$cedula=$_POST['cedula'];
$nombreGrado=($_POST['nombreGrado']);
$grado=$_POST['grado'];
$tabla=$_POST['tablaPeriodo'];
/*$totalPeriodo=$_POST['totalPeriodo'];
$pagado=$_POST['pagado'];
$morosida=$_POST['morosida'];
$periodo=$_POST['periodo'];*/
$salida=$_POST['salida'];


mysqli_query($link,"INSERT INTO miscelaneos_ingresos (tabla,fecha, fechaTransf, fechaDebito, fechaPagMovil ) VALUE ('$tabla', '$fechaHoy', '$fechaTransf', '$fechaDebito', '$fechaPagMovil' ) ") or die ("NO SE CREO ".mysqli_error($link));
$nuevo_query = mysqli_query($link,"SELECT LAST_INSERT_ID(id) as nuevoCodigo FROM miscelaneos_ingresos order by id desc limit 0,1  ");
$row=mysqli_fetch_array($nuevo_query);
$recibo=$row['nuevoCodigo'];
if($bancoTransf>0)
{
	mysqli_query($link,"INSERT INTO operaciones (idAlum,recibo,  banco,referencia,tipo,fecha,usuario,periodo ) VALUE ('$idAlum','$recibo','$bancoTransf','$nroTransf','3','$fechaTransf','$emitidoPor','$periodo' ) ") or die ("NO SE CREO ".mysqli_error($link));
}
if($bancoPagMov>0)
{
	mysqli_query($link,"INSERT INTO operaciones (idAlum,recibo,  banco,referencia,tipo,fecha,usuario,periodo ) VALUE ('$idAlum','$recibo','$bancoPagMov','$nroPagMovil','5','$fechaPagMovil','$emitidoPor','$periodo' ) ") or die ("NO SE CREO ".mysqli_error($link));
}
$vanFP=0; $ref=''; $bancos=''; $pago=0;
for ($i=1; $i < 11; $i++) { 
	${'id_concepto'.$i}=$_POST['id_concepto'.$i];
	${'detalle'.$i}=$_POST['detalle'.$i];
	${'fpag'.$i}=$_POST['fpag'.$i];
	${'cant'.$i}=$_POST['cant'.$i];
	${'banco'.$i}=''; ${'nrodeposito'.$i}=''; ${'fechadepo'.$i}='';
	if (${'fpag'.$i}=='3') {
		${'banco'.$i}=$bancoTransf;
		${'nrodeposito'.$i}=$nroTransf;
		${'fechadepo'.$i}=$fechaTransf;
		$ref.=$nroTransf;
		$bancos.=$bancoTransf;
	}
	if (${'fpag'.$i}=='4') {
		${'banco'.$i}=$bancoDebito;
		${'nrodeposito'.$i}=$nroDebito;
		${'fechadepo'.$i}=$fechaDebito;
		$ref.=$nroDebito;
		$bancos.=$bancoDebito;
	}
	if (${'fpag'.$i}=='5') {
		${'banco'.$i}=$bancoPagMov;
		${'nrodeposito'.$i}=$nroPagMovil;
		${'fechadepo'.$i}=$fechaPagMovil;
		$ref.=$nroPagMovil;
		$bancos.=$bancoPagMov;
	}
	${'montoDolar'.$i} = (isset($_POST['montoDolar'.$i])) ? $_POST['montoDolar'.$i] : 0 ;
	${'montoBs'.$i}=$_POST['montoBs'.$i];

	if(${'montoDolar'.$i}>0)
	{
		$banco=${'banco'.$i};
		$nrodeposito=${'nrodeposito'.$i};
		$fechadepo=${'fechadepo'.$i};
		$monto=${'montoBs'.$i};
		$id_concepto=${'id_concepto'.$i};
		$concepto=${'detalle'.$i};
		$operacion=${'fpag'.$i};
		$cantidad=${'cant'.$i};
		$montoDolar=${'montoDolar'.$i};
		mysqli_query($link,"INSERT INTO miscelaneos (idAlum,banco,nrodeposito,fechadepo,fecha,recibo,monto,id_concepto,concepto,operacion,montoDolar,montoTasa,emitidoPor,statusPago, comentario,proceso,cantidad ) VALUE ('$idAlum','$banco','$nrodeposito','$fechadepo','$fechaHoy','$recibo','$monto','$id_concepto','$concepto','$operacion','$montoDolar','$montoTasa','$emitidoPor','1','$comentario','2','$cantidad' ) ") or die ("NO SE CREO FACTURA".mysqli_error());	
		$vanFP++;
	}
}

$van1 = ($vanFP>5) ? 5 : $vanFP ;
$van2 = ($vanFP>5) ? $vanFP-5 : 0 ;

$recibo=encriptar($recibo);
header("location:factura-reimprime-misc-pdf.php?recibo=$recibo&sale=$salida"); 

	
?>
