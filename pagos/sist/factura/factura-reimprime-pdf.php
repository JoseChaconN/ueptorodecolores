<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
include_once("../../include/funciones.php");
include_once("../../../inicia.php");
$link = Conectarse();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaHoy = date("Y-m-d H:i:s");
$fechaYa = date("Y-m-d");
$recibo=desencriptar($_GET['recibo']);
$idPrint=$_SESSION['impresora']; // impresora del usuario activo
$margen_query = mysqli_query($link,"SELECT * FROM impresora WHERE id = '$idPrint' ");
$row2=mysqli_fetch_array($margen_query);
$mgIzq=$row2['izquierdo_FIS'];
$mgSup=$row2['superior_FIS'];
$mgCop=$row2['copia_FIS'];		


$recibo_query = mysqli_query($link,"SELECT tabla,fecha FROM ingresos WHERE recibo ='$recibo'  ");
while($row=mysqli_fetch_array($recibo_query)) 
{
	$tablaPeriodo=$row['tabla'];
	$fecha=date("d-m-Y", strtotime($row['fecha']));
	$hora=date("H:i", strtotime($row['fecha']));
	$fechaRecibo=date("Y-m-d", strtotime($row['fecha']));
}
$periodo_query = mysqli_query($link,"SELECT nombre_periodo FROM periodos WHERE tablaPeriodo='$tablaPeriodo'  ");
while($row=mysqli_fetch_array($periodo_query)) 
{
	$nombre_periodo=$row['nombre_periodo'];
}
$pagos_query = mysqli_query($link,"SELECT A.*,B.cedula,B.nombre,B.apellido,B.id_quienPaga, B.ced_rep, G.nombreUser, G.apellidoUser FROM pagos".$tablaPeriodo." A, alumcer B, user G WHERE A.recibo='$recibo' and A.statusPago='1' and A.idAlum=B.idAlum and A.emitidoPor=G.idUser  ");
$van=0; $transf=0; $pagMov=0; $efeDiv=0; $efeBs=0; $punto=0;
while($row=mysqli_fetch_array($pagos_query)) 
{
	$van++;
	${'codigo'.$van}=$row['id_concepto'];
	${'concepto'.$van}=$row['concepto'];
	${'montoRec'.$van}=$row['monto'];
	$cedula=$row['cedula'];
	$alumno=utf8_decode($row['nombre'].' '.$row['apellido']);
	/*$ced_reci=$row['ced_reci'];
	$nom_reci=$row['nom_reci'];
	$dir_reci=$row['dir_reci'];*/
	$operador=utf8_decode($row['nombreUser'].' '.$row['apellidoUser']) ;
	$tipOpe=$row['operacion'];
	if ($tipOpe==1) { $efeDiv=$efeDiv+$row['montoDolar'];}
	if ($tipOpe==2) { $efeBs=$efeBs+$row['monto'];}
	if ($tipOpe==3) { $transf=$transf+$row['monto'];}
	if ($tipOpe==4) { $punto=$punto+$row['monto'];}
	if ($tipOpe==5) { $pagMov=$pagMov+$row['monto'];}
	$idAlum=$row['idAlum'];
	$montoTasa=$row['montoTasa'];
	$id_quienPaga=$row['id_quienPaga'];
	$ced_rep=$row['ced_rep'];
	$prontoPagText=$row['prontoPagText'];
	$prontoPagMon=$row['prontoPagMon']*$montoTasa;
	if($prontoPagMon>0){
		$textPP='Nota: '.$prontoPagText.' '.number_format($prontoPagMon,2,',','.').' Bs.';
	}else{$textPP='';}
	if(empty($id_quienPaga))
	{
		$repre_query = mysqli_query($link,"SELECT * FROM represe WHERE cedula ='$ced_rep' "); 
		while($row=mysqli_fetch_array($repre_query)) 
		{
			$ced_reci=$row['cedula'];
			$nom_reci=($row['representante']);
			$dir_reci=($row['direccion']);
			//$=$row[''];
		}
	}else
	{
		$paga_query = mysqli_query($link,"SELECT * FROM emite_pago WHERE id ='$id_quienPaga' "); 
		while($row=mysqli_fetch_array($paga_query)) 
		{
			$ced_reci=$row['ced_reci'];
			$nom_reci=($row['nom_reci']);
			$dir_reci=($row['dir_reci']);
			//$=$row[''];
		}
	}
	
	$matri_query = mysqli_query($link,"SELECT A.grado,B.nombreGrado, B.nomAbrev,C.nombre as nomSecc FROM matri".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id ");
	if(mysqli_num_rows($matri_query) > 0)
	{
		while($row=mysqli_fetch_array($matri_query)) 
		{
			$grado=$row['grado'];
			$nombreGrado=utf8_decode($row['nomAbrev']);
			$nomSecc=$row['nomSecc'];
		}
	}else
	{
		$matri_query = mysqli_query($link,"SELECT A.grado,B.nombreGrado, B.nomAbrev,C.nombre as nomSecc FROM notaprimaria".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id ");
		if(mysqli_num_rows($matri_query) > 0)
		{
			while($row=mysqli_fetch_array($matri_query)) 
			{
				$grado=$row['grado'];
				$nombreGrado=utf8_decode($row['nomAbrev']);
				$nomSecc=$row['nomSecc'];
			}	
		}
	}
}
$saldo_query = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,B.monto,B.fecha_vence FROM matri".$tablaPeriodo." A, montos".$tablaPeriodo." B WHERE A.idAlumno='$idAlum' and A.grado=B.id_grado  ");


$nro=0; $totalPeriodo=0;$morosida=0;
while($row=mysqli_fetch_array($saldo_query)) 
{
	$nro++;
	${'desc'.$nro}=$row['desc'.$nro];
	${'monto'.$nro}=$row['monto'];
	${'fecha_vence'.$nro}=$row['fecha_vence'];
	if($row['fecha_vence']<=$fechaYa)
	{
		$morosida=$morosida+(${'monto'.$nro}-${'desc'.$nro});
	}
	$totalPeriodo=$totalPeriodo+(${'monto'.$nro}-${'desc'.$nro});
}
$pagado_query = mysqli_query($link,"SELECT SUM(montoDolar) as pago FROM matri".$tablaPeriodo." A, pagos".$tablaPeriodo." B, conceptos C WHERE A.idAlumno='$idAlum' and B.statusPago='1' and A.idAlumno=B.idAlum and B.id_concepto=C.id and C.afecta='S'  ");


$pagado=0;
while($row=mysqli_fetch_array($pagado_query)) 
{
	$pagado=$row['pago'];
}

$van1 = ($van>5) ? 5 : $van ;
$van2 = ($van>5) ? $van-5 : 0 ;
$bancos_query = mysqli_query($link,"SELECT A.banco,A.operacion,A.nrodeposito,B.nom_banco,C.abrev2 FROM pagos".$tablaPeriodo." A, bancos B, formas_pago C WHERE A.recibo='$recibo' and A.banco=B.cod_banco and A.operacion=C.id GROUP BY A.operacion ");
$operacion=''; $banco=''; $fpag='';
while($row=mysqli_fetch_array($bancos_query)) 
{
	if($row['banco']>0)
	{
		$banco.=$row['nom_banco'].', ';
		$operacion.=$row['nrodeposito'].', ';
	}
	$fpag.=$row['abrev2'].',';
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{}
	function Footer()
	{}
	
}
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->Image('../img/fondoagua.jpg',$mgIzq+55,$mgSup+35,60,64);
for ($i=0; $i <2 ; $i++) { 
	$pdf->SetFont('Arial','B',10);
	if($i==0){
		$pdf->Ln($mgSup);
	}
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, NKXS.' "'.EKKS.'"',0,1,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, utf8_decode(DIRECCM),0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, ' RIF.'.RIFCOLM,0,0,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(35,4, 'Factura',0,1,'R');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'Telefono: '.TELEMPM,0,0,'L');
	
	$pdf->SetFont('Arial','B',10);
	if($van2>0)
	{ $pdf->Cell(35,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT).' (1/2)',0,1,'R');}else 
	{ $pdf->Cell(35,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,1,'R');}
	
	$pdf->SetFont('Arial','',10);
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, utf8_decode('Nombre o Razón Social:'.$nom_reci),0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, utf8_decode('C.I./RIF: '.$ced_reci),0,0,'L');
	$pdf->Cell(35,4, utf8_decode('Fecha: '.$fecha.' '.$hora),0,1,'R');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->MultiCell(130,4, utf8_decode('Domicilio Fiscal: '.$dir_reci),0,'J');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(1);
	$pdf->setX($mgIzq);
	$pdf->Cell(10,4, utf8_decode('Cod.'),1,0,'C');
	$pdf->Cell(130,4, utf8_decode('Descripción del Producto o Servicio '),1,0,'C');
	$pdf->Cell(25,4, utf8_decode('Monto'),1,1,'C');
	$subTot1=0; $subTot2=0;
	for ($b1=1; $b1 <= $van1; $b1++) { 
		$pdf->setX($mgIzq);
		$pdf->Cell(10,4, str_pad(${'codigo'.$b1}, 2, "0", STR_PAD_LEFT) ,0,0,'C');
		$pdf->Cell(130,4, utf8_decode(${'concepto'.$b1}),0,0,'L');
		$pdf->Cell(25,4,number_format(${'montoRec'.$b1},2,',','.') ,0,1,'R');
		$subTot1=$subTot1+${'montoRec'.$b1};
	}
	if ($van1<5) {
		for ($c=$van1; $c <=5 ; $c++) { 
			$pdf->setX($mgIzq);
			$pdf->Cell(10,4, '',0,0,'C');
			$pdf->Cell(130,4, '',0,0,'L');
			$pdf->Cell(25,4,'',0,1,'R');		
		}
	}
	$pdf->Ln(1);
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, $textPP ,0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'Estudiante: '.utf8_decode($alumno) ,0,0,'L');
	$pdf->Cell(20,4, 'Sub Total Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,',','.') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'C.I.: '.$cedula.' '.$nombreGrado.' "'.$nomSecc.'" Periodo: '.$nombre_periodo ,0,0,'L');
	$pdf->Cell(20,4, 'Monto Pagado Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,',','.') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'Forma de Pago: '.$fpag ,0,0,'L');
	$pdf->Cell(20,4, 'Deducible Bs. ' ,0,0,'R');
	$pdf->Cell(15,4, ' 0,00' ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'Ref: '.$operacion ,0,0,'L');
	$pdf->Cell(20,4, 'IVA Exento Bs. ' ,0,0,'R');
	$pdf->Cell(15,4, ' 0,00' ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'Banco: '.$banco ,0,0,'L');
	$pdf->Cell(20,4, 'Total Factura Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,',','.') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'Operador: '.$operador ,0,1,'L');
	if ($salida==1 && $i<1) {$pdf->Cell(160,20, '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',0,1,'C');}
	$pdf->Ln($mgCop);
	
}
/*for ($i=1; $i <2 ; $i++) { 
	$pdf->SetFont('Arial','B',9);
	$pdf->Ln($mgSup);
	$pdf->setX($mgIzq+78);
	$pdf->Cell(20,4, 'Factura',0,0,'R');
	$pdf->setX($mgCop+78);
	$pdf->Cell(20,4, 'Factura',0,1,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgIzq);
	$pdf->Cell(78,4, utf8_decode('Nombre o Razón Social:'.substr($nom_reci,0,35)),0,0,'L');
	$pdf->SetFont('Arial','B',8);
	if($van2>0)
	{ $pdf->Cell(20,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT).' (1/2)',0,0,'R');}else 
	{ $pdf->Cell(20,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'R');}
	
	$pdf->SetFont('Arial','',7);
	
	$pdf->setX($mgCop);
	$pdf->Cell(78,4, utf8_decode('Nombre o Razón Social:'.substr($nom_reci,0,35)),0,0,'L');
	$pdf->SetFont('Arial','B',8);
	if($van2>0)
	{ $pdf->Cell(20,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT).' (1/2)',0,1,'R');}else 
	{ $pdf->Cell(20,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,1,'R');}

	$pdf->SetFont('Arial','',8);
	$pdf->setX($mgIzq);
	$pdf->Cell(78,4, utf8_decode('C.I./RIF: '.$ced_reci),0,0,'L');
	$pdf->Cell(20,4, utf8_decode('Fecha: '.$fecha.' '.$hora),0,0,'R');
	$pdf->setX($mgCop);
	$pdf->Cell(78,4, utf8_decode('C.I./RIF: '.$ced_reci),0,0,'L');
	$pdf->Cell(20,4, utf8_decode('Fecha: '.$fecha.' '.$hora),0,1,'R');

	$pdf->SetFont('Arial','',8);
	$pdf->setX($mgIzq);
	$pdf->Cell(78,4, utf8_decode('Domicilio Fiscal: '.$dir_reci),0,0,'L');
	$pdf->setX($mgCop);
	$pdf->Cell(78,4, utf8_decode('Domicilio Fiscal: '.$dir_reci),0,1,'L');
	$pdf->SetFont('Arial','',8);
		
	$pdf->Ln(1);
	$pdf->setX($mgIzq);
	$pdf->Cell(10,4, utf8_decode('Cod.'),0,0,'C');
	$pdf->Cell(57,4, utf8_decode('Descripción del Producto o Servicio '),0,0,'C');
	$pdf->Cell(30,4, utf8_decode('Monto'),0,0,'R');
	$pdf->setX($mgCop);
	$pdf->Cell(10,4, utf8_decode('Cod.'),0,0,'C');
	$pdf->Cell(57,4, utf8_decode('Descripción del Producto o Servicio '),0,0,'C');
	$pdf->Cell(30,4, utf8_decode('Monto'),0,1,'R');
	
	$subTot1=0; $subTot2=0;
	for ($b1=1; $b1 <= $van1; $b1++) { 
		$pdf->setX($mgIzq);
		$pdf->Cell(10,4, str_pad(${'codigo'.$b1}, 2, "0", STR_PAD_LEFT) ,0,0,'C');
		$pdf->Cell(57,4, utf8_decode(${'concepto'.$b1}),0,0,'L');
		$pdf->Cell(30,4,${'montoRec'.$b1} ,0,0,'R');
		$pdf->setX($mgCop);
		$pdf->Cell(10,4, str_pad(${'codigo'.$b1}, 2, "0", STR_PAD_LEFT) ,0,0,'C');
		$pdf->Cell(57,4, utf8_decode(${'concepto'.$b1}),0,0,'L');
		$pdf->Cell(30,4,${'montoRec'.$b1} ,0,1,'R');
		$subTot1=$subTot1+${'montoRec'.$b1};
	}
	if ($van1<5) {
		for ($c=$van1; $c <=5 ; $c++) { 
			$pdf->setX($mgIzq);
			$pdf->Cell(10,4, '',0,0,'C');
			$pdf->Cell(77,4, '',0,0,'L');
			$pdf->Cell(30,4,'',0,0,'R');
			$pdf->setX($mgCop);
			$pdf->Cell(10,4, '',0,0,'C');
			$pdf->Cell(77,4, '',0,0,'L');
			$pdf->Cell(30,4,'',0,1,'R');		
		}
	}
	$alumno=strtolower($alumno);
	$alumno=ucwords($alumno);
	$pdf->Ln(1);
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgIzq);
	$pdf->Cell(100,3, $textPP ,0,0,'L');
	$pdf->setX($mgCop);
	$pdf->Cell(100,3, $textPP ,0,1,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->setX($mgIzq);
	$pdf->Cell(62,4, 'Estud.: '.substr($alumno,0,40) ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'Sub Total Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,'.',',') ,0,0,'R');
	$pdf->SetFont('Arial','',8);
	$pdf->setX($mgCop);
	$pdf->Cell(62,4, 'Estud.: '.substr($alumno,0,40) ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'Sub Total Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgIzq);
	$pdf->Cell(62,4, 'C.I.: '.$cedula.' '.$nombreGrado.' "'.$nomSecc.'" '.$nombre_periodo ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'Pagado Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,'.',',') ,0,0,'R');
	$pdf->setX($mgCop);
	$pdf->Cell(62,4, 'C.I.: '.$cedula.' '.$nombreGrado.' "'.$nomSecc.'" '.$nombre_periodo ,0,0,'L');
	$pdf->Cell(20,4, 'Pagado Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgIzq);
	$pdf->Cell(62,4, 'Forma de Pago: '.$fpag ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'Deducible Bs. ' ,0,0,'R');
	$pdf->Cell(15,4, ' 0.00' ,0,0,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgCop);
	$pdf->Cell(62,4, 'Forma de Pago: '.$fpag ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'Deducible Bs. ' ,0,0,'R');
	$pdf->Cell(15,4, ' 0.00' ,0,1,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgIzq);
	$pdf->Cell(62,4, 'Ref: '.$operacion ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'IVA Exento Bs. ' ,0,0,'R');
	$pdf->Cell(15,4, ' 0.00' ,0,0,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgCop);
	$pdf->Cell(62,4, 'Ref: '.$operacion ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'IVA Exento Bs. ' ,0,0,'R');
	$pdf->Cell(15,4, ' 0.00' ,0,1,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgIzq);
	$pdf->Cell(62,4, 'Banco: '.$banco ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'Total Factura Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,'.',',') ,0,0,'R');
	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgCop);
	$pdf->Cell(62,4, 'Banco: '.$banco ,0,0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,4, 'Total Factura Bs. ',0,0,'R');
	$pdf->Cell(15,4, number_format($subTot1,2,'.',',') ,0,1,'R');
	
	
	$detalle='';

	if($efeDiv>0){
		$detalle.='Divisa: $'.number_format($efeDiv,2,'.',',').', ';
	}
	if($transf>0){
		$detalle.='Transf.: Bs.'.number_format($transf,2,'.',',').', ';
	}
	if($pagMov>0){
		$detalle.='P.Movil: Bs.'.number_format($pagMov,2,'.',',').', ';
	}
	if($efeBs>0){
		$detalle.='Ef.: Bs.'.number_format($efeBs,2,'.',',').', ';
	}
	if($punto>0){
		$detalle.='Punto: Bs.'.number_format($punto,2,'.',',').', ';
	}
	$pdf->setX($mgIzq);
	$pdf->Cell(100,4, 'Detalle: '.substr($detalle,0,-2),0,0,'L');
	$pdf->setX($mgCop);
	$pdf->Cell(100,4, 'Detalle: '.substr($detalle,0,-2),0,1,'L');

	$pdf->SetFont('Arial','',7);
	$pdf->setX($mgIzq);
	$pdf->Cell(62,4, 'Operador: '.$operador ,0,0,'L');
	$pdf->setX($mgCop);
	$pdf->Cell(62,4, 'Operador: '.$operador ,0,1,'L');
	$pdf->SetFont('Arial','',8);
}*/


mysqli_free_result($margen_query);
mysqli_free_result($recibo_query);
mysqli_free_result($periodo_query);
mysqli_free_result($pagos_query);
mysqli_free_result($repre_query);
mysqli_free_result($paga_query);
mysqli_free_result($matri_query);
mysqli_free_result($saldo_query);
mysqli_free_result($pagado_query);
mysqli_free_result($bancos_query);
$pdf->Output();
	
?>
