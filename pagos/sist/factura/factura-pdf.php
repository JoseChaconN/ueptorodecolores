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
if($nroDebito>0 && $montoDebito>0)
{$fechaDebito=$_POST['fechaDebito'];}else{$fechaDebito=NULL;}
$bancoPagMov=$_POST['bancoPagMov'];
$montoPagMovil=$_POST['montoPagMovil'];
$nroPagMovil=$_POST['nroPagMovil'];
$fechaPagMovil=$_POST['fechaPagMovil'];
$emitidoPor=$_SESSION['idUser'];
$comentario=$_POST['comentario'];
$idAlum=desencriptar($_POST['idAlum']);
$alumno=$_POST['alumno'];
$cedula=$_POST['cedula'];
$nombreGrado=utf8_decode($_POST['nombreGrado']);
$grado=$_POST['grado'];
$tablaFactura=$_POST['tablaFactura'];
$totalPeriodo=$_POST['totalPeriodo'];
$pagado=$_POST['pagado'];
$morosida=$_POST['morosida'];
$periodo=$_POST['periodo'];
$id_quienPaga=desencriptar($_POST['quien_paga']);
$prontoPagText='';
$prontoPagMon=$_POST['prontoPagMonDol'];
if ($prontoPagMon>0) {
	$prontoPagText=$_POST['prontoPagText'];	
}
$nuevo_query = mysqli_query($link,"SELECT recibo as recibo FROM ingresos order by recibo desc limit 0,1  ");
$row=mysqli_fetch_array($nuevo_query);
$recibo=$row['recibo']+1;

mysqli_query($link,"INSERT INTO ingresos (recibo,tabla, fecha, fechaTransf, fechaDebito, fechaPagMovil ) VALUE ('$recibo', '$tablaFactura','$fechaHoy', '$fechaTransf', '$fechaDebito', '$fechaPagMovil' ) ") or die ("NO SE CREO ".mysqli_error($link));
#$nuevo_query = mysqli_query($link,"SELECT LAST_INSERT_ID(id) as nuevoCodigo FROM ingresos order by id desc limit 0,1  ");
#$row=mysqli_fetch_array($nuevo_query);
#$recibo=$row['nuevoCodigo'];
if($bancoTransf>0)
{
	mysqli_query($link,"INSERT INTO operaciones (idAlum,recibo,  banco,referencia,tipo,fecha,usuario,periodo ) VALUE ('$idAlum','$recibo','$bancoTransf','$nroTransf','3','$fechaTransf','$emitidoPor','$periodo' ) ") or die ("NO SE CREO ".mysqli_error($link));
}
if($bancoPagMov>0)
{
	mysqli_query($link,"INSERT INTO operaciones (idAlum,recibo,  banco,referencia,tipo,fecha,usuario,periodo ) VALUE ('$idAlum','$recibo','$bancoPagMov','$nroPagMovil','5','$fechaPagMovil','$emitidoPor','$periodo' ) ") or die ("NO SE CREO ".mysqli_error($link));
}

mysqli_query($link,"UPDATE emite_pago SET ced_reci='$reciboRif', nom_reci='$reciboNombre', dir_reci='$reciboDire' WHERE id='$id_quienPaga' ") or die ("NO ACTUALIZO ".mysqli_error($link));

$vanFP=0; $ref=''; $bancos=''; $pago=0;
for ($i=1; $i < 11; $i++) { 
	${'id_concepto'.$i}=$_POST['id_concepto'.$i];
	${'detalle'.$i}=$_POST['detalle'.$i];
	${'fpag'.$i}=$_POST['fpag'.$i];
	${'afecta'.$i}=$_POST['afecta'.$i];
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
		$montoDolar=${'montoDolar'.$i};
		mysqli_query($link,"INSERT INTO pagos".$tablaFactura." (idAlum,banco,nrodeposito,fechadepo,fecha,recibo,monto,id_concepto,concepto,operacion,montoDolar,montoTasa,emitidoPor,statusPago, comentario, prontoPagText,prontoPagMon ) VALUE ('$idAlum','$banco','$nrodeposito','$fechadepo','$fechaHoy','$recibo','$monto','$id_concepto','$concepto','$operacion','$montoDolar','$montoTasa','$emitidoPor','1','$comentario', '$prontoPagText','$prontoPagMon' ) ") or die ("NO SE CREO FACTURA".mysqli_error($link));	
		$vanFP++;
		if(${'afecta'.$i}=='S'){
			$pagado=$pagado+${'montoDolar'.$i};
			$pago=$pago+${'montoDolar'.$i};
			$morosida=$morosida-${'montoDolar'.$i};
		}
	}
}


$fpag='';
for ($i=1; $i <= $vanFP ; $i++) { 
	$operacion=${'fpag'.$i};
	$forma_query = mysqli_query($link,"SELECT abrev FROM formas_pago WHERE id='$operacion' ");	
	while($row=mysqli_fetch_array($forma_query)) 
	{
		$fpag.=$row['abrev'].',';
	}
}
if($grado<60)
{
	mysqli_query($link,"UPDATE notaprimaria".$tablaFactura." SET totalPeriodo='$totalPeriodo', pagado='$pagado', morosida='$morosida' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error($link));
}else
{
	mysqli_query($link,"UPDATE matri".$tablaFactura." SET totalPeriodo='$totalPeriodo', pagado='$pagado', morosida='$morosida' WHERE idAlumno='$idAlum' ") or die ("NO ACTUALIZO ".mysqli_error($link));
}
$van1 = ($vanFP>5) ? 5 : $vanFP ;
$van2 = ($vanFP>5) ? $vanFP-5 : 0 ;

$recibo=encriptar($recibo);
mysqli_free_result($forma_query);
header("location:factura-reimprime-pdf.php?recibo=$recibo"); 

	
?>
