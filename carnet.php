<?php
session_start();
if(isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	include_once("conexion.php"); 
	include_once("inicia.php"); 
	$tablaPeriodo=$_SESSION['tablaPeriodo'];
	if(!empty($_SESSION['admin']))
	{
		$usuario = $_GET['cedalum'];
	} else { $usuario = $_SESSION['usuario']; }
	$link = Conectarse(); 
	$result = mysqli_query($link,"SELECT A.nacion, A.cedula, A.apellido, A.grado, A.seccion, A.correo, A.ced_rep, A.nombre, A.ruta, A.pagado, A.deudatotal, A.Periodo, B.nombreGrado as nomgra, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C WHERE A.cedula = '$usuario' and A.grado = B.grado and A.seccion=C.id ");
	while ($row = mysqli_fetch_array($result))
    {
        $correo = $row['correo'];
        $ced_rep = $row['ced_rep'];
        $gra_alu = $row['grado'];
        $nac_alu = $row['nacion'];
		$ced_alu = $row['cedula'];
		$ape_alu = utf8_decode($row['apellido']);
		$nom_alu = utf8_decode($row['nombre']);
		$sec_alu= $row['seccion'];
		$nomgra = utf8_decode($row['nomgra']);
		$seccion= $row['nomsec'];
		$deudatotal = $row['deudatotal'];
		$periodo = $row['Periodo'];
		if(empty($row['ruta']))
		{ $fot_alu = 'imagenes/fotocarnet.jpg'; } else
		{ $fot_alu = 'fotoalu/'.$row['ruta'];}
		$ruta=$row['ruta'];
    }
    $morosida=$_SESSION['morosida'];
    $pagado=$_SESSION['pagado'];
    if ( empty($correo) || empty($ced_rep) || empty($gra_alu) || mysqli_num_rows($result)==0 || $pagado==0 ||  empty($ruta) || $sec_alu==99 || $morosida>0) 
    {
    	include_once("header.php"); 
		?>
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
		</style>
		<?php	
	    if ( empty($correo) || empty($ced_rep) || empty($gra_alu) ) 
	    {
			echo '<div align="center" class="error">POR FAVOR UTILICE LA OPCION DATOS PERSONALES<br>';
			echo ' CARGUE Y GUARDE TODOS LOS DATOS REQUERIDOS POR EL SISTEMA<br>';
			echo ' PARA PODER IMPRIMIR SU CARNET DE ESTUDIOS</div>';
	    }
		if(mysqli_num_rows($result)==0)
		{
			echo '<div align="center" class="error">POR FAVOR VERIFIQUE QUE TODOS<br>';
			echo ' LOS DATOS PERSONALES ESTEN COMPLETOS<br>';
			echo ' PARA PODER IMPRIMIR SU CARNET</div>';
		} 
		if ($morosida>0) 
		{
			echo '<div align="center" class="error">Atencion!<br>';
		    echo 'Estimado Representante en necesaria su presencia en el dpto. de administación<br>';
		    echo 'con el fin de solventar su situación administrativa<br></div>';
		}
		if (empty($ruta) ) 
		{
			echo '<div align="center" class="error">POR FAVOR VUELVA A CARGAR, <br>';
		   	echo ' UNA FOTO PARA EL ALUMNO<br>';
		   	echo ' Y ASI PODER IMPRIMIR SU CARNET DE ESTUDIOS</div>';	   	
		}
		if( $sec_alu==99)
		{
			echo '<div align="center" class="error">Para poder imprimir su carnet debe verificar en la sección PROCESOS Perfil de Alumno:<br>
			   	 1- Estar asignado a un grado o año.<br>
			   	 2- Estar asignado a una sección (diferente a X si este es el caso notifique a la institución) </div>';
		} 
		if ($pagado==0) 
		{
			echo '<div align="center" class="error">DISCULPE USTED NO TIENE PAGOS REGISTRADOS <br>
			NOTIFIQUE A LA INSTITUCION PARA PODER EMITIR SU CARNET</div>';
		}
		?>
		<br><div class="col-md-12 text-center">
			<button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> REGRESAR</button>
			</div>
		<?php
		exit;
	}
	require('fpdf/fpdf.php');
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
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->Addpage();
	$pdf->SetFillColor(1,1,1);
	$pdf->SetFont('Times','',8);
	$pdf->setTextColor(255,255,255);
	$pdf->Ln(-2);
	$pdf->SetX(67);
	$pdf->Cell(64,4, utf8_decode('La dirección del plantel certifica que el portador de'),0,1,'C');
	$pdf->SetX(67);
	$pdf->Cell(64,4, 'este carnet cursa estudios actualmente en esta',0,1,'C');
	$pdf->SetX(67);
	$pdf->Cell(64,4, utf8_decode('institución, por lo tanto debe tener las'),0,1,'C');
	$pdf->SetX(67);
	$pdf->Cell(64,4, 'consideraciones del caso.',0,1,'C');
	$pdf->SetX(67);
	$pdf->Cell(64,4, '',0,1,'C');
	$pdf->Ln(2);
	$pdf->SetX(67);
	$pdf->Cell(64,4, DIRECCM ,0,1,'C');
	$pdf->SetX(67);
	$pdf->Cell(64,4, CIUDADM.' - '.ESTADOM.' Tlf.: '.TELEMPM ,0,1,'C');
	$pdf->SetX(67);
	$pdf->Cell(64,4, 'Valido para el Periodo: '.$periodo ,0,1,'C');
	$pdf->SetFont('Times','',10);
	$pdf->setTextColor(1,1,1);
	$pdf->Ln(35);
	$pdf->SetX(3);
	$pdf->Cell(75,5, $ape_alu,0,1,'L');
	$pdf->SetX(3);
	$pdf->Cell(75,5, $nom_alu,0,1,'L');
	$pdf->SetX(3);
	$pdf->Cell(30,5, $nac_alu.'-'.$ced_alu,0,1,'L');
	$pdf->SetX(3);
	$pdf->Cell(90,5, $nomgra.utf8_decode(' Sección: "').$seccion.'"',0,1,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(70);
	$pdf->Cell(58,5, DOMINIO,0,1,'C');
	$pdf->setTextColor(255,255,255);
	$pdf->SetFont('Arial','B',13);
	$pdf->Ln(.8);
	$pdf->SetX(3);
	$pdf->Cell(61,6, utf8_decode('AÑO ESCOLAR ').$periodo,0,1,'C');
	$pdf->Image($fot_alu,3,40,30,32);
	mysqli_free_result($result);
	mysqli_close($link);
	$pdf->Output();
}
else
{
	header("location:index.php#features");			
}
?>