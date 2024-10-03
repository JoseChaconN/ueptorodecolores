<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
$link = Conectarse();
require('../include/fpdf/fpdf.php');
include_once("../../../inicia.php");
$filtro = (!empty($_GET['filtro'])) ? '%'.$_GET['filtro'].'%' : '' ;
$desde = $_GET["desde"];
$hasta = $_GET["hasta"];
$resultado = mysqli_query($link,"SELECT A.*, B.apellido, B.nombre, C.nombreGrado FROM pagos A, alumcer B, grado2324 C WHERE A.recibo is NULL and A.fecha>='$desde' AND A.fecha<='$hasta' and A.ced_alu=B.cedula and B.grado=C.grado order by A.fecha");
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		$this->Addpage();
		$this->SetFillColor(232,232,232);
	}
	function Header()
	{
		global $desde;
		global $hasta;
		$this->Image('../../../imagenes/logo.jpg',10,1,20);
		$this->Cell(35);
		$this->SetFont('Arial','',12);
		$this->Cell(30,6,'Confirmar pagos desde: '.date("d-m-Y", strtotime($desde)).' hasta: '.date("d-m-Y", strtotime($hasta)),0,1,'L');
		$this->Ln(8);
		$this->SetFont('Arial','',9);
	}
	function Footer()
	{
		$this->SetY(-25);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf = new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',9);
$l=0;
while ($row = mysqli_fetch_array($resultado))
{
	$l++;
	$opera='';
	if($row['operacion']=='T')
	{$opera='Transf.';}
	if($row['operacion']=='D')
	{$opera='Deposi.';}
	if($row['operacion']=='Pa')
	{$opera='P.Movi.';}
	
	$ced_alu=$row['ced_alu'];
	$alumno=utf8_decode($row['apellido'].' '.$row['nombre']);
	$realizado = date("d-m-Y", strtotime($row['fechadepo']));
	$enviado = date("d-m-Y", strtotime($row['fecha']));
	$monto=$row['monto'];
	$rif_titular=$row['rif_titular'];
	$nombre_titular=utf8_decode($row['nombre_titular']);
	$bancoemisor=$row['bancoemisor'];
	$banco=utf8_decode($row['banco']);
	$nombreGrado=substr($row['nombreGrado'],0,18);
	$nrodeposito=$row['nrodeposito'];
	$concepto=utf8_decode($row['concepto']);
	$comentario=utf8_decode($row['comentario']);
	//$=$row[''];
	
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(2);
	
	$pdf->SetX(2);
	$pdf->Cell(25,6, 'Cedula',1,0,'C',1);
	$pdf->Cell(80,6, 'Alumno',1,0,'C',1);
	$pdf->Cell(30,6, 'Grado',1,0,'C',1);
	$pdf->Cell(30,6, 'C.I./Rif',1,0,'C',1);
	$pdf->Cell(70,6, 'Nombre',1,0,'C',1);
	$pdf->Cell(45,6, 'Banco Emisor',1,0,'C',1);
	$pdf->Cell(45,6, 'Banco Receptor',1,0,'C',1);
	$pdf->Cell(25,6, 'Realizado',1,1,'C',1);

	$pdf->SetX(2);
	$pdf->Cell(25,5, $ced_alu,1,0,'L');
	$pdf->Cell(80,5, $alumno,1,0,'L');
	$pdf->Cell(30,5, $nombreGrado,1,0,'L');
	$pdf->Cell(30,5, $rif_titular,1,0,'L');
	$pdf->Cell(70,5, $nombre_titular,1,0,'L');
	$pdf->Cell(45,5, $bancoemisor,1,0,'L');
	$pdf->Cell(45,5, $banco,1,0,'L');
	$pdf->Cell(25,5, $realizado,1,1,'L');
	$pdf->SetX(2);
	$pdf->Cell(30,5, 'Monto: '.number_format($monto,2,',','.'),1,0,'L');
	$pdf->Cell(55,5, $opera.' Nro.'.$nrodeposito,1,0,'L');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(120,5, 'Concepto: '.$concepto,1,0,'L');
	$pdf->Cell(145,5, 'Comenta: '.$comentario,1,1,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Ln(5);
	
	if ($l>=7) 
	{
		$pdf->Addpage();
		$l=0;
	}
	
}
$pdf->SetFont('Arial','',11);
$pdf->Ln(5);
$pdf->SetX(15);
mysqli_free_result($resultado);
mysqli_close($link);

$pdf->Output();

?>