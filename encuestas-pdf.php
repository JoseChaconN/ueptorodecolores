<?php
session_start();
if(!isset($_SESSION['usuario']))
{
	header("location:index.php?vencio");
}
include_once ('conexion.php');

setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fechahoy = date("d") . " de " . $meses[date('n')-1] . " de " . date("Y");
include_once ('includes/funciones.php');
$link = conectarse();
include_once("inicia.php");
$id_encuesta = desencriptar($_GET["idEnc"]);
$idAlum=$_SESSION['idAlum'];
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$alumno_query = mysqli_query($link,"SELECT A.nacion,A.cedula,A.nombre,A.apellido,A.ced_rep,A.parentesco, B.nombreGrado,C.nombre as nomSec FROM alumcer A, grado".$tablaPeriodo." B, secciones C, encuesta_respuesta D WHERE A.idAlum ='$idAlum' and D.id_alum='$idAlum' and D.grado=B.grado and D.seccion=C.id  "); 
while ($row = mysqli_fetch_array($alumno_query))
{
	$nacion=$row['nacion'];
    $cedula=$row['cedula'];
    $nombre=$row['nombre'];
    $apellido=$row['apellido'];
    $nombreGrado=utf8_decode($row['nombreGrado']);
    $nomSec=$row['nomSec'];
    $ced_rep=$row['ced_rep'];
    $parenRep=$row['parentesco'];
}
mysqli_free_result($alumno_query);
$represe_query = mysqli_query($link,"SELECT cedula,representante FROM represe WHERE cedula ='$ced_rep' "); 
while ($row = mysqli_fetch_array($represe_query))
{
	$nomRepre=utf8_decode($row['representante']);
	$cedRepre=$row['cedula'];
}
mysqli_free_result($represe_query);
$parent_query = mysqli_query($link,"SELECT nomparen FROM parentescos WHERE idparen ='$parenRep' "); 
while ($row = mysqli_fetch_array($parent_query))
{
	$parentesco=$row['nomparen'];
}
mysqli_free_result($parent_query);
$encuesta_query = mysqli_query($link,"SELECT * FROM encuesta WHERE id_encuesta ='$id_encuesta' "); 
while ($row = mysqli_fetch_array($encuesta_query))
{
    $titulo_enc=$row['titulo_enc'];
    $descripcion=$row['descripcion'];
    $fecha_ini=$row['fecha_ini'];
    $fecha_fin=$row['fecha_fin'];
    $periodo=$row['periodo'];
}
mysqli_free_result($encuesta_query);

require('fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{}
	
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);

$pdf->Image('img/logo.jpg',10,8,20);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',12);
$pdf->Cell(190,5,utf8_decode(NKXS),0,1,'C');
$pdf->Cell(190,5,utf8_decode(EKKS),0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,5,'Periodo Escolar '.$periodo,0,1,'C');
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(190,5, utf8_decode($titulo_enc),0,'C');
$pdf->SetFont('Arial','',8);
$pdf->SetX(10);
$pdf->Cell(30,5, 'Cedula',1,0,'C',1);
$pdf->Cell(120,5, 'Estudiante',1,0,'C',1);
$pdf->Cell(40,5, 'Grado/Seccion',1,1,'C',1);
$pdf->SetFont('Arial','',9);
$pdf->SetX(10);
$pdf->Cell(30,5, $nacion.'-'.$cedula,1,0,'C');
$pdf->Cell(120,5, $apellido.' '.$nombre,1,0,'L');
$pdf->Cell(40,5, $nombreGrado.' '.$nomSec ,1,1,'C');
$pdf->SetFont('Arial','',8);
$pdf->SetX(10);
$pdf->Cell(30,5, 'Cedula',1,0,'C',1);
$pdf->Cell(120,5, 'Representante Legal',1,0,'C',1);
$pdf->Cell(40,5, 'Parentesco',1,1,'C',1);
$pdf->SetFont('Arial','',9);
$pdf->SetX(10);
$pdf->Cell(30,5, $cedRepre,1,0,'C');
$pdf->Cell(120,5, $nomRepre,1,0,'L');
$pdf->Cell(40,5, $parentesco ,1,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(190,5, utf8_decode($descripcion),0,'J');
$pdf->SetFont('Arial','',9);

$pregunta_query = mysqli_query($link,"SELECT * FROM encuesta_pregunta WHERE id_encuesta ='$id_encuesta' "); 
while ($row = mysqli_fetch_array($pregunta_query))
{
    $id_pregunta=$row['id_pregunta'];
    $pregunta=$row['pregunta'];
    $tipo_pregunta=$row['tipo_pregunta'];
    if($tipo_pregunta==1){$tipoPre='Selección Simple';}
    if($tipo_pregunta==2){$tipoPre='Selección Multiple';}
    if($tipo_pregunta==3){$tipoPre='Texto';}
    $comentario=$row['comentario'];
    $respuesta_query = mysqli_query($link,"SELECT respuesta,comentario,fecha_res FROM encuesta_respuesta WHERE id_encuesta ='$id_encuesta' and id_pregunta='$id_pregunta' and id_alum='$idAlum' ");
    $preguntas=mysqli_num_rows($pregunta_query) ;
    if(mysqli_num_rows($respuesta_query) > 0)
    { 
        $opcion='';
        while ($row2 = mysqli_fetch_array($respuesta_query))
        {
            $respuesta=$row2['respuesta'];
            $comentarioAlum=$row2['comentario'];
            $fecha_res=$row2['fecha_res'];
            if($tipo_pregunta!=3){
                $preguntas_query = mysqli_query($link,"SELECT opcion FROM encuesta_preguntas WHERE id_preguntas ='$respuesta' "); 
                 $van=0;
                while ($row3 = mysqli_fetch_array($preguntas_query))
                {
                    $van++;
                    $opcion.= $row3['opcion'].', ';
                }
            }else{$opcion=$respuesta;} 
        }
        mysqli_free_result($respuesta_query);
    }else{
        $respuesta='';
        if(empty($comentario)){
            $comentarioAlum='';
        }else{$comentarioAlum='No Respondida';}
        
        $fecha_res='';
        $opcion='No Respondida';
    }
    $pdf->MultiCell(190,5, utf8_decode($pregunta),1,'L',1);
    $pdf->MultiCell(190,5, utf8_decode($opcion),1,'L');
    if(!empty($comentario)){
	    $pdf->MultiCell(190,5, utf8_decode($comentario),1,'L',1);
	    $pdf->MultiCell(190,5, utf8_decode($comentarioAlum),1,'L');
	}
}
if($preguntas>16){
    $pdf->Addpage();
}
$pdf->Line(10, 263, 100, 263);
$pdf->Line(110, 263, 200, 263);
$pdf->SetXY(110,258);
$pdf->Cell(90,5,CIUDADM.', '.$fechahoy ,0,1,'C');

$pdf->Cell(90,4,'Firma Representante' ,0,0,'C');
$pdf->Cell(10,4,'' ,0,0,'C');
$pdf->Cell(90,4,'Lugar y Fecha' ,0,1,'C');
$pdf->Cell(90,4,$nomRepre ,0,1,'C');
$pdf->Cell(90,4,$cedRepre ,0,1,'C');



$pdf->Output();


?>
