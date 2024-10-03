<?php
include_once("inicia.php");
include_once("conexion.php");
session_start();
if(isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	$tablaPeriodo=$_SESSION['tablaPeriodo'];
	setlocale(LC_TIME, "spanish");
	date_default_timezone_set("America/Caracas");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$mes = $meses[date('n')-1];
	require('fpdf/fpdf.php');
	$usuario = $_SESSION['usuario'];
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
			
		}
		function Footer()
		{
			$this->SetY(-15);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
		}
	}
	$link = Conectarse(); 
	$resultado = mysqli_query($link,"SELECT A.nombre as 'nomalu', B.nombreGrado as 'nomgra',  A.apellido, A.nacion, A.cedula, A.grado, A.correo, A.ced_rep, A.Periodo, A.pagado, A.morosida, A.deudatotal, B.especialidad, B.grado FROM alumcer A, grado".$tablaPeriodo." B WHERE A.cedula = $usuario and A.grado = B.grado");

	while ($row = mysqli_fetch_array($resultado))
    {
        $correo = $row['correo'];
        $ced_rep = $row['ced_rep'];
        $gra_alu = $row['grado'];
        $nac_alu = $row['nacion'];
		$ced_alu = $row['cedula'];
		$ape_alu = ($row['apellido']);
		$nom_alu = ($row['nomalu']);
		$nom_gra = ($row['nomgra']);
		$esp_alu = $row['especialidad'];
		$periodo = $row['Periodo'];
		$pagado = $row['pagado'];
		$morosida = $row['morosida'];
		$deudatotal = $row['deudatotal'];
    }
    $periodo_query=mysqli_query($link,"SELECT nombre_periodo, directorPeriodo FROM periodos where nombre_periodo='$periodo' "); 
	while($row=mysqli_fetch_array($periodo_query))
	{
	    $director=$row['directorPeriodo'];
	}
    if ( empty($correo) || empty($ced_rep) || empty($gra_alu) || mysqli_num_rows($resultado)==0 )
    /*if ( empty($correo) || empty($ced_rep) || empty($gra_alu) || mysqli_num_rows($resultado)==0 || $pagado==0 || $morosida>0 || $deudatotal<DEUDATOT) */
    {
    	include_once("encabezado1.php"); 
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
			    margin-top: 40px;
			    font-size: 25px;
			    color: white; 
			    font-weight: bold; 
			}
		</style>
		<?php
		if ( empty($correo) || empty($ced_rep) || empty($gra_alu))
		{		
			echo '<div align="center" class="error">POR FAVOR UTILICE LA OPCION DATOS PERSONALES<br>';
			echo ' CARGUE Y GUARDE TODOS LOS DATOS REQUERIDOS POR EL SISTEMA<br>';
			echo ' PARA PODER IMPRIMIR SU CONSTANCIA DE INSCRIPCIÓN</div><br><br>';
		}

		if(mysqli_num_rows($resultado)==0)
		{
			echo '<div align="center" class="error">POR FAVOR VERIFIQUE QUE TODOS<br>';
		   	echo ' LOS DATOS PERSONALES ESTEN COMPLETOS<br>';
		   	echo ' PARA PODER IMPRIMIR SU CONSTANCIA</div><br><br>';
		}
		/*if ($pagado==0 || $morosida>0) 
		{
			if ($morosida>0) 
			{
				echo '<div align="center" class="error">DISCULPE DEBE ESTAR SOLVENTE PARA PODER EMITIR LA CONSTANCIA<br>';
				echo "Saldo Pendiente Bs. ".$morosida."</div>";
			}else
			{
				echo '<div align="center" class="error">DISCULPE USTED NO TIENE PAGOS REGISTRADOS <br>';
				echo "NOTIFIQUE A LA INSTITUCION PARA PODER EMITIR LA CONSTANCIA</div>";
			}
		}
		if ($deudatotal<DEUDATOT) 
		{
			echo '<div align="center" class="error">DISCULPE ACTUALICE SU INSCRIPCION PARA EL PERIODO ESCOLAR '.PROXANOE.'<br>';
			echo "DEBE ESTAR SOLVENTE CON LA INSCRIPCION Y EL MES DE SEPTIEMBRE </div>";
		}
		if ($deudatotal==0) 
		{
			echo '<div align="center" class="error">Si usted esta solvente por favor verifique que el nro. de cedula del alumno en un recibo de pago sea igual al nro. de cedula registrado en la página WEB, en caso de ser diferentes solicite correción de cedula en el plantel</div>';
		}*/
		echo '<div class="col-md-12 text-center">
		<br><button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> REGRESAR</button>
		</div>';
		exit;
    }

	//{
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->Addpage();
		$pdf->Image('assets/img/logo.png',40,9,22,23);
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
		$pdf->Ln(15);
		$pdf->Cell(190,6, utf8_decode('CONSTANCIA DE INSCRIPCIÓN'),0,1,'C');
		$pdf->Image('assets/img/fondoagua.jpg',40,65,130,150);
		$pdf->Ln(15);
		$pdf->SetFont('Times','',12);
		$pdf->SetX(30);
		$pdf->Cell(160,8,'La suscrita Directora de la '.NKXS."  ".utf8_decode(EKKS),0,1,'L');
		$pdf->Line(75,86,190,86);
		$pdf->SetX(20);
		$pdf->Cell(190,8, 'hace constar por medio de la presente que el(la) Estudiante :',0,1,'L');
		$pdf->Ln(3);
		$pdf->SetFont('Times','',14);
		$pdf->SetX(20);
		$pdf->Cell(170,8, utf8_decode($ape_alu." ".$nom_alu),1,1,'C');
		$pdf->SetFont('Times','',12);
		$pdf->Ln(3);
		$pdf->SetX(20);
		$pdf->Cell(190,8, utf8_decode('portador(a) de la Cedula de Identidad : '.$nac_alu.'-'.$ced_alu),0,0,'L');
		$pdf->SetX(122);
		$pdf->Cell(190,8, utf8_decode(', fue inscrito(a) en esta Institución para '),0,1,'L');
		$pdf->Line(87,116,120,116);
		$pdf->SetX(20);
		$pdf->Cell(190,8, utf8_decode('cursar el '.$nom_gra .' de '.$esp_alu.', durante el periodo escolar '.$periodo).'.',0,1,'L');
		
		$pdf->Ln(10);
		$pdf->SetX(30);
		$pdf->Cell(160,8, utf8_decode('Constancia que se expide a petición del representante,  en  la ciudad de '.CIUDADM),0,1,'L');
		
		$pdf->SetX(20);
		$pdf->Cell(170,8,' a los '. strftime("%d dias del mes de ".$mes." de %Y"),0,1,'L');
		
		$pdf->Ln(20);
		$pdf->SetX(10);
		$pdf->Cell(190,8, 'Atentamente.',0,1,'C');
		$pdf->Ln(20);
		$pdf->SetX(120);
		$pdf->Cell(70,6, $director,0,1,'L');
		$pdf->SetX(120);
		$pdf->Cell(70,6, 'Directora.',0,1,'L');
		$pdf->SetFont('Times','',9);
		$pdf->Ln(40);
		$pdf->SetX(10);
		$pdf->Cell(190,6, 'Constancia valida si tiene sello humedo de la Institucion',0,1,'C');
		$pdf->SetFont('Times','',11);
		$pdf->Line(120,200,190,200);
		$pdf->Line(20,257,190,257);
		$pdf->Ln(1);
		$pdf->SetX(10);
		$pdf->Cell(190,5,utf8_decode(DIRECCM).' - '.CIUDADM.' - Telefono '.TELEMPM,0,1,'C');
		$pdf->Cell(190,5,'https://'.DOMINIO.'  -  Email '.SUCORREO,0,1,'C');
		mysqli_free_result($resultado);
		mysqli_free_result($periodo_query);
		mysqli_close($link);
		$pdf->Output();
	//}

}
else
{
	header("location:index.php#features"); 			
} 
?>