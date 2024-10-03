<?php
session_start();
if(!isset($_SESSION['usuario']) && !isset($_SESSION['password']) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaHora = date("d-m-Y H:i:s");
$link = Conectarse();
include_once("../../../inicia.php");
include_once("../../include/funciones.php");
$idAlum = desencriptar($_GET['id']);
$tablaPeriodo=$_GET['peri'];	
$anio=$_GET['anio'];
$pago=$_GET['pago'];
$moro=$_GET['moro'];
$resultado = mysqli_query($link,"SELECT A.nacion,A.cedula,A.nombre,A.apellido,A.ced_rep,A.Periodo,A.direccion, B.*, C.nombreGrado FROM alumcer A, parentescos B, grado".$tablaPeriodo." C WHERE A.idAlum = '$idAlum' and B.idparen = A.parentesco and A.grado=C.grado ");
while ($row = mysqli_fetch_array($resultado))
{
	$nac_alu = $row['nacion'];
	$ced_alu = $row['cedula'];
	$periodo = $row['Periodo'];
	$ape_alu = utf8_decode($row['apellido']);
	$nom_alu = utf8_decode($row['nombre']);
	$ced_rep = $row['ced_rep'];
	$par_rep = $row['nomparen'];
	$gra_alu = utf8_decode($row['nombreGrado']);
	$dir_alu = utf8_decode($row['direccion']);
}
$resultado2 = mysqli_query($link,"SELECT * FROM represe WHERE cedula = '$ced_rep' ORDER BY cedula ASC");
while ($row = mysqli_fetch_array($resultado2))
{
	$cedu_rep = ($row['cedula']);
	$nom_rep=utf8_decode($row['representante']);
	$tlf_rep = ($row['telefono']);
	$dir_tra_rep = utf8_decode($row['dir_trab_rep']);
	$dir_rep = utf8_decode($row['direccion']);
	$lug_rep = utf8_decode($row['lug_trabaj']);
	$mai_rep = $row['correo'];
	$tcel_rep = ($row['tlf_celu']);
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		$this->Addpage();
		$this->SetFillColor(232,232,232);
	}
	function Header()
	{
		
	}
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->Image('../../../imagenes/logo.jpg',10,8,30,30);
$a=1;
if($a==1)
{
	$pdf->SetFont('Times','',15);
	$pdf->Cell(35);
	$pdf->Cell(100,5,utf8_decode(NKXS),0,1,'L');
	$pdf->Cell(35);
	$pdf->Cell(100,5,utf8_decode(EKKS),0,1,'L');
	$pdf->SetFont('Times','',12);
	$pdf->Cell(35);
	$pdf->Cell(100,5,CKLS,0,1,'L');
	$pdf->Cell(35);
	$pdf->Cell(100,5,'Tlf.'.TELEMPM,0,1,'L');
	$pdf->Cell(35);
	$pdf->Cell(100,5,'Email.'.SUCORREO,0,1,'L');
	$pdf->Ln(3);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetX(10);
	$pdf->Cell(30,4,utf8_decode('Datos del estudiante:'),0,1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Cedula',1,0,'L',1);
	$pdf->Cell(80,4,'Apellidos',1,0,'L',1);
	$pdf->Cell(80,4,'Nombres',1,1,'L',1);
}
if($a==1)
{
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(30,5, $nac_alu.'-'.$ced_alu,1,0,'L');
	$pdf->Cell(80,5, $ape_alu,1,0,'L');
	$pdf->Cell(80,5, $nom_alu,1,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(25,4,'Periodo',1,0,'L',1);
	$pdf->Cell(50,4,utf8_decode('Grado/Año'),1,0,'L',1);
	$pdf->Cell(115,4, utf8_decode('Dirección'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(25,5, $periodo,1,0,'L');
	$pdf->Cell(50,5, $gra_alu,1,0,'L');
	$pdf->Cell(115,5, $dir_alu,1,1,'L');
}
if($a==1) //Representante
{
	$pdf->Ln(2);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Datos del representante legal'),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Cedula',1,0,'L',1);
	$pdf->Cell(120,4,'Nombres y Apellidos',1,0,'L',1);
	$pdf->Cell(40,4,'Parentesco',1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(30,5, $cedu_rep,1,0,'L');
	$pdf->Cell(120,5, $nom_rep,1,0,'L');
	$pdf->Cell(40,5, $par_rep,1,1,'L');

	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Fecha Nac.',1,0,'L',1);
	$pdf->Cell(120,4,'Direccion',1,0,'L',1);
	$pdf->Cell(40,4,'Celular',1,1,'L',1);

	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(30,5, date("d-m-Y", strtotime($fnac_repre)),1,0,'L');
	$pdf->Cell(120,5, $dir_rep,1,0,'L');
	$pdf->Cell(40,5, $tcel_rep,1,1,'L');

	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(80,4,'E-mail',1,0,'L',1);
	$pdf->Cell(30,4,'Tlf. de Habitacion',1,0,'L',1);
	$pdf->Cell(80,4,'Lugar de Trabajo',1,1,'L',1);

	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(80,5, $mai_rep,1,0,'L');
	$pdf->Cell(30,5, $tlf_rep,1,0,'L');
	$pdf->Cell(80,5, $lug_rep,1,1,'L');
}
$pdf->Ln(2);
$pdf->Cell(80);
$pdf->SetFont('Arial','B',12);
$pdf->SetX(10);
$pdf->Cell(190,6, utf8_decode('ESTADO DE CUENTA ('.$fechaHora.')'),0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(10);
$pdf->Cell(50,4,'Monto del Periodo',1,0,'L',1);
$pdf->Cell(45,4,'Monto Pagado',1,0,'L',1);
$pdf->Cell(45,4,'Monto por Pagar',1,0,'L',1);
$pdf->Cell(50,4,'Morosidad a la Fecha',1,1,'L',1);
$pdf->SetFont('Arial','',12);
$pdf->SetX(10 );
$pdf->Cell(50,8, number_format($anio,2,',','.'),1,0,'C');
$pdf->Cell(45,8, number_format($pago,2,',','.'),1,0,'C');
$pdf->Cell(45,8, number_format(($anio-$pago),2,',','.'),1,0,'C');
$pdf->Cell(50,8, number_format($moro,2,',','.'),1,1,'C');
mysqli_free_result($resultado);
mysqli_free_result($resultado2);
mysqli_close($link);

$pdf->Output();


?>
