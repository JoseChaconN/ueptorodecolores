<?php
session_start();
#error_reporting(E_ALL);
#ini_set('display_errors', '1');
if (isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	include_once("conexion.php");  
	$link = Conectarse(); 
	$tablaPeriodo=$_SESSION['tablaPeriodo'];
	$encuesta = (isset($_POST['encuesta'])) ? $_POST['encuesta'] : $_GET['encuesta'] ;
	//$encuesta=$_POST['encuesta'];
	include_once("inicia.php");
	require('fpdf/fpdf.php');
	$usuario = $_SESSION['usuario'];
	if ($encuesta==1) {$reinscribe='1';}
	if ($encuesta==2) {$reinscribe='2';}
	if ($encuesta==3) {$reinscribe='3';}
	mysqli_query($link,"UPDATE alumcer SET reinscribe='$reinscribe' WHERE cedula = '$usuario'");
	$_SESSION['reinscribe'] = $reinscribe;
	class PDF extends FPDF 
	{
		function Header()
		{
			$this->Image('assets/img/logo.png',35,9,25,28);
			$this->SetFont('Times','I',15);
			$this->Cell(80);
			$this->Cell(30,6,strtoupper(NKXS),0,1,'C');
			$this->Cell(80);
			$this->Cell(30,6,utf8_decode(EKKS),0,1,'C');
			$this->Cell(80);
			$this->SetFont('Times','I',13);
			$this->Cell(30,6,'EDUCACION INICIAL Y PRIMARIA ',0,1,'C');
			$this->Cell(80);
			$this->Cell(30,6,'M.P.P.E. '.CKLS.' RIF. '.RIFCOLM,0,1,'C');
			$this->Ln(10);
			$this->Cell(80);
			$this->SetFont('Arial','',15);
		}
		function Footer()
		{
			$this->SetY(-35);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}
	$resultado = mysqli_query($link,"SELECT A.nombre as 'nomalu', B.nombreGrado as 'nomgra',  A.cedula, A.apellido, A.grado, A.nacion, B.grado, C.representante as 'nomrep', A.ced_rep FROM alumcer A, grado".$tablaPeriodo." B, represe C WHERE A.cedula = '$usuario' and A.grado = B.grado and A.ced_rep = C.cedula");

	while ($row = mysqli_fetch_array($resultado))
	{
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->Addpage();
		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Times','B',20);
		$pdf->Ln(18);
		$pdf->Cell(190,6, utf8_decode('ENCUESTA AÑO ESCOLAR ').PROXANOE,0,1,'C');
		$pdf->Ln(10);
		//$pdf->Image('assets/img/fondoagua.jpg',15,75,180,80);
		$pdf->SetFont('Times','',12);
		$pdf->SetX(20);
		$pdf->Cell(160,8,'Apellidos y Nombres: '.utf8_decode($row['apellido']." ".$row['nomalu']),0,1,'L');
		$pdf->Line(59,84,190,84);

		$pdf->SetX(20);
		$pdf->Cell(170,8, utf8_decode('Cedula de Identidad : ').$row['nacion'].'-'.$row['cedula'],0,0,'L');
		$pdf->Line(58,92,90,92);
		
		$pdf->SetX(95);
		$pdf->Cell(170,8, utf8_decode('Cursante del : '.$row['nomgra']),0,1,'L');
		$pdf->Line(120,92,190,92);
		
		$pdf->SetFont('Times','',12);
		$pdf->Ln(3);
		$pdf->SetX(20);
		$pdf->MultiCell(170,8, utf8_decode('1.- INDIQUE CON UNA (X) LA ALTERNATIVA DESEADA: '),0,1,'L');
			if ($encuesta==1) {$opc1='X';$opc2='';$opc3='';}
			if ($encuesta==2) {$opc2='X';$opc1='';$opc3='';}
			if ($encuesta==3) {$opc3='X';$opc1='';$opc2='';}
			$pdf->SetX(30);
			$pdf->Cell(7,6, $opc1,1,0,'C');
			$pdf->Cell(190,8, utf8_decode('A) RESERVA DE CUPO PARA NUEVO AÑO ESCOLAR').' ('.PROXANOE. ')',0,1,'L');
			$pdf->SetX(30);
			$pdf->Cell(7,6, $opc2,1,0,'C');
			$pdf->Cell(190,8, utf8_decode('B) SOLICITA RETIRO Y ENTREGA DE DOCUMENTOS'),0,1,'L');
			$pdf->SetX(30);
			$pdf->Cell(7,6, $opc3,1,0,'C');
			$pdf->Cell(190,8, utf8_decode('C) DESEA ZONIFICACIÓN PARA UN PLANTEL OFICIAL (SOLO 1° Y 4° AÑO)'),0,1,'L');

			$pdf->Ln(3);
			$pdf->SetX(20);
			$pdf->MultiCell(170,8, utf8_decode('2.- RESPONDER LA PRESENTE ENCUESTA ANTES DE 25 DE MAYO '.substr(ANOESCM,5,4).' A FIN DE CUBRIR LOS REQUISITOS DE RESERVACIÓN, ZONIFICACIÓN O RETIRO: '),0,1,'L');

			$pdf->Ln(3);
			$pdf->SetX(20);
			$pdf->MultiCell(170,8, utf8_decode('3.- SU SOLICITUD SERA CONSIDERADA CON PRIORIDAD Y PUNTUALIDAD. '),0,1,'L');

			$pdf->Ln(3);
			$pdf->SetX(20);
			$pdf->MultiCell(170,8, utf8_decode('4.- COMPROMISO DEL REPRESENTANTE '),0,1,'L');
			$pdf->SetFillColor(236,236,236);
			$pdf->SetX(30);
			$pdf->Cell(120,8, utf8_decode('YO,  '.$row['nomrep']),0,0,'L');
			$pdf->Cell(40,8, utf8_decode('C.I. '.$row['ced_rep']),0,1,'L');
			$pdf->Line(39,176,150,176);
			$pdf->Line(158,176,190,176);

			//$pdf->Ln(2);
			$pdf->SetX(30);
			$pdf->MultiCell(160,8, utf8_decode('ME COMPROMETO A FORMALIZAR LA INSCRIPCIÓN DE MI REPRESENTADO PARA EL AÑO ESCOLAR '.PROXANOE.' (DEL 01 AL 30/07/'.substr(ANOESCM,0,4).') DE CONFORMIDAD CON EL ART. 54 LOPNNA.  '),0,1,'L');
			$pdf->SetX(30);
			$pdf->MultiCell(160,8, utf8_decode('EN CASO CONTRARIO, AUTORIZO A LA INSTITUCIÓN PARA DISPONER DEL CUPO DE MI REPRESENTADO. '),0,1,'L');

			$pdf->Ln(24);
			$pdf->SetX(20);
			$pdf->Cell(100,8, utf8_decode(CIUDADM.', ______/_______/___________  '),0,1,'L');
			$pdf->SetX(123);
			$pdf->Line(123,248,190,248);
			$pdf->Cell(70,6, utf8_decode('FIRMA DEL REPRESENTANTE'),0,0,'C');
	}
	$pdf->Output();
}else
{
	header("location:index.php?vencio");
} 
?>