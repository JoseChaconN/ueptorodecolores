<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
include_once("../../../inicia.php");

require('../include/fpdf/fpdf.php');
$desde=$_GET['des'];
$hasta=$_GET['has'];
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		/*$this->Addpage();
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','B',9);
		//$this->SetX(15);
		$this->Cell(30,6, 'Cedula',1,0,'C',1);
		$this->Cell(70,6, 'Estudiantes',1,0,'C',1);
		$this->Cell(30,6, utf8_decode('Cursando'),1,0,'C',1);
		$this->Cell(35,6, 'Telefono',1,0,'C',1);
		$this->Cell(20,6, 'Solicita',1,1,'C',1);
		$this->SetFont('Arial','',8);*/
	}
	function Header()
	{
		$this->Image('../../../imagenes/logo.png',10,8,20);
		$this->SetFont('Arial','',15);
		$this->Cell(80);
		$this->Cell(30,6,utf8_decode(NKXS),0,1,'C');
		$this->Cell(80);
		$this->Cell(30,6,utf8_decode(EKKS),0,1,'C');
		$this->Cell(80);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');
		$this->Cell(80);
		$this->Cell(30,5,'Rif.: '.RIFCOLM,0,1,'C');
		$this->Ln(6);
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','B',13);
		$this->Cell(190,6, 'Cambios de cedula realizados en el sistema',0,1,'C');
		$this->SetFont('Arial','B',9);
		$this->Cell(30,6, 'Ced.Actual',1,0,'C',1);
		$this->Cell(30,6, 'Ced.Vieja',1,0,'C',1);
		$this->Cell(70,6, 'Estudiante',1,0,'C',1);
		$this->Cell(30,6, 'En Fecha',1,0,'C',1);
		$this->Cell(35,6, 'Procesado por',1,1,'C',1);
		
	}
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$link = Conectarse();
$alumno_query = mysqli_query($link,"SELECT A.*,B.nombre,B.apellido FROM cambio_ced A, alumcer B  WHERE A.idAlum=B.idAlum and A.fecha>='$desde' and A.fecha<='$hasta' ORDER BY A.fecha ASC ");

		
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
/*$pdf->SetFont('Arial','B',13);
$pdf->Cell(190,6, 'Cambios de cedula realizados en el sistema',0,1,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(30,6, 'Ced.Actual',1,0,'C',1);
$pdf->Cell(30,6, 'Ced.Vieja',1,0,'C',1);
$pdf->Cell(70,6, 'Estudiante',1,0,'C',1);
$pdf->Cell(30,6, 'En Fecha',1,0,'C',1);
$pdf->Cell(35,6, 'Procesado por',1,1,'C',1);*/

$rein=0; $reti=0;
while ($row = mysqli_fetch_array($alumno_query))
{
	$cedula=$row['cedula'];
    $cedula_vie=$row['cedula_vie'];
    $alumno=$row["apellido"].' '.$row["nombre"];
    $fecha=$row['fecha'];
    $usuario=$row['usuario'];
	
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(30,5, $cedula,0,0,'R');
	$pdf->Cell(30,5, $cedula_vie,0,0,'R');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(70,5, substr(utf8_decode($alumno),0,40),0,0,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(30,5, date("d-m-Y H:i", strtotime($fecha)),0,0,'L');
	$pdf->Cell(35,5, $usuario,0,1,'L');
	
	$rein++;
}

$pdf->Output();
	
?>
