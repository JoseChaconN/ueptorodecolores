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
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{
		$nomPeri = ($_GET["nomP"]>0) ? $_GET["nomP"] : 'Todos' ;
		$this->Image('../../../imagenes/logo.png',10,8,20);
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','',12);
		$this->Cell(25);
		$this->Cell(30,5,utf8_decode(NKXS),0,1,'L');
		$this->Cell(25);
		$this->Cell(30,5,utf8_decode(EKKS),0,1,'L');
		$this->Cell(25);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'L');

		$this->SetFont('Arial','',13);
		$this->Cell(25);
		$this->Cell(180,6,'Stock disponible por articulo',0,1,'L');
		$this->SetFont('Arial','',9);
		$this->Cell(70,6,'Articulo',0,0,'L');
		$this->Cell(20,6,'Precio',0,0,'L');
		$this->Cell(20,6,'Ingresado',0,0,'L');
		$this->Cell(20,6,'Vendidos',0,0,'L');
		$this->Cell(20,6,'Disponibles',0,1,'L');
	}
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFont('Arial','',8);
			
$van=0; $pagado=0;
$concepto_query = mysqli_query($link,"SELECT * FROM miscelaneos_conceptos WHERE articulo=1 and status=1  ");
while ($row = mysqli_fetch_array($concepto_query))
{
	$id=$row['id'];
	$concepto=$row['concepto'];
    $monto=$row['monto'];

	$stock_query = mysqli_query($link,"SELECT SUM(IF(proceso=1 and statusPago=1,cantidad,0)) as suma, SUM(IF(proceso=2 and statusPago=1,cantidad,0)) as resta FROM miscelaneos WHERE id_concepto='$id' ");
	$sto=mysqli_fetch_array($stock_query);
	$dispo=$sto['suma']-$sto['resta'];
	$van=$van+1;
	//$pdf->SetX(5);
	$pdf->Cell(70,5, utf8_decode($concepto),1,0,'L');
	$pdf->Cell(20,5, number_format($monto,2,'.',',').' $',1,0,'R');
	$pdf->Cell(20,5, $sto['suma'],1,0,'R');
	$pdf->Cell(20,5, $sto['resta'],1,0,'R');
	$pdf->Cell(20,5, $dispo,1,1,'R');
	$total=$total+($dispo*$monto);
}
$pdf->Ln(1);
$pdf->SetFillColor(205,246,204);
$pdf->SetX(101);
$pdf->Cell(30,5, 'TOTAL EN STOCK-->',0,0,'L',1);
$pdf->Cell(30,5, number_format($total,2,',','.').' $',0,0,'R',1);
mysqli_free_result($concepto_query);
$pdf->Output();



?>
