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
$fechaHoy = strftime( "%Y-%m-%d");
$link = Conectarse();
include_once("../../../inicia.php");
$tablaPeriodo=$_GET['peri'];
$nombre_periodo=$_GET['nomP'];
$represe_query = mysqli_query($link,"SELECT A.cedula,A.representante, A.tlf_celu  FROM represe A, alumcer B WHERE A.cedula=B.ced_rep and B.Periodo='$nombre_periodo' and B.statusAlum='1' and B.cargo is NULL GROUP BY A.representante ORDER BY A.representante ASC ");   

require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		$this->Addpage();
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','B',9);
		$this->Cell(25,6, 'Cedula',1,0,'C',1);
		$this->Cell(90,6, 'Representante',1,0,'C',1);
		$this->Cell(35,6, 'Telefono',1,0,'C',1);
		$this->Cell(30,6, 'Alumnos',1,1,'C',1);
		$this->SetFont('Arial','',9);
	}
	function Header()
	{
		global $nombre_periodo;
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
		$this->Cell(30,5,'Rif.: '.RIFCOLM.' - Telefono '.TELEMPM,0,1,'L');
		$this->Ln(2);
		//$this->Cell(25);
		$this->SetFont('Arial','',12);
		$this->Cell(190,6,'Listado de Representantes '.$nombre_periodo ,0,1,'C');
		$this->SetFont('Arial','',9);
	}
	function Footer()
	{
		$this->SetY(-25);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetTitle('Listado Representante');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,6, 'Cedula',1,0,'C',1);
$pdf->Cell(90,6, 'Representante',1,0,'C',1);
$pdf->Cell(35,6, 'Telefono',1,0,'C',1);
$pdf->Cell(30,6, 'Alumnos',1,1,'C',1);

$pdf->SetFont('Arial','',9);
$l=1; $sonAlu=0; $sonRep=0;
while ($row = mysqli_fetch_array($represe_query))
{
	$ced_rep=$row['cedula'];
    $nom_rep=utf8_decode($row['representante']);
    $tlf_celu=$row['tlf_celu'];
    $sonRep++;
    $alumnos_query = mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido,A.grado, B.nombreGrado, C.nombre as nomSec FROM alumcer A, grado".$tablaPeriodo." B, secciones C WHERE A.ced_rep='$ced_rep' and A.grado=B.grado and A.seccion=C.id ");
    $nroAlum=0; $cursa=''; $boton='';
    while($row2=mysqli_fetch_array($alumnos_query))
    {
    	$nroAlum++;
    	$sonAlu++;
    	$grado=$row2['grado'];
		if ($grado>60) {
            $gra=substr($grado, 1,1).utf8_decode('Año');
        }
        if($grado>40 && $grado<50){
            $gra=substr($grado, 1,1).'Nv.';
        }
        if($grado>50 && $grado<60){
            $gra=substr($grado, 1,1).'Gr.';
        }
        $boton.=$gra.', ';
    }
	if($l==1)
	{
		$pdf->Cell(25,5, $ced_rep,0,0,'L');
		$pdf->Cell(90,5, $nom_rep,0,0,'L');
		$pdf->Cell(35,5, $tlf_celu,0,0,'L');
		$pdf->Cell(30,5, $nroAlum.') '.$boton ,0,1,'L');
		$l=2;
	}else
	{
		$pdf->Cell(25,5, $ced_rep,0,0,'L',1);
		$pdf->Cell(90,5, $nom_rep,0,0,'L',1);
		$pdf->Cell(35,5, $tlf_celu,0,0,'L',1);
		$pdf->Cell(30,5, $nroAlum.') '.$boton ,0,1,'L',1);
		$l=1;
	}
	
}
$pdf->SetFillColor(205,246,204);
$pdf->Ln(3);
$pdf->SetX(10);
$pdf->Cell(60,5, 'TOTAL DE REPRESENTANTES: ',0,0,'R',1);
$pdf->Cell(10,5, $sonRep,0,1,'L',1);
$pdf->Cell(60,5, 'TOTAL DE ESTUDIANTES: ',0,0,'R',1);
$pdf->Cell(10,5, $sonAlu,0,1,'L',1);
$pdf->SetFillColor(232,232,232);
mysqli_free_result($represe_query);
mysqli_free_result($alumnos_query);
mysqli_close($link);
$pdf->Output();


?>