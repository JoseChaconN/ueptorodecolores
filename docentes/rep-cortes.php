<?php
session_start();
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$van=0;
	
if(isset($_SESSION['usuario']) && isset($_SESSION['password']) && !empty($_SESSION['cargo']))
{
	require('../fpdf/fpdf.php');
	include_once("../inicia.php");
	include_once("../conexion.php");  
	$link = Conectarse(); 
	$ced_prof = $_GET['ced_prof'];
	$materia = $_GET["materia"];
	$grado = $_GET["grado"];
	$seccion = $_GET["seccion"];
	$lapso = $_GET["lapso"];
	if ($lapso==1) {$nomlap='Primer';}
	if ($lapso==2) {$nomlap='Segundo';}
	if ($lapso==3) {$nomlap='Tercer';}
		class PDF extends FPDF 
		{
			function AcceptPageBreak()
			{
				$this->Addpage();
				$this->SetFillColor(232,232,232);
				$this->SetFont('Arial','B',9);
				$this->SetX(15);
				$this->Cell(18,6, 'Cedula',1,0,'C',1);
				$this->SetX(33);
				$this->Cell(98,6, 'Estudiantes',1,0,'C',1);
				$this->SetX(131);
				$this->Cell(8,6, 'sexo',1,0,'C',1);
				$this->SetX(139);
				$this->Cell(25,6, utf8_decode('Año/Grado'),1,0,'C',1);
				$this->SetX(164);
				$this->Cell(30,6, 'Telefono',1,0,'C',1);
				$this->Ln();
				$this->SetFont('Arial','',8);
				$this->SetX(15);
			}
			
			function Header()
			{
				
				//$this->Image('../imagenes/logo.png',10,8,30);
				$this->Image('../assets/img/logo.png',30,9,25,25);
				$this->SetFont('Arial','',15);
				$this->Cell(80);
				$this->Cell(30,6,utf8_decode(NKXS),0,1,'C');
				$this->Cell(80);
				$this->Cell(30,6,utf8_decode(EKKS),0,1,'C');
				$this->Cell(80);
				$this->SetFont('Arial','',10);
				$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');
				$this->Cell(80);
				$this->Cell(30,5,'Telefono '.TELEMPM,0,1,'C');
			}
			
			function Footer()
			{
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			}
			
		}

		
		$anoescm=ANOESCM;
		$todos=mysqli_query($link,"SELECT A.*, B.*, C.nombre as 'nomalum', C.apellido as 'apealum', C.cedula as 'cedalu', D.* FROM trgsmp".$tablaPeriodo." A, cortes".$tablaPeriodo." B, alumcer C, cortes1".$tablaPeriodo." D WHERE 
			C.Periodo = '$anoescm' and
			A.cod_grado = '$grado' and 
			A.cod_materia= '$materia' and 
			A.cod_seccion = '$seccion' and 
			A.cod_materia=B.cod_materia and 
			A.cod_grado=C.grado and 
			A.cod_seccion=C.seccion and
			B.ced_alu=C.cedula and
			D.cod_materia=A.cod_materia and
			D.cod_seccion=A.cod_seccion ORDER BY C.apellido ASC
			");
		$qgrados = mysqli_query($link,"SELECT A.nombreGrado as 'nomgra', B.nombre as 'nomsec', C.* FROM grado".$tablaPeriodo." A,secciones B,materiass".$tablaPeriodo." C WHERE A.grado='$grado' and B.id='$seccion' and C.codigo='$materia' ");	
		while ($row = mysqli_fetch_array($qgrados))
		{
			$nomgra = utf8_decode($row['nomgra']);
			$nomsec = $row['nomsec'];
			$nommat = $row['nombremate'];
		}

		$porce = mysqli_query($link,"SELECT * FROM cortes1".$tablaPeriodo." WHERE cod_materia='$materia' and cod_seccion='$seccion' ");
		$profe = mysqli_query($link,"SELECT * FROM alumcer WHERE cedula='$ced_prof'");
		while ($row = mysqli_fetch_array($profe))
		{
			$nomprof = utf8_decode($row['nombre']).' '.utf8_decode($row['apellido']);
		}
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->Addpage();
		$pdf->SetFillColor(232,232,232);
		$pdf->Ln(1);
		$pdf->Cell(80);
		$pdf->SetFont('Arial','',13);
		$pdf->Cell(30,6,'Corte de Notas '.$nomlap.' Lapso',0,1,'C');
		$pdf->Cell(80);
		$pdf->Cell(30,6,'Periodo Escolar '.ANOESCM,0,1,'C');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetX(5);
		$pdf->Cell(90,6,'Docente: '.$nomprof,1,0,'L',1);
		$pdf->Cell(60,6,'Materia: '.$nommat,1,0,'L',1);
		$pdf->Cell(40,6,$nomgra.' "'.$nomsec.'"',1,1,'L',1);
		
		$pdf->SetX(5);
		$pdf->Cell(8,6, 'Nro.',1,0,'C',1);
		$pdf->Cell(22,6, 'Cedula',1,0,'C',1);
		$pdf->Cell(75,6, 'Estudiantes',1,0,'C',1);
		while ($row = mysqli_fetch_array($porce))
		{
		 $pdf->Cell(13,6, $row["porcentaje1".$lapso].'%',1,0,'C',1);
		 $pdf->Cell(13,6, $row["porcentaje2".$lapso].'%',1,0,'C',1);
		 $pdf->Cell(13,6, $row["porcentaje3".$lapso].'%',1,0,'C',1);
		 $pdf->Cell(13,6, $row["porcentaje4".$lapso].'%',1,0,'C',1);
		 $pdf->Cell(13,6, $row["porcentaje5".$lapso].'%',1,0,'C',1);
		}
		
		$pdf->Cell(20,6, 'Definitiva',1,0,'C',1);
		
		$pdf->Ln();
		while ($row = mysqli_fetch_array($todos))
		{
			if (empty($row['admin']) && empty($row['cargos']))
			{
				$nota1 = $row["nota1".$lapso] * $row["porcentaje1".$lapso] / 100;
				$nota2 = $row["nota2".$lapso] * $row["porcentaje2".$lapso] / 100;
				$nota3 = $row["nota3".$lapso] * $row["porcentaje3".$lapso] / 100;
				$nota4 = $row["nota4".$lapso] * $row["porcentaje4".$lapso] / 100;
				$nota5 = $row["nota5".$lapso] * $row["porcentaje5".$lapso] / 100;

				$pdf->SetFont('Arial','',8);
				$pdf->SetX(5);
				$van=$van+1;
				$pdf->Cell(8,5, $van,0,0,'C');
				$pdf->Cell(22,5, $row['cedalu'],0,0,'R');
				$pdf->Cell(75,5, trim(utf8_decode($row['apealum']))." ".utf8_decode($row['nomalum']),0,0,'L');
				$pdf->Cell(13,5, $nota1 ,0,0,'C');
				$pdf->Cell(13,5, $nota2 ,0,0,'C');
				$pdf->Cell(13,5, $nota3 ,0,0,'C');
				$pdf->Cell(13,5, $nota4 ,0,0,'C');
				$pdf->Cell(13,5, $nota5 ,0,0,'C');
				$pdf->Cell(20,5, $nota1+$nota2+$nota3+$nota4+$nota5 ,0,1,'C');

			}	
		}
		$pdf->Ln(2);
		$pdf->SetX(15);
		$pdf->Cell(50,5, 'Total de Alumnos Inscritos.:'.$van,1,1,'L',1);
		$pdf->Output();
		mysqli_free_result($resultado);
		mysqli_close($link);
	
}
else
{
	include ("../encabezado1.php");
	echo '<div class="container">
         <div class="row">
             <div class="col-md-12">
                 <h2><div class="alert alert-danger text-center" role="alert">¡Necesita Iniciar Sesion como Administrador!</div></h2>
             </div>
         </div>
     </div>';
	echo '<div align="center"><a href="../login.php"><img src="../imagenes/login.png" width="150px"></a></div>';

	/*session_destroy();
	include ("encabezado.php");

    echo '<div align="center" class="error">Necesitas iniciar sesion como Administrador</div>';
    echo '<div align="center"><a href="login.php"><img src="imagenes/login.png" width="150px"></a></div>';
    echo '<div align="center"><a href="/uepprecursor/index.php"><img src="imagenes/inicio.png" width="100px"></a></div>';*/
}
?>
