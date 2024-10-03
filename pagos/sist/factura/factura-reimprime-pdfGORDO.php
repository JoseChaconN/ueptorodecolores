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

require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{}
	function Footer()
	{}
	
}
#error_reporting(E_ALL);
#ini_set('display_errors', '1');
#$pdf=new PDF('L','mm',array(216,150));
#$pdf->AliasNbPages();
#$pdf->Addpage();
// Crea una nueva instancia de FPDF
$pdf = new FPDF();
#$pdf->AddPage();

// Define el tamaño personalizado de la página (216 mm x 150 mm)
$width = 216;
$height = 150;

// Establecer el área imprimible para ajustar el tamaño de la página
$pdf->SetAutoPageBreak(false); // Deshabilitar el salto automático de página
$pdf->SetMargins(5, 5); // Establecer márgenes (0.5 mm = 5 en FPDF)
$pdf->AddPage('L', array($width, $height)); // Añadir una nueva página con el tamaño personalizado
$pdf->AddPage('P', array($width, $height));
$pdf->SetFillColor(232,232,232);
#$mgIzq=0.5;
//$copIzq=$mgIzq+105;
$pdf->SetFont('Arial','B',9);
$pdf->Ln(15);
#$pdf->setX($mgIzq);
#$pdf->Cell(130,1, '',0,0,'L');
$pdf->SetFont('Arial','',8);
#$pdf->setX($mgIzq);
$pdf->Cell(78,4, utf8_decode('Nombre o Razón Social:José Gregorio Chacón # 000002 '),0,1,'L');

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
