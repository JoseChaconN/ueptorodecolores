<?php
session_start();
if (isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	$tablaPeriodo=$_SESSION['tablaPeriodo'];
	setlocale(LC_TIME, "spanish");
	date_default_timezone_set("America/Caracas");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$mes = $meses[date('n')-1];
	require('fpdf/fpdf.php');
	include_once("conexion.php");  
	include_once("inicia.php");
	$usuario = $_SESSION['usuario'];
	$motivo = ($_POST["motivo"]);
	$diaasis = $_POST["diaasis"];
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
		{}
		function Footer()
		{
			$this->SetY(-15);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
		}
	}
	$link = Conectarse(); 
	$resultado = mysqli_query($link,"SELECT A.nombre as 'nomalu',A.cedula as 'cedalu', A.nacion as 'nacalu',B.nombreGrado as 'nomgra',  A.apellido, A.nacion, A.cedula, A.apellido, A.grado, A.Periodo, A.correo as cor_alu, A.ced_rep, B.grado, B.especialidad, C.cedula as 'cedrep', C.cedula, C.representante FROM alumcer A, grado".$tablaPeriodo." B, represe C WHERE A.cedula = $usuario and A.grado = B.grado and C.cedula = A.ced_rep");
	while ($row = mysqli_fetch_array($resultado))
	{
		$correo = $row['cor_alu'];
        $ced_rep = $row['ced_rep'];
        $gra_alu = $row['grado'];
        $nac_alu = $row['nacalu'];
		$ced_alu = $row['cedalu'];
		$ape_alu = utf8_decode($row['apellido']);
		$nom_alu = utf8_decode($row['nomalu']);
		$repre = utf8_decode($row['representante']);
		$nom_gra = utf8_decode($row['nomgra']);
		$espe = $row['especialidad'];
		$periodo = $row['Periodo'];
	}
	$periodo_query=mysqli_query($link,"SELECT nombre_periodo, directorPeriodo FROM periodos where nombre_periodo='$periodo' "); 
	while($row=mysqli_fetch_array($periodo_query))
	{
	    $director=$row['directorPeriodo'];
	}	
    if ( empty($correo) || empty($ced_rep) || empty($gra_alu) ) 
    {
     	include_once ("encabezado1.php");
		?>
		<style type="text/css">
			body 
			{
			    background-image: url("imagenes/fondo.jpg" ) ;
			    background-size: 100vw 100vh; 
			}
			.error
			{ 
			    background-color:#929495;
			    padding:5px;
			    border:#F8E808 5px solid;
			    float: center;
			    margin-top: 200px;
			    font-size: 25px;
			    color: white; 
			    font-weight: bold; 
			}
		</style>
		<?php
			echo '<div align="center" class="error">POR FAVOR UTILICE LA OPCION DATOS PERSONALES<br>';
			echo ' CARGUE Y GUARDE TODOS LOS DATOS REQUERIDOS POR EL SISTEMA<br>';
			echo ' PARA PODER IMPRIMIR SU CONSTANCIA DE ASISTENCIA<br><br></div>';
		exit;
    }
		
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->Addpage();
	$pdf->Image('assets/img/logo.png',40,9,25,28);
	$pdf->SetFont('Times','I',15);
	$pdf->Cell(80);
	$pdf->Cell(30,6,(NKXS),0,1,'C');
	$pdf->Cell(80);
	$pdf->Cell(30,6,utf8_decode(EKKS),0,1,'C');
	$pdf->Cell(80);
	$pdf->SetFont('Times','I',13);
	$pdf->Cell(30,6,utf8_decode('Educación Inicial y Primaria '),0,1,'C');
	$pdf->Cell(80);
	$pdf->Cell(30,6,'M.P.P.E. '.CKLS.' RIF. '.RIFCOLM,0,1,'C');
	$pdf->Ln(10);
	$pdf->Cell(80);
	$pdf->SetFont('Arial','',15);
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Times','B',20);
	$pdf->Ln(10);
	$pdf->Cell(190,6, 'CONSTANCIA DE ASISTENCIA',0,1,'C');
	$pdf->Image('assets/img/fondoagua.jpg',40,65,130,150);
	$pdf->Ln(10);
	$pdf->SetFont('Times','',14);
	$pdf->SetX(30);
	$pdf->Cell(190,8,  'Se   hace   constar  por   medio   de   la  presente   que   el (la)   ciudadano (a) ',0,1,'L');
	$pdf->SetX(20);
	$pdf->Cell(190,8, $repre,0,0,'L');
	$pdf->SetX(90);
	$pdf->Cell(190,8,' C.I.   '.$ced_rep.'   representante del Estudiante :',0,1,'L');
	$pdf->Line(20,84,90,84);
	$pdf->Line(100,84,124,84);
	$pdf->Ln(3);
	$pdf->SetX(20);
	$pdf->Cell(170,8, $ape_alu." ".$nom_alu,1,1,'C');
	$pdf->Ln(3);
	$pdf->SetX(20);
	$pdf->Cell(190,8, 'C.I.: '.$nac_alu.'-'.$ced_alu,0,0,'L');
	$pdf->SetX(65);
	$pdf->Cell(190,8, utf8_decode(',  cursante del ').$nom_gra.' de '.$espe,0,1,'L');
	$pdf->Line(30,106,65,106);
	$pdf->Line(95,106,190,106);
	$pdf->SetX(20);
	$pdf->Cell(190,8, utf8_decode('del periodo escolar '.$periodo.' asistió a esta Institución Educativa con la finalidad de: '),0,1,'L');
	$pdf->SetFont('Times','',10);
	$pdf->Ln(10);
	$pdf->SetX(20);
	$pdf->Cell(170,8, utf8_decode($motivo),1,1,'C');
	$pdf->SetFont('Times','',14);
	$pdf->Ln(10);
	$pdf->SetX(30);
	$pdf->Cell(190,8, utf8_decode('Constancia que se expide a petición del representante,  en '.CIUDADM),0,1,'L');
	$pdf->SetX(20);
	$pdf->Cell(190,8, ' el '.strftime("%d de ".$mes." de %Y",strtotime($diaasis)).'.',0,1,'L');
	$pdf->Ln(20);
	$pdf->SetX(10);
	$pdf->Cell(190,8, 'Atentamente.',0,1,'C');
	$pdf->Ln(20);
	$pdf->SetFont('Times','',10);
	$pdf->SetX(120);
	$pdf->Cell(100,6, $director,0,1,'L');
	$pdf->SetX(120);
	$pdf->Cell(100,6, 'Directora.',0,1,'L');
	$pdf->SetFont('Times','',9);
	$pdf->Ln(37);
	$pdf->SetX(10);
	$pdf->Cell(190,6, 'Constancia valida si tiene sello humedo de la Institucion',0,1,'C');
	$pdf->Line(120,208,190,208);
	$pdf->Line(20,262,190,262);
	$pdf->Ln(1);
	$pdf->SetFont('Times','',11);
	$pdf->SetX(10);
	$pdf->Cell(190,5,utf8_decode(DIRECCM).' - '.CIUDADM.' - Telefono '.TELEMPM,0,1,'C');
	$pdf->SetX(10);
	$pdf->Cell(190,5, 'https://'.DOMINIO.'  -  Email '.SUCORREO,0,1,'C');
	mysqli_free_result($periodo_query);
	mysqli_free_result($resultado);
	mysqli_close($link);
	$pdf->Output();
	
}
else
{
	header("location:index.php#features");			
} 
?>