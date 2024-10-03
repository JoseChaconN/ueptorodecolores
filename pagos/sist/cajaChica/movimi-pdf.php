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
$id_caja_chica = $_GET["caja"];
$disponible = desencriptar($_GET["fondo"]);
$simMoneda=$_GET['simbo'];
$nomMoneda=$_GET['moned'];
$nomCaja=$_GET['nombre'];

$caja_query = mysqli_query($link,"SELECT A.*, B.nombre_tipo, C.nombreUser,C.apellidoUser FROM caja_chica_movi A, caja_chica_tipo B, user C WHERE A.id_caja_chica='$id_caja_chica' and A.tipo_mov=B.id and A.usuario=C.idUser ");

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
		$this->Cell(35,6, 'Fecha/Hora',1,0,'C',1);
		$this->Cell(40,6, 'Operacion',1,0,'C',1);
		$this->Cell(30,6, 'Monto',1,0,'C',1);
		$this->Cell(70,6, 'Procesado Por',1,1,'C',1);
		$this->Ln();
		$this->SetFont('Arial','',9);
		$this->SetX(5);
	}
	function Header()
	{
		global $nomCaja;
		global $nomMoneda;
		$this->Image('../../../imagenes/logo.jpg',10,8,20);
		$this->SetFont('Arial','',15);
		$this->Cell(25);
		$this->Cell(30,6,NKXS,0,1,'L');
		$this->Cell(25);
		$this->Cell(30,6,utf8_decode(EKKS),0,1,'L');
		$this->Cell(25);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'L');
		$this->Cell(25);
		$this->Cell(30,5,'Rif.: '.RIFCOLM,0,1,'L');
		$this->Ln(5);
		$this->Cell(5);
		$this->SetFont('Arial','B',11);
		$this->MultiCell(180,6,'Movimientos de Caja Chica '.$nomCaja.' Moneda:('.$nomMoneda.')',0,'C');
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
$pdf->SetFont('Arial','B',9);
$pdf->SetX(5);
$pdf->Cell(25,6, 'Recibo',1,0,'C',1);
$pdf->Cell(35,6, 'Fecha/Hora',1,0,'C',1);
$pdf->Cell(40,6, 'Operacion',1,0,'C',1);
$pdf->Cell(30,6, 'Monto',1,0,'C',1);
$pdf->Cell(70,6, 'Procesado Por',1,1,'C',1);
$pdf->SetFont('Arial','',9);
$l=1;
$dispon=0;
while ($row = mysqli_fetch_array($caja_query))
{
	$recibo=$row['id'];
    $concepto=utf8_decode($row['concepto']);
    $fecha=date("d-m-Y / H:i:s ", strtotime($row['fecha_mov']));
    $monto=$row['monto'];
    $nombre_tipo=$row['nombre_tipo'];
    $usuario=ucwords(strtolower($row['nombreUser'].' '.$row['apellidoUser']));
    $simbOpera = ($row['tipo_operacion']==1) ? '+' : '-' ;
    if ($row['tipo_operacion']==1) {
    	$dispon=$dispon+$monto;
    }else{$dispon=$dispon-$monto;}
    $pdf->SetFont('Arial','',9);
	$pdf->SetX(12);
	if($l==1)
	{
		$pdf->SetX(5);
		$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C');
		$pdf->Cell(35,5, $fecha,0,0,'C');
		$pdf->Cell(40,5, $nombre_tipo,0,0,'L');
		$pdf->Cell(30,5, $simMoneda.' '.number_format($monto,2,',','.').' '.$simbOpera,0,0,'R');
		$pdf->Cell(70,5, $usuario,0,1,'L');
		$pdf->SetX(5);
		$pdf->Cell(200,5, 'Motivo: '.$concepto,0,1,'L');
		$l=2;
	}else
	{
		$pdf->SetX(5);
		$pdf->Cell(25,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C',1);
		$pdf->Cell(35,5, $fecha,0,0,'C',1);
		$pdf->Cell(40,5, $nombre_tipo,0,0,'L',1);
		$pdf->Cell(30,5, $simMoneda.' '.number_format($monto,2,',','.').' '.$simbOpera,0,0,'R',1);
		$pdf->Cell(70,5, $usuario,0,1,'L',1);
		$pdf->SetX(5);
		$pdf->Cell(200,5, 'Motivo: '.$concepto,0,1,'L',1);
		$l=1;
	}
	
}


$pdf->SetFont('Arial','',11);
$pdf->Ln(5);
$pdf->SetX(15);
$pdf->Cell(90,5, '*** TOTALES ***',1,1,'C',1);	
$pdf->SetX(15);
$pdf->Cell(60,5, 'Disponible en Caja Chica -> ',1,0,'L');	
$pdf->Cell(30,5, $simMoneda.' '.number_format($dispon,2,'.',','),1,1,'R');
/*$pdf->SetX(15);
$pdf->Cell(60,5, 'Egresos del Banco -> ',1,0,'L');	
$pdf->Cell(30,5, 'Bs. '.number_format($totBanco2,2,'.',','),1,1,'R');
$pdf->SetX(15);
$pdf->Cell(60,5, 'Fondo Disponible -> ',1,0,'L');	
$pdf->Cell(30,5, 'Bs. '.number_format($totBanco-$totBanco2,2,'.',','),1,1,'R');*/
mysqli_free_result($caja_query);
$pdf->Output();

?>