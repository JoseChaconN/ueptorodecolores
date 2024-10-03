<?php
session_start();
if(isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	include_once('conexion.php');
	$link = Conectarse(); 
	$morosida=$_SESSION['morosida'];
  	$pagado=$_SESSION['pagado'];
  	if($_SESSION['morosida']>0)
	{ 
	  header("location:index.php?moro"); 
	}
	if($_SESSION['pagado']==0)
	{ 
	  header("location:index.php?sinpago"); 
	}
	//$periodoAlum=$_SESSION['periodoAlum'];
	require('fpdf/fpdf.php');
	include_once("inicia.php");
	$fechahoy = strftime( "%Y-%m-%d");	
	$periodoAlum=$_SESSION['periodoAlum']; 
	$periodoActivo=$_SESSION['nombre_periodo'];
	$usuario = $_SESSION['usuario'];
	if($periodoActivo!=ANOESCM)
	{
		$peridom=3;
	}else
	{
		$lapso_query=mysqli_query($link,"SELECT lapso FROM preinscripcion WHERE id = 2 ");
		while($row=mysqli_fetch_array($lapso_query))
		{
			$peridom=$row['lapso'];			
		}
		if($peridom=='1')
		{
			$fecha_query=mysqli_query($link,"SELECT publicar FROM preinscripcion WHERE id=3 ");	
		}
		if($peridom=='2')
		{
			$fecha_query=mysqli_query($link,"SELECT publicar FROM preinscripcion WHERE id=4 ");	
		}
		if($peridom=='3')
		{
			$fecha_query=mysqli_query($link,"SELECT publicar FROM preinscripcion WHERE id=5 ");	
		}
		while($row=mysqli_fetch_array($fecha_query))
		{
			$fecpublica=$row['publicar'];			
		}
		if( $fechahoy < $fecpublica )
		{
			if($peridom=='1')
			{
				$dia=substr($fecpublica, 8,2);
				$mes=substr($fecpublica, 5,2);
				$ano=substr($fecpublica, 0,4);
				$fecpublica=$dia.'/'.$mes.'/'.$ano;
				include_once("header.php"); ?>
				<style type="text/css">
					.error
					{ 
					    background-color:#929495;
					    padding:5px;
					    border:#F8E808 5px solid;
					    float: center;
					    margin-top: 100px;
					    font-size: 25px;
					    color: white; 
					    font-weight: bold; 
					}
				</style>
				<div align="center" class="col-md-8 offset-md-2 error">DISCULPE!<br>
					<h2> Reporte de notas para el primer momento disponible a partir del dia : <?php echo $fecpublica; ?> </h2>
				</div>
				<div class="col-md-12 text-center" style="margin-top: 1%;">
					<button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"><i class="ri-logout-box-line"></i> CERRAR VENTANA</button>
				</div><?php
				exit;
			}
			if($peridom=='2')
			{
				$peridom=1;	
			}
			if($peridom=='3')
			{
				$peridom=2;
			}
		}
	}
	if ($peridom==1) {$lapactm = 'Primero';}
	if ($peridom==2) {$lapactm = 'Segundo';}
	if ($peridom==3) {$lapactm = 'Tercero';}
	define("ANOESC", $periodoActivo);
	$resultado = mysqli_query($link,"SELECT nacion, cedula, apellido, nombre, ruta, deudatotal, grado, seccion, parentesco FROM alumcer WHERE cedula = '$usuario' ORDER BY cedula ASC");
	
	while($row=mysqli_fetch_array($resultado))
	{	
		$nacion=$row['nacion']; 
		$cedula=$row['cedula']; 
		$nombre=utf8_decode($row['nombre']); 
		$apellido=utf8_decode($row['apellido']); 
		$deudatotal = $row['deudatotal']; 
		$id_paren=$row['parentesco'];
		$foto = 'fotoalu/'.$row['ruta']; 
		$gra_alu = $row['grado'];
		$sec_alu = $row['seccion'];
		$ruta=$row['ruta'];
	}
	$resultado4 = mysqli_query($link,"SELECT * FROM cortes".$periodoAlum." WHERE ced_alu = '$usuario' ");
	if ($pagado==0 || $morosida>0 || empty($ruta) || $gra_alu<60 || mysqli_num_rows($resultado4)==0)
	{
		include_once ("header.php"); ?>
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
		echo '<div align="center" class="error"';
		if ($gra_alu<61)
		{
			echo '<p> DISCULPE! <br> Opcion valida solo para alumnos de Bachillerato<br> </p>';
		}
		if ($morosida>0) 
		{
			echo '<div align="center" class="error">Atencion!<br>';
		    echo 'Estimado Representante en necesaria su presencia en el dpto. de administación<br>';
		    echo 'con el fin de solventar su situación administrativa<br></div>';
		}
	   	if ($pagado==0)
	   	{
	   		echo'
	    	<p>ATENCION! <br> Usted no tiene pagos registrados<br>
	    	 por favor notifique el mensaje a la Institucion<br>
	    	 a la brevedad posible</p>';
	   	}
	   	if(mysqli_num_rows($resultado4)==0)
	   	{
	   		echo '<p> DISCULPE! <br>No tiene notas cargadas, por favor notifique a la institución</p>';
	   	}
	   	if (!file_exists($foto) || empty($foto) || is_null($foto))
		{
			echo '<div align="center" class="error">POR FAVOR VUELVA A CARGAR, <br>';
			echo ' UNA FOTO PARA EL ALUMNO<br>';
			echo ' TAMAÑO MAXIMO 500 X 500<br>';
			echo ' Y ASI PODER IMPRIMIR SUS NOTAS</div>';
		}
		echo '<div class="col-md-12 text-center">
		<br><button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> REGRESAR</button>
		</div>';
		include_once ("footer.php");
		exit;

	}
	
	class PDF extends FPDF 
	{
		function Header()
		{
			$this->Image('assets/img/logo.png',40,9,20,23);
			$this->SetFont('Arial','',15);
			$this->Cell(80);
			$this->Cell(30,6,utf8_decode(NKXS),0,1,'C');
			$this->Cell(80);
			$this->Cell(30,6,utf8_decode(EKKS),0,1,'C');
			$this->Cell(80);
			$this->SetFont('Arial','',10);
			$this->Cell(30,6,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');
			$this->Cell(80);
			$this->Cell(30,6,'Rif.: '.RIFCOLM.' - Telefono '.TELEMPM,0,1,'C');
			$this->Ln(10);
			$this->Cell(80);
			$this->SetFont('Arial','',20);
			$this->Cell(30,6,'Reporte de Notas',0,1,'C');
			$this->Cell(80);
			$this->SetFont('Arial','',12);
			$this->Cell(30,6,'Periodo Escolar '.ANOESC,0,1,'C');
		}
	}

	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->Addpage();
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(10);
	$pdf->Cell(30,6,'Cedula',1,0,'C',1);
	$pdf->SetX(43);
	$pdf->Cell(114,6,'Alumno',1,0,'C',1);
	$pdf->SetX(160);
	$pdf->Cell(40,6,'Momento',1,0,'C',1);
	$pdf->SetX(10);
	$pdf->Cell(30,6,'Cedula',1,0,'C',1);
	$pdf->Ln();
	$pdf->SetFont('Arial','',10);
	$pdf->SetX(10);
	$pdf->Image("$foto",165,8,35,38);
	$pdf->Cell(30,6, $nacion.'-'.$cedula,1,0,'C');
	$pdf->SetX(43);
	$pdf->Cell(114,6, $apellido.' '.$nombre,1,0,'C');
	$pdf->SetX(160);
	$pdf->Cell(40,6, $lapactm,1,1,'C');
	$resultado2 = mysqli_query($link,"SELECT B.nombre as nomsec, A.* FROM grado".$periodoAlum." A, secciones B WHERE A.grado = '$gra_alu' and B.id = '$sec_alu' ");	
	
	while ($row = mysqli_fetch_array($resultado2))
	{
		$pdf->SetFont('Arial','B',10);
		$pdf->SetX(10);
		$pdf->Cell(79,6,'Especialidad',1,0,'C',1);
		$pdf->SetX(92);
		$pdf->Cell(65,6,'Mencion',1,0,'C',1);
		$pdf->SetX(160);
		$pdf->Cell(40,6, utf8_decode('Año / Sección'),1,1,'C',1);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(79,6, ($row['especialidad']),1,0,'C');
		$pdf->SetX(92);
		$pdf->Cell(65,6, utf8_decode($row['mencion']),1,0,'C');
		$pdf->SetX(160);
		$nom_sec = $row['nomsec'];
		$pdf->Cell(40,6, utf8_decode($row['nombreGrado'].' "'.$nom_sec.'"'),1,1,'C');
		$mat1 = utf8_encode($row['mate1']);$mat2 = utf8_encode($row['mate2']);$mat3 = utf8_encode($row['mate3']);
		$mat4 = utf8_encode($row['mate4']);$mat5 = utf8_encode($row['mate5']);$mat6 = utf8_encode($row['mate6']);
		$mat7 = utf8_encode($row['mate7']);$mat8 = utf8_encode($row['mate8']);$mat9 = utf8_encode($row['mate9']);
		$mat10 = utf8_encode($row['mate10']);$mat11 = utf8_encode($row['mate11']);$mat12 = utf8_encode($row['mate12']);
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(10);
	$pdf->Cell(53,6,'Areas de Formacion',1,0,'C',1);
	$pdf->Cell(20,6,'Fecha',1,0,'C',1);
	$pdf->Cell(77,6, 'Didactica/Tecnica',1,0,'C',1);
	$pdf->Cell(10,6, '%',1,0,'C',1);
	$pdf->Cell(10,6, 'Nota',1,0,'C',1);
	$pdf->Cell(10,6, 'Acum',1,0,'C',1);
	$pdf->Cell(10,6,'Inas.',1,0,'C',1);
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',10);

	$notas=mysqli_query($link,"SELECT * FROM cortes".$periodoAlum." A, materiass".$periodoAlum." B, cortes1".$periodoAlum." C WHERE ced_alu = '$usuario' AND A.cod_materia = B.codigo AND A.cod_materia = C.cod_materia AND C.cod_materia = B.codigo AND C.cod_seccion = '$sec_alu' ORDER BY B.codigo  ");
	$aa='1';
	$son=0;
	while ($row=mysqli_fetch_array($notas)) 
	{ 
		$son+=1;
		$por1=$row['porcentaje1'.$peridom];
		$por2=$row['porcentaje2'.$peridom];
		$por3=$row['porcentaje3'.$peridom];
		$por4=$row['porcentaje4'.$peridom];
		$por5=$row['porcentaje5'.$peridom];
		$nom_mate=$row['nombremate'];
		$not1=$row['nota1'.$peridom];
		$not2=$row['nota2'.$peridom];
		$not3=$row['nota3'.$peridom];
		$not4=$row['nota4'.$peridom];
		$not5=$row['nota5'.$peridom];
		$nota_def = (($not1*$por1)/100) + (($not2*$por2)/100) + (($not3*$por3)/100) + (($not4*$por4)/100) + (($not5*$por5)/100);
		$porc_def = $por1+$por2+$por3+$por4+$por5;
		$inas1=$row['inas1'.$peridom];
		if(empty($inas1)){$inas1='0';}
		$inas2=$row['inas2'.$peridom];
		if(empty($inas2)){$inas2='0';}
		$inas3=$row['inas3'.$peridom];
		if(empty($inas3)){$inas3='0';}
		$inas4=$row['inas4'.$peridom];
		if(empty($inas4)){$inas4='0';}
		$inas5=$row['inas5'.$peridom];
		if(empty($inas5)){$inas5='0';}
		$inas_def = $inas1+$inas2+$inas3+$inas4+$inas5;
		$fecha1=$row['fecha1'.$peridom];
		$fecha2=$row['fecha2'.$peridom];
		$fecha3=$row['fecha3'.$peridom];
		$fecha4=$row['fecha4'.$peridom];
		$fecha5=$row['fecha5'.$peridom];
		$obser1=$row['obser1'.$peridom];
		$obser2=$row['obser2'.$peridom];
		$obser3=$row['obser3'.$peridom];
		$obser4=$row['obser4'.$peridom];
		$obser5=$row['obser5'.$peridom];
		$pdf->SetX(10);
		$pdf->Cell(53,6, utf8_decode($nom_mate),0,0,'L',$aa);

		$dia=substr($fecha1, 8,2);
		$mes=substr($fecha1, 5,2);
		$ano=substr($fecha1, 0,4);
		$fecha1=$dia.'/'.$mes.'/'.$ano;

		$pdf->Cell(20,6,$fecha1,0,0,'C',$aa);
		$pdf->Cell(77,6,utf8_decode(substr($obser1,0,35)),0,0,'L',$aa);
		$pdf->Cell(10,6,$por1,0,0,'R',$aa);
		$pdf->Cell(10,6,$not1,0,0,'R',$aa);
		$pdf->Cell(10,6,number_format(($not1*$por1)/100,2),0,0,'R',$aa);
		$pdf->Cell(10,6,$inas1,0,1,'R',$aa);
		if (!empty($fecha2)) 
		{
			$pdf->SetX(10);
			$pdf->Cell(53,6, '',0,0,'L',$aa);
			$dia=substr($fecha2, 8,2);
			$mes=substr($fecha2, 5,2);
			$ano=substr($fecha2, 0,4);
			$fecha2=$dia.'/'.$mes.'/'.$ano;
			$pdf->Cell(20,6,$fecha2,0,0,'C',$aa);
			$pdf->Cell(77,6,utf8_decode(substr($obser2,0,35)),0,0,'L',$aa);
			$pdf->Cell(10,6,$por2,0,0,'R',$aa);
			$pdf->Cell(10,6,$not2,0,0,'R',$aa);
			$pdf->Cell(10,6,number_format(($not2*$por2)/100,2),0,0,'R',$aa);
			$pdf->Cell(10,6,$inas2,0,1,'R',$aa);
		}
		if (!empty($fecha3)) 
		{
			$pdf->SetX(10);
			$pdf->Cell(53,6, '',0,0,'L',$aa);
			$dia=substr($fecha3, 8,2);
			$mes=substr($fecha3, 5,2);
			$ano=substr($fecha3, 0,4);
			$fecha3=$dia.'/'.$mes.'/'.$ano;
			$pdf->Cell(20,6,$fecha3,0,0,'C',$aa);
			$pdf->Cell(77,6,utf8_decode(substr($obser3,0,35)),0,0,'L',$aa);
			$pdf->Cell(10,6,$por3,0,0,'R',$aa);
			$pdf->Cell(10,6,$not3,0,0,'R',$aa);
			$pdf->Cell(10,6,number_format(($not3*$por3)/100,2),0,0,'R',$aa);
			$pdf->Cell(10,6,$inas3,0,1,'R',$aa);
		}	
		if (!empty($fecha4)) 
		{
			$pdf->SetX(10);
			$pdf->Cell(53,6, '',0,0,'L',$aa);
			$dia=substr($fecha4, 8,2);
			$mes=substr($fecha4, 5,2);
			$ano=substr($fecha4, 0,4);
			$fecha4=$dia.'/'.$mes.'/'.$ano;
			$pdf->Cell(20,6,$fecha4,0,0,'C',$aa);
			$pdf->Cell(77,6,utf8_decode(substr($obser4,0,35)),0,0,'L',$aa);
			$pdf->Cell(10,6,$por4,0,0,'R',$aa);
			$pdf->Cell(10,6,$not4,0,0,'R',$aa);
			$pdf->Cell(10,6,number_format(($not4*$por4)/100,2),0,0,'R',$aa);
			$pdf->Cell(10,6,$inas4,0,1,'R',$aa);
		}
		if (!empty($fecha5)) 
		{
			$pdf->SetX(10);
			$pdf->Cell(53,6, '',0,0,'L',$aa);
			$dia=substr($fecha5, 8,2);
			$mes=substr($fecha5, 5,2);
			$ano=substr($fecha5, 0,4);
			$fecha5=$dia.'/'.$mes.'/'.$ano;
			$pdf->Cell(20,6,$fecha5,0,0,'C',$aa);
			$pdf->Cell(77,6,utf8_decode(substr($obser5,0,35)),0,0,'L',$aa);
			$pdf->Cell(10,6,$por5,0,0,'R',$aa);
			$pdf->Cell(10,6,$not5,0,0,'R',$aa);
			$pdf->Cell(10,6,number_format(($not5*$por5)/100,2),0,0,'R',$aa);
			$pdf->Cell(10,6,$inas5,0,1,'R',$aa);
		}
		$pdf->Cell(120,6,'' ,0,0,'C',$aa);
		$pdf->Cell(30,6, 'Totales->',0,0,'C',$aa);
		$pdf->Cell(10,6, $porc_def,0,0,'R',$aa);
		$pdf->Cell(10,6,'',0,0,'R',$aa);
		$pdf->Cell(10,6, number_format($nota_def,2),0,0,'R',$aa);
		$pdf->Cell(10,6, $inas_def,0,1,'R',$aa);
		if($aa=='1'){$aa='';} else {$aa='1';}
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->Ln(1);
	$pdf->SetX(20);
	$pdf->Cell(30,6, '(* Leyenda: 01 = No Entrego)',0,1,'C');
	mysqli_free_result($lapso_query);
	mysqli_free_result($fecha_query);
	
	mysqli_free_result($resultado);
	mysqli_free_result($resultado4);
	mysqli_free_result($resultado2);
	mysqli_free_result($notas);
	$pdf->Output();
}
else
{
	header("location:index.php#features");
} 
?>