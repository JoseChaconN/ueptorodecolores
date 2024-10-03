<?php
session_start();
if(!isset($_SESSION['usuario']) && !isset($_SESSION['password']) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}


$a=1;
$link = Conectarse();
$tablaPeriodo=$_GET['peri'];
include_once("../../../inicia.php");
$usuario = $_GET['cedalum'];
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");

$resultado = mysqli_query($link,"SELECT A.*, B.*, C.estado as nomedo, D.nombreGrado, E.ciudad, F.municipio, G.correo as mail_rep, G.representante, G.lug_trabaj, G.ocupacion, G.tlf_celu, G.direccion as dire_rep, G.telefono as tlf_rep, G.ruta as foto_rep, G.fnac_repre FROM alumcer A, parentescos B, estado C, grado".$tablaPeriodo." D, ciudades E, municipios F, represe G WHERE A.cedula='$usuario' and B.idparen=A.parentesco and C.id_edo=A.estado and A.grado=D.grado and A.locali=E.id_ciudad and A.municip=F.id_municipio and A.ced_rep=G.cedula ");
while ($row = mysqli_fetch_array($resultado))
{
	$idAlum=$row['idAlum'];
    $correo = $row['correo'];
    $ced_rep = $row['ced_rep'];
    $gra_alu = $row['grado'];
    $nac_alu = $row['nacion'];
	$ced_alu = $row['cedula'];
	$ape_alu = utf8_decode($row['apellido']);
	$nom_alu = utf8_decode($row['nombre']);
	$pas_alu = $row['pasaporte'];
	$sex_alu = $row['sexo'];
	$str = $row['FechaNac'];
	$da= explode('-', $str);   
	$dia = $da[2];  
	$mes = $da[1]; 
	$anio = $da[0];  
	$diac =date("d"); 
	$mesc =date("m"); 
	$anioc =date("Y"); 
	$edadac =  $anioc-$anio; 
	if($mesc < $mes && $diac < $dia || $mesc < $mes || $diac < $dia)
	{ 
		$edad_aux = $edadac - 1; 
		$edadac = $edad_aux; 
	} 
	$edad = $edadac.utf8_decode(' años');
	$fec_alu = date("d-m-Y", strtotime($row['FechaNac']));
	$loc_alu=utf8_decode($row['ciudad']);
	$est_alu = $row['nomedo'];
	$municip = $row['municipio'];
	$pai_alu = $row['pais'];
	$tlf_alu = $row['telefono'];
	$dir_alu = utf8_decode($row['direccion']);
	$mail_alu = utf8_decode($row['correo']);
	$ced_rep = $row['ced_rep'];
	$ced_papa = $row['ced_papa'];
	$ced_mama = $row['ced_mama'];
	$par_rep = $row['nomparen'];
	$gra_alu = $row['grado'];
	$nombreGrado=utf8_decode($row['nombreGrado']);
	$fot_alu = '../../../fotoalu/'.$row['ruta']; 
	$ruta_alu=$row['ruta']; 
	$talla=$row["talla"];
	$peso=$row["peso"];
	
	
	// Datos de Emergencia
	$nom_emerg_1=$row["nom_emerg_1"];
	$pare_emerg_1=$row["pare_emerg_1"];
	$tlf_emerg_hab_1=$row["tlf_emerg_hab_1"];
	$tlf_emerg_ofi_1=$row["tlf_emerg_ofi_1"];
	$tlf_emerg_cel_1=$row["tlf_emerg_cel_1"];
	$nom_emerg_2=$row["nom_emerg_2"];
	$pare_emerg_2=$row["pare_emerg_2"];
	$tlf_emerg_hab_2=$row["tlf_emerg_hab_2"];
	$tlf_emerg_ofi_2=$row["tlf_emerg_ofi_2"];
	$tlf_emerg_cel_2=$row["tlf_emerg_cel_2"];
	//Representante
	$cedu_rep = ($row['ced_rep']);
	$nom_rep=utf8_decode($row['representante']);
	$mai_rep = $row['mail_rep'];
	$tlf_rep = $row['tlf_rep'];
	$dir_rep = utf8_decode($row['dire_rep']);
	$lug_rep = utf8_decode($row['lug_trabaj']);
	$ocu_rep = utf8_decode($row['ocupacion']);
	$tcel_rep = ($row['tlf_celu']);
	$fot_rep = '../../../fotorep/'.$row['foto_rep']; 
	$fnac_repre = $row['fnac_repre']; 
	//$religion_rep = $row['religion_rep']; 
	$ruta_rep=$row['foto_rep'];
	
}
if ($gra_alu>60) {
	$planilla='EDUCACIÓN MEDIA GENERAL';
}else{
	$planilla='EDUCACIÓN PRIMARIA';
}
if ( empty($correo) || empty($ced_rep) || empty($gra_alu) || mysqli_num_rows($resultado)==0 || empty($ruta_alu) || empty($ruta_rep) ) 
{
   	include_once ("encabezado1.php");?>
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
			margin-top: 50px;
			font-size: 25px;
			color: white; 
			font-weight: bold; 
		}
	</style><?php
	if ( empty($correo) || empty($ced_rep) || empty($gra_alu))
	{		
		echo '<div align="center" class="error">POR FAVOR UTILICE LA OPCION DATOS PERSONALES<br>';
		echo ' CARGUE Y GUARDE TODOS LOS DATOS REQUERIDOS POR EL SISTEMA<br>';
		echo ' PARA PODER IMPRIMIR SU PLANILLA DE INSCRIPCION</div><br><br>';
	}
	if(mysqli_num_rows($resultado)==0)
	{
		echo '<div align="center" class="error">POR FAVOR VERIFIQUE QUE TODOS<br>';
	   	echo ' LOS DATOS PERSONALES ESTEN COMPLETOS<br>';
	   	echo ' PARA PODER IMPRIMIR SU PLANILLA DE INSCRIPCION</div><br><br>';
	}
	if (empty($ruta_rep) ) 
	{
		echo '<div align="center" class="error">POR FAVOR VUELVA A CARGAR, <br>';
	   	echo ' UNA FOTO PARA EL REPRESENTANTE<br>';
	   	echo ' TAMAÑO MAXIMO 500 X 500<br>';
	   	echo ' Y ASI PODER IMPRIMIR SU PLANILLA DE INSCRIPCION</div>';	   	
	}
	if (empty($ruta_alu)) 
	{
		echo '<div align="center" class="error">POR FAVOR VUELVA A CARGAR, <br>';
	   	echo ' UNA FOTO PARA EL ALUMNO<br>';
	   	echo ' TAMAÑO MAXIMO 500 X 500<br>';
	   	echo ' Y ASI PODER IMPRIMIR SU PLANILLA DE INSCRIPCION</div>';	   	
	}?>
	<br><div class="col-md-12 text-center">
		<button type="button" class="fa fa-reply btn btn-warning" onClick="window.close()"> REGRESAR</button>
	</div><?php
	exit;
}
$paren1 = mysqli_query($link,"SELECT * FROM parentescos WHERE idparen = '$pare_emerg_1'");
while ($row = mysqli_fetch_array($paren1))
{
	$nom_pare1 = $row['nomparen'];
}
$paren2 = mysqli_query($link,"SELECT * FROM parentescos WHERE idparen = '$pare_emerg_2'");
$nom_pare2='';
while ($row = mysqli_fetch_array($paren2))
{
	$nom_pare2 = $row['nomparen'];
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		$this->Addpage();
		$this->SetFillColor(232,232,232);
	}
		
	function Header()
	{
		//include("inicia.php");
	}
		
	function Footer()
	{
		$this->SetY(-20);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->SetTitle('Planilla de Inscripcion');
$pdf->Image('../img/logo.png',10,10,23,25);
$pdf->Image('../img/planilla.jpg',1,40,210,245);
if($a==1)
{
	$pdf->SetFont('Times','',9);
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'),0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN'),0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('U.E.P. "').EKKS,0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('CÓDIGO: ').CKLS,0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,'RIF.:'.RIFCOLM,0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,CIUDADM,0,1,'L');
	$pdf->Ln(3);
	$pdf->Cell(125);
	$pdf->Cell(30,5,'Representante',0,0,'C');
	$pdf->Cell(5);
	$pdf->Cell(30,5,'Alumno',0,1,'C');
	$pdf->Cell(80);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetX(10);
	$pdf->Cell(190,5, utf8_decode('PLANILLA DE PRE-INSCRIPCIÓN '),0,1,'C');
	$pdf->Cell(190,5, utf8_decode($planilla).' '.$_SESSION['nombre_periodo'],0,1,'C');
	$pdf->Cell(190,5, ($nombreGrado),0,1,'C');

	$pdf->SetFont('Arial','B',12);
	$pdf->SetX(10);
	$pdf->Cell(30,4,utf8_decode('Datos del estudiante:'),0,1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Cedula',1,0,'L',1);
	$pdf->Cell(80,4,'Apellidos',1,0,'L',1);
	$pdf->Cell(80,4,'Nombres',1,1,'L',1);
	if (!empty($ruta_rep) ) 
	{
	    $pdf->Image($fot_rep,135,8,30,30);
	} else 
	{
		$pdf->Image('../../../imagenes/fotocarnet.jpg',135,8,30,30);
	}
	if (!empty($ruta_alu) ) 
	{
	    $pdf->Image($fot_alu,170,8,30,30);
	} else 
	{
		$pdf->Image('../../../imagenes/fotocarnet.jpg',170,8,30,30);
	}
}
if($a==1)
{
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(30,5, $nac_alu.'-'.$ced_alu,1,0,'L');
	$pdf->Cell(80,5, $ape_alu,1,0,'L');
	$pdf->Cell(80,5, $nom_alu,1,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,4,'Sexo',1,0,'C',1);
	$pdf->Cell(20,4,'Fecha de Nac.',1,0,'L',1);
	$pdf->Cell(20,4,'Edad',1,0,'C',1);
	$pdf->Cell(105,4, 'Lugar de Nacimiento',1,0,'L',1);
	$pdf->Cell(15,4,'Talla',1,0,'C',1);
	$pdf->Cell(15,4,'Peso',1,0,'C',1);
	$pdf->Ln();
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(15,5, $sex_alu,1,0,'C');
	$pdf->Cell(20,5, $fec_alu,1,0,'C');
	$pdf->Cell(20,5, $edad,1,0,'C');
	$pdf->Cell(105,5, $loc_alu,1,0,'L');
	$pdf->Cell(15,5, $talla,1,0,'C');
	$pdf->Cell(15,5, $peso,1,1,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(45,4,'Estado',1,0,'L',1);
	$pdf->Cell(50,4,'Municipio',1,0,'L',1);
	$pdf->Cell(45,4,'Pais',1,0,'L',1);
	$pdf->Cell(50,4,'Celular',1,0,'L',1);
	$pdf->Ln();
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(45,5, $est_alu,1,0,'L');
	$pdf->Cell(50,5,$municip,1,0,'L');
	$pdf->Cell(45,5, $pai_alu,1,0,'L');
	$pdf->Cell(50,5, $tlf_alu,1,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10 );
	$pdf->Cell(80,4, utf8_decode('Email'),1,0,'L',1);
	$pdf->Cell(110,4, utf8_decode('Dirección'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(80,5, $mail_alu,1,0,'L');
	$pdf->Cell(110,5, $dir_alu,1,1,'L');
}
$madre_query = mysqli_query($link,"SELECT * FROM madres WHERE ced_mama = '$ced_mama'");
while ($row = mysqli_fetch_array($madre_query))
{
	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Datos de la Madre'),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,utf8_decode('Cedula'),1,0,'L',1);
	$pdf->Cell(130,4,'Nombres y Apellidos',1,0,'L',1);
	$pdf->Cell(30,4,'Celular',1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(30,5, $row['ced_mama'],1,0,'L');
	$pdf->Cell(130,5, utf8_decode($row['nom_ape_mama']),1,0,'L');
	$pdf->Cell(30,5, $row['tlf_cel_mama'],1,1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(75,4,'Lugar de Nacimiento',1,0,'L',1);
	$pdf->Cell(25,4,'Fecha de Nac.',1,0,'L',1);
	$pdf->Cell(20,4,'Edad',1,0,'C',1);
	$pdf->Cell(35,4,utf8_decode('Teléfono Habitación'),1,0,'L',1);
	$pdf->Cell(35,4,utf8_decode('Teléfono Oficina'),1,1,'L',1);
	$str = $row['fnac_mama'];
	$da= explode('-', $str);   
	$dia = $da[2];  
	$mes = $da[1]; 
	$anio = $da[0];  
	$diac =date("d"); 
	$mesc =date("m"); 
	$anioc =date("Y"); 
	$edadac =  $anioc-$anio; 
	if($mesc < $mes && $diac < $dia || $mesc < $mes || $diac < $dia)
	{ 
		$edad_aux = $edadac - 1; 
		$edadac = $edad_aux; 
	} 
	$edad = $edadac.utf8_decode(' años');
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(75,4,$row['lugar_nac_mama'],1,0,'L');
	$pdf->Cell(25,4,date("d-m-Y", strtotime($row['fnac_mama'])),1,0,'C');
	$pdf->Cell(20,4,$edad,1,0,'C');
	$pdf->Cell(35,4,$row['tlf_hab_mama'],1,0,'L');
	$pdf->Cell(35,4,$row['tlf_ofi_mama'],1,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(60,4,utf8_decode('Profesión'),1,0,'L',1);
	$pdf->Cell(130,4,utf8_decode('Lugar de Trabajo'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(60,5, utf8_decode($row['ocupa_mama']),1,0,'L');
	$pdf->Cell(130,5, utf8_decode($row['lug_trab_mama']),1,1,'L');
	$pdf->SetFont('Arial','B',8);
	$estudios=$row['estudio_mama'];
	if ($estudios==1) {$est_mama='Pimarios';}
	if ($estudios==2) {$est_mama='Secundarios';}
	if ($estudios==3) {$est_mama='Tercearios o Universitarios';}

	$pdf->SetX(10);
	$pdf->Cell(140,4,utf8_decode('Dirección de Habitación'),1,0,'L',1);
	$pdf->Cell(50,4,utf8_decode('Estudios'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(140,5, utf8_decode($row['dire_mama']),1,0,'L');
	$pdf->Cell(50,5, utf8_decode($est_mama),1,1,'L');
}
$padre_query = mysqli_query($link,"SELECT * FROM padres WHERE ced_papa = '$ced_papa'");
while ($row = mysqli_fetch_array($padre_query))
{
	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Datos del Padre'),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Cedula',1,0,'L',1);
	$pdf->Cell(130,4,'Nombres y Apellidos',1,0,'L',1);
	$pdf->Cell(30,4,'Celular',1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(30,5, $row['ced_papa'],1,0,'L');
	$pdf->Cell(130,5, utf8_decode($row['nom_ape_papa']),1,0,'L');
	$pdf->Cell(30,5, $row['tlf_cel_papa'],1,1,'L');

	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(75,4,'Lugar de Nacimiento',1,0,'L',1);
	$pdf->Cell(25,4,'Fecha de Nac.',1,0,'L',1);
	$pdf->Cell(20,4,'Edad',1,0,'C',1);
	$pdf->Cell(35,4,utf8_decode('Teléfono Habitación'),1,0,'L',1);
	$pdf->Cell(35,4,utf8_decode('Teléfono Oficina'),1,1,'L',1);
	$str = $row['fnac_papa'];
	$da= explode('-', $str);   
	$dia = $da[2];  
	$mes = $da[1]; 
	$anio = $da[0];  
	$diac =date("d"); 
	$mesc =date("m"); 
	$anioc =date("Y"); 
	$edadac =  $anioc-$anio; 
	if($mesc < $mes && $diac < $dia || $mesc < $mes || $diac < $dia)
	{ 
		$edad_aux = $edadac - 1; 
		$edadac = $edad_aux; 
	} 
	$edad = $edadac.utf8_decode(' años');
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(75,4,$row['lugar_nac_papa'],1,0,'L');
	$pdf->Cell(25,4,date("d-m-Y", strtotime($row['fnac_papa'])),1,0,'C');
	$pdf->Cell(20,4,$edad,1,0,'C');
	$pdf->Cell(35,4,$row['tlf_hab_papa'],1,0,'L');
	$pdf->Cell(35,4,$row['tlf_ofi_papa'],1,1,'L');

	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(60,4,utf8_decode('Profesión'),1,0,'L',1);
	$pdf->Cell(130,4,utf8_decode('Lugar de Trabajo'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(60,5, utf8_decode($row['ocupa_papa']),1,0,'L');
	$pdf->Cell(130,5, utf8_decode($row['lug_trab_papa']),1,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$estudios=$row['estudio_papa'];
	if ($estudios==1) {$est_papa='Pimarios';}
	if ($estudios==2) {$est_papa='Secundarios';}
	if ($estudios==3) {$est_papa='Tercearios o Universitarios';}

	$pdf->SetX(10);
	$pdf->Cell(140,4,utf8_decode('Dirección de Habitación'),1,0,'L',1);
	$pdf->Cell(50,4,utf8_decode('Estudios'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(140,5, utf8_decode($row['dire_papa']),1,0,'L');
	$pdf->Cell(50,5, utf8_decode($est_papa),1,1,'L');
}
$medica_query = mysqli_query($link,"SELECT * FROM ficha_medica WHERE idAlum = '$idAlum'");
while ($row = mysqli_fetch_array($medica_query))
{
	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Control de Esfinteres'),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(64,4,'Edad en que controlo',1,0,'L',1);
	$pdf->Cell(63,4,utf8_decode('Va al baño solo?'),1,0,'L',1);
	$pdf->Cell(63,4,utf8_decode('Por las noches moja la cama?'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$ducha = ($row['ducha_solo']==1) ? 'SI' : 'NO' ;
	$moja = ($row['moja_cama']==1) ? 'SI' : 'NO' ;
	$pdf->Cell(64,5, utf8_decode($row['edad_efinteres']),1,0,'C');
	$pdf->Cell(63,5, $ducha,1,0,'C');
	$pdf->Cell(63,5, $moja,1,1,'C');

	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Salud'),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(95,4,'Alergico a:',1,0,'L',1);
	$pdf->Cell(95,4,utf8_decode('Dificultad motora'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(95,5, utf8_decode($row['alergico']),1,0,'L');
	$pdf->Cell(95,5, utf8_decode($row['defic_motora']),1,1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,utf8_decode('Realizaron exámenes'),1,0,'L',1);
	$pdf->Cell(160,4,utf8_decode('Sufrió algún accidente, convulsiones, enfermedades'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$exam_moto = ($row['examen_motora']==1) ? 'SI' : 'NO' ;
	$pdf->Cell(30,5, $exam_moto,1,0,'C');
	$pdf->Cell(160,5, utf8_decode($row['accidentes']),1,1,'L');

	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Enfermedades que padeció y Lateridad '),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(27,4,'Bronquitis',1,0,'L',1);
	$pdf->Cell(27,4,'Hepatitis',1,0,'L',1);
	$pdf->Cell(27,4,'Paperas',1,0,'L',1);
	$pdf->Cell(27,4,'Asma',1,0,'L',1);
	$pdf->Cell(27,4,'Varicela',1,0,'L',1);
	$pdf->Cell(27,4,'Resfrio',1,0,'L',1);
	$pdf->Cell(28,4,'Lateridad',1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$bronquiti = ($row['bronquiti']==1) ? 'SI' : 'NO' ;
	$hepatitis = ($row['hepatitis']==1) ? 'SI' : 'NO' ;
	$paperas = ($row['paperas']==1) ? 'SI' : 'NO' ;
	$asma = ($row['asma']==1) ? 'SI' : 'NO' ;
	$varicela = ($row['varicela']==1) ? 'SI' : 'NO' ;
	$resfrio = ($row['resfrio']==1) ? 'SI' : 'NO' ;
	$lateridad=$row['lateridad'];
	if ($lateridad==1) { $lateri='Derecho';}
	if ($lateridad==2) { $lateri='Izquierdo';}
	if ($lateridad==3) { $lateri='Ambidiestro';}
	$pdf->Cell(27,5, $bronquiti,1,0,'C');
	$pdf->Cell(27,5, $hepatitis,1,0,'C');
	$pdf->Cell(27,5, $paperas,1,0,'C');
	$pdf->Cell(27,5, $asma,1,0,'C');
	$pdf->Cell(27,5, $varicela,1,0,'C');
	$pdf->Cell(27,5, $resfrio,1,0,'C');
	$pdf->Cell(28,5, $lateri,1,1,'C');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(31,4,utf8_decode('Está medicado?'),1,0,'L',1);
	$pdf->Cell(31,4,'Ve bien?',1,0,'L',1);
	$pdf->Cell(32,4,'Utiliza anteojos?',1,0,'L',1);
	$pdf->Cell(32,4,'Oye bien?',1,0,'L',1);
	$pdf->Cell(32,4,'Utiliza audifonos',1,0,'L',1);
	$pdf->Cell(32,4,utf8_decode('Grupo sanguíneo'),1,1,'L',1);
	$es_medicado = ($row['es_medicado']==1) ? 'SI' : 'NO' ;
	$ve_bien = ($row['ve_bien']==1) ? 'SI' : 'NO' ;
	$lentes = ($row['lentes']==1) ? 'SI' : 'NO' ;
	$oye_bien = ($row['oye_bien']==1) ? 'SI' : 'NO' ;
	$audifono = ($row['audifono']==1) ? 'SI' : 'NO' ;
	
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(31,5, $es_medicado,1,0,'C');
	$pdf->Cell(31,5, $ve_bien,1,0,'C');
	$pdf->Cell(32,5, $lentes,1,0,'C');
	$pdf->Cell(32,5, $oye_bien,1,0,'C');
	$pdf->Cell(32,5, $audifono,1,0,'C');
	$pdf->Cell(32,5, $row['sangre'] ,1,1,'C');

	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(95,4,utf8_decode('Tiene alguna dificultad cardiológica'),1,0,'L',1);
	$pdf->Cell(95,4,utf8_decode('Tiene alguna dificultad respiratoria'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(95,5, $row['cardiologica'] ,1,0,'L');
	$pdf->Cell(95,5, $row['respiratoria'] ,1,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(80,4,utf8_decode('Pediatra que lo atiende'),1,0,'L',1);
	$pdf->Cell(80,4,utf8_decode('Clínica - Hospital'),1,0,'L',1);
	$pdf->Cell(30,4,utf8_decode('Telefono'),1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10);
	$pdf->Cell(80,5, $row['pediatra'] ,1,0,'L');
	$pdf->Cell(80,5, $row['clinica'] ,1,0,'L');
	$pdf->Cell(30,5, $row['tlfClinica'] ,1,1,'L');

	$bsc = ($row['bsc']==1) ? 'SI' : 'NO' ;
	$polio = ($row['polio']==1) ? 'SI' : 'NO' ;
	$penta = ($row['penta']==1) ? 'SI' : 'NO' ;
	$anti_hepati = ($row['anti_hepati']==1) ? 'SI' : 'NO' ;
	$bacteriana = ($row['bacteriana']==1) ? 'SI' : 'NO' ;
	$triple_viral = ($row['triple_viral']==1) ? 'SI' : 'NO' ;
	$amarilla = ($row['amarilla']==1) ? 'SI' : 'NO' ;
	$doble_viral = ($row['doble_viral']==1) ? 'SI' : 'NO' ;
	$tetanico = ($row['tetanico']==1) ? 'SI' : 'NO' ;
	$difterico = ($row['difterico']==1) ? 'SI' : 'NO' ;
	$influenza = ($row['influenza']==1) ? 'SI' : 'NO' ;
	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Vacunas'),0,1,'L');
	$pdf->SetFont('Times','',9);
	$pdf->SetX(10);
	$pdf->Cell(27,4,'BSC: ('.$bsc.')',1,0,'L');
	$pdf->Cell(35,4,'Anti-Poliomelitica: ('.$polio.')',1,0,'L');
	$pdf->Cell(32,4,'Pentavelente: ('.$penta.')',1,0,'L');
	$pdf->Cell(32,4,'Anti-Hepatitis B: ('.$anti_hepati.')',1,0,'L');
	$pdf->Cell(32,4,'Triple Bacteriana: ('.$bacteriana.')',1,0,'L');
	$pdf->Cell(32,4,'Trivalente Viral: ('.$triple_viral.')',1,1,'L');
	
	$pdf->SetX(10);
	$pdf->Cell(38,4,'Anti-Amarilica: ('.$amarilla.')',1,0,'L');
	$pdf->Cell(38,4,'Doble Viral: ('.$doble_viral.')',1,0,'L');
	$pdf->Cell(38,4,'Toxoide Tetanico: ('.$tetanico.')',1,0,'L');
	$pdf->Cell(38,4,'Toxoide Difterico: ('.$difterico.')',1,0,'L');
	$pdf->Cell(38,4,'Anti-Influenza: ('.$influenza.')',1,1,'L');
	
	
	$pdf->SetX(10);
	$pdf->Cell(190,4,'Otras: '.$row['otras'],1,0,'L');
	

	
}

/*if($a==1) //Representante
{
	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Datos del representante'),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Cedula',1,0,'L',1);
	$pdf->Cell(120,4,'Nombres y Apellidos',1,0,'L',1);
	$pdf->Cell(40,4,'Parentesco',1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(30,5, $cedu_rep,1,0,'L');
	$pdf->Cell(120,5, $nom_rep,1,0,'L');
	$pdf->Cell(40,5, $par_rep,1,1,'L');

	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Fecha Nac.',1,0,'L',1);
	$pdf->Cell(120,4,'Direccion',1,0,'L',1);
	$pdf->Cell(40,4,'Celular',1,1,'L',1);

	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(30,5, date("d-m-Y", strtotime($fnac_repre)),1,0,'L');
	$pdf->Cell(120,5, $dir_rep,1,0,'L');
	$pdf->Cell(40,5, $tcel_rep,1,1,'L');

	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(80,4,'E-mail',1,0,'L',1);
	$pdf->Cell(30,4,'Tlf. de Habitacion',1,0,'L',1);
	$pdf->Cell(80,4,'Lugar de Trabajo',1,1,'L',1);

	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(80,5, $mai_rep,1,0,'L');
	$pdf->Cell(30,5, $tlf_rep,1,0,'L');
	$pdf->Cell(80,5, $lug_rep,1,1,'L');
}
//Otros Contactos
if($a==1)
{
	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Datos de otros contactos (Casos de Emergencia)'),0,1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(65,4,'Nombres y Apellidos',1,0,'L',1);
	$pdf->Cell(30,4,'Parentesco',1,0,'L',1);
	$pdf->Cell(30,4,'Telf. de Habitacion',1,0,'L',1);
	$pdf->Cell(30,4,'Telf. de Oficina',1,0,'L',1);
	$pdf->Cell(35,4,'Telf. Celular',1,1,'L',1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(65,5, utf8_decode($nom_emerg_1),1,0,'L');
	$pdf->Cell(30,5, $nom_pare1,1,0,'L');
	$pdf->Cell(30,5, $tlf_emerg_hab_1,1,0,'L');
	$pdf->Cell(30,5, $tlf_emerg_ofi_1,1,0,'L');
	$pdf->Cell(35,5, $tlf_emerg_cel_1,1,1,'L');

	$pdf->SetX(10 );
	$pdf->Cell(65,5, utf8_decode($nom_emerg_2),1,0,'L');
	$pdf->Cell(30,5, $nom_pare2,1,0,'L');
	$pdf->Cell(30,5, $tlf_emerg_hab_2,1,0,'L');
	$pdf->Cell(30,5, $tlf_emerg_ofi_2,1,0,'L');
	$pdf->Cell(35,5, $tlf_emerg_cel_2,1,1,'L');
}*/



mysqli_free_result($resultado);
mysqli_free_result($paren1);
mysqli_free_result($paren2);
mysqli_free_result($madre_query);
mysqli_free_result($padre_query);
mysqli_close($link);
$pdf->Output();
?>
