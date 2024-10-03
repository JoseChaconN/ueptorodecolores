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
$salida = (isset($_GET['sale'])) ? $_GET['sale'] : '1' ;
$idPrint=$_SESSION['impresora']; // impresora del usuario activo
$margen_query = mysqli_query($link,"SELECT * FROM impresora WHERE id = '$idPrint' ");
$row2=mysqli_fetch_array($margen_query);
$mgIzq=$row2['izquierdo_HB'];
$mgSup=$row2['superior_HB'];
$mgCop=$row2['copia_HB'];
$recibo_query = mysqli_query($link,"SELECT A.tabla,A.fecha,B.nombre_periodo FROM miscelaneos_ingresos A, periodos B WHERE A.id='$recibo' and A.tabla=B.tablaPeriodo  ");
while($row=mysqli_fetch_array($recibo_query)) 
{
	$tablaPeriodo=$row['tabla'];
	$nombre_periodo=$row['nombre_periodo'];
	$fecha=date("d-m-Y", strtotime($row['fecha']));
	$hora=date("H:i", strtotime($row['fecha']));
	$fechaRecibo=date("Y-m-d", strtotime($row['fecha']));
}

$pagos_query = mysqli_query($link,"SELECT A.*,B.cedula,B.nombre,B.apellido,B.id_quienPaga, B.ced_rep, G.nombreUser FROM miscelaneos A, alumcer B, user G WHERE A.recibo='$recibo' and A.statusPago='1' and A.idAlum=B.idAlum and A.emitidoPor=G.idUser  ");
$van=0;
while($row=mysqli_fetch_array($pagos_query)) 
{
	$van++;
	${'codigo'.$van}=$row['id_concepto'];
	${'concepto'.$van}=$row['concepto'].' ('.$row['cantidad'].' Unid.)' ;
	${'montoRec'.$van}=$row['monto'];
	$cedula=$row['cedula'];
	$alumno=$row['nombre'].' '.$row['apellido'];
	$operador=$row['nombreUser'];
	$idAlum=$row['idAlum'];
	$montoTasa=$row['montoTasa'];
	$id_quienPaga=$row['id_quienPaga'];
	$ced_rep=$row['ced_rep'];
	if(empty($id_quienPaga))
	{
		$repre_query = mysqli_query($link,"SELECT * FROM represe WHERE cedula ='$ced_rep' "); 
		while($row=mysqli_fetch_array($repre_query)) 
		{
			$ced_reci=$row['cedula'];
			$nom_reci=$row['representante'];
			$dir_reci=$row['direccion'];
			//$=$row[''];
		}
	}else
	{
		$paga_query = mysqli_query($link,"SELECT * FROM emite_pago WHERE id ='$id_quienPaga' "); 
		while($row=mysqli_fetch_array($paga_query)) 
		{
			$ced_reci=$row['ced_reci'];
			$nom_reci=$row['nom_reci'];
			$dir_reci=$row['dir_reci'];
			//$=$row[''];
		}
	}
	
	$matri_query = mysqli_query($link,"SELECT A.grado,B.nombreGrado,C.nombre as nomSecc FROM matri".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id ");
	if(mysqli_num_rows($matri_query) > 0)
	{
		while($row=mysqli_fetch_array($matri_query)) 
		{
			$grado=$row['grado'];
			$nombreGrado=utf8_decode($row['nombreGrado']);
			$nomSecc=$row['nomSecc'];
		}
	}else
	{
		$matri_query = mysqli_query($link,"SELECT A.grado,B.nombreGrado,C.nombre as nomSecc FROM notaprimaria".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id ");
		if(mysqli_num_rows($matri_query) > 0)
		{
			while($row=mysqli_fetch_array($matri_query)) 
			{
				$grado=$row['grado'];
				$nombreGrado=utf8_decode($row['nombreGrado']);
				$nomSecc=$row['nomSecc'];
			}	
		}
	}
}
$van1 = ($van>5) ? 5 : $van ;
$van2 = ($van>5) ? $van-5 : 0 ;
$bancos_query = mysqli_query($link,"SELECT A.banco,A.operacion,A.nrodeposito,B.nom_banco,C.abrev2 FROM miscelaneos A, bancos B, formas_pago C WHERE A.recibo='$recibo' and A.banco=B.cod_banco and A.operacion=C.id GROUP BY A.operacion ");
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
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->Image('../img/fondoagua.jpg',$mgIzq+55,$mgSup+25,60,65);
for ($i=0; $i <2 ; $i++) { 
	$pdf->SetFont('Arial','B',10);
	if($i==0){ $pdf->Ln($mgSup); }
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, NKXS.' "'.EKKS.'"',0,1,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, DIRECCM,0,1,'L');
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, ' RIF.'.RIFCOLM,0,0,'L');
	
	$pdf->Cell(30,4, '',0,1,'R');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, 'Telefono: '.TELEMPM,0,0,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(30,4, 'Ctrl. Int.',0,1,'R');
	
	$pdf->SetFont('Arial','',10);
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, utf8_decode('Nombre o Razón Social:'.$nom_reci),0,0,'L');
	$pdf->SetFont('Arial','B',10);
	if($van2>0)
	{ $pdf->Cell(30,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT).' (1/2)',0,1,'R');}else 
	{ $pdf->Cell(30,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,1,'R');}
	$pdf->SetFont('Arial','',10);
	$pdf->setX($mgIzq);
	$pdf->Cell(130,4, utf8_decode('C.I./RIF: '.$ced_reci),0,0,'L');
	$pdf->Cell(30,4, utf8_decode('Fecha: '.$fecha.' '.$hora),0,1,'R');
	$pdf->SetFont('Arial','',9);
	$pdf->setX($mgIzq);
	$pdf->MultiCell(130,4, utf8_decode('Domicilio Fiscal: '.$dir_reci),0,'J');
	$pdf->SetFont('Arial','',10);
		
	$pdf->Ln(1);
	$pdf->setX($mgIzq);
	$pdf->Cell(10,4, utf8_decode('Cod.'),0,0,'C');
	$pdf->Cell(120,4, utf8_decode('Descripción del Producto o Servicio '),0,0,'C');
	$pdf->Cell(30,4, utf8_decode('Monto'),0,1,'R');
	
	$subTot1=0; $subTot2=0;
	for ($b1=1; $b1 <= $van1; $b1++) { 
		$pdf->setX($mgIzq);
		$pdf->Cell(10,4, str_pad(${'codigo'.$b1}, 2, "0", STR_PAD_LEFT) ,0,0,'C');
		$pdf->Cell(120,4, utf8_decode(${'concepto'.$b1}),0,0,'L');
		$pdf->Cell(30,4,${'montoRec'.$b1} ,0,1,'R');
		$subTot1=$subTot1+${'montoRec'.$b1};
	}
	if ($van1<5) {
		for ($c=$van1; $c <=5 ; $c++) { 
			$pdf->setX($mgIzq);
			$pdf->Cell(10,4, '',0,0,'C');
			$pdf->Cell(120,4, '',0,0,'L');
			$pdf->Cell(30,4,'',0,1,'R');		
		}
	}
	$pdf->Ln(1);
	$pdf->setX($mgIzq);
	$pdf->Cell(120,4, 'Estudiante: '.utf8_decode($alumno) ,0,0,'L');
	$pdf->Cell(20,4, 'Sub Total Bs. ',0,0,'R');
	$pdf->Cell(20,4, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(120,4, 'C.I.: '.$cedula.' '.$nombreGrado.' "'.$nomSecc.'" Periodo: '.$nombre_periodo ,0,0,'L');
	$pdf->Cell(20,4, 'Monto Pagado Bs. ',0,0,'R');
	$pdf->Cell(20,4, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(120,4, 'Forma de Pago: '.$fpag ,0,0,'L');
	$pdf->Cell(20,4, 'Deducible Bs. ' ,0,0,'R');
	$pdf->Cell(20,4, ' 0.00' ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(120,4, 'Ref: '.$operacion ,0,0,'L');
	$pdf->Cell(20,4, 'IVA Exento Bs. ' ,0,0,'R');
	$pdf->Cell(20,4, ' 0.00' ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(120,4, 'Banco: '.$banco ,0,0,'L');
	$pdf->Cell(20,4, 'Total Factura Bs. ',0,0,'R');
	$pdf->Cell(20,4, number_format($subTot1,2,'.',',') ,0,1,'R');
	$pdf->setX($mgIzq);
	$pdf->Cell(120,4, 'Operador: '.$operador ,0,1,'L');
	if ($salida==1 && $i<1) {$pdf->Cell(160,20, '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',0,1,'C');}
	$pdf->Ln($mgCop);
}

if ($van2>0) {
	$pdf->Addpage();
	for ($i=0; $i <2 ; $i++) { 
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln($mgSup);
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, NKXS.' "'.EKKS.'"',0,1,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, DIRECCM,0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, ' RIF.'.RIFCOLM,0,0,'L');
		$pdf->Cell(30,4, '',0,1,'R');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'Telefono: '.TELEMPM,0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(30,4, 'Ctrl. Int.',0,1,'R');
		
		$pdf->SetFont('Arial','',10);
		//$pdf->Ln(2);
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, utf8_decode('Nombre o Razón Social:'.$nom_reci),0,0,'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT).' (2/2)',0,1,'R');
		$pdf->SetFont('Arial','',10);
		
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, utf8_decode('C.I./RIF: '.$ced_reci),0,0,'L');
		$pdf->Cell(30,4, utf8_decode('Fecha: '.$fecha.' '.$hora),0,1,'R');
		//$pdf->Cell(30,4, utf8_decode('Hora: '.$hora),0,1,'R');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq);
		$pdf->MultiCell(130,4, utf8_decode('Domicilio Fiscal: '.$dir_reci),0,'J');
		$pdf->SetFont('Arial','',10);
			
		$pdf->Ln(1);
		$pdf->setX($mgIzq);
		$pdf->Cell(10,4, utf8_decode('Cod.'),0,0,'C');
		$pdf->Cell(120,4, utf8_decode('Descripción del Producto o Servicio '),0,0,'C');
		$pdf->Cell(30,4, utf8_decode('Monto'),0,1,'R');
		$subTot1=0; $subTot2=0;
		for ($b=$van1+1; $b <= $van2+$van1; $b++) { 
			$pdf->setX($mgIzq);
			$pdf->Cell(10,4, str_pad(${'codigo'.$b}, 2, "0", STR_PAD_LEFT) ,0,0,'C');
			$pdf->Cell(120,4, utf8_decode(${'concepto'.$b}),0,0,'L');
			$pdf->Cell(30,4,${'montoRec'.$b} ,0,1,'R');
			$subTot1=$subTot1+${'montoRec'.$b};
		}
		if ($van2<5) {
			for ($c=$van2; $c <=5 ; $c++) { 
				$pdf->setX($mgIzq);
				$pdf->Cell(10,4, '',0,0,'C');
				$pdf->Cell(120,4, '',0,0,'L');
				$pdf->Cell(30,4,'',0,1,'R');		
			}
		}
		$pdf->Ln(1);
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'Estudiante: '.$alumno ,0,0,'L');
		$pdf->Cell(20,4, 'Sub Total Bs. ',0,0,'R');
		$pdf->Cell(10,4, number_format($subTot1,2,'.',',') ,0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'C.I.: '.$cedula.' '.$nombreGrado.' "'.$nomSecc.'"' ,0,0,'L');
		$pdf->Cell(20,4, 'Monto Pagado Bs. ',0,0,'R');
		$pdf->Cell(10,4, number_format($subTot1,2,'.',',') ,0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'Forma de Pago: '.$fpag ,0,0,'L');
		$pdf->Cell(20,4, 'Deducible Bs. ' ,0,0,'R');
		$pdf->Cell(10,4, ' 0.00' ,0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'Ref: '.$operacion ,0,0,'L');
		$pdf->Cell(20,4, 'IVA Exento Bs. ' ,0,0,'R');
		$pdf->Cell(10,4, ' 0.00' ,0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'Banco: '.$banco ,0,0,'L');
		$pdf->Cell(20,4, 'Total Factura Bs. ',0,0,'R');
		$pdf->Cell(10,4, number_format($subTot1,2,'.',',') ,0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'Operador: '.$operador ,0,1,'L');
		/*$pdf->setX($mgIzq);
		$pdf->Cell(130,4, 'Estado General de Cuenta al '.$fecha ,0,1,'C');
		$pdf->setX($mgIzq);
		$pdf->Cell(25,4, 'Monto Total: ' ,0,0,'L');
		$pdf->Cell(13,4, number_format($totalPeriodo,2,'.',',') ,0,0,'R');
		$pdf->Cell(25,4, 'Monto Pendiente: ' ,0,0,'L');
		$pdf->Cell(13,4, number_format(($totalPeriodo-$pagado),2,'.',',') ,0,0,'R');
		$pdf->Cell(25,4, 'Tasa del B.C.V.: ' ,0,0,'L');
		$pdf->Cell(13,4, number_format($montoTasa,2,'.',',') ,0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(25,4, 'Monto Pagado: ' ,0,0,'L');
		$pdf->Cell(13,4, number_format($pagado,2,'.',',') ,0,0,'R');
		$pdf->Cell(25,4, 'Morosidad: ' ,0,0,'L');
		$pdf->Cell(13,4, number_format(($morosida-$pagado),2,'.',',') ,0,1,'R');
		
		$pdf->setX($mgIzq);
		$pdf->Cell(130,3, 'NOTA: La Mensualidad debe ser pagada los primeros cinco dias de cada mes por adelantado.' ,0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(130,3,utf8_decode('Los montos del Edo. de cuenta solo reflejan pagos por inscripción y mensualidad.') ,0,1,'L');*/
		if ($salida==1 && $i<1) {$pdf->Cell(160,20, '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',0,1,'C');}else
		{$pdf->Ln($mgCop);}
	}	
}

$pdf->Output();
	
?>
