<?php
session_start();
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
if(!isset($_SESSION['usuario']) && !isset($_SESSION['password']))
{ ?>
  <script type='text/javascript'>                                
    header("location:index.php#features");
  </script><?php
} 
//error_reporting(0);
include_once ('conexion.php');
include_once ('inicia.php');
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$link = Conectarse();
if($_SESSION['morosida']>0)
{ 
  header("location:index.php?moro"); 
}
if($_SESSION['pagado']==0)
{ 
  header("location:index.php?sinpago"); 
}
$usuario = $_SESSION['usuario'];

$alumno_query=mysqli_query($link,"SELECT ruta FROM alumcer WHERE cedula='$usuario' "); 
$row=mysqli_fetch_array($alumno_query);
$rutas=$row['ruta'];
if (empty($rutas) || $rutas==NULL || $rutas=='') {
	include_once("header.php");?>
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
      <h2> Es necesario que agregue una fotografia del estudiante para ver su boletin de calificaciones </h2>
    </div>
    <div class="col-md-12 text-center" style="margin-top: 1%;">
      <button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"><i class="ri-logout-box-line"></i> CERRAR VENTANA</button>
    </div><?php
    exit;
    die();
}

$morosida=$_SESSION['morosida'];
$pagado=$_SESSION['pagado'];
$periodoAlum=$_GET['peri'];  //$_SESSION['periodoAlum']; 
$peridom=$_GET['lapsom'];
$idAlu=$_SESSION['idAlum'];
$puede_query=mysqli_query($link,"SELECT bole1,bole2,bole3 FROM matri".$periodoAlum." WHERE idAlumno='$idAlu' ");
while($row=mysqli_fetch_array($puede_query))
{
	$bole1=$row['bole1'];
	$bole2=$row['bole2'];
	$bole3=$row['bole3'];
}
if (${'bole'.$peridom}==2) {?>
	<script type="text/javascript"> 
		if (screen.width<768) { window.location='indexm.php?depa'; }else{
			window.location='index.php?depa';
		}
	</script><?php
}
$periodo_query=mysqli_query($link,"SELECT nombre_periodo, directorPeriodo, jefeControlEstud FROM periodos where tablaPeriodo='$periodoAlum' "); 
while($row=mysqli_fetch_array($periodo_query))
{
    $periodoActivo=$row['nombre_periodo'];
    $director=$row['directorPeriodo'];
    $ctrlEstudio=$row['jefeControlEstud'];
}
$lapso_query=mysqli_query($link,"SELECT lapso FROM preinscripcion WHERE id=2 ");
while($row=mysqli_fetch_array($lapso_query))
{
	$lapsoActivo=$row['lapso'];			
}

if($morosida>0)
{ 
	header("location:index.php?moro"); 
}
if($pagado==0)
{
	header("location:index.php?sinpago"); 
}
if($peridom=='1')
{
	$fecha_query=mysqli_query($link,"SELECT publicarBoleta FROM preinscripcion WHERE id=3 ");
	$nomLap = 'Primero';	
}
if($peridom=='2')
{
	$fecha_query=mysqli_query($link,"SELECT publicarBoleta FROM preinscripcion WHERE id=4 ");
	$nomLap = 'Segundo';	
}
if($peridom=='3')
{
	$fecha_query=mysqli_query($link,"SELECT publicarBoleta FROM preinscripcion WHERE id=5 ");
	$nomLap = 'Tercero';	
}
while($row=mysqli_fetch_array($fecha_query))
{
	$fecpublica=$row['publicarBoleta'];			
}
if( $fechahoy < $fecpublica && $periodoActivo==ANOESCM && $usuario!='25662937')
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
		<div align="center" class="col-md-8 offset-md-2 error">DISCULPE!<br>
			<h2> Boletin definitivo para el primer momento disponible a partir del dia : <?= date("d-m-Y", strtotime($fecpublica)); ?> </h2>
		</div><?php
	}
	if($peridom=='2')
	{ ?>
		<div align="center" class="col-md-8 offset-md-2 error">DISCULPE!<br>
			<h2> Boletin definitivo para el segundo momento disponible a partir del dia : <?= date("d-m-Y", strtotime($fecpublica)); ?> </h2>
		</div><?php
	}
	if($peridom=='3')
	{ ?>
		<div align="center" class="col-md-8 offset-md-2 error">DISCULPE!<br>
			<h2> Boletin definitivo para el tercer momento disponible a partir del dia : <?= date("d-m-Y", strtotime($fecpublica)); ?> </h2>
		</div><?php 
	}?>
	<div class="col-md-12 text-center" style="margin-top: 1%;">
			<button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"><i class="ri-logout-box-line"></i> CERRAR VENTANA</button>
	</div><?php
	exit;
}
if ($peridom==1) {$nomLap = 'Primero';}
if ($peridom==2) {$nomLap = 'Segundo';}
if ($peridom==3) {$nomLap = 'Tercero';}
$alumnos_query=mysqli_query($link,"SELECT A.*, B.idAlum, B.nacion, B.cedula, B.apellido,B.nombre,B.ruta, B.FechaNac, B.ced_rep, B.parentesco, B.correo as mailAlum, C.nombreGrado, C.especialidad, C.codigoEsp, C.tipo1, C.tipo2, C.tipo3, C.tipo4, C.tipo5, C.tipo6, C.tipo7, C.tipo8, C.tipo9, C.tipo10, C.tipo11, C.tipo12, D.nombre as nombreSeccion FROM matri".$periodoAlum." A, alumcer B, grado".$periodoAlum." C, secciones D WHERE cedula='$usuario' and A.idAlumno=B.idAlum and A.grado=C.grado and A.idSeccion=D.id "); 
require('fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{}
	function Footer()
	{
		/**$this->SetY(-30);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,4,'Boletin '.$this->PageNo().'/{nb}',0,1,'C');*/
	}
}
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetFillColor(232,232,232);
$van=1;
$pdf->SetFont('Arial','',8);
$pdf->Addpage();
$pdf->Image('assets/img/logo.png',10,9,28,30);

$pdf->SetFont('Arial','',15);
$pdf->Cell(80);
$pdf->Cell(30,6,utf8_decode(NKXS),0,1,'C');
$pdf->Cell(80);
$pdf->Cell(30,6,utf8_decode(EKKS),0,1,'C');
$pdf->Cell(80);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');
$pdf->Cell(80);
$pdf->Cell(30,5,'Rif.: '.RIFCOLM.' - Telefono '.TELEMPM,0,1,'C');
$pdf->Ln(2);
$pdf->Cell(80);
$pdf->SetFont('Arial','',20);
$pdf->Cell(30,6,'Boletin de Calificaciones',0,1,'C');
$pdf->Cell(80);
$pdf->SetFont('Arial','',12);
$pdf->Cell(30,6,'Periodo Escolar '.$periodoActivo,0,1,'C');
$pdf->SetFont('Arial','B',9);
$pdf->SetX(10);
$pdf->Cell(30,4,'Cedula',1,0,'C',1);
$pdf->SetX(43);
$pdf->Cell(114,4,'Estudiante',1,0,'C',1);
$pdf->SetX(160);
$pdf->Cell(40,4,'Momento',1,0,'C',1);
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
while($row=mysqli_fetch_array($alumnos_query))
{
	$foto  = 'fotoalu/'.$row['ruta'];
	$pdf->Image($foto,165,8,30,33);
	$ced_alu=$row['cedula'];
	/*$observa_query=mysqli_query($link,"SELECT B.observa FROM observa_guia_alum A,observa_guia B WHERE A.cedula='$ced_alu' and A.lapso='$peridom' and A.id_observa=B.id_observa ");
	if(mysqli_num_rows($observa_query) > 0)
	{
		$row2=mysqli_fetch_array($observa_query);
		$observa_guia=$row2['observa'];
	}else {*/$observa_guia='';//}
	$pdf->Cell(30,6, $row['nacion'].'-'.$row['cedula'],1,0,'C');
	$pdf->SetX(43);
	$pdf->Cell(114,6, utf8_decode($row['apellido']).' '.utf8_decode($row['nombre']),1,0,'C');
	$pdf->SetX(160);
	$pdf->Cell(40,6,$nomLap,1,1,'C');
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(10);
	$pdf->Cell(79,4,'Especialidad',1,0,'C',1);
	$pdf->SetX(92);
	$pdf->Cell(65,4,'Codigo',1,0,'C',1);
	$pdf->SetX(160);
	$pdf->Cell(40,4, utf8_decode('Año / Sección'),1,1,'C',1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(79,6, ($row['especialidad']),1,0,'C');
	$pdf->SetX(92);
	$pdf->Cell(65,6, utf8_decode($row['codigoEsp']),1,0,'C');
	$pdf->SetX(160);
	$pdf->Cell(40,6, utf8_decode($row['nombreGrado'].' "'.$row['nombreSeccion'].'"'),1,1,'C');
	$ced_rep=$row['ced_rep'];
	$id_paren=$row['parentesco'];
	$mailAlum=$row['mailAlum'];
	$grado=$row['grado'];
	for ($i=1; $i < 13; $i++) 
	{ 
		${'tip'.$i} = $row['tipo'.$i]; 
	}
	$grado_query=mysqli_query($link,"SELECT nombreGrado, mate1, mate2, mate3, mate4, mate5, mate6, mate7, mate8, mate9, mate10, mate11, mate12, mate13 FROM grado".$periodoAlum." where grado='$grado' "); 
	$nromat=0;
	while($rowG=mysqli_fetch_array($grado_query))
	{
		for ($i=1; $i <=13 ; $i++) 
		{ 
			${'materia'.$i}=$rowG['mate'.$i];
			$nromat = (${'materia'.$i}!='') ? $nromat+1 : $nromat ;
		}
	}
	$result = mysqli_query($link,"SELECT * FROM parentescos WHERE idparen = $id_paren");	
	$par_rep='';
	while ($rowP = mysqli_fetch_array($result))
	{$par_rep = $rowP['nomparen'];}

	$resultado3 = mysqli_query($link,"SELECT cedula, representante, correo FROM represe WHERE cedula = $ced_rep");
	$nomRep='';	$cedRep=''; $correoRepre='';
	while ($rowR = mysqli_fetch_array($resultado3))
	{
		// REPRESENTANTE
		$cedRep=$rowR['cedula'];
		$nomRep=utf8_decode($rowR['representante']);
		$correoRepre=$rowR['correo'];
	}
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Cedula',1,0,'C',1);
	$pdf->SetX(43);
	$pdf->Cell(114,4,'Representante',1,0,'C',1);
	$pdf->SetX(160);
	$pdf->Cell(40,4, 'Parentesco',1,1,'C',1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,6, $cedRep,1,0,'C');
	$pdf->SetX(43);
	$pdf->Cell(114,6, $nomRep,1,0,'C');
	$pdf->SetX(160);
	$pdf->Cell(40,6, $par_rep,1,1,'C');
	$pdf->Ln(.8);
	$pdf->SetFont('Arial','B',9);
	
	$pdf->SetX(91);
	$pdf->Cell(25,4,'1er.Momento',1,0,'C',1);
	$pdf->SetX(119);
	$pdf->Cell(25,4, '2do.Momento',1,0,'C',1);
	$pdf->SetX(147);
	$pdf->Cell(25,4, '3er.Momento',1,0,'C',1);
	$pdf->SetX(175);
	$pdf->Cell(25,4, 'Definitiva',1,1,'C',1);
	
	$pdf->SetX(10);
	$pdf->Cell(79,4,'Asignaturas',1,0,'C',1);
	$pdf->SetX(91);	
	$pdf->Cell(25,4,'Nota / Inas.',1,0,'C',1);
	$pdf->SetX(119);
	$pdf->Cell(25,4, 'Nota / Inas.',1,0,'C',1);
	$pdf->SetX(147);
	$pdf->Cell(25,4, 'Nota / Inas.',1,0,'C',1);
	$pdf->SetX(175);
	$pdf->Cell(25,4, 'Nota / Inas.',1,1,'C',1);
	$pdf->SetFont('Arial','',10);
	
	$prom=0;
	$curMat=0;
	for ($i=1; $i <=$nromat ; $i++) 
	{ 
		${'mat'.$i}=($row['escola']==2 && $row['mat'.$i]=='') ? 'N' : ' ' ;
		$pdf->Cell(79,6,${'materia'.$i},1,0,'L');
		${'not1'.$i}=(${'mat'.$i}=='N') ? 'N' : $row['nota1'.$i];
		if(${'not1'.$i}!='N' && ${'not1'.$i}<10)
		{ ${'not1'.$i}='0'.${'not1'.$i}; }
		${'not2'.$i}=(${'mat'.$i}=='N') ? 'N' : $row['nota2'.$i];
		if(${'not2'.$i}!='N' && ${'not2'.$i}<10)
		{ ${'not2'.$i}='0'.${'not2'.$i}; }
		${'not3'.$i}=(${'mat'.$i}=='N') ? 'N' : $row['nota3'.$i];
		if(${'not3'.$i}!='N' && ${'not3'.$i}<10)
		{ ${'not3'.$i}='0'.${'not3'.$i}; }
		${'inas1'.$i}=(${'mat'.$i}=='N') ? 'N' : $row['inas1'.$i];
		${'inas2'.$i}=(${'mat'.$i}=='N') ? 'N' : $row['inas2'.$i];
		${'inas3'.$i}=(${'mat'.$i}=='N') ? 'N' : $row['inas3'.$i];
		//$defi=(${'mat'.$i}=='N') ? 'N' : ($row['not1'.$i]+$row['not2'.$i]+$row['not3'.$i])/3;
		if(${'tip'.$i}=='T')
		{
			//NOTAS1
			if(${'not1'.$i}>0 && ${'not1'.$i}<=9){${'not1'.$i}=' E';}
			if(${'not1'.$i}>9 && ${'not1'.$i}<=12){${'not1'.$i}=' D';}
			if(${'not1'.$i}>12 && ${'not1'.$i}<=15){${'not1'.$i}=' C';}
			if(${'not1'.$i}>15 && ${'not1'.$i}<=18){${'not1'.$i}=' B';}
			if(${'not1'.$i}>18 && ${'not1'.$i}<=20){${'not1'.$i}=' A';}
			//NOTAS2
			if(${'not2'.$i}>0 && ${'not2'.$i}<=9){${'not2'.$i}=' E';}
			if(${'not2'.$i}>9 && ${'not2'.$i}<=12){${'not2'.$i}=' D';}
			if(${'not2'.$i}>12 && ${'not2'.$i}<=15){${'not2'.$i}=' C';}
			if(${'not2'.$i}>15 && ${'not2'.$i}<=18){${'not2'.$i}=' B';}
			if(${'not2'.$i}>18 && ${'not2'.$i}<=20){${'not2'.$i}=' A';}
			//NOTAS3
			if(${'not3'.$i}>0 && ${'not3'.$i}<=9){${'not3'.$i}=' E';}
			if(${'not3'.$i}>9 && ${'not3'.$i}<=12){${'not3'.$i}=' D';}
			if(${'not3'.$i}>12 && ${'not3'.$i}<=15){${'not3'.$i}=' C';}
			if(${'not3'.$i}>15 && ${'not3'.$i}<=18){${'not3'.$i}=' B';}
			if(${'not3'.$i}>18 && ${'not3'.$i}<=20){${'not3'.$i}=' A';}
			
		}
		$pdf->SetX(91);	
		if($peridom>0)
		{
			$pdf->Cell(13,6,${'not1'.$i},1,0,'C');
			$pdf->Cell(12,6,${'inas1'.$i},1,0,'C');
			$defi=(${'mat'.$i}=='N') ? 'N' : ($row['nota1'.$i])/$peridom;
			$tIna=(${'mat'.$i}=='N') ? 'N' : ($row['inas1'.$i]);
			if(${'mat'.$i}=='X' || ${'mat'.$i}==' ')
			{
				$curMat=$curMat+1;
			}
			
		}else
		{
			$pdf->Cell(13,6,'**',1,0,'C');
			$pdf->Cell(12,6,'**',1,0,'C');
		}
		$pdf->SetX(119);
		if($peridom>1)
		{
			$pdf->Cell(13,6,${'not2'.$i},1,0,'C');
			$pdf->Cell(12,6,${'inas2'.$i},1,0,'C');
			$defi=(${'mat'.$i}=='N') ? 'N' : ($row['nota1'.$i]+$row['nota2'.$i])/$peridom;
			$tIna=(${'mat'.$i}=='N') ? 'N' : ($row['inas1'.$i]+$row['inas2'.$i]);
			if(${'mat'.$i}=='X' || ${'mat'.$i}==' ')
			{
				$curMat=$curMat+1;
			}
		}else
		{
			$pdf->Cell(13,6,'**',1,0,'C');
			$pdf->Cell(12,6,'**',1,0,'C');	
		}
		$pdf->SetX(147);
		if($peridom==3)
		{
			$pdf->Cell(13,6,${'not3'.$i},1,0,'C');
			$pdf->Cell(12,6,${'inas3'.$i},1,0,'C');
			$defi=(${'mat'.$i}=='N') ? 'N' : ($row['nota1'.$i]+$row['nota2'.$i]+$row['nota3'.$i])/$peridom;
			$tIna=(${'mat'.$i}=='N') ? 'N' : ($row['inas1'.$i]+$row['inas2'.$i]+$row['inas3'.$i]);
			if(${'mat'.$i}=='X' || ${'mat'.$i}==' ')
			{
				$curMat=$curMat+1;
			}
		}else
		{
			$pdf->Cell(13,6,'**',1,0,'C');
			$pdf->Cell(12,6,'**',1,0,'C');	
		}
		$pdf->SetX(175);
		//$defi=floatval($defi);
		$defi=round($defi);
		$defi = ($defi<9.5) ? '0'.$defi : $defi ;
		$prom=$prom+$defi;
		if(${'tip'.$i}=='T')
		{
		 	//DEFINITIVA
			if($defi>0 && $defi<=9){$defi=' E';}
			if($defi>9 && $defi<=12){$defi=' D';}
			if($defi>12 && $defi<=15){$defi=' C';}
			if($defi>15 && $defi<=18){$defi=' B';}
			if($defi>18 && $defi<=20){$defi=' A';}
			$pdf->Cell(13,6,$defi,1,0,'C');
		}else
		{ $pdf->Cell(13,6,$defi,1,0,'C'); }
		$pdf->Cell(12,6,$tIna,1,1,'C');
	}

	if($nromat<13)
	{
		for ($i=$nromat; $i <13 ; $i++) 
		{
			$pdf->Cell(79,6,'*****',1,0,'C'); 
			$pdf->SetX(91);	
			$pdf->Cell(13,6,'**',1,0,'C');
			$pdf->Cell(12,6,'**',1,0,'C');
			$pdf->SetX(119);
			$pdf->Cell(13,6,'**',1,0,'C');
			$pdf->Cell(12,6,'**',1,0,'C');
			$pdf->SetX(147);
			$pdf->Cell(13,6,'**',1,0,'C');
			$pdf->Cell(12,6,'**',1,0,'C');
			$pdf->SetX(175);
			$pdf->Cell(13,6,'**',1,0,'C');
			$pdf->Cell(12,6,'**',1,1,'C');
		}
	}
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(79,4,'Asignaturas de Materia Pendiente',1,0,'C',1); 
	$pdf->SetX(91);	
	$pdf->Cell(25,4,'1er. Momento',1,0,'C',1);
	$pdf->SetX(119);
	$pdf->Cell(25,4,'2do. Momento',1,0,'C',1);
	$pdf->SetX(147);
	$pdf->Cell(25,4,'3er. Momento',1,0,'C',1);
	$pdf->SetX(175);
	$pdf->Cell(25,4,'4to. Momento',1,1,'C',1);
	$pdf->SetFont('Arial','',10);
	$matPen1=$row['matPendiente1'];
	$matPen2=$row['matPendiente2'];
	if($matPen1>0)
	{
		$mp1_query=mysqli_query($link,"SELECT nombremate FROM materiass".$periodoAlum." where codigo='$matPen1' "); 
		while ($rowMp1 = mysqli_fetch_array($mp1_query))
		{
			$nomMp1=$rowMp1['nombremate'];
		}
		mysqli_free_result($mp1_query);

		$pdf->Cell(79,6,$nomMp1,1,0,'C'); 	
		$pdf->Cell(2,6,'',0,0,'L'); 	
		$pdf->Cell(25,6,$row['notaMp11'].' Ptos.' ,1,0,'C');
		$pdf->Cell(3,6,'',0,0,'L'); 
		if($row['notaMp11']<10)
		{$pdf->Cell(25,6,$row['notaMp12'].' Ptos.' ,1,0,'C');}else{$pdf->Cell(25,6,'**' ,1,0,'C');}
		$pdf->Cell(3,6,'',0,0,'L'); 
		if($row['notaMp11']<10 && $row['notaMp12']<10)
		{$pdf->Cell(25,6,$row['notaMp13'].' Ptos.' ,1,0,'C');}else{$pdf->Cell(25,6,'**' ,1,0,'C');}
		$pdf->Cell(3,6,'',0,0,'L'); 
		if($row['notaMp11']<10 && $row['notaMp12']<10 && $row['notaMp13']<10)
		{$pdf->Cell(25,6,$row['notaMp14'].' Ptos.' ,1,1,'C');}else{$pdf->Cell(25,6,'**' ,1,1,'C');}
		if($matPen2>0)
		{
			$mp2_query=mysqli_query($link,"SELECT nombremate FROM materiass".$periodoAlum." where codigo='$matPen2' "); 
			while ($rowMp2 = mysqli_fetch_array($mp2_query))
			{
				$nomMp2=$rowMp2['nombremate'];
			}
			mysqli_free_result($mp2_query);
			$pdf->Cell(79,6,$nomMp2,1,0,'C'); 	
			$pdf->Cell(2,6,'',0,0,'L'); 	
			$pdf->Cell(25,6,$row['notaMp21'].' Ptos.' ,1,0,'C');
			$pdf->Cell(3,6,'',0,0,'L'); 
			if($row['notaMp21']<10)
			{$pdf->Cell(25,6,$row['notaMp22'].' Ptos.' ,1,0,'C');}else{$pdf->Cell(25,6,'**' ,1,0,'C');}
			$pdf->Cell(3,6,'',0,0,'L'); 
			if($row['notaMp21']<10 && $row['notaMp22']<10)
			{$pdf->Cell(25,6,$row['notaMp23'].' Ptos.' ,1,0,'C');}else{$pdf->Cell(25,6,'**' ,1,0,'C');}
			$pdf->Cell(3,6,'',0,0,'L'); 
			if($row['notaMp21']<10 && $row['notaMp22']<10 && $row['notaMp23']<10)
			{$pdf->Cell(25,6,$row['notaMp24'].' Ptos.' ,1,1,'C');}else{$pdf->Cell(25,6,'**' ,1,1,'C');}
		} else
		{
			$pdf->Cell(79,6,'*****',1,0,'C'); 	
			$pdf->Cell(2,6,'',0,0,'L'); 	
			$pdf->Cell(25,6,'**' ,1,0,'C');
			$pdf->Cell(3,6,'',0,0,'L'); 
			$pdf->Cell(25,6,'**' ,1,0,'C');
			$pdf->Cell(3,6,'',0,0,'L'); 
			$pdf->Cell(25,6,'**' ,1,0,'C');
			$pdf->Cell(3,6,'',0,0,'L'); 
			$pdf->Cell(25,6,'**' ,1,1,'C');
		}
	}else
	{
		$pdf->Cell(79,6,'*****',1,0,'C'); 	
		$pdf->Cell(2,6,'',0,0,'L'); 	
		$pdf->Cell(25,6,'**' ,1,0,'C');
		$pdf->Cell(3,6,'',0,0,'L'); 
		$pdf->Cell(25,6,'**' ,1,0,'C');
		$pdf->Cell(3,6,'',0,0,'L'); 
		$pdf->Cell(25,6,'**' ,1,0,'C');
		$pdf->Cell(3,6,'',0,0,'L'); 
		$pdf->Cell(25,6,'**' ,1,1,'C');
		$pdf->Cell(79,6,'*****',1,0,'C'); 	
		$pdf->Cell(2,6,'',0,0,'L'); 	
		$pdf->Cell(25,6,'**' ,1,0,'C');
		$pdf->Cell(3,6,'',0,0,'L'); 
		$pdf->Cell(25,6,'**' ,1,0,'C');
		$pdf->Cell(3,6,'',0,0,'L'); 
		$pdf->Cell(25,6,'**' ,1,0,'C');
		$pdf->Cell(3,6,'',0,0,'L'); 
		$pdf->Cell(25,6,'**' ,1,1,'C');
	}
	$pdf->Ln(.9);	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(10);
	$pdf->Cell(190,4,utf8_decode('Promedios'),1,1,'C',1);
	$pdf->SetFont('Arial','',10);
	$pdf->SetX(10);
	/*if($periodoActivo==ANOESCM and $peridom<$lapsoActivo)
	{
		$proemdio='***';
		$promeAlum='***';
		$promCurso='***';
		$posiCurso='**';
		$posiAno='**';
		$posiPlan='**';
	}else
	{*/
		$promedio=$prom/$curMat;	
		$promeAlum=number_format($row['prom_alum'],3,",",".");
		$promCurso=number_format($row['prom_curso'],3,",",".");
		$posiCurso=$row['posi_curso'];
		$posiAno=$row['posi_ano'];
		$posiPlan=$row['posi_plan'];
	//}
	
	
	$pdf->Cell(94,5,'Promedio del Estudiante: '.$promeAlum,1,0,'C');
	$pdf->Cell(2,5,'',0,0,'C');
	$pdf->Cell(94,5,'Promedio del Curso..: '.$promCurso,1,1,'C');
	$pdf->Cell(62,5,utf8_decode('Posición en Sección: ').$posiCurso,1,0,'C');
	$pdf->Cell(2,5,'',0,0,'C');
	$pdf->Cell(62,5,utf8_decode('Posición en Año: ').$posiAno,1,0,'C');
	$pdf->Cell(2,5,'',0,0,'C');
	$pdf->Cell(62,5,utf8_decode('Posición en Plantel: ').$posiPlan,1,1,'C');
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(10);
	$pdf->Ln(1);
	$pdf->Cell(190,4,('Recomendaciones u Observaciones'),1,1,'C',1);
	$pdf->Ln(1);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(190,6,($observa_guia),0,1,'L');
	$pdf->SetXY(10,204);
	$pdf->SetX(10);
	$pdf->Cell(190,6,'',0,1,'L');
	$pdf->SetX(10);
	$pdf->Cell(190,6,'',0,1,'L');
	$pdf->Ln(30);
	$pdf->SetX(10);
	$pdf->Cell(90,4,'_____________________________________',0,0,'C');
	$pdf->SetX(110);
	$pdf->Cell(90,4,'_____________________________________',0,1,'C');
	$pdf->SetX(10);
	$pdf->Cell(90,4,$director,0,0,'C');
	$pdf->SetX(110);
	$pdf->Cell(90,4,$ctrlEstudio,0,1,'C');
	$pdf->SetX(10);
	$pdf->Cell(90,4,'Director',0,0,'C');
	$pdf->SetX(110);
	$pdf->Cell(90,4,'Control de Estudio',0,1,'C');
	$pdf->Ln(5);
	$van++;
	
}
mysqli_free_result($periodo_query);
mysqli_free_result($lapso_query);
mysqli_free_result($fecha_query);
mysqli_free_result($alumnos_query);
mysqli_free_result($grado_query);
mysqli_free_result($result);
mysqli_free_result($resultado3);

$pdf->Output('boletin.pdf','I'); 
?>