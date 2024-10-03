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


if($grado=='0' and $seccion=='0')
{
	$alumnos_query=mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion,A.apellido ASC");
}
if($grado>0 and $seccion=='0')
{
	$alumnos_query=mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.grado='$grado' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion,A.apellido ASC");
}
if($grado=='0' and $seccion>0)
{
	$alumnos_query=mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.idSeccion='$seccion' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion,A.apellido ASC");
}
if($grado>0 && $seccion>0)
{
	$alumnos_query=mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.sexo, A.telefono, B.nombreGrado, C.nombre as nom_sec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.statusAlum='1' and D.grado='$grado' and D.idSeccion='$seccion' and B.grado='$grado' and C.id='$seccion' ORDER BY A.apellido ASC");
}


require('../include/fpdf/fpdf.php');

class PDF extends FPDF 
{
	function Header()
	{
		$nomPeri = ($_GET["nomP"]>0) ? $_GET["nomP"] : 'Todos' ;
		$this->Image('../../../imagenes/logo.png',10,8,20);
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','',12);
		$this->Cell(80);
		$this->Cell(30,5,utf8_decode(NKXS),0,1,'C');
		$this->Cell(80);
		$this->Cell(30,5,utf8_decode(EKKS),0,1,'C');
		$this->Cell(80);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');

		$this->SetFont('Arial','',13);
		$this->Cell(180,6,'Listado de Estudiantes (Media General)',0,1,'C');
		$this->SetFont('Arial','',10);
		$this->Cell(180,6,'Periodo Escolar '.$nomPeri,0,1,'C');
		$this->SetFont('Arial','B',9);
		$this->SetX(5);
		$this->Cell(8,6, 'Nro.',1,0,'C',1);
		$this->Cell(25,6, 'Cedula',1,0,'C',1);
		$this->Cell(90,6, 'Estudiantes',1,0,'C',1);
		$this->Cell(8,6, 'Sexo',1,0,'C',1);
		$this->Cell(30,6, utf8_decode('Año/Grado'),1,0,'C',1);
		$this->Cell(35,6, 'Telefono',1,1,'C',1);
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
while ($row = mysqli_fetch_array($alumnos_query))
{
	$van=$van+1;
	$pdf->SetX(5);
	$pdf->Cell(8,5, $van,1,0,'C');
	$pdf->Cell(25,5, $row['cedula'],1,0,'C');
	$pdf->Cell(90,5, utf8_decode($row['apellido']).' '.utf8_decode($row['nombre']),1,0,'L');
	$pdf->Cell(8,5, $row['sexo'],1,0,'C');
	$pdf->Cell(30,5, utf8_decode($row['nombreGrado']).' '.$row['nom_sec'] ,1,0,'C');
	$pdf->Cell(35,5, $row['telefono'] ,1,1,'C');
}
mysqli_free_result($alumnos_query);
$pdf->Output();
?>
