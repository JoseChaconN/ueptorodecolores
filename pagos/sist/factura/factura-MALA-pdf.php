<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include ('../include/sesion.php');
}else
{
	include ('../../include/conectar.php');
}
$totalReciboDolar=$_POST['totalReciboDolar'];
if($totalReciboDolar==0)
{
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
} ?>
<!--script type="text/javascript">
	//opener.document.location.reload();
</script--><?php

include("../../include/funciones.php");
include("../../include/inicia.php");
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
/*$fechaHoy = date("Y-m-d H:i:s");
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
$nombreGrado=$_POST['nombreGrado'];
$tablaFactura=$_POST['tablaFactura'];
$totalPeriodo=$_POST['totalPeriodo'];
$pagado=$_POST['pagado'];
$morosida=$_POST['morosida'];*/
//mysqli_query($link,"INSERT INTO ingresos (tabla, fecha ) VALUE ('$tablaFactura','$fechaHoy' ) ") or die ("NO SE CREO ".mysqli_error());
//$nuevo_query = mysqli_query($link,"SELECT LAST_INSERT_ID(id) as nuevoCodigo FROM ingresos order by id desc limit 0,1  ");
/*$row=mysqli_fetch_array($nuevo_query);
$recibo=$row['nuevoCodigo'];
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
		//mysqli_query($link,"INSERT INTO pagos".$tablaFactura." (idAlum,banco,nrodeposito,fechadepo,fecha,recibo,monto,id_concepto,concepto,operacion,montoDolar,montoTasa,emitidoPor,statusPago, comentario ) VALUE ('$idAlum','$banco','$nrodeposito','$fechadepo','$fechaHoy','$recibo','$monto','$id_concepto','$concepto','$operacion','$montoDolar','$montoTasa','$emitidoPor','1','$comentario' ) ") or die ("NO SE CREO FACTURA".mysqli_error());	
		$vanFP++;
		if(${'afecta'.$i}=='S'){
			$pagado=$pagado+${'montoDolar'.$i};
			$pago=$pago+${'montoDolar'.$i};
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

$van1 = ($vanFP>5) ? 5 : $vanFP ;
$van2 = ($vanFP>5) ? $vanFP-5 : 0 ;*/
require('../include/fpdf/fpdf.php');

class PDF extends FPDF 
{
	function Header()
	{
		/*$this->Image('..//img/logo.png',10,8,20);
		$this->SetFont('Arial','',15);
		$this->Cell(80);
		$this->Cell(30,6,utf8_decode(NKXS),0,1,'C');
		$this->Cell(80);
		$this->Cell(30,6,utf8_decode(EKKS),0,1,'C');
		$this->Cell(80);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');
		$this->Cell(80);
		$this->Cell(30,5,'Rif.: '.RIFCOLM.' - Telefono '.TELEFONO,0,1,'C');
		$this->Ln(6);
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','B',13);
		$this->Cell(190,6, 'Cambios de cedula realizados en el sistema',0,1,'C');
		$this->SetFont('Arial','B',9);
		$this->Cell(30,6, 'Ced.Actual',1,0,'C',1);
		$this->Cell(30,6, 'Ced.Vieja',1,0,'C',1);
		$this->Cell(70,6, 'Estudiante',1,0,'C',1);
		$this->Cell(30,6, 'En Fecha',1,0,'C',1);
		$this->Cell(35,6, 'Procesado por',1,1,'C',1);*/
		
	}
	function Footer()
	{
		/*$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');*/
	}
}

$mgIzq=40;
$mgSup=0;

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);

/*$fecha = date("d-m-Y");
$hora = date("H:i");*/

/*$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',10);*/
/*for ($i=0; $i <2 ; $i++) { 
	$pdf->SetFont('Arial','B',10);
	$pdf->Ln($mgSup);
	$pdf->setX($mgIzq);
	$pdf->Cell(100,4, NKXS.' '.EKKS,0,1,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->Cell(100,4, DIRECCM.' RIF.'.RIFCOLM,0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(100,4, CIUDADM,0,0,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(30,4, 'Factura',0,1,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->Cell(100,4, TELEFONO,0,0,'L');
	$pdf->SetFont('Arial','B',9);
	if($van2>0)
	{ $pdf->Cell(30,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT).' (1/2)',0,1,'L');}else 
	{ $pdf->Cell(30,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,1,'L');}
	$pdf->SetFont('Arial','',9);
	$pdf->Ln(2);
	$pdf->SetFont('Arial','',8);
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, utf8_decode('Nombre o Razón Social:'.$reciboNombre),0,0,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(30,3, utf8_decode('Fecha: '.$fecha),0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, utf8_decode('C.I./RIF: '.$reciboRif),0,0,'L');
	$pdf->Cell(30,3, utf8_decode('Hora: '.$hora),0,1,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setX($mgIzq);
	$pdf->MultiCell(130,3, utf8_decode('Domicilio Fiscal: '.$reciboDire),0,'J');
	$pdf->SetFont('Arial','',9);
		
	$pdf->Ln(1);
	$pdf->setX($mgIzq);
	$pdf->Cell(10,4, utf8_decode('Cod.'),1,0,'C');
	$pdf->Cell(90,4, utf8_decode('Descripción del Producto o Servicio '),1,0,'C');
	$pdf->Cell(30,4, utf8_decode('Monto'),1,1,'C');
	$pdf->SetFont('Arial','',9);
	$subTot1=0; $subTot2=0;
	for ($b=1; $b <= $van1; $b++) { 
		$pdf->setX($mgIzq);
		$pdf->Cell(10,4, str_pad(${'id_concepto'.$b}, 2, "0", STR_PAD_LEFT) ,0,0,'C');
		$pdf->Cell(90,4, utf8_decode(${'detalle'.$b}),0,0,'L');
		$pdf->Cell(30,4,${'montoBs'.$b} ,0,1,'R');
		$subTot1=$subTot1+${'montoBs'.$b};
	}
	if ($van1<5) {
		for ($c=$van1; $c <=5 ; $c++) { 
			$pdf->setX($mgIzq);
			$pdf->Cell(10,4, '',0,0,'C');
			$pdf->Cell(90,4, '',0,0,'L');
			$pdf->Cell(30,4,'',0,1,'R');		
		}
	}
	$pdf->SetFont('Arial','',8);
	$pdf->Ln(1);
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, 'Estudiante: '.$alumno ,0,0,'L');
	$pdf->Cell(20,3, 'Sub Total Bs. ',0,0,'R');
	$pdf->Cell(10,3, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, 'C.I.: '.$cedula.' '.$nombreGrado,0,0,'L');
	$pdf->Cell(20,3, 'Monto Pagado Bs. ',0,0,'R');
	$pdf->Cell(10,3, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, 'Forma de Pago: '.$fpag ,0,0,'L');
	$pdf->Cell(20,3, 'Deducible Bs. ' ,0,0,'R');
	$pdf->Cell(10,3, ' 0.00' ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, 'Ref: '.$ref ,0,0,'L');
	$pdf->Cell(20,3, 'IVA Exento Bs. ' ,0,0,'R');
	$pdf->Cell(10,3, ' 0.00' ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, 'Banco: '.$bancos ,0,0,'L');
	$pdf->Cell(20,3, 'Total Factura Bs. ',0,0,'R');
	$pdf->Cell(10,3, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, 'Operador: '.$operador ,0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,3, 'Estado de Cuenta: ' ,0,1,'C');
	$pdf->setX($mgIzq);
	$pdf->Cell(25,3, 'Monto Total: ' ,0,0,'L');
	$pdf->Cell(13,3, number_format($totalPeriodo,2,'.',',') ,0,0,'R');
	$pdf->Cell(25,3, 'Monto Pendiente: ' ,0,0,'L');
	$pdf->Cell(13,3, number_format(($totalPeriodo-$pagado),2,'.',',') ,0,0,'R');
	$pdf->Cell(25,3, 'Tasa del B.C.V.: ' ,0,0,'L');
	$pdf->Cell(13,3, number_format($montoTasa,2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(25,3, 'Monto Pagado: ' ,0,0,'L');
	$pdf->Cell(13,3, number_format($pagado,2,'.',',') ,0,0,'R');
	$pdf->Cell(25,3, 'Morosidad: ' ,0,0,'L');
	$pdf->Cell(13,3, number_format(($morosida-$pago),2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,3, 'NOTA: La Mensualidad debe ser pagada los primeros cinco dias de cada mes por adelantado.' ,0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,3,utf8_decode('Los montos del Edo. de cuenta solo reflejan pagos por inscripción y mensualidad.') ,0,1,'L');
	
	$pdf->Ln(20);
}*/
$pdf->Output(); 
?>