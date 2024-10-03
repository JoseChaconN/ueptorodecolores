<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
{
  header("location:../index.php?vencio");
}
include_once ('../inicia.php');
include_once ('../conexion.php');
$link = Conectarse();
$idDocente=$_SESSION['idAlum'];
$periAct_query=mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos where activoPeriodo ='1' "); 
while($row=mysqli_fetch_array($periAct_query))
{
    $nombrePeriodo=$row['nombre_periodo'];
    $tablaPeriodo=$row['tablaPeriodo'];
    //$=$row[''];
}
$docente_query = mysqli_query($link,"SELECT nombre,apellido FROM alumcer WHERE idAlum='$idDocente' "); 
while ($row = mysqli_fetch_array($docente_query))
{
    $docente=$row['nombre'].' '.$row['apellido'];
}
$horario_dias_query = mysqli_query($link,"SELECT * FROM horario_dias WHERE docente_trabaja='1' "); 
$dia_son=0;
while ($row = mysqli_fetch_array($horario_dias_query))
{
    $dia_son++;
    ${'id_dia'.$dia_son} = $row['id'];
    ${'dia'.$dia_son} = $row['nombre_dia'];
}
$grado_query = mysqli_query($link,"SELECT grado,nombreGrado FROM grado".$tablaPeriodo); 
while ($row = mysqli_fetch_array($grado_query))
{
    $codGra=$row['grado'];
    ${$codGra.'graNom'}=utf8_decode($row['nombreGrado']);
}
/*$seccion_query = mysqli_query($link,"SELECT nombre FROM secciones"); 
while ($row = mysqli_fetch_array($seccion_query))
{
    $nombreSec=$row['nombre'];
}*/
$horario_docentes_query = mysqli_query($link,"SELECT * FROM horario_docentes WHERE turno='1' and nivel=1 and grado=61 "); 
$hora_son=0; 
while ($row = mysqli_fetch_array($horario_docentes_query))
{
    $hora_son++;
    ${'id_hora'.$hora_son} = $row['id'];
    ${'horaIni'.$hora_son} = date("H:i", strtotime($row['hora_inicia']));
    ${'horaTer'.$hora_son} = date("H:i", strtotime($row['hora_termina']));
}
$horario_grado_query = mysqli_query($link,"SELECT A.*,B.nombre as nomSec FROM horario_grado A,secciones B WHERE A.periodo='$tablaPeriodo' and A.id_profesor='$idDocente' and A.id_seccion=B.id ORDER BY A.id_dia,A.id_hora "); 
$van=0;
while ($row = mysqli_fetch_array($horario_grado_query))
{
    $van++;
    ${'idTabla'.$van}=$row['id'];
    ${'id_materia'.$van}=$row['id_materia'];
    ${'id_profesor'.$van}=$row['id_profesor'];
    $id_dia=$row['id_dia'];
    $id_hora=$row['id_hora'];
    $id_materia=$row['id_materia'];
    $id_profesor=$row['id_profesor'];
    $grado=$row['id_grado'];  //.' '.
    $seccion=$row['nomSec'];

    ${'idProf'.$id_hora.$id_dia}=$id_profesor;   
    ${'idMate'.$id_hora.$id_dia}=$id_materia;

    $materia_query = mysqli_query($link,"SELECT nombremate,color_mate FROM materiass".$tablaPeriodo." WHERE codigo='$id_materia' "); 
    if(mysqli_num_rows($materia_query) > 0)
    {
        $row2=mysqli_fetch_array($materia_query);
        ${'nombremate'.$id_hora.$id_dia}=substr($row2['nombremate'],0,20);
        ${'gradomate'.$id_hora.$id_dia}=${$grado.'graNom'}.' "'.$seccion.'"'; // $grado ;
    }else
    {
        ${'nombremate'.$id_hora.$id_dia}='L I B R E';
        ${'gradomate'.$id_hora.$id_dia}='' ;
    }
    ${'mate'.$id_materia}=$row2['nombremate'].': '.$row2['nombre'].' '.$row2['apellido'];
}

require('../fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{
		
		
	}
	function Footer()
	{
		//$this->SetY(-30);
		
	}
}
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
// plantilla del encabezado
$pdf->Image("../img/logo.png",10,8,25,25);	
$pdf->SetFont('Arial','B',13);
$pdf->SetX(45);
$pdf->Cell(112,6,utf8_decode(strtoupper(NKXS.' '.EKKS )),0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(45);
$pdf->Cell(112,5,utf8_decode('Inscrito en el M.P.P.E bajo el nro.'.strtoupper(CKLS)),0,1,'L');
$pdf->SetX(45);
$pdf->Cell(112,5,utf8_decode('Telefono: '.TELEMPM.' '.SUCORREO),0,1,'L');
$pdf->SetX(45);
$pdf->Cell(112,5,(DIRECCM),0,1,'L');

$pdf->SetFont('Arial','B',13);
$pdf->Cell(190,6,utf8_decode('HORARIO DE CLASES '.$nombrePeriodo),0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(190,6,utf8_decode('Docente: '.$docente),1,1,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(20,6,'Hora',1,0,'L');
for ($i=1; $i <= $dia_son ; $i++) { 
	$pdf->Cell(34,6,${'dia'.$i},1,0,'L');
}
$pdf->Cell(1,6,'',0,1,'L');
$pdf->SetFont('Arial','',9);

for ($i=1; $i <=$hora_son ; $i++) { 
	$pdf->Cell(20,6,${'horaIni'.$i}.' a ',0,0,'L');
    for ($x=1; $x <=$dia_son ; $x++) {
		$pdf->Cell(34,6,${'nombremate'.$i.$x} ,0,0,'L');
	}
    $pdf->Cell(34,6,'',0,1,'L');
    $pdf->Cell(20,6,${'horaTer'.$i},0,0,'L');
    for ($x=1; $x <=$dia_son ; $x++) {
        $pdf->Cell(34,6,${'gradomate'.$i.$x} ,0,0,'L');
    }
    $pdf->Cell(34,6,'',0,1,'L');
}
$pdf->Ln(-($hora_son*12) );
for ($i=1; $i <=$hora_son ; $i++) { 
    $pdf->Cell(20,12,'',1,0,'L');
    for ($x=1; $x <=$dia_son ; $x++) {
        $pdf->Cell(34,12,'',1,0,'L');
    }
    $pdf->Cell(34,12,'',0,1,'L');
}

$pdf->Ln(4);
$pdf->Cell(190,4,'___________________________________________________________________________________',0,1,'C');
$pdf->Cell(190,5,'nuestra pagina web: https://'.DOMINIO.'',0,1,'C');
$pdf->Output('horario.pdf','I'); 
?>