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
$link=Conectarse();
$filtro = (!empty($_GET['filtro'])) ? '%'.$_GET['filtro'].'%' : '' ;
$usuario = $_SESSION['usuario'];
$desBus = $_GET["desde"];
$hasBus = $_GET["hasta"];
$cod_banco1 = $_GET["banco"];
$ingresos_query = mysqli_query($link,"SELECT * FROM ingresos WHERE (fechaTransf>='$desBus' AND fechaTransf<='$hasBus') or (fechaDebito>='$desBus' AND fechaDebito<='$hasBus') or (fechaPagMovil>='$desBus' AND fechaPagMovil<='$hasBus') ORDER BY id ");
$miscela_query = mysqli_query($link,"SELECT id, tabla FROM miscelaneos_ingresos WHERE (fechaTransf>='$desBus' and fechaTransf<='$hasBus') or (fechaDebito>='$desBus' and fechaDebito<='$hasBus') or (fechaPagMovil>='$desBus' and fechaPagMovil<='$hasBus') ");
$bancos_query = mysqli_query($link,"SELECT nom_banco FROM bancos WHERE cod_banco='$cod_banco1' ");
$van=0; $totBanco=0;
while($row=mysqli_fetch_array($bancos_query)) 
{
	$van++;
	$banco=$row['nom_banco'];
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		$this->Addpage();
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','B',9);
		$this->SetX(5);
		$this->Cell(25,6, 'Recibo',1,0,'C',1);
		$this->Cell(30,6, '# Operacion',1,0,'C',1);
		$this->Cell(20,6, 'Fecha',1,0,'C',1);
		$this->Cell(25,6, 'Bolivares',1,1,'C',1);
		$this->Ln();
		$this->SetFont('Arial','',9);
		$this->SetX(5);
	}
	function Header()
	{
		global $banco;
		global $desBus;
		global $hasBus;
		$this->Image('../../../imagenes/logo.jpg',10,8,25);
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
		$this->Cell(35);
		$this->SetFont('Arial','B',11);
		$this->Cell(30,6,'Movimientos del Banco '.$banco.' desde: '.date("d-m-Y", strtotime($desBus)).' hasta: '.date("d-m-Y", strtotime($hasBus)),0,1,'L');
		$this->Ln(3);
		$this->SetFont('Arial','',9);
	}
	function Footer()
	{
		$this->SetY(-25);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->Ln(3);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(130,6,'Ingresos al Banco ',0,1,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetX(5);
$pdf->Cell(25,6, 'Recibo',1,0,'C',1);
$pdf->Cell(40,6, '# Operacion',1,0,'C',1);
$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
$pdf->Cell(25,6, 'Bolivares',1,1,'C',1);
$pdf->SetFont('Arial','',9);
$l=1;
while ($row = mysqli_fetch_array($ingresos_query))
{
	$recibo = ($row['recibo']>0) ? $row['recibo'] : $row['recibo2'] ;
    $reciboTabla = ($row['recibo']>0) ? 'recibo' : 'recibo2' ;
    $tipRecibo = ($row['recibo']>0) ? 'H-' : 'F-' ;
    $tablaPeriodo=$row['tabla'];
    $pagos_query = mysqli_query($link,"SELECT sum(A.monto) as todo,A.fechadepo,A.nrodeposito,A.idAlum,B.cedula,B.nombre, B.apellido, B.grado,B.seccion,B.Periodo, C.nombreUser, D.abrev FROM pagos".$tablaPeriodo." A, alumcer B, user C, formas_pago D WHERE A.$reciboTabla='$recibo' and A.banco='$cod_banco1' and A.idAlum=B.idAlum and A.emitidoPor=C.idUser and A.operacion=D.id GROUP BY A.$reciboTabla,A.operacion  ");
    $monto=0;
    while($row2=mysqli_fetch_array($pagos_query)) 
    {
    	$nrodeposito=$row2['nrodeposito'];
    	$fecDep=$row2['fechadepo'];
    	$fecha = date("d-m-Y", strtotime($row2['fechadepo']));
    	$monto=$row2['todo'];
   	   	$nombrePago=$row2['abrev'];
	    if ($monto>0 && $fecDep>=$desBus && $fecDep<=$hasBus) {
	    	$totBanco=$totBanco+$monto;
		    $pdf->SetFont('Arial','',9);
			$pdf->SetX(12);
			if($l==1)
			{
				$pdf->SetX(5);
				$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C');
				$pdf->Cell(40,5, $nrodeposito.' / '.$nombrePago,0,0,'L');
				$pdf->Cell(20,5, $fecha,0,0,'C');
				$pdf->Cell(25,5, number_format($monto,2,',','.'),0,1,'R');
				$l=2;
			}else
			{
				$pdf->SetX(5);
				$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C',1);
				$pdf->Cell(40,5, $nrodeposito.' / '.$nombrePago,0,0,'L',1);
				$pdf->Cell(20,5, $fecha,0,0,'C',1);
				$pdf->Cell(25,5, number_format($monto,2,',','.'),0,1,'R',1);
				$l=1;
			}
		}
	}
}
$pdf->Ln(3);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(130,6,'Ingresos al Banco por Miscelaneos ',0,1,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetX(5);
$pdf->Cell(25,6, 'Recibo',1,0,'C',1);
$pdf->Cell(40,6, '# Operacion',1,0,'C',1);
$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
$pdf->Cell(25,6, 'Bolivares',1,1,'C',1);
$pdf->SetFont('Arial','',9);
$l=1;
while ($row = mysqli_fetch_array($miscela_query))
{
	$recibo=$row['id'];
    $tablaPeriodo=$row['tabla'];
    $pagos_miscela_query = mysqli_query($link,"SELECT sum(A.monto) as todo,A.fechadepo,A.nrodeposito,A.idAlum,B.cedula,B.nombre, B.apellido, B.grado,B.seccion,B.Periodo, C.nombreUser,D.abrev FROM miscelaneos A, alumcer B, user C, formas_pago D WHERE A.statusPago=1 and A.recibo='$recibo' and A.banco='$cod_banco1' and A.idAlum=B.idAlum and A.emitidoPor=C.idUser and A.operacion=D.id GROUP BY A.recibo,A.operacion  ");
    $monto=0;
    while($row2=mysqli_fetch_array($pagos_miscela_query)) 
    {
    	$nrodeposito=$row2['nrodeposito'];
    	$fecDep=$row2['fechadepo'];
    	$fecha = date("d-m-Y", strtotime($row2['fechadepo']));
    	$monto=$row2['todo'];
    	$nombrePago=$row2['abrev'];
	    if ($monto>0 && $fecDep>=$desBus && $fecDep<=$hasBus) {
	    	$totBanco=$totBanco+$monto;
		    $pdf->SetFont('Arial','',9);
			$pdf->SetX(12);
			if($l==1)
			{
				$pdf->SetX(5);
				$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C');
				$pdf->Cell(40,5, $nrodeposito.' / '.$nombrePago,0,0,'L');
				$pdf->Cell(20,5, $fecha,0,0,'C');
				$pdf->Cell(25,5, number_format($monto,2,',','.'),0,1,'R');
				$l=2;
			}else
			{
				$pdf->SetX(5);
				$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C',1);
				$pdf->Cell(40,5, $nrodeposito.' / '.$nombrePago,0,0,'L',1);
				$pdf->Cell(20,5, $fecha,0,0,'C',1);
				$pdf->Cell(25,5, number_format($monto,2,',','.'),0,1,'R',1);
				$l=1;
			}
		}
	}
}
$pdf->SetFont('Arial','B',11);
$pdf->Cell(130,6,'Egresos del Banco ',0,1,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetX(5);
$pdf->Cell(25,6, 'Recibo',1,0,'C',1);
$pdf->Cell(40,6, '# Operacion',1,0,'C',1);
$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
$pdf->Cell(25,6, 'Bolivares',1,1,'C',1);
$pdf->SetFont('Arial','',9);
$l=1;
$egresos_query = mysqli_query($link,"SELECT sum(A.montoBs) as todo,A.recibo, A.fecha_depo,A.refePag,A.id_provee,B.cedula,B.nombre, B.apellido, C.nombreUser, D.abrev FROM egresos A, alumcer B, user C, formas_pago D WHERE A.fecha_depo>='$desBus' and A.fecha_depo<='$hasBus' and  A.banco='$cod_banco1' and A.id_provee=B.idAlum and A.emitidoPor=C.idUser and A.operacion=D.id GROUP BY A.recibo,A.operacion  ");
$monto2=0; $totBanco2=0;
while($row3=mysqli_fetch_array($egresos_query)) 
{
	$recibo=$row3['recibo'];
    $monto2=$row3['todo'];
    $totBanco2=$totBanco2+$monto2;
    $nrodeposito=$row3['refePag'];
    $fechadepo=date("d-m-Y", strtotime($row3['fecha_depo']));
    $idAlum=$row3['id_provee'];
    $cedula=$row3['cedula'];
    $alumno=($row3['apellido'].' '.$row3['nombre']);
    $emitidoPor=$row3['nombreUser'];
    $nombrePago=$row3['abrev'];
    if ($monto2>0) {
	    $pdf->SetFont('Arial','',9);
		$pdf->SetX(12);
		if($l==1)
		{
			$pdf->SetX(5);
			$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C');
			$pdf->Cell(40,5, $nrodeposito.' / '.$nombrePago,0,0,'L');
			$pdf->Cell(20,5, $fechadepo,0,0,'C');
			$pdf->Cell(25,5, number_format($monto2,2,',','.'),0,1,'R');
			$l=2;
		}else
		{
			$pdf->SetX(5);
			$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C',1);
			$pdf->Cell(40,5, $nrodeposito.' / '.$nombrePago,0,0,'L',1);
			$pdf->Cell(20,5, $fechadepo,0,0,'C',1);
			$pdf->Cell(25,5, number_format($monto2,2,',','.'),0,1,'R',1);
			$l=1;
		}
	}
}
$pdf->SetFont('Arial','',11);
$pdf->Ln(5);
$pdf->SetX(15);
$pdf->Cell(90,5, '*** TOTALES ***',1,1,'C',1);	
$pdf->SetX(15);
$pdf->Cell(60,5, 'Ingresos en Banco -> ',1,0,'L');	
$pdf->Cell(30,5, 'Bs. '.number_format($totBanco,2,'.',','),1,1,'R');
$pdf->SetX(15);
$pdf->Cell(60,5, 'Egresos del Banco -> ',1,0,'L');	
$pdf->Cell(30,5, 'Bs. '.number_format($totBanco2,2,'.',','),1,1,'R');
$pdf->SetX(15);
$pdf->Cell(60,5, 'Fondo Disponible -> ',1,0,'L');	
$pdf->Cell(30,5, 'Bs. '.number_format($totBanco-$totBanco2,2,'.',','),1,1,'R');
mysqli_free_result($ingresos_query);
mysqli_free_result($bancos_query);
mysqli_free_result($pagos_query);
mysqli_free_result($egresos_query);
$pdf->Output();
?>