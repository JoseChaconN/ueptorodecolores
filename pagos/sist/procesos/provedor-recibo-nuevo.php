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

$recibo_query = mysqli_query($link,"SELECT id_recibo FROM egresos_nro ORDER BY id_recibo DESC LIMIT 1 ");
$recibo=1;
while($row = mysqli_fetch_array($recibo_query))
{
	$recibo=$row['id_recibo']+1;
}
$id_provee=desencriptar($_POST['idAlum']);
//bancos segun la operacion
$bancoTransf=$_POST['bancoTransf'];
$nroTransf=$_POST['nroTransf'];
$fechaTransf=$_POST['fechaTransf'];
$bancoDebito=$_POST['bancoDebito'];
$nroDebito=$_POST['nroDebito'];
$fechaDebito=$_POST['fechaDebito'];
$bancoPagMov=$_POST['bancoPagMov'];
$nroPagMovil=$_POST['nroPagMovil'];
$fechaPagMovil=$_POST['fechaPagMovil'];
$fecha_egreso = $_POST['fechaRecibo'];  //date("Y-m-d");
$comentario=$_POST['comentario'];
$tasaDolar=$_POST['tasaDolar'];
$emitidoPor=$_SESSION['idUser'];
$fecha_registro = $fecha_egreso.' '.date("H:i:s");
if($_POST['formato']==2)
{
	$desde=$_POST['desde'];
	$hasta=$_POST['hasta'];	
}else
{
	$desde=NULL;
	$hasta=NULL;
}
$enviar = (isset($_POST["enviar"])) ? $_POST["enviar"] : '' ;
$copia = (isset($_POST["copia"])) ? $_POST["copia"] : '2' ;
$cuentaEgreso=$_POST['cuentaEgreso'];
mysqli_query($link,"INSERT INTO egresos_nro (fecha,desde,hasta,cuentaEgreso) VALUE ('$fecha_registro','$desde','$hasta','$cuentaEgreso' ) ") or die ("NO SE CREO EGRESO_NRO".mysqli_error());
$vanFP=0; $ref=''; $bancos=''; $pago=0;
for ($i=1; $i < 11; $i++) { 
	${'id_concepto'.$i}=$_POST['id_concepto'.$i];
	${'concepto_pago'.$i}=$_POST['detalle'.$i];
	${'operacion'.$i}=$_POST['fpag'.$i];
	${'banco'.$i}=''; ${'refePag'.$i}=''; ${'fecha_depo'.$i}='';
	if (${'operacion'.$i}=='3') {
		${'banco'.$i}=$bancoTransf;
		${'refePag'.$i}=$nroTransf;
		${'fecha_depo'.$i}=$fechaTransf;
		$ref.=$nroTransf;
		$bancos.=$bancoTransf;
	}
	if (${'operacion'.$i}=='4') {
		${'banco'.$i}=$bancoDebito;
		${'refePag'.$i}=$nroDebito;
		${'fecha_depo'.$i}=$fechaDebito;
		$ref.=$nroDebito;
		$bancos.=$bancoDebito;
	}
	if (${'operacion'.$i}=='5') {
		${'banco'.$i}=$bancoPagMov;
		${'refePag'.$i}=$nroPagMovil;
		${'fecha_depo'.$i}=$fechaPagMovil;
		$ref.=$nroPagMovil;
		$bancos.=$bancoPagMov;
	}
	${'montoDolar'.$i} = (isset($_POST['montoDolar'.$i])) ? $_POST['montoDolar'.$i] : 0 ;
	${'montoBs'.$i} = (isset($_POST['montoBs'.$i])) ? $_POST['montoBs'.$i] : 0 ;
	
	if(${'montoDolar'.$i}>0)
	{
		$banco=${'banco'.$i};
		$refePag=${'refePag'.$i};
		$fecha_depo=${'fecha_depo'.$i};
		$montoBs=${'montoBs'.$i};
		$id_concepto=${'id_concepto'.$i};
		$concepto_pago=${'concepto_pago'.$i};
		$operacion=${'operacion'.$i};
		$montoDolar=${'montoDolar'.$i};
		mysqli_query($link,"INSERT INTO egresos (id_provee,recibo,banco,refePag,fecha_depo,fecha_egreso,montoBs,id_concepto,concepto_pago,comentario,operacion,montoDolar,tasaDolar,emitidoPor,status_egreso, cuentaEgreso ) VALUE ('$id_provee','$recibo','$banco','$refePag','$fecha_depo','$fecha_egreso','$montoBs','$id_concepto','$concepto_pago','$comentario','$operacion','$montoDolar','$tasaDolar','$emitidoPor','1','$cuentaEgreso' ) ") or die ("NO SE CREO EGRESO".mysqli_error());	
		$vanFP++;
	}
}
mysqli_query($link,"UPDATE tasa_dia SET cuentaEgreso='$cuentaEgreso' WHERE idTasa=1 ") or die ("NO ACTUALIZO ".mysqli_error());
$van1 = ($vanFP>5) ? 5 : $vanFP ;
$van2 = ($vanFP>5) ? $vanFP-5 : 0 ;
mysqli_free_result($recibo_query);
$recibo=encriptar($recibo);
header("location:provedor-recibo-pdf.php?recibo=$recibo&envia=$enviar&cop=$copia"); 
?>
