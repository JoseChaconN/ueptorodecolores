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
$grado = $_GET["idG"];
$seccion = $_GET["idS"];
$tablaPeriodo = $_GET["peri"];
$nomPeriodo = $_GET["nomP"];
$concepVer = $_GET["conc"];
$concepto1_query = mysqli_query($link,"SELECT * FROM conceptos WHERE id='$concepVer' ");
while($row1 = mysqli_fetch_array($concepto1_query))
{
    $concepto=$row1['concepto'];
}
if ($grado==1) {
	$nomGrado='(Inicial y Primaria) seccion: ';
}
if ($grado==2) {
	$nomGrado='(Media General) seccion: ';
}
if ($grado>2) {
	$grado_query = mysqli_query($link,"SELECT A.nombreGrado,B.nombre FROM grado".$tablaPeriodo." A, secciones B WHERE A.grado='$grado' and B.id='$seccion' ");
	while($row1 = mysqli_fetch_array($grado_query))
	{
	    $nomGrado=utf8_decode($row1['nombreGrado']);
	    $nomsecci=$row1['nombre'];
	}
}
if ($grado<3) {
	$grado_query = mysqli_query($link,"SELECT B.nombre FROM secciones B WHERE B.id='$seccion' ");
	while($row1 = mysqli_fetch_array($grado_query))
	{
	    $nomsecci=$row1['nombre'];
	}
}
if($grado==1)
{
	$alumnos_query=mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.grado<60 and D.idSeccion='$seccion' and B.grado=D.grado and C.id='$seccion' ORDER BY A.apellido ASC");
}
if($grado==2)
{
	$alumnos_query=mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.grado>60 and D.idSeccion='$seccion' and B.grado=D.grado and C.id='$seccion' ORDER BY A.apellido ASC");
}
if($grado>60)
{
	$alumnos_query=mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.grado='$grado' and D.idSeccion='$seccion' and B.grado='$grado' and C.id='$seccion' ORDER BY A.apellido ASC");
}
if($grado>40 && $grado<60)
{
	$alumnos_query=mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.grado='$grado' and D.idSeccion='$seccion' and B.grado='$grado' and C.id='$seccion' ORDER BY A.apellido ASC");
}

require('../include/fpdf/fpdf.php');

class PDF extends FPDF 
{
	function Header()
	{
		$nomPeri = ($_GET["nomP"]>0) ? $_GET["nomP"] : 'Todos' ;
		global $grado;
		global $concepto;
		global $nomGrado;
		global $nomsecci;
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
		if ($grado>60) {
			$this->Cell(180,6,'Listado de Estudiantes (Media General)',0,1,'L');
		}else
		{
			$this->Cell(180,6,'Listado de Estudiantes (Inicial y Primaria)',0,1,'L');
		}
		
		$this->SetFont('Arial','',10);
		$this->Cell(25);
		$this->Cell(180,6,$nomGrado.' '.$nomsecci.' '.$nomPeri,0,1,'L');
		$this->SetFont('Arial','B',11);
		$this->Cell(180,6,'Pagos realizados por '.$concepto,0,1,'C');
		$this->SetFont('Arial','B',9);
		$this->SetX(5);
		$this->Cell(8,6, 'Nro.',1,0,'C',1);
		$this->Cell(25,6, 'Cedula',1,0,'C',1);
		$this->Cell(103,6, 'Estudiantes',1,0,'C',1);
		$this->Cell(15,6, 'Pagado',1,0,'C',1);
		$this->Cell(45,6, 'Telefono',1,1,'C',1);
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
while ($row = mysqli_fetch_array($alumnos_query))
{
	$idAlum=$row['idAlum'];
	$pagos_query = mysqli_query($link,"SELECT SUM(montoDolar) as monto FROM pagos".$tablaPeriodo."  WHERE idAlum ='$idAlum' and id_concepto='$concepVer' and statusPago='1' GROUP BY recibo "); 
    $pago=0;
    while($rowx=mysqli_fetch_array($pagos_query)) 
    {   
        $pago=$pago+$rowx['monto'];
    }
	$van=$van+1;
	$pdf->SetX(5);
	$pdf->Cell(8,5, $van,1,0,'C');
	$pdf->Cell(25,5, $row['cedula'],1,0,'L');
	$pdf->Cell(103,5, utf8_decode($row['apellido']).' '.utf8_decode($row['nombre']),1,0,'L');
	$pdf->Cell(15,5, number_format($pago,2,'.',',').' $',1,0,'R');
	$pdf->Cell(45,5, $row['telefono'] ,1,1,'C');
	$pagado=$pagado+$pago;
}
$pdf->Ln(1);
$pdf->SetFillColor(205,246,204);
$pdf->SetX(101);
$pdf->Cell(30,5, 'TOTALES-->',0,0,'L',1);
$pdf->Cell(25,5, number_format($pagado,2,',','.').' $',0,0,'R',1);
mysqli_free_result($concepto1_query);
mysqli_free_result($grado_query);
mysqli_free_result($alumnos_query);
mysqli_free_result($pagos_query);
$pdf->Output();



?>
