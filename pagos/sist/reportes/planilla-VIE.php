<?php
session_start();
if(!isset($_SESSION['usuario']) && !isset($_SESSION['password']) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
$link = Conectarse();
$tablaPeriodo=$_GET['peri'];
include_once("../../../inicia.php");
$usuario = $_GET['cedalum'];
	
$resultado = mysqli_query($link,"SELECT C.estado as nomedo, A.*, B.*,D.ciudad, F.municipio FROM alumcer A, parentescos B, estado C, ciudades D, municipios F WHERE A.cedula = '$usuario' and B.idparen = A.parentesco and C.id_edo = A.estado and A.locali=D.id_ciudad and A.municip=F.id_municipio ORDER BY cedula ASC");
while ($row = mysqli_fetch_array($resultado))
{
	$nac_alu = $row['nacion'];
	$ced_alu = $row['cedula'];
	$periodo = $row['Periodo'];
	$ape_alu = utf8_decode($row['apellido']);
	$nom_alu = utf8_decode($row['nombre']);
	$pas_alu = $row['pasaporte'];
	$sex_alu = $row['sexo'];
	$fec_alu = $row['FechaNac'];
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
	$fec_alu = date("d-m-Y", strtotime($fec_alu));
	$loc_alu = utf8_decode($row['ciudad']);
	$est_alu = $row['nomedo'];
	$municip = $row['municipio'];
	$pai_alu = $row['pais'];
	$tlf_alu = $row['telefono'];
	$dir_alu = utf8_decode($row['direccion']);
	$ced_rep = $row['ced_rep'];
	$ced_papa = $row['ced_papa'];
	$ced_mama = $row['ced_mama'];
	$par_rep = $row['nomparen'];
	$gra_alu = $row['grado'];
	$fot_alu = $row['ruta'];
	$correo = $row['correo'];
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

	$escolaridad=$row["escolaridad"];
	$matpen=$row["matpen"];
	$planteProcede=$row["planteProcede"];
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
$periodo_query=mysqli_query($link,"SELECT tablaPeriodo FROM  periodos where nombre_periodo='$periodo'"); 
while($row=mysqli_fetch_array($periodo_query))
{
    $periodoAlum=trim($row['tablaPeriodo']);
}

$ngrado = mysqli_query($link,"SELECT grado, nombreGrado, especialidad, mencion FROM grado".$periodoAlum." WHERE grado = '$gra_alu'");
while ($row = mysqli_fetch_array($ngrado))
{
	$nomgrado = utf8_decode($row['nombreGrado']);
	$nommenci = $row['mencion'];
	$nomespec = $row['especialidad'];
}
$resultado2 = mysqli_query($link,"SELECT * FROM represe WHERE cedula = '$ced_rep' ORDER BY cedula ASC");
while ($row = mysqli_fetch_array($resultado2))
{
	$cedu_rep = ($row['cedula']);
	$nom_rep=utf8_decode($row['representante']);
	$tlf_rep = ($row['telefono']);
	$dir_tra_rep = utf8_decode($row['dir_trab_rep']);
	$dir_rep = utf8_decode($row['direccion']);
	$lug_rep = utf8_decode($row['lug_trabaj']);
	$ttra_rep = ($row['tlf_trab']);
	$ocu_rep = utf8_decode($row['ocupacion']);
	$niv_rep = utf8_decode($row['niv_estudio']);
	$fot_rep = $row['ruta'];

	$mai_rep = $row['correo'];
	$tcel_rep = ($row['tlf_celu']);
	$fnac_repre = $row['fnac_repre']; 
	$religion_rep = $row['religion_rep']; 
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
		
	}
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->Image('../../../imagenes/logo.jpg',10,8,23,30);
$a=1;
if($a==1)
{
	if ($gra_alu>60) {
		$planilla='EDUCACIÓN MEDIA GENERAL';
	}else{
		$planilla='EDUCACIÓN PRIMARIA';
	}
	$pdf->SetFont('Times','',9);
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'),0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN'),0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('U.E.P. COLEGIO "DON RUFINO GONZÁLEZ"'),0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,utf8_decode('CÓDIGO: ').CKLS,0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,'RIF.:'.RIFCOLM,0,1,'L');
	$pdf->Cell(25);
	$pdf->Cell(100,4,'SANTA CRUZ DE ARAGUA',0,1,'L');
	$pdf->Ln(3);
	$pdf->Cell(125);
	$pdf->Cell(30,5,'Representante',0,0,'C');
	$pdf->Cell(5);
	$pdf->Cell(30,5,'Alumno',0,1,'C');
	$pdf->Cell(80);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetX(10);
	$pdf->Cell(190,5, utf8_decode('PLANILLA DE INSCRIPCIÓN '),0,1,'C');
	$pdf->Cell(190,5, utf8_decode($planilla).' '.$periodo,0,1,'C');
	$pdf->Cell(190,5, ($nomgrado),0,1,'C');

	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetX(10);
	$pdf->Cell(30,4,utf8_decode('Datos del estudiante:'),0,1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(30,4,'Cedula',1,0,'L',1);
	$pdf->Cell(80,4,'Apellidos',1,0,'L',1);
	$pdf->Cell(80,4,'Nombres',1,1,'L',1);
	if (!empty($fot_rep) && !is_null($fot_rep)) 
	{
		$pdf->Image('../../../fotorep/'.$fot_rep,135,8,30,30);
	} else 
	{
		$pdf->Image('../../../imagenes/fotocarnet.jpg',135,8,30,30);
	}
	if (!empty($fot_alu) && !is_null($fot_alu)) 
	{
	    $pdf->Image('../../../fotoalu/'.$fot_alu,170,8,30,30);
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
	$pdf->Cell(15,4,'Sexo',1,0,'L',1);
	$pdf->Cell(45,4,'Fecha de Nacimiento',1,0,'L',1);
	$pdf->Cell(20,4,'Edad',1,0,'L',1);
	$pdf->Cell(110,4, 'Lugar de Nacimiento',1,0,'L',1);

	$pdf->Ln();
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(10 );
	$pdf->Cell(15,5, $sex_alu,1,0,'C');
	$pdf->Cell(45,5, $fec_alu,1,0,'C');
	$pdf->Cell(20,5, $edad,1,0,'L');
	$pdf->Cell(110,5, $loc_alu,1,1,'L');
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
	$pdf->Cell(80,5, $correo,1,0,'L');
	$pdf->Cell(110,5, $dir_alu,1,1,'L');
	
}
if($a==1) //Representante
{
	$pdf->Ln(.5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,5, utf8_decode('Datos del representante legal'),0,1,'L');
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
if($a==1) //MAdre
{
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
		$pdf->Cell(30,5, ($row['tlf_cel_mama']),1,1,'L');
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetX(10);
		$pdf->Cell(160,4,utf8_decode('Dirección de Habitación'),1,0,'L',1);
		$pdf->Cell(30,4,utf8_decode('Teléfono de Hab.'),1,1,'L',1);
		
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(10);
		$pdf->Cell(160,5, utf8_decode($row['dire_mama']),1,0,'L');
		$pdf->Cell(30,5, $row['tlf_hab_mama'],1,1,'L');
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetX(10);
		$pdf->Cell(70,4,utf8_decode('Lugar de Trabajo'),1,0,'L',1);
		$pdf->Cell(60,4,utf8_decode('Profesión'),1,0,'L',1);
		$pdf->Cell(60,4,utf8_decode('Dedicación Actual'),1,1,'L',1);
		
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(10);
		$pdf->Cell(70,5, utf8_decode($row['lug_trab_mama']),1,0,'L');
		$pdf->Cell(60,5, utf8_decode($row['ocupa_mama']),1,0,'L');
		$pdf->Cell(60,5, utf8_decode($row['dedicaMama']),1,1,'L');
	}
}
if($a==1) //Padre
{
	$padre_query = mysqli_query($link,"SELECT * FROM padres WHERE ced_papa = '$ced_papa'");
	while ($row = mysqli_fetch_array($padre_query))
	{
		$pdf->Ln(.5);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(190,5, utf8_decode('Datos del Padre'),0,1,'L');
		$pdf->SetFont('Arial','B',8);
		$pdf->SetX(10);
		$pdf->Cell(30,4,utf8_decode('Cedula'),1,0,'L',1);
		$pdf->Cell(130,4,'Nombres y Apellidos',1,0,'L',1);
		$pdf->Cell(30,4,'Celular',1,1,'L',1);
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(10 );
		$pdf->Cell(30,5, $row['ced_papa'],1,0,'L');
		$pdf->Cell(130,5, utf8_decode($row['nom_ape_papa']),1,0,'L');
		$pdf->Cell(30,5, ($row['tlf_cel_papa']),1,1,'L');
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetX(10);
		$pdf->Cell(160,4,utf8_decode('Dirección de Habitación'),1,0,'L',1);
		$pdf->Cell(30,4,utf8_decode('Teléfono de Hab.'),1,1,'L',1);
		
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(10);
		$pdf->Cell(160,5, utf8_decode($row['dire_papa']),1,0,'L');
		$pdf->Cell(30,5, $row['tlf_hab_papa'],1,1,'L');
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetX(10);
		$pdf->Cell(70,4,utf8_decode('Lugar de Trabajo'),1,0,'L',1);
		$pdf->Cell(60,4,utf8_decode('Profesión'),1,0,'L',1);
		$pdf->Cell(60,4,utf8_decode('Dedicación Actual'),1,1,'L',1);
		
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(10);
		$pdf->Cell(70,5, utf8_decode($row['lug_trab_papa']),1,0,'L');
		$pdf->Cell(60,5, utf8_decode($row['ocupa_papa']),1,0,'L');
		$pdf->Cell(60,5, utf8_decode($row['dedicaPapa']),1,1,'L');
			
	}
}
if ($a==1) { // Emergencias
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
}
$pdf->Ln(.5);
$pdf->SetX(10);
$pdf->Cell(30,8,'',1,0,'C');
$pdf->Cell(25,8,'',1,0,'C');
$pdf->Cell(20,8,'',1,0,'C');
$pdf->Cell(30,8,'',1,0,'C');
$pdf->Cell(30,8,'',1,0,'C');
$pdf->Cell(25,8,'',1,0,'C');
$pdf->Cell(30,8,'',1,1,'C');
$pdf->Ln(-8);
$pdf->SetX(10);
$pdf->Cell(30,4,utf8_decode('Año'),0,0,'C');
$pdf->Cell(25,4,utf8_decode('Año'),0,0,'C');
$pdf->Cell(20,4,utf8_decode('N°'),0,0,'C');
$pdf->Cell(30,4,utf8_decode('Firma del'),0,0,'C');
$pdf->Cell(30,4,utf8_decode('Firma del'),0,0,'C');
$pdf->Cell(25,4,utf8_decode('Fecha de'),0,0,'C');
$pdf->Cell(30,4,utf8_decode('Actualización del N°'),0,1,'C');

$pdf->SetX(10);
$pdf->Cell(30,4,utf8_decode('Sección'),0,0,'C');
$pdf->Cell(25,4,utf8_decode('Escolar'),0,0,'C');
$pdf->Cell(20,4,utf8_decode('Registro'),0,0,'C');
$pdf->Cell(30,4,utf8_decode('Representante'),0,0,'C');
$pdf->Cell(30,4,utf8_decode('Docente'),0,0,'C');
$pdf->Cell(25,4,utf8_decode('Inscripción'),0,0,'C');
$pdf->Cell(30,4,utf8_decode('de teléfono'),0,1,'C');
for ($i=1; $i <=6 ; $i++) { 
	$pdf->SetX(10);
	$pdf->Cell(30,8,'',1,0,'C');
	$pdf->Cell(25,8,'',1,0,'C');
	$pdf->Cell(20,8,'',1,0,'C');
	$pdf->Cell(30,8,'',1,0,'C');
	$pdf->Cell(30,8,'',1,0,'C');
	$pdf->Cell(25,8,'',1,0,'C');
	$pdf->Cell(30,8,'',1,1,'C');	
}
$pdf->Addpage();
$l1=0;
$pdf->SetFont('Arial','',10);
$pdf->Cell(195,266,'',1,0,'C');
$pdf->SetX(10);
$pdf->Cell(195,5,utf8_decode('Representante Escolar: Madre:_______ Padre:_______ Otro:_______'),0,1,'L');
$pdf->Cell(195,5,utf8_decode('En caso de ser otra persona llenar autorización: Anexar fotocopia de la cédula de identidad.'),0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(195,4,utf8_decode('AUTORIZACIÓN'),0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(140,5,utf8_decode('YO:'),0,0,'L');
$pdf->Cell(50,5,utf8_decode('C.I. N°:'),0,1,'L');
$pdf->Cell(140,5,utf8_decode('Representante legal de:'),0,0,'L');
$pdf->Cell(50,5,utf8_decode('C.I. N°:'),0,1,'L');
$pdf->Cell(140,5,utf8_decode('Autorizo  a:'),0,0,'L');
$pdf->Cell(50,5,utf8_decode('C.I. N°:'),0,1,'L');
$pdf->Cell(70,5,utf8_decode('Teléfono:'),0,0,'L');
$pdf->Cell(50,5,utf8_decode('Edad:'),0,0,'L');
$pdf->Cell(70,5,utf8_decode('Parentesco:'),0,1,'L');
$pdf->Cell(190,5,utf8_decode('Dirección:'),0,1,'L');
$pdf->Line(17, $l1+28, 150, $l1+28); $pdf->Line(165, $l1+28, 203, $l1+28);
$pdf->Line(49, $l1+33, 150, $l1+33); $pdf->Line(165, $l1+33, 203, $l1+33);
$pdf->Line(29, $l1+38, 150, $l1+38); $pdf->Line(165, $l1+38, 203, $l1+38);
$pdf->Line(28, $l1+43, 80, $l1+43); $pdf->Line(90, $l1+43, 130, $l1+43);$pdf->Line(150, $l1+43, 203, $l1+43);
$pdf->Line(27, $l1+48, 203, $l1+48);
$pdf->Ln(1);
$pdf->MultiCell(195,4,utf8_decode('Para que realice cualquier trámite referente a mi Representado dentro de la Institución, en cuanto a reuniones, entrega de boletas, citaciones, pases de entrada y salidas, cumplimiento de los acuerdos de convivencia entre otros. En todo momento su actuación dentro de la Institución y sus adyacencias, así como su rendimiento académico y de comportamiento. Será responsabilidad total de su persona. Por lo que asumimos ambos de manera responsable todo lo antes expuesto y sin coacción alguna.'),0,'J');
$pdf->Ln(8);
$pdf->Cell(90,5,'Representante Legal',0,0,'C');
$pdf->Cell(10,5,'',0,0,'L');
$pdf->Cell(90,5,'Representante Autorizado',0,1,'C');
$pdf->Line(27, $l1+78, 85, $l1+78); $pdf->Line(120, $l1+78, 185, $l1+78);

$pdf->MultiCell(195,4,utf8_decode('Una vez formalizada la inscripción y en derecho de ejercicio de la libre disposición, administración que poseo sobre mi patrimonio y conducta, expreso mi voluntad de cumplir con lo establecido en los Acuerdos de Convivencia. Así mismo, mediante la presente declaro, que estoy en conocimiento que la educación privada es optativa debiendo cumplir con las obligaciones contractuales correspondientes al servicio de educación que brinda la institución a mi representado, los cuales acepto voluntariamente, sometiéndome a los órganos jurisdiccionales competentes en caso de incumplimiento; y por lo tanto: '),0,'J');
$pdf->Ln(1);
$pdf->Cell(140,5,'YO:',0,0,'L');
$pdf->Cell(50,5,utf8_decode('C.I. N°:'),0,1,'L');
$pdf->Cell(190,5,'Representante del Estudiante:',0,1,'L');
$pdf->Line(17, $l1+112, 150, $l1+112); $pdf->Line(165, $l1+112, 203, $l1+112);
$pdf->Line(58, $l1+117, 203, $l1+117);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,'ME COMPROMETO A:',0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(195,4,utf8_decode('1.	Garantizar el fiel cumplimiento del traje escolar de mi representado (PANTALÓN DE GABARDINA AZUL MARINO, CAMISA AZUL CLARA O BEIGE SEGÚN EL AÑO, ZAPATOS Y CORREA EXCLUSIVAMENTE NEGRO). PARA DEPORTE (MONO AZUL MARINO, FRANELA BLANCA CON EL LOGO DE LA INSTITUCIÓN, ZAPATOS DEPORTIVOS NEGROS O BLANCOS) En caso de Suéter, el mismo debe ser azul marino o negro con abertura por delante.   Asistir a las reuniones, entrevistas, citaciones, talleres y otros, cuando se me convoque, entendiéndose que las mismas forman parte de las obligaciones contraídas con la institución, en función de mis deberes con respecto a mi representado. (Art 55 de LOPNNA).'),0,'J');
$pdf->MultiCell(195,4,utf8_decode('2.	Cancelar puntualmente entre el 01 al 05 de cada mes la mensualidad correspondiente, compromiso que ratifico con mi firma, con la que formalizo la inscripción de mi representado, establecidos en los Acuerdos de Paz, Convivencia escolar y comunitaria, que por medio del presente hago constar que conozco y acepto. En caso de retardo del pago de la mensualidad será establecido según Jurisprudencia 2020 del TSJ en relación a la morosidad por incumplimiento de pago (semanario pedagógico Aragua. Atención a la morosidad y procedimiento administrativo para resolución de la misma).'),0,'J');
$pdf->MultiCell(195,4,utf8_decode('3.	Cancelar en el mes de junio las mensualidades de junio y Julio y en Julio la mensualidad de agosto.'),0,'J');
$pdf->MultiCell(195,4,utf8_decode('4.	Aceptar responsablemente la estructura de costo correspondiente para éste año escolar, aprobada en asamblea general de padres y/o representante. Sin embargo, de generarse un Decreto Presidencial, que influya de manera directa en la estructura de costo, me comprometo asistir a la asamblea de padres y representantes para la discusión de la nueva estructura de costo de propiciarse algún aumento en la mensualidad.'),0,'J');
$pdf->MultiCell(195,4,utf8_decode('5.	Cancelar el total de las mensualidades correspondientes si llegase a necesitar el retiro de los documentos de mi representado. En tal sentido, es mi deber estar solvente, para poder solicitar cualquier documentación, pertinente a mi representado, lo cual acepto por estar plenamente establecido en los Acuerdos de Paz, Convivencia escolar y comunitaria.'),0,'J');
$pdf->MultiCell(195,4,utf8_decode('6.	Que una vez que incurra en el atraso del pago de DOS (02) mensualidades sin causa justificada, autorizo que el caso sea enviado al Departamento de Defensoría Estudiantil, en específico a la Oficina de Inclusión Escolar de Zona Educativa de Aragua,  a los fines de que sea gestionado las acciones pertinentes en aras de preservar el Derecho a la Educación que se encuentra establecido en el Art. 54 de la LOPNNA, y como consecuencia sea tramitado el cambio de ambiente de mi representado, como efecto del incumplimiento de mis obligaciones por vulnerar los derechos colectivos de la comunidad educativa.'),0,'J');
$pdf->MultiCell(195,4,utf8_decode('7.	En concordancia con los Acuerdos de Paz, Convivencia escolar y comunitaria.  aportar y mantener la información actualizada con respecto al correo electrónico y el número telefónico, los cuales acepto y reconozco como único medio de notificación oficial con la institución, a los fines de efectuar los respectivos comunicados e información administrativa; inherente a mi relación con el colegio y el servicio educativo prestado, además de los deberes y derechos que se desprenden con respecto a mi representado.'),0,'J');
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(195,4,utf8_decode('En señal de mi consentimiento firmo el siguiente documento, libre de todo apremio y sin coacción de ninguna naturaleza, ya que lo único que refleja es mi libre convicción en coadyuvar con esta institución'),0,'J');
$pdf->Ln(13);
$pdf->Cell(120,5,utf8_decode('FIRMA Y CÉDULA DEL REPRESENTANTE LEGAL'),0,1,'C');
$pdf->Ln(-20);
$pdf->SetX(175);
$pdf->Cell(25,18,'',1,1,'C');
$pdf->SetX(175);
$pdf->Cell(25,5,'Huella Dactilar',0,1,'C');
$pdf->SetFont('Arial','',10);

mysqli_free_result($resultado);
mysqli_free_result($periodo_query);
mysqli_free_result($paren1);
mysqli_free_result($paren2);
mysqli_free_result($ngrado);
mysqli_free_result($resultado2);
mysqli_free_result($madres);
mysqli_free_result($padres);
mysqli_close($link);

$pdf->Output();


?>
