<?php
session_start();
#error_reporting(E_ALL);
#ini_set('display_errors', '1');
include_once("conexion.php"); 
include_once("inicia.php"); 
if(isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	$tablaPeriodo=$_SESSION['tablaPeriodo'];
	$usuario = $_SESSION['usuario'];
	$link = Conectarse(); 
	setlocale(LC_TIME, "spanish");
	date_default_timezone_set("America/Caracas");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$mes = $meses[date('n')-1];
	$resultado = mysqli_query($link,"SELECT A.idAlum, A.nombre as 'nomalu', B.nombreGrado as 'nomgra',  A.apellido, A.nacion, A.cedula, A.grado, A.correo, A.ced_rep, A.Periodo, A.pagado, A.morosida, A.deudatotal, B.especialidad, B.grado FROM alumcer A, grado".$tablaPeriodo." B WHERE A.cedula = $usuario and A.grado = B.grado");
	while ($row = mysqli_fetch_array($resultado))
    {
    	$idAlum=$row['idAlum'];
        $correo = $row['correo'];
        $ced_rep = $row['ced_rep'];
        $gra_alu = $row['grado'];
        $nac_alu = $row['nacion'];
		$ced_alu = $row['cedula'];
		$ape_alu = utf8_decode($row['apellido']);
		$nom_alu = utf8_decode($row['nomalu']);
		$nom_gra = $row['nomgra'];
		$esp_alu = $row['especialidad'];
		$periodo = $row['Periodo'];
		$deudatotal = $row['deudatotal'];
    }
    $morosida=$_SESSION['morosida'];
    $pagado=$_SESSION['pagado'];
    $periodo_query=mysqli_query($link,"SELECT nombre_periodo, directorPeriodo FROM periodos where nombre_periodo='$periodo' "); 
	while($row=mysqli_fetch_array($periodo_query))
	{
	    $director=$row['directorPeriodo'];
	}
	if ( $morosida>0 || empty($correo) || empty($ced_rep) || empty($gra_alu) || mysqli_num_rows($resultado)==0) 
    {
    	include_once("header.php"); ?>
		<style type="text/css">
			.error
			{ 
			    background-color:#34ACEC;
			    padding:5px;
			    border:#3180F9 5px solid;
			    float: center;
			    margin-top: 100px;
			    font-size: 25px;
			    color: white; 
			    font-weight: bold; 
			}
		</style><?php	
		if ($morosida>0) 
		{
			echo '<div align="center" class="error">Atencion!<br>';
		    echo 'Estimado Representante en necesaria su presencia en el dpto. de administación<br>';
		    echo 'con el fin de solventar su situación administrativa<br></div>';
		}
	    if ( empty($correo) || empty($ced_rep) || empty($gra_alu) ) 
	    {
			echo '<div align="center" class="error">POR FAVOR UTILICE LA OPCION DATOS PERSONALES<br>';
			echo ' CARGUE Y GUARDE TODOS LOS DATOS REQUERIDOS POR EL SISTEMA<br>';
			echo ' PARA PODER IMPRIMIR SU CARNET DE ESTUDIOS</div>';
	    }
		if(mysqli_num_rows($resultado)==0)
		{
			echo '<div align="center" class="error">POR FAVOR VERIFIQUE QUE TODOS<br>';
			echo ' LOS DATOS PERSONALES ESTEN COMPLETOS<br>';
			echo ' PARA PODER IMPRIMIR SU CARNET</div>';
		} ?>
		<br><div class="col-md-12 text-center">
			<button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> REGRESAR</button>
			</div><?php
		exit;
    }
    $tot=0;$totMate=0;$prom=0;
    for ($i=61; $i <= 65; $i++) 
	{
	 	$nroMat_query=mysqli_query($link,"SELECT tipo1, tipo2, tipo3, tipo4, tipo5, tipo6, tipo7, tipo8, tipo9, tipo10, tipo11, tipo12 FROM grado".$tablaPeriodo." where grado='$i' "); 
	 	while($row=mysqli_fetch_array($nroMat_query))
		{
			for ($x=1; $x < 13; $x++) 
			{
				${'tipo'.$x}=$row['tipo'.$x];
			}
		}
		$notas_query=mysqli_query($link,"SELECT notas FROM certifi where idAlumno='$idAlum' and idGrado='$i' ");
		if(mysqli_num_rows($notas_query) > 0)
		{
			$row3=mysqli_fetch_array($notas_query);
			$notas=$row3['notas'];
			$notas=str_replace("[","",$notas);
			$notas=str_replace("]","",$notas);
			$notas=str_replace('"',"",$notas);
			$notas_array = explode(",", $notas);
			 
			for ($i2=1; $i2 < 13; $i2++) 
			{ 
				if(${'tipo'.$i2}=='S')
				{
					$prom=$prom+$notas_array[$i2];
					if($notas_array[$i2]>0){$totMate = $totMate+1;}
				}
				if($notas_array[$i2]=='EX')
				{
					$totMate=$totMate-1;
				}
			}
		}
		if($totMate<1){$tot=0;}else{$tot=($prom/$totMate);}
	}
    require('fpdf/fpdf.php');
    class PDF extends FPDF 
	{
		function AcceptPageBreak()
		{
			
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
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->Addpage();
	$pdf->Image('assets/img/logo.png',40,9,23,25);
	$pdf->Image('assets/img/fondoagua.jpg',40,65,130,150);
	$pdf->SetFont('Times','I',15);
	$pdf->Cell(80);
	$pdf->Cell(30,6,(NKXS),0,1,'C');
	$pdf->Cell(80);
	$pdf->Cell(30,6,utf8_decode(EKKS),0,1,'C');
	$pdf->Cell(80);
	$pdf->SetFont('Times','I',13);
	$pdf->Cell(30,6,utf8_decode('Educación Media General '),0,1,'C');
	$pdf->Cell(80);
	$pdf->Cell(30,6,'M.P.P.E. '.CKLS.' RIF. '.RIFCOLM,0,1,'C');
	$pdf->Ln(10);
	$pdf->Cell(80);
	$pdf->SetFont('Arial','',15);
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Times','B',20);
	$pdf->Ln(18);
	$pdf->Cell(190,6, 'CONSTANCIA DE PROMEDIO',0,1,'C');
	$pdf->Ln(10);
	
	$pdf->SetFont('Times','',12);
	$pdf->SetX(30);
	$pdf->Cell(160,8,'La   suscrita   Directora   de   la   '.NKXS."  ".utf8_decode(EKKS),0,1,'L');
	$pdf->Line(87,84,190,84);
	$pdf->SetX(20);
	$pdf->Cell(170,8, 'hace constar por medio de la presente que el(la) Estudiante :',0,1,'L');
	$pdf->Ln(3);
	$pdf->SetFont('Times','',14);
	$pdf->SetX(20);
	$pdf->Cell(170,8, $ape_alu." ".$nom_alu,1,1,'C');
	$pdf->SetFont('Times','',12);
	$pdf->Ln(3);
	$pdf->SetX(20);
	$pdf->Cell(170,8, utf8_decode('portador(a) de la Cedula de Identidad '.$nac_alu.'-'.$ced_alu.','),0,0,'L');
	$pdf->SetX(118);
	$pdf->Cell(190,8, utf8_decode('ha  acumulado  durante  sus  estudios  de'),0,1,'L');
	$pdf->Line(85,114,115,114);
	//$pdf->Line(160,114,190,114);
	$pdf->SetX(20);
	$pdf->Cell(170,8, ($esp_alu.', el siguiente promedio de notas.'),0,1,'L');
	$pdf->Ln(3);
	$pdf->SetX(90);
	$pdf->Cell(30,8, number_format($tot,2,',','.').' Ptos.',1,1,'C');
	$pdf->Ln(7);
	$pdf->SetX(30);
	$pdf->Cell(160,8, utf8_decode('Constancia que  se  expide  a   petición   del  representante,  en  '.CIUDADM.'  a  los ').strftime("%d  dias"),0,1,'L');
	$pdf->SetX(20);
	$pdf->Cell(170,8, strftime("del  mes de ".$mes." de %Y"),0,1,'L');
	$pdf->Ln(10);
	$pdf->SetX(120);
	$pdf->Cell(70,8, 'Atentamente.',0,1,'L');
	$pdf->Ln(20);
	$pdf->SetFont('Times','',9);
	$pdf->SetX(120);
	$pdf->Cell(80,4, $director,0,1,'L');
	$pdf->SetX(120);
	$pdf->Cell(80,4, 'Directora.',0,1,'L');
	$pdf->Ln(52);
	$pdf->SetX(10);
	$pdf->Cell(190,6, 'Constancia valida si tiene sello humedo de la Institucion',0,1,'C');
	$pdf->Line(120,194,190,194);
	$pdf->Line(20,263,190,263);
	$pdf->Ln(1);
	$pdf->SetX(10);
	$pdf->Cell(190,6,utf8_decode(DIRECCM).' - '.CIUDADM.' - Telefono '.TELEMPM,0,1,'C');
	$pdf->SetX(10);
	$pdf->SetFont('Times','',10);
	$pdf->Cell(190,6, 'Pagina Web www.'.DOMINIO.'  -  Email '.SUCORREO,0,1,'C');
	mysqli_free_result($resultado);
	mysqli_free_result($periodo_query);
	mysqli_free_result($nroMat_query);
	mysqli_free_result($notas_query);

	$pdf->Output();
}
else
{
	header("location:index.php#features"); 			
} 
?>