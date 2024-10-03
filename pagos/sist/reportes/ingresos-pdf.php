<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
include_once("../../include/funciones.php");
include_once("../../../inicia.php");
$link=Conectarse();
$filtro = (!empty($_GET['filtro'])) ? '%'.$_GET['filtro'].'%' : '' ;
$recDesde=$_GET['recDes']; 
$recHasta=$_GET['recHas'];
$usuario = $_SESSION['usuario'];
$desde = $_GET["desde"];
$hasta = $_GET["hasta"];
$verRecibos=$_GET['salen'];
$desBus=$desde.' 00:00:00';
$hasBus=$hasta.' 23:59:59';

if ($verRecibos==0) {
	$ingresos_query = mysqli_query($link,"SELECT recibo,recibo2, fecha, tabla FROM ingresos WHERE fecha>='$desBus' AND fecha<='$hasBus' ");
}
if ($verRecibos==1) {
	$ingresos_query = mysqli_query($link,"SELECT recibo,recibo2, fecha, tabla FROM ingresos WHERE recibo>0 and fecha>='$desBus' AND fecha<='$hasBus' ");
}
if ($verRecibos==2) {
	$ingresos_query = mysqli_query($link,"SELECT recibo,recibo2, fecha, tabla FROM ingresos WHERE recibo2>0 and fecha>='$desBus' AND fecha<='$hasBus' ");
}

$bancos_query = mysqli_query($link,"SELECT cod_banco,nom_banco FROM bancos WHERE banco_mio='X' ");
$van=0;
while($row=mysqli_fetch_array($bancos_query)) 
{
	$van++;
	${'banco'.$van}=$row['nom_banco'];
	${'cod_banco'.$van}=$row['cod_banco'];
	${'totBanco'.$van}=0;
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
	}
	function Header()
	{
		global $desde;
		global $hasta;
		$this->Image('../../../imagenes/logo.jpg',10,8,20);
		$this->SetFont('Arial','',15);
		$this->Cell(35);
		$this->Cell(30,6,NKXS,0,1,'L');
		$this->Cell(35);
		$this->Cell(30,6,utf8_decode(EKKS),0,1,'L');
		$this->Cell(35);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'L');
		$this->Cell(35);
		$this->Cell(30,5,'Rif.: '.RIFCOLM,0,1,'L');
	}
	function Footer()
	{
		$this->SetY(-25);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf = new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',15);
$pdf->Ln(1);
$pdf->SetX(5);
$pdf->Cell(255,6,'Recibos Procesados por Ingresos desde: '.date("d-m-Y", strtotime($desde)).' hasta: '.date("d-m-Y", strtotime($hasta)),0,1,'L');
$pdf->Ln(1);
$pdf->SetFont('Arial','B',9);
$pdf->SetX(5);
$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
$pdf->Cell(30,6, 'Cedula',1,0,'C',1);
$pdf->Cell(70,6, 'Estudiante',1,0,'C',1);
$pdf->Cell(15,6, 'Recibo',1,0,'C',1);
$pdf->Cell(20,6, 'Divisa',1,0,'C',1);
$pdf->Cell(15,6, 'Tasa',1,0,'C',1);
$pdf->Cell(25,6, 'Bolivares',1,0,'C',1);
$pdf->Cell(60,6, 'Forma de Pago',1,0,'C',1);
$pdf->Ln();
$pdf->SetFont('Arial','',9);
$efec=0;$tran=0;$debi=0;$cred=0;$depo=0;$cheq=0;$vanBs=0;
$bsXdolar=0; $movil=0;
$l=1;
$subDiv=0; $subBs=0;$efecBs=0; $lin=0; $totDiv=0; $totBs=0;
while ($row = mysqli_fetch_array($ingresos_query))
{
	$recibo = ($row['recibo']>0) ? $row['recibo'] : $row['recibo2'] ;
    $reciboTabla = ($row['recibo']>0) ? 'recibo' : 'recibo2' ;
    $tipRecibo = ''; //($row['recibo']>0) ? 'H-' : 'F-' ;
    $salio = ($row['recibo']>0) ? '1' : '2' ;
    $tablaPeriodo=$row['tabla'];
    $fecha=$row['fecha'];
    $pagos_query = mysqli_query($link,"SELECT A.montoDolar,A.monto, A.montoTasa,A.operacion,A.banco,A.statusPago, B.cedula,B.nombre, B.apellido, C.abrev FROM pagos".$tablaPeriodo." A, alumcer B, formas_pago C WHERE A.$reciboTabla='$recibo' and A.idAlum=B.idAlum and A.operacion=C.id ");
	$fecha = date("d-m-Y", strtotime($fecha));
	$montoDolar=0; 	$montoBs=0; $fPago='';
	while($row2=mysqli_fetch_array($pagos_query)) 
    {
        $cedula=$row2['cedula'];
        $alumno=utf8_decode($row2['apellido'].' '.$row2['nombre']);
        $statusPago=$row2['statusPago'];
	    $tasaDolar=$row2['montoTasa'];
	    if($statusPago==1)
        {
	        if($row2['operacion']=='1'){$montoDolar=$montoDolar+$row2['montoDolar'];}
	        if($row2['operacion']!='1'){$montoBs=$montoBs+$row2['monto'];}
	        if($row2['operacion']=='2'){$efecBs=$efecBs+$row2['monto'];}
		    for ($i=1; $i <= $van; $i++) { 
	        	if(${'cod_banco'.$i}==$row2['banco']){
	        		${'totBanco'.$i}=${'totBanco'.$i}+$row2['monto'];
	        	}
	        }
	    }
    }
    $formaPago_query = mysqli_query($link,"SELECT C.abrev FROM pagos".$tablaPeriodo." A, formas_pago C WHERE A.$reciboTabla='$recibo' and A.operacion=C.id GROUP BY A.operacion ");
    while($row3=mysqli_fetch_array($formaPago_query)) 
    {
    	$fPago.=$row3['abrev'].', ';
    }
    $subDiv=$subDiv+$montoDolar;
    $subBs=$subBs+$montoBs;
    $totDiv=$totDiv+$montoDolar;
    $totBs=$totBs+$montoBs;
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(12);
	
	$totalBs=$efec;
	if($l==1)
	{
		$pdf->SetX(5);
		$pdf->Cell(20,5, $fecha,0,0,'C');
		$pdf->Cell(30,5, $cedula,0,0,'L');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,5, $alumno,0,0,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(15,5, $tipRecibo.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C');
		if($statusPago==2)
		{
			$pdf->Cell(20,5, 'ANULADO',0,0,'R');	
		}else
		{
			$pdf->Cell(20,5, number_format($montoDolar,2,',','.'),0,0,'R');
		}
		$pdf->Cell(15,5, number_format($tasaDolar,2,',','.'),0,0,'R');
		if($statusPago==2)
		{
			$pdf->Cell(25,5, 'ANULADO',0,0,'R');	
		}else
		{
			$pdf->Cell(25,5, number_format($montoBs,2,',','.'),0,0,'R');
		}
		$pdf->Cell(60,5, $fPago,0,1,'L');
		$l=2;
	}else
	{
		$pdf->SetX(5);
		$pdf->Cell(20,5, $fecha,0,0,'C',1);
		$pdf->Cell(30,5, $cedula,0,0,'L',1);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,5, $alumno,0,0,'L',1);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(15,5, $tipRecibo.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C',1);
		if($statusPago==2)
		{
			$pdf->Cell(20,5, 'ANULADO',0,0,'R',1);	
		}else
		{
			$pdf->Cell(20,5, number_format($montoDolar,2,',','.'),0,0,'R',1);
		}
		$pdf->Cell(15,5, number_format($tasaDolar,2,',','.'),0,0,'R',1);
		if($statusPago==2)
		{
			$pdf->Cell(25,5, 'ANULADO',0,0,'R',1);	
		}else
		{
			$pdf->Cell(25,5, number_format($montoBs,2,',','.'),0,0,'R',1);
		}
		$pdf->Cell(60,5, $fPago,0,1,'L',1);
		$l=1;
	}
	$lin++;
	if($lin==26)
	{
		$pdf->Addpage();
		$pdf->SetFillColor(232,232,232);
		$pdf->SetFont('Arial','',15);
		$pdf->Ln(1);
		$pdf->SetX(5);
		$pdf->Cell(255,6,'Recibos Procesados por Ingresos desde: '.date("d-m-Y", strtotime($desde)).' hasta: '.date("d-m-Y", strtotime($hasta)),0,1,'L');
		$pdf->Ln(1);
		$pdf->SetFont('Arial','B',9);
		$pdf->SetX(5);
		$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
		$pdf->Cell(30,6, 'Cedula',1,0,'C',1);
		$pdf->Cell(70,6, 'Estudiante',1,0,'C',1);
		$pdf->Cell(15,6, 'Recibo',1,0,'C',1);
		$pdf->Cell(20,6, 'Divisa',1,0,'C',1);
		$pdf->Cell(15,6, 'Tasa',1,0,'C',1);
		$pdf->Cell(25,6, 'Bolivares',1,0,'C',1);
		$pdf->Cell(60,6, 'Forma de Pago',1,0,'C',1);
		$pdf->Ln();
		$pdf->SetFont('Arial','',9);
		$lin=0;
	}
}
$pdf->SetX(100);
$pdf->Cell(40,5, 'Sub Totales-->',0,0,'R');
$pdf->Cell(20,5, number_format($subDiv,2,',','.'),0,0,'R');
$pdf->Cell(40,5, number_format($subBs,2,',','.'),0,1,'R');
$subDiv=0; $subBs=0;
$ingreMis_query = mysqli_query($link,"SELECT * FROM miscelaneos_ingresos WHERE fecha>='$desBus' and fecha<='$hasBus' ");
if(mysqli_num_rows($ingreMis_query) > 0)
{
	$pdf->SetFont('Arial','',15);
	$pdf->Ln(3);
	$pdf->SetX(5);
	$pdf->Cell(255,6,'Recibos Procesados por Miscelaneos desde: '.date("d-m-Y", strtotime($desde)).' hasta: '.date("d-m-Y", strtotime($hasta)),0,1,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(5);
	$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
	$pdf->Cell(30,6, 'Cedula',1,0,'C',1);
	$pdf->Cell(70,6, 'Estudiante',1,0,'C',1);
	$pdf->Cell(15,6, 'Recibo',1,0,'C',1);
	$pdf->Cell(20,6, 'Divisa',1,0,'C',1);
	$pdf->Cell(15,6, 'Tasa',1,0,'C',1);
	$pdf->Cell(25,6, 'Bolivares',1,0,'C',1);
	$pdf->Cell(60,6, 'Forma de Pago',1,0,'C',1);
	$pdf->Ln();
	$pdf->SetFont('Arial','',9);
	$lin=0;  
	while ($row = mysqli_fetch_array($ingreMis_query))
	{
		$recibo=$row['id'];
	    $fecha=$row['fecha'];
	    $miscela_query = mysqli_query($link,"SELECT A.montoDolar,A.monto, A.montoTasa,A.operacion,A.banco,A.statusPago, B.cedula,B.nombre, B.apellido, C.abrev FROM miscelaneos A, alumcer B, formas_pago C WHERE A.recibo='$recibo' and A.idAlum=B.idAlum and A.operacion=C.id ");
	    $montoDolar2=0; $montoBs2=0;
	    while ($row2 = mysqli_fetch_array($miscela_query))
		{
		    $cedula=$row2['cedula'];
	        $alumno=utf8_decode($row2['apellido'].' '.$row2['nombre']);
	        $statusPago2=$row2['statusPago'];
		    $tasaDolar=$row2['montoTasa'];
		    if($statusPago2==1)
	        {
		        if($row2['operacion']=='1'){$montoDolar2=$montoDolar2+$row2['montoDolar'];}
		        if($row2['operacion']!='1'){$montoBs2=$montoBs2+$row2['monto'];}
		        if($row2['operacion']=='2'){$efecBs=$efecBs+$row2['monto'];}
		        for ($i=1; $i <= $van; $i++) { 
	        	if(${'cod_banco'.$i}==$row2['banco']){
	        		${'totBanco'.$i}=${'totBanco'.$i}+$row2['monto'];
	        		}
	        	}
		    }
	    }
	    $fPago='';
	    $formaPago_query = mysqli_query($link,"SELECT C.abrev FROM miscelaneos A, formas_pago C WHERE A.recibo='$recibo' and A.operacion=C.id GROUP BY A.operacion ");
	    while($row3=mysqli_fetch_array($formaPago_query)) 
	    {
	    	$fPago.=$row3['abrev'].', ';
	    }
	    $subDiv=$subDiv+$montoDolar2;
	    $subBs=$subBs+$montoBs2;
	    $totDiv=$totDiv+$montoDolar2;
    	$totBs=$totBs+$montoBs2;
	    if($l==1)
		{
			$pdf->SetX(5);
			$pdf->Cell(20,5, date("d-m-Y", strtotime($fecha)),0,0,'C');
			$pdf->Cell(30,5, $cedula,0,0,'L');
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(70,5, $alumno,0,0,'L');
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(15,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C');
			if($statusPago2==2)
			{
				$pdf->Cell(20,5, 'ANULADO',0,0,'R');
			}else
			{
				$pdf->Cell(20,5, number_format($montoDolar2,2,',','.'),0,0,'R');
			}
			$pdf->Cell(15,5, number_format($tasaDolar,2,',','.'),0,0,'R');
			if($statusPago2==2)
			{
				$pdf->Cell(25,5, 'ANULADO',0,0,'R');	
			}else
			{
				$pdf->Cell(25,5, number_format($montoBs2,2,',','.'),0,0,'R');
			}
			$pdf->Cell(60,5, $fPago,0,1,'L');
			$l=2;
		}else
		{
			$pdf->SetX(5);
			$pdf->Cell(20,5, date("d-m-Y", strtotime($fecha)),0,0,'C',1);
			$pdf->Cell(30,5, $cedula,0,0,'L',1);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(70,5, $alumno,0,0,'L',1);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(15,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C',1);
			if($statusPago2==2)
			{
				$pdf->Cell(20,5, 'ANULADO',0,0,'R',1);
			}else
			{
				$pdf->Cell(20,5, number_format($montoDolar2,2,',','.'),0,0,'R',1);
			}
			$pdf->Cell(15,5, number_format($tasaDolar,2,',','.'),0,0,'R',1);
			if($statusPago2==2)
			{
				$pdf->Cell(25,5, 'ANULADO',0,0,'R',1);	
			}else
			{
				$pdf->Cell(25,5, number_format($montoBs2,2,',','.'),0,0,'R',1);
			}
			$pdf->Cell(60,5, $fPago,0,1,'L',1);
			$l=1;
		}
		$lin++;
		if($lin==26)
		{
			$pdf->Addpage();
			$pdf->SetFillColor(232,232,232);
			$pdf->SetFont('Arial','',15);
			$pdf->Ln(1);
			$pdf->SetX(5);
			$pdf->Cell(255,6,'Recibos Procesados por Miscelaneos desde: '.date("d-m-Y", strtotime($desde)).' hasta: '.date("d-m-Y", strtotime($hasta)),0,1,'L');
			$pdf->Ln(1);
			$pdf->SetFont('Arial','B',9);
			$pdf->SetX(5);
			$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
			$pdf->Cell(30,6, 'Cedula',1,0,'C',1);
			$pdf->Cell(70,6, 'Estudiante',1,0,'C',1);
			$pdf->Cell(15,6, 'Recibo',1,0,'C',1);
			$pdf->Cell(20,6, 'Divisa',1,0,'C',1);
			$pdf->Cell(15,6, 'Tasa',1,0,'C',1);
			$pdf->Cell(25,6, 'Bolivares',1,0,'C',1);
			$pdf->Cell(60,6, 'Forma de Pago',1,0,'C',1);
			$pdf->Ln();
			$pdf->SetFont('Arial','',9);
			$lin=0;
		}
	}
	$pdf->SetX(100);
	$pdf->Cell(40,5, 'Sub Totales-->',0,0,'R');
	$pdf->Cell(20,5, number_format($subDiv,2,',','.'),0,0,'R');
	$pdf->Cell(40,5, number_format($subBs,2,',','.'),0,1,'R');
}


$pdf->SetFont('Arial','',11);
$pdf->Ln(5);
$pdf->SetX(15);
$pdf->Cell(90,5, '*** TOTALES ***',1,1,'C',1);	
$pdf->SetX(15);
$pdf->Cell(60,5, 'Efectivo -> ',1,0,'L');	
$pdf->Cell(30,5, '$ '.number_format($totDiv,2,'.',','),1,1,'R');
$pdf->SetX(15);
$pdf->Cell(60,5, 'Efectivo -> ',1,0,'L');	
$pdf->Cell(30,5, 'Bs. '.number_format($efecBs,2,'.',','),1,1,'R');
for ($i=1; $i <=$van ; $i++) { 
	$pdf->SetX(15);
	$pdf->Cell(60,5, 'Banco '.${'banco'.$i}.'-> ',1,0,'L');	
	$pdf->Cell(30,5, 'Bs. '.number_format(${'totBanco'.$i},2,'.',','),1,1,'R');
}
mysqli_free_result($ingresos_query);
mysqli_free_result($bancos_query);
mysqli_free_result($pagos_query);
mysqli_free_result($formaPago_query);
mysqli_free_result($ingreMis_query);
mysqli_free_result($miscela_query);

$pdf->Output();

?>