<?php
session_start();
include_once("../conexion.php"); 
include_once("../inicia.php"); 
//include("encabezado1.php");
$link = Conectarse(); 
if(isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	require('../fpdf/fpdf.php');
	if(!empty($_SESSION['admin']))
	{
		$usuario = $_GET['ced_prof'];
	} else { $usuario = $_SESSION['usuario']; }
	class PDF extends FPDF 
	{
		function AcceptPageBreak()
		{
			$this->Addpage();
			$this->SetFillColor(232,232,232);
			$this->SetFont('Arial','B',12);
			$this->SetX(10);
			$this->Cell(70,6,'ESTADO',1,0,'C',1);
			$this->SetX(80);
			$this->Cell(20,6,'ID',1,0,'C',1);
			$this->SetX(100);
			$this->Cell(70,6,'MUNICIPIO',1,0,'C',1);
			$this->Ln();
		}
		function Header()
		{
			//include("../inicia.php");
			$this->Image('imagenes/carnet.jpg',2,8,63,102);
			$this->Image('imagenes/dorso.jpg',67,8,63,102);
		}
		function Footer()
		{
			$this->SetY(-15);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}
	
	$result = mysqli_query($link,"SELECT alumcer.cedula, alumcer.nombre, alumcer.apellido, alumcer.cargo, alumcer.pasaporte, alumcer.ruta, alumcer.nacion, cargos.* FROM alumcer,cargos WHERE alumcer.cedula = '$usuario' and alumcer.cargo = cargos.idcargo ");
	while ($row = mysqli_fetch_array($result))
	{
		$nac_alu = $row['nacion'];
		$ced_alu = $row['cedula'];
		$ape_alu = utf8_decode($row['apellido']);
		$nom_alu = utf8_decode($row['nombre']);
		$pas_alu = $row['pasaporte'];
		$cargo = $row['nomcargo'];
		if(empty($row['ruta']) || !file_exists('fotodoc/'.$row['ruta']))
		{ $fot_alu = 'imagenes/fotocarnet.jpg'; } else
		{ $fot_alu = 'fotodoc/'.$row['ruta'];}
	}
		
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->Addpage();
		$pdf->SetFillColor(1,1,1);
		
		$pdf->SetFont('Times','',8);
			$pdf->setTextColor(255,255,255);
			$pdf->Ln(-2);
			$pdf->SetX(67);
			$pdf->Cell(64,4, 'Se le agradece a las Autoridades Civiles y Policiales',0,1,'C');
			$pdf->SetX(67);
			$pdf->Cell(64,4, 'la colaboracion al portador de este carnet, el mismo',0,1,'C');
			$pdf->SetX(67);
			$pdf->Cell(64,4, 'permite el disfrute del pasaje preferencial estudiantil',0,1,'C');
			$pdf->SetX(67);
			$pdf->Cell(64,4, 'segun los decretos 2038-2757 del subsidio indirecto, ',0,1,'C');
			$pdf->SetX(67);
			$pdf->Cell(64,4, 'en todas las rutas de transporte publico segun gaceta',0,1,'C');
			$pdf->Ln(2);
			$pdf->SetX(67);
			$pdf->Cell(64,4, DIRECCM ,0,1,'C');
			$pdf->SetX(67);
			$pdf->Cell(64,4, CIUDADM.' '.ESTADOM.' Tlf.: '.TELEMPM ,0,1,'C');
			$pdf->SetX(67);
			$pdf->Cell(64,4, 'Valido para el Periodo: '.ANOESCM ,0,1,'C');

			$pdf->setTextColor(1,1,1);
			$pdf->SetFont('Times','B',10);
			//$pdf->Ln(5);
			$pdf->SetX(3);
			$pdf->Cell(62,5, '',0,1,'C');
			$pdf->Ln(33);
			$pdf->SetX(2);
			$pdf->Cell(63,5, $nom_alu,0,1,'C');
			$pdf->SetX(2);
			$pdf->Cell(63,5, $ape_alu,0,1,'C');
		
			$pdf->SetX(2);
			$pdf->Cell(63,5, $nac_alu.'-'.$ced_alu,0,0,'C');
			$pdf->SetX(70);
			$pdf->Cell(58,5, '',0,1,'C');
			
			$pdf->SetX(2);
			$pdf->Cell(63,5, $cargo,1,0,'C');
			$pdf->SetX(70);
			$pdf->Cell(58,5, '',0,1,'C');
			
			$pdf->SetFont('Arial','',9);
			$pdf->SetX(70);
			$pdf->Cell(58,3, DOMINIO,0,1,'C');
			$pdf->SetFont('Arial','B',14);
			$pdf->SetX(4);
			$pdf->setTextColor(255,255,255);
			$pdf->Cell(60,5, ANOESCM,0,1,'C');
			$pdf->Image($fot_alu,3,40,30,32);
			if(file_exists('imagenes/'.$ced_alu.'.png')){
				$pdf->Image('imagenes/'.$ced_alu.'.png',68,94,68,15);
			}
			//$pdf->Image('imagenes/firma.png',75,60,25,32);
			//$pdf->Image('imagenes/selloCole.png',92,55,30,30);
			$pdf->Ln();
		$pdf->Ln();
	
	$pdf->Output();
	mysqli_free_result($result);
	mysqli_close($link);
}
else
{
	header("location:../login.php"); 			
}
?>