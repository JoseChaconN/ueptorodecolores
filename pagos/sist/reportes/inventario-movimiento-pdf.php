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
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$id_concepto=$_GET['idCon'];
$concepto=$_GET['conce'];
$stock_query = mysqli_query($link,"SELECT SUM(IF(proceso=1 and statusPago=1,cantidad,0)) as suma, SUM(IF(proceso=2 and statusPago=1,cantidad,0)) as resta FROM miscelaneos WHERE id_concepto='$id_concepto'  ");
$sto=mysqli_fetch_array($stock_query);
$dispo=$sto['suma']-$sto['resta'];
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{
		global $concepto;
		global $dispo;
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
		$this->Cell(180,6,'Movimiento de Inventario ('.$concepto.') Disponibles: '.$dispo,0,1,'L');
		$this->SetFont('Arial','',9);
		$this->Cell(10,6,'Nro',0,0,'L');
		$this->Cell(15,6,'Recibo',0,0,'L');
		$this->Cell(20,6,'Fecha',0,0,'L');
		$this->Cell(90,6,'Estudiante',0,0,'L');
		$this->Cell(15,6,'Cantidad',0,1,'L');
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


$van=0;
$vendidos_query = mysqli_query($link,"SELECT A.idAlum,A.recibo,A.fecha,A.proceso,A.cantidad,B.apellido, B.nombre FROM miscelaneos A, alumcer B WHERE A.statusPago=1 and A.id_concepto='$id_concepto' and A.fecha>='$desde' and A.fecha<='$hasta' and A.idAlum>0 and A.idAlum=B.idAlum ORDER BY A.recibo ");

while ($row = mysqli_fetch_array($vendidos_query))
{
	$van++;
	$recibo=$row['recibo'];
	$fecha=date("d-m-Y", strtotime($row['fecha']));
    $alumno=$row['apellido'].' '.$row['nombre'];
    $cantidad=$row['cantidad'];
    $pdf->Cell(10,5, str_pad($van, 2, "0", STR_PAD_LEFT),1,0,'C');
	$pdf->Cell(15,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),1,0,'C');
	$pdf->Cell(20,5, $fecha,1,0,'C');
	$pdf->Cell(90,5, $alumno,1,0,'L');
	$pdf->Cell(15,5, $cantidad,1,1,'R');
	
	$total=$total+$cantidad;
}
$pdf->Ln(1);
$pdf->SetFillColor(205,246,204);
$pdf->SetX(101);
$pdf->Cell(30,5, 'TOTAL Vendidas-->',0,0,'L',1);
$pdf->Cell(30,5, str_pad($total, 2, "0", STR_PAD_LEFT).' Unid.',0,0,'R',1);
mysqli_free_result($concepto_query);
$pdf->Output();



?>
