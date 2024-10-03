<?php
session_start();
if(isset($_SESSION['usuario']) && isset($_SESSION['password']))
{
	include_once("inicia.php");
	include_once('conexion.php');
	$link = Conectarse(); 
	require('fpdf/fpdf.php');
	setlocale(LC_TIME, "spanish");
	date_default_timezone_set("America/Caracas");
	$fechahoy = strftime( "%Y-%m-%d");	
	if($_SESSION['morosida']>0)
	{ 
	  header("location:index.php?moro"); 
	}
	if($_SESSION['pagado']==0)
	{ 
	  header("location:index.php?sinpago"); 
	}
	$periodoAlum=$_GET['peri'];
	$peridom=$_GET['lapsom'];
	$periodo_query=mysqli_query($link,"SELECT nombre_periodo, directorPeriodo FROM periodos where tablaPeriodo='$periodoAlum' "); 
	while($row=mysqli_fetch_array($periodo_query))
	{
	    $periodoActivo=$row['nombre_periodo'];
	    $director=$row['directorPeriodo'];
	}
	$lapso_query=mysqli_query($link,"SELECT lapso FROM preinscripcion WHERE id=2 ");
	while($row=mysqli_fetch_array($lapso_query))
	{
		$lapsoActivo=$row['lapso'];			
	}
	$usuario = $_SESSION['usuario'];
	
	if($peridom=='1')
	{
		$fecha_query=mysqli_query($link,"SELECT publicarPrimaria, iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=3 ");	
	}
	if($peridom=='2')
	{
		$fecha_query=mysqli_query($link,"SELECT publicarPrimaria, iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=4 ");	
	}
	if($peridom=='3')
	{
		$fecha_query=mysqli_query($link,"SELECT publicarPrimaria, iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=5 ");	
	}
	while($row=mysqli_fetch_array($fecha_query))
	{
		$fecpublica=$row['publicarPrimaria'];
		$iniciaMaestro=strftime("%B %Y", strtotime($row['iniciaMaestro']));
		$terminaMaestro=strftime("%B %Y", strtotime($row['terminaMaestro']));
	}
	if( $fechahoy < $fecpublica && $periodoActivo==ANOESCM )
	{
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
		</style><?php
		if($peridom=='1')
		{ ?>
			<div align="center" class="error">DISCULPE!<br>
				<h2> Boleta para el primer momento disponible a partir del dia : <?= strftime("%d de %B de %Y", strtotime($fecpublica)); ?> </h2><br>
			</div>
			<div class="col-md-12 text-center">
				<button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> CERRAR VENTANA</button>
			</div><?php
			exit;
		}
		if($peridom=='2')
		{ ?>
			<div align="center" class="error">DISCULPE!<br>
				<h2> Boleta para el segundo momento disponible a partir del dia : <?= strftime("%d de %B de %Y", strtotime($fecpublica)); ?> </h2><br>
			</div>
			<div class="col-md-12 text-center">
				<br><button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> CERRAR VENTANA</button>
			</div><?php
			exit;	
		}
		if($peridom=='3')
		{ ?>
			<div align="center" class="error">DISCULPE!<br>
				<h2> Boleta para el tercer momento disponible a partir del dia : <?= strftime("%d de %B de %Y", strtotime($fecpublica)); ?> </h2><br>
			</div>
			<div class="col-md-12 text-center">
				<br><button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> CERRAR VENTANA</button>
			</div>
			<?php //$peridom=2;
			exit;
		}
	}
	$resultado = mysqli_query($link,"SELECT A.nacion, A.cedula, A.nombre, A.apellido, A.ced_rep, A.parentesco,A.grado, A.seccion, A.pagado, A.deudatotal, A.ruta, A.FechaNac, B.nombreGrado, B.mate1, B.mate2, B.mate3, B.mate4, B.mate5, B.mate6, B.mate7, B.mate8, B.mate9, B.mate10, B.sonMate, B.lin_x_mate, C.nombre as nombreSec, D.representante, E.nomparen FROM alumcer A, grado".$periodoAlum." B, secciones C, represe D, parentescos E WHERE A.cedula = '$usuario' and A.grado=B.grado and A.seccion=C.id and A.ced_rep=D.cedula and A.parentesco=E.idparen ");
	while ($row = mysqli_fetch_array($resultado))
	{	
		$pagado = $row['pagado']; 
		$deudatotal = $row['deudatotal']; 
		$id_paren=$row['parentesco'];
		$foto  = 'fotoalu/'.$row['ruta']; 
		$nac_alu = $row['nacion'];
		$ced_alu = $row['cedula'];
		$alumno = utf8_decode($row['apellido'].' '.$row['nombre']);
		$nom_alu = utf8_decode($row['nombre']);
		$ape_alu = utf8_decode($row['apellido']);
		$cedula = $row['nacion'].' '.$row['cedula'];
		$fechanac=$row['FechaNac'];
		$nacido=strftime("%d de %B de %Y", strtotime($fechanac));
		$ced_rep = $row['ced_rep'];
		$par_rep = $row['parentesco'];
		$gra_alu = $row['grado'];
		$sec_alu = $row['seccion'];
		$nomgra=$row['nombreGrado'];
		$mate1=$row["mate1"];
		$mate2=$row["mate2"];
		$mate3=$row["mate3"];
		$mate4=$row["mate4"];
		$mate5=$row["mate5"];
		$mate6=$row["mate6"];
		$mate7=$row["mate7"];
		$mate8=$row["mate8"];
		$mate9=$row["mate9"];
		$mate10=$row["mate10"];
		$sonMate=$row['sonMate'];
    	$lin_x_mat=$row['lin_x_mate'];
    	$nomsec=$row['nombreSec'];
    	$representante=$row['representante'];
    	$par_rep = $row['nomparen'];
	}
	$morosida=$_SESSION['morosida'];
	if($peridom == 1){ $nom_lapso = "Primero";}
	if($peridom == 2){ $nom_lapso = "Segundo";}
	if($peridom == 3){ $nom_lapso = "Tercero";}
	$boleta=mysqli_query($link,"SELECT * FROM boletas".$periodoAlum." WHERE grado = '$gra_alu' and seccion = '$sec_alu' AND lapso = '$peridom'");
	$row2=mysqli_fetch_array($boleta);
	$proyecto=$row2['proyecto'];
	$proyecto_aula=$row2['proyecto_aula'];
	$resultado5 = mysqli_query($link,"SELECT ced_prof FROM trgsp".$periodoAlum." WHERE (principal='1' and id_grado1 = '$gra_alu' and id_seccion1 = '$sec_alu') or (principal='1' and id_grado2 = '$gra_alu' and id_seccion2 = '$sec_alu') LIMIT 1");
	while ($rowP = mysqli_fetch_array($resultado5))
	{
		$cedprof=$rowP['ced_prof'];
	}
	$resultado6 = mysqli_query($link,"SELECT nombre,apellido FROM alumcer WHERE cedula = '$cedprof' ");
	while ($row = mysqli_fetch_array($resultado6))
	{
		$nomprof=utf8_decode($row['nombre'].' '.$row['apellido']);
	}
	class PDF extends FPDF 
	{
		function Header()
		{
			global $nom_lapso;	
			global $cedula;
			global $alumno;
			global $ced_rep;
			global $representante;
			global $par_rep;
			global $nomgra;
			global $nomsec;
			global $nomprof;
			global $proyecto;
			global $diashab;
			global $asistio;
			global $periodoActivo;
			global $foto;
			if ($this->PageNo() >2)
			{
				$this->Image('assets/img/logo.jpg',10,5,55,38);
				$this->SetFont('Arial','',12);
				$this->Cell(80);
				$this->Cell(30,6,utf8_decode(NKXS),0,1,'C');
				$this->Cell(80);
				$this->SetFont('Times','B',19);
				$this->Cell(30,6,utf8_decode(EKKS),0,1,'C');
				$this->Cell(80);
				$this->SetFont('Arial','',10);
				$this->Cell(30,4,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');
				$this->Cell(80);
				$this->Cell(30,4,'Rif.: '.RIFCOLM.' - Telefono '.TELEMPM,0,1,'C');
				$this->Cell(80);
				$this->SetFont('Arial','',12);
				$this->Cell(30,6,utf8_decode('Periodo Escolar ').$periodoActivo,0,1,'C');
				$this->SetFont('Arial','B',10);
				$this->SetFillColor(232,232,232);
				$this->SetX(10);
				$this->Cell(30,5,'Cedula',1,0,'C',1);
				$this->Cell(120,5,'Estudiante',1,0,'C',1);
				$this->Cell(40,5,('Momento'),1,0,'C',1);
				$this->SetX(10);
				$this->Cell(30,5,'Cedula',1,0,'C',1);
				$this->Ln();
				$this->SetFont('Arial','',10);
				$this->SetX(10);
				$this->Cell(30,5, $cedula,1,0,'C');
				$this->Cell(120,5, $alumno,1,0,'C');
				$this->Cell(40,5, $nom_lapso,1,1,'C');
				$this->SetFont('Arial','B',10);
				$this->SetX(10);
				$this->Cell(30,5,'Cedula',1,0,'C',1);
				$this->Cell(120,5,'Representante',1,0,'C',1);
				$this->Cell(40,5, 'Parentesco',1,1,'C',1);
				$this->SetFont('Arial','',10);
				$this->Cell(30,5, $ced_rep,1,0,'C');
				$this->Cell(120,5, utf8_decode($representante),1,0,'C');
				$this->Cell(40,5, utf8_decode($par_rep),1,1,'C');
				$this->Cell(35,5, $nomgra.' "'.$nomsec.'"',1,0,'C');
				$this->Cell(30,5, utf8_decode('Días Hábiles: ').$diashab,1,0,'L');
				$this->Cell(25,5, utf8_decode('Asistió: ').$asistio,1,0,'L');
				$this->Cell(100,5, 'Docente: '.$nomprof,1,1,'L');
				$this->SetFont('Arial','B',10);
				$this->Ln(2);
				$this->Image($foto,165,5,25,28);
			}
		}
		function Footer()
		{
			$this->SetY(-15);
			$this->SetX(-15);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,4,'Pagina '.$this->PageNo().'/{nb}',0,1,'C');
			//$this->Cell(0,4,'www.'.DOMINIO,0,0,'C');
			
		}
	}
	$diashabil = mysqli_query($link,"SELECT * FROM notaprimaria".$periodoAlum." WHERE ced_alu = '$usuario'");
	if (mysqli_num_rows($diashabil)>0)
	{
		while ($row = mysqli_fetch_array($diashabil))
		{
			$gra_alu=$row['grado'];
			$sec_alu = $row['idSeccion'];
			if ($peridom==1)
			{
				$diashab = $row['dias_habiles1'];
				$asistio = $row['asistencia1'];
				$momento='Primer Momento Septiembre-Diciembre';
			}
			if ($peridom==2)
			{
				$diashab = $row['dias_habiles2'];
				$asistio = $row['asistencia2'];
				$momento='Segundo Momento Enero-Abril';
			}
			if ($peridom==3)
			{
				$diashab = $row['dias_habiles3'];
				$asistio = $row['asistencia3'];
				$literal = $row['literal'];
				$momento='Tercer Momento Abril-Julio';
			}
		}
	}
	if (mysqli_num_rows($boleta)==0 || !file_exists($foto) || empty($foto) || is_null($foto) || (mysqli_num_rows($diashabil)==0 && $pagado>0 && $morosida==0) || ($morosida>0 || $pagado==0) )
	{ 
		include_once("header.php");?>
		<style type="text/css">
			.error
			{ 
				background-color:#929495;
				padding:5px;
				border:#F8E808 5px solid;
				float: center;
				margin-top: 50px;
				font-size: 25px;
				color: white; 
				font-weight: bold; 
			}
		</style>
		<div class="col-md-12" style="margin-top: 10%;"></div><?php
		if (!file_exists($foto) || empty($foto) || is_null($foto)) 
		{
			echo '<div align="center" class="error col-md-8 offset-md-2">POR FAVOR VUELVA A CARGAR, <br>';
		   	echo ' UNA FOTO PARA EL ALUMNO<br>';
		   	echo ' TAMAÑO MAXIMO 500 X 500<br>';
		   	echo ' Y ASI PODER IMPRIMIR SU BOLETIN DE NOTAS</div>';	 
		}
		if (mysqli_num_rows($boleta)==0)
		{ ?>
			<div align="center" class="error col-md-8 offset-md-2" >DISCULPE!<br>
			La boleta para el momento <?= $nom_lapso ?> no ha sido creada<br>
			Por favor solicite la fecha de publicación en el Plantel<br>
			</div><?php
		}
		if (mysqli_num_rows($diashabil)==0 and $pagado>0 and $morosida==0)
		{ 
			echo '<div align="center" class="error col-md-8 offset-md-2">No tiene notas Cargadas<br> Notifique a la Institución</div>';
		}
    	if ($morosida>0)
    	{
	    	echo '<div align="center" class="error col-md-8 offset-md-2">Estimado Representante<br>';
	    	echo 'Es necesaria su presencia en el dpto.de administración a la brevedad<br></div>';
	    	
    	}
    	if ($pagado==0)
    	{
	    	echo '<div align="center" class="error col-md-8 offset-md-2">ATENCION USTED NO TIENE PAGOS REGISTRADOS<br>';
	    	echo ' POR FAVOR NOTIFIQUE EL MENSAJE A LA INSTITUCION<br>';
	    	echo ' A LA BREVEDAD POSIBLE<br><br></div>';
	    	
    	}
    	echo '<div align="center" style="margin-top:2%; margin-bottom:2%;"><button class="btn-group btn btn-warning text-center" role="group" aria-label="" name="regresar" onClick="window.close()">CERRAR VENTANA</button></div>';
    	include("footer.php");
    	exit;
		
	}
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->Addpage();
	$pdf->SetFillColor(253,254,254);
	$pdf->Ln(10);
	// Primera Pagina
	$pdf->SetFont('Arial','',11);
	$pdf->Image('assets/img/logo.jpg',12,17,40,25);
	$pdf->Cell(190,6,utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'),0,1,'C');
	$pdf->Cell(190,6,utf8_decode('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN'),0,1,'C');
	$pdf->SetFont('Times','B',17);
	$pdf->Cell(190,6,utf8_decode('U.E.P. '.strtoupper(EKKS)),0,1,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(190,4,CIUDADM.' - Edo. '.ESTADOM,0,1,'C');
	$pdf->Ln(60);
	$pdf->SetFont('Times','',20);
	
	$pdf->Image('imagenes/navidad.png',30,45,150,140);
	$pdf->SetX(55);
	$pdf->Cell(100,10,'INFORME DESCRIPTIVO DE',0,1,'C',1);
	$pdf->SetX(55);
	$pdf->Cell(100,10,utf8_decode('EDUCACIÓN INICIAL'),0,1,'C',1);
	$pdf->SetFillColor(234,237,237);
	$pdf->Ln(70);
	$pdf->SetFont('Times','',13);
	$pdf->Cell(35,6,'Mi nombre es: ',0,1,'L');
	$pdf->Cell(35,6,'Mis apellidos son: ',0,1,'L');
	$pdf->Cell(35,6,utf8_decode('Nací el: '),0,1,'L');
	$pdf->Cell(35,6,'Cursando el: ',0,1,'L');
	$pdf->Cell(35,6,'Mi maestra es: ',0,1,'L');
	$pdf->Cell(35,6,utf8_decode('Año escolar: '),0,1,'L');

	$pdf->SetFont('Times','B',13);
	$pdf->Ln(-36);
	$pdf->SetX(47);
	$pdf->Cell(90,6,$nom_alu,0,1,'L');
	$pdf->SetX(47);
	$pdf->Cell(90,6,$ape_alu,0,1,'L');
	$pdf->SetX(47);
	$pdf->Cell(90,6,$nacido,0,1,'L');
	$pdf->SetX(47);
	$pdf->Cell(90,6,$nomgra.utf8_decode(', Sección ').$nomsec,0,1,'L');
	$pdf->SetX(47);
	$pdf->Cell(90,6,$nomprof,0,1,'L');
	$pdf->SetX(47);
	$pdf->Cell(90,6,$periodoActivo,0,1,'L');

	$pdf->Ln(30);
	$pdf->SetFont('Times','',20);
	$pdf->Cell(190,6,utf8_decode('¡UNIDOS POR LA EXCELENCIA EDUCATIVA!'),0,1,'C');

	// Segunda Pagina
	$pdf->Addpage();
	$pdf->Ln(10);
	$pdf->SetFont('Arial','',14);
	$pdf->Cell(190,10,utf8_decode('ESCALA DE ESTIMACIÓN:'),0,1,'C');
	$pdf->SetFont('Arial','',11);
	$pdf->SetX(25);
	$pdf->Cell(40,18,utf8_decode('CONSOLIDADO'),1,0,'C');
	$pdf->Cell(15,18,utf8_decode('C'),1,0,'C');
	$pdf->MultiCell(105,6,utf8_decode('El(a) estudiante alcanzó las competencias del nivel, en las diferentes áreas académicas y en la transferencia de los aprendizajes a otras situaciones de manera espontánea.'),1,'J');
	$pdf->SetX(25);
	$pdf->Cell(40,18,utf8_decode('PROCESO'),1,0,'C');
	$pdf->Cell(15,18,utf8_decode('P'),1,0,'C');
	$pdf->MultiCell(105,6,utf8_decode('El(a) estudiante muestra avances significativos en su desempeño hacia el alcance de las competencias propuestas para el nivel.'),1,'J');
	$pdf->SetX(25);
	$pdf->Cell(40,18,utf8_decode('INICIADO'),1,0,'C');
	$pdf->Cell(15,18,utf8_decode('I'),1,0,'C');
	$pdf->MultiCell(105,6,utf8_decode('El(a) estudiante muestra los primeros indicios de avance en su desempeño con relación a las competencias a alcanzar.                '),1,'J');

	$pdf->Ln(16);
	$pdf->SetFont('Times','B',16);
	$pdf->Cell(190,8,'PROYECTO EDUCATIVO INTEGRAL',0,1,'C');
	$pdf->Cell(190,8,'COMUNITARIO (P.E.I.C.)',0,1,'C');
	$pdf->SetFont('Times','',16);
	$pdf->Ln(4);
	$pdf->SetX(25);
	$pdf->MultiCell(160,8,utf8_decode($proyecto),1,'C');

	$pdf->Ln(18);
	$pdf->SetFont('Times','B',16);
	$pdf->Cell(190,6,utf8_decode('PROYECTO DE AULA (P.A.)'),0,1,'C');
	$pdf->SetFont('Times','B',12);
	$pdf->Ln(6);
	
	$pdf->SetX(25);
	$pdf->Cell(190,10,utf8_decode($momento),0,1,'L');
	$pdf->SetX(25);
	$pdf->Cell(15,6,'P.A.',0,0,'L');
	$pdf->SetFont('Times','',18);
	$pdf->MultiCell(145,8,utf8_decode($proyecto_aula),1,'C');
	$pdf->Addpage();
	

	
	//$pdf->Image($foto,165,8,30,33);
	
	mysqli_data_seek($boleta, 0);
	while($row=mysqli_fetch_array($boleta))
	{
	 	//INDICADORES PRIMERA MATERIA//
		for ($i=1; $i <=$sonMate ; $i++) 
	 	{ 
	 		for ($x=1; $x <=$lin_x_mat ; $x++) 
	 		{ 
	 			${'mate1'.$x} = $row['mate1'.$x];
                ${'mate2'.$x} = $row['mate2'.$x];
                ${'mate3'.$x} = $row['mate3'.$x];
                ${'mate4'.$x} = $row['mate4'.$x];
                ${'mate5'.$x} = $row['mate5'.$x];
                if($sonMate>5){ ${'mate6'.$x} = $row['mate6'.$x]; }
                if($sonMate>6){ ${'mate7'.$x} = $row['mate7'.$x]; }
                if($sonMate>7){ ${'mate8'.$x} = $row['mate8'.$x]; }
                if($sonMate>8){ ${'mate9'.$x} = $row['mate9'.$x]; }
                if($sonMate>9){ ${'mate10'.$x} = $row['mate10'.$x]; }
	 		}
	 	}
	}
	mysqli_data_seek($diashabil, 0);
	if (mysqli_num_rows($diashabil)>0 ) //&& $morosida<=140000 && $pagado>0
	{
		while ($row = mysqli_fetch_array($diashabil))
		{
			if ($peridom==1){$lp='1';}
			if ($peridom==2){$lp='2';}
			if ($peridom==3){$lp='3';}

			for ($i=1; $i <= $lin_x_mat; $i++) 
			{ 
				${'aprecia1'.$i} = (empty($row['notap1'.$lp.$i])) ? '***' : $row['notap1'.$lp.$i];
				${'aprecia2'.$i} = (empty($row['notap2'.$lp.$i])) ? '***' : $row['notap2'.$lp.$i];
				${'aprecia3'.$i} = (empty($row['notap3'.$lp.$i])) ? '***' : $row['notap3'.$lp.$i];
				${'aprecia4'.$i} = (empty($row['notap4'.$lp.$i])) ? '***' : $row['notap4'.$lp.$i];
				${'aprecia5'.$i} = (empty($row['notap5'.$lp.$i])) ? '***' : $row['notap5'.$lp.$i];
				${'aprecia6'.$i} = (empty($row['notap6'.$lp.$i])) ? '***' : $row['notap6'.$lp.$i];
				${'aprecia7'.$i} = (empty($row['notap7'.$lp.$i])) ? '***' : $row['notap7'.$lp.$i];
				${'aprecia8'.$i} = (empty($row['notap8'.$lp.$i])) ? '***' : $row['notap8'.$lp.$i];
				${'aprecia9'.$i} = (empty($row['notap9'.$lp.$i])) ? '***' : $row['notap9'.$lp.$i];
				${'aprecia10'.$i} = (empty($row['notap10'.$lp.$i])) ? '***' : $row['notap10'.$lp.$i];
			}

			$observa=$row['observacion'.$lp];
			$lin=0;
			for ($i=1; $i <= $sonMate; $i++) 
			{
				if(!empty(${'mate'.$i.'1'}))
				{
					$pdf->SetFont('Arial','B',10);
					$pdf->SetX(10);
					$pdf->Cell(190,5,strtoupper(${'mate'.$i}),1,1,'L',1);
					$pdf->Cell(160,5,'INDICADORES',1,0,'C',1);
					$pdf->Cell(10,5,'I',1,0,'C',1);
					$pdf->Cell(10,5,'P',1,0,'C',1);
					$pdf->Cell(10,5,'C',1,0,'C',1);
					$pdf->Ln(7);
					$pdf->SetFont('Arial','',9);
					$lin++;
					for ($x=1; $x <= $lin_x_mat; $x++) 
					{ 
						if(!empty(${'mate'.$i.$x}))
						{
							$y=$pdf->GetY();
							$pdf->MultiCell(160,3.5,utf8_decode(${'mate'.$i.$x}),0,'L',1);
							$pdf->SetXY(170,$y);
							
							$n1 = (${'aprecia'.$i.$x}=='I') ? '4' : '[' ;
							$n2 = (${'aprecia'.$i.$x}=='P') ? '4' : '[' ;
							$n3 = (${'aprecia'.$i.$x}=='C') ? '4' : '[' ;
							
							$pdf->SetFont('ZapfDingbats','', 12);

							$pdf->Cell(10,7, ($n1),1,0,'C');
							$pdf->Cell(10,7, ($n2),1,0,'C');
							$pdf->Cell(10,7, ($n3),1,0,'C');
							$pdf->SetFont('Arial','',9);
							$pdf->Ln(10);
							$lin++;
						}
						if($lin>=16)
						{
							$pdf->Addpage(); $lin=0;
							$pdf->SetFont('Arial','B',10);
							$pdf->SetX(10);
							$pdf->Cell(190,5,strtoupper(${'mate'.$i}),1,1,'L',1);
							$pdf->Cell(160,5,'INDICADORES',1,0,'C',1);
							$pdf->Cell(10,5,'I',1,0,'C',1);
							$pdf->Cell(10,5,'P',1,0,'C',1);
							$pdf->Cell(10,5,'C',1,0,'C',1);
							$pdf->Ln(7);
							$pdf->SetFont('Arial','',9);
							$lin++;
						}
					}
				}
			}
			if ($peridom==3)
			{
				$bus_literal = mysqli_query($link,"SELECT descripcion_literal FROM literales WHERE escala_literal = '$literal' ");
				while ($row=mysqli_fetch_array($bus_literal)) 
				{
					$descripcion=utf8_encode($row['descripcion_literal']);
				}
				$pdf->Cell(190,5,'',0,1,'C');
				$pdf->SetFont('Arial','B',10);
				$pdf->Ln(2);
				$pdf->Cell(190,5,utf8_decode('RESULTADOS EN LA ESCALA ALFABÉTICA, LITERAL: "'.$literal.'"'),1,1,'C',1);
				$pdf->Ln(1);
				$pdf->Cell(190,5,utf8_decode('INTERPRETACIÓN DE LOS RESULTADOS'),1,1,'C',1);
				$pdf->SetFont('Arial','',9);
				$pdf->MultiCell(190,5,utf8_decode($descripcion),1,1,'L');
				$pdf->Ln(5);
				$pdf->SetX(10);
			}
			$pdf->MultiCell(190,5,'Observaciones: '.utf8_decode($observa),1,'J');
			$pdf->SetFont('Arial','',9);
			//$pdf->SetXY(30, 138);
			
			$pdf->Ln(25);
			$pdf->SetX(10);
			$pdf->Cell(90,0,'',1,0,'C');
			$pdf->SetX(105);
			$pdf->Cell(90,0,'',1,1,'C');

			$pdf->SetX(10);
			$pdf->Cell(95,4,$nomprof,0,0,'C');
			$pdf->Cell(95,4,$director,0,1,'C');

			$pdf->SetX(10);
			$pdf->Cell(95,4,'Maestra',0,0,'C');
			$pdf->Cell(95,4,'Director(a)',0,1,'C');


			/*$pdf->Ln(17);
			
			
			$pdf->Cell(95,5,'______________________________________',0,0,'C');
			$pdf->Cell(95,5,'______________________________________',0,1,'C');
			$pdf->Cell(95,4,$nomprof,0,0,'C');
			$pdf->Cell(95,4,$director,0,1,'C');
			$pdf->Cell(95,4,'Docente',0,0,'C');
			$pdf->Cell(95,4,'Director(a)',0,1,'C');*/
		}
		$pdf->Output();
	}

	
	
	mysqli_free_result($result);
	mysqli_free_result($resultado);
	mysqli_free_result($resultado2);
	mysqli_free_result($resultado3);
	
	mysqli_close($link);
}
else
{
	header("location:login.php"); 			
} 
?>
