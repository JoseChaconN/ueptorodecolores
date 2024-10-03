<?php
session_start();
if(!isset($_SESSION['usuario']) && !isset($_SESSION['password']) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaHoy = strftime( "%Y-%m-%d");
$link = Conectarse();
include_once("../../../inicia.php");
$grado=$_GET['idG'];
$secci=$_GET['idS'];
$tablaPeriodo=$_GET['peri'];
$nombre_periodo=$_GET['nomP'];
if ($grado==0) {
	$nomGrado='(Inicial, Primaria y Media General) seccion: ';
}
if ($grado>0) {
	$grado_query = mysqli_query($link,"SELECT A.nombreGrado, B.nombre FROM grado".$tablaPeriodo." A, secciones B WHERE A.grado='$grado' and B.id='$secci' ");	
	$row2=mysqli_fetch_array($grado_query);
	$nomGrado=utf8_decode($row2['nombreGrado']);
	$nomSecci=$row2['nombre'];
}else
{
	$grado_query = mysqli_query($link,"SELECT B.nombre FROM secciones B WHERE B.id='$secci' ");
	$row2=mysqli_fetch_array($grado_query);
	$nomSecci=$row2['nombre'];
}
if($grado==0)
{
    $resultado = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13, A.suma_a_pagado, A.idAlumno,A.grado, B.cedula, B.apellido, B.nombre, C.nombreGrado FROM notaprimaria".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C WHERE A.grado<60 and A.idSeccion='$secci' and A.idAlumno=B.idAlum and A.grado=C.grado ORDER BY B.apellido ");	   
    
    $resultado2 = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13, A.suma_a_pagado, A.idAlumno,A.grado, B.cedula, B.apellido, B.nombre, C.nombreGrado FROM matri".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C WHERE A.grado>60 and A.idSeccion='$secci' and A.idAlumno=B.idAlum and A.grado=C.grado ORDER BY B.apellido ");
}
if ($grado>40 && $grado<61) {
	$resultado = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13, A.suma_a_pagado, A.idAlumno,A.grado, B.cedula, B.apellido, B.nombre, C.representante, C.tlf_celu FROM notaprimaria".$tablaPeriodo." A, alumcer B, represe C WHERE A.grado='$grado' and A.idSeccion='$secci' and A.idAlumno=B.idAlum and B.ced_rep=C.cedula ORDER BY B.apellido ");	
}
if($grado>60)
{
	$resultado = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13, A.suma_a_pagado, A.idAlumno,A.grado, B.cedula, B.apellido, B.nombre, C.representante, C.tlf_celu FROM matri".$tablaPeriodo." A, alumcer B, represe C WHERE A.grado='$grado' and A.idSeccion='$secci' and A.idAlumno=B.idAlum and B.ced_rep=C.cedula ORDER BY B.apellido ");	
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		global $grado;
		$this->Addpage();
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','B',9);
		$this->Cell(25,6, 'Cedula',1,0,'C',1);
		$this->Cell(65,6, 'Estudiante',1,0,'C',1);
		$this->Cell(20,6, 'Total Periodo',1,0,'C',1);
		$this->Cell(20,6, 'Pagado',1,0,'C',1);
		$this->Cell(20,6, 'Morosidad',1,0,'C',1);
		if($grado==0)
		{
			$this->Cell(20,6, 'Descuento',1,0,'C',1);
			$this->Cell(20,6, 'Cursa',1,1,'C',1);
		}else
		{
			$this->Cell(20,6, 'Descuento',1,1,'C',1);
		}
		
		$this->SetFont('Arial','',9);
	}
	function Header()
	{
		global $nomGrado;
		global $nomSecci;
		$this->Image('../../../imagenes/logo.jpg',10,8,20);
		$this->SetFont('Arial','',15);
		$this->Cell(25);
		$this->Cell(30,6,NKXS,0,1,'L');
		$this->Cell(25);
		$this->Cell(30,6,utf8_decode(EKKS),0,1,'L');
		$this->Cell(25);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'L');
		$this->Cell(25);
		$this->Cell(30,5,'Rif.: '.RIFCOLM.' - Telefono '.TELEMPM,0,1,'L');
		$this->Ln(2);
		//$this->Cell(25);
		$this->SetFont('Arial','',12);
		$this->Cell(190,6,'Alumnos con Descuento '.$nomGrado.' '.$nomSecci ,0,1,'C');
		$this->SetFont('Arial','',9);
	}
	function Footer()
	{
		$this->SetY(-25);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetTitle('Listado Descuentos');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,6, 'Cedula',1,0,'C',1);
$pdf->Cell(65,6, 'Estudiante',1,0,'C',1);
$pdf->Cell(20,6, 'Total Periodo',1,0,'C',1);
$pdf->Cell(20,6, 'Pagado',1,0,'C',1);
$pdf->Cell(20,6, 'Morosidad',1,0,'C',1);
if($grado==0)
{
	$pdf->Cell(20,6, 'Descuento',1,0,'C',1);
	$pdf->Cell(20,6, 'Cursa',1,1,'C',1);
}else
{
	$pdf->Cell(20,6, 'Descuento',1,1,'C',1);
}

$pdf->SetFont('Arial','',9);
$moroTot=0;$pagoTot=0;$totTot=0; $descTot=0;
$l=1;
if ($grado==0 && mysqli_num_rows($resultado) > 0) {
	$pdf->Cell(65,5, 'Alumnos de Inicial y Primaria',0,1,'L');
}
while ($row = mysqli_fetch_array($resultado))
{
	$idAlumno=$row['idAlumno'];
	$cedula=$row['cedula'];
	$alumno=ucwords(strtolower(utf8_decode($row['apellido'].' '.$row['nombre'])));
	$gradoCur=$row['grado'];
	if ($grado==0) {
		$nombreGrado=utf8_decode($row['nombreGrado']);
	}
	$suma_a_pagado=$row['suma_a_pagado'];
	$pasa=0; $descuento=0;
	for ($i=1; $i <14 ; $i++) { 
        ${'desc'.$i} = $row['desc'.$i];
        if ($row['desc'.$i]>0) {
        	$pasa++;
        	$descuento=$descuento+$row['desc'.$i];
        }
    }
	$montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$gradoCur' "); 
    $deudatotal=0; $meses=0; $morosida=0; $exonera=0;
    while ($row = mysqli_fetch_array($montos_query))
    {
        $meses++;
        $deudatotal=$deudatotal+($row['monto']);
        ${'insc'.$meses} = $row['insc'];
        ${'mes'.$meses} = $row['mes'];
        ${'f_vence'.$meses} = $row['fecha_vence'];
        ${'monto'.$meses} = $row['monto'];
        if($row['fecha_vence']<$fechaHoy)
        {
            $morosida=$morosida+($row['monto']-${'desc'.$meses});
        }
    }
    $pagos_query = mysqli_query($link,"SELECT A.*,D.afecta FROM pagos".$tablaPeriodo." A, conceptos D WHERE A.idAlum = '$idAlumno' and A.recibo <> '' and A.id_concepto=D.id ORDER BY A.id ");
    $pagado=$suma_a_pagado; $pagos=0;
    while ($row = mysqli_fetch_array($pagos_query))
    {
        if($row['statusPago']=='1' and $row['afecta']=='S' )
        {
            $pagado=$pagado+$row['montoDolar'];
            $pagos++;
        }
    }
    $morosida=$morosida-$pagado;
	$morosida = ($morosida<0) ? 0 : $morosida ;
	if ($pasa>0) {
		$moroTot=$moroTot+$morosida;
		$pagoTot=$pagoTot+$pagado;
    	$totTot=$totTot+$deudatotal;
    	$descTot=$descTot+$descuento;
		if($l==1)
		{
			$pdf->Cell(25,5, $cedula,0,0,'L');
			$pdf->Cell(65,5, $alumno,0,0,'L');
			$pdf->Cell(20,5, number_format($deudatotal,2,',','.').' $',0,0,'R');
			$pdf->Cell(20,5, number_format($pagado,2,',','.').' $',0,0,'R');
			$pdf->Cell(20,5, number_format($morosida,2,',','.').' $',0,0,'R');
			if ($grado==0) {
				$pdf->Cell(20,5, number_format($descuento,2,',','.').' $',0,0,'R');
				$pdf->Cell(20,5, substr($nombreGrado,0,10),0,1,'L');
			}else
			{
				$pdf->Cell(20,5, number_format($descuento,2,',','.').' $',0,1,'R');
			}
			
			$l=2;
		}else
		{
			$pdf->Cell(25,5, $cedula,0,0,'L',1);
			$pdf->Cell(65,5, $alumno,0,0,'L',1);
			$pdf->Cell(20,5, number_format($deudatotal,2,',','.').' $',0,0,'R',1);
			$pdf->Cell(20,5, number_format($pagado,2,',','.').' $',0,0,'R',1);
			$pdf->Cell(20,5, number_format($morosida,2,',','.').' $',0,0,'R',1);
			if ($grado==0) {
				$pdf->Cell(20,5, number_format($descuento,2,',','.').' $',0,0,'R',1);
				$pdf->Cell(20,5, substr($nombreGrado,0,10),0,1,'L',1);
			}else
			{
				$pdf->Cell(20,5, number_format($descuento,2,',','.').' $',0,1,'R',1);
			}
			
			$l=1;
		}
	}
}
if($grado==0)
{
	$pdf->Cell(65,5, 'Alumnos de Media General',0,1,'L');
	while ($row = mysqli_fetch_array($resultado2))
	{
		$idAlumno=$row['idAlumno'];
		$cedula=$row['cedula'];
		$alumno=ucwords(strtolower(utf8_decode($row['apellido'].' '.$row['nombre'])));
		$gradoCur=$row['grado'];
		$nombreGrado=utf8_decode($row['nombreGrado']);
		$suma_a_pagado=$row['suma_a_pagado'];
		$pasa=0; $descuento=0;
		for ($i=1; $i <14 ; $i++) { 
	        ${'desc'.$i} = $row['desc'.$i];
	        if ($row['desc'.$i]>0) {
	        	$pasa++;
	        	$descuento=$descuento+$row['desc'.$i];
	        }
	    }
		$montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$gradoCur' "); 
	    $deudatotal=0; $meses=0; $morosida=0; $exonera=0;
	    while ($row = mysqli_fetch_array($montos_query))
	    {
	        $meses++;
	        $deudatotal=$deudatotal+($row['monto']);
	        ${'insc'.$meses} = $row['insc'];
	        ${'mes'.$meses} = $row['mes'];
	        ${'f_vence'.$meses} = $row['fecha_vence'];
	        ${'monto'.$meses} = $row['monto'];
	        if($row['fecha_vence']<$fechaHoy)
	        {
	            $morosida=$morosida+($row['monto']-${'desc'.$meses});
	        }
	    }
	    $pagos_query = mysqli_query($link,"SELECT A.*,D.afecta FROM pagos".$tablaPeriodo." A, conceptos D WHERE A.idAlum = '$idAlumno' and A.recibo <> '' and A.id_concepto=D.id ORDER BY A.id ");
	    $pagado=$suma_a_pagado; $pagos=0;
	    while ($row = mysqli_fetch_array($pagos_query))
	    {
	        if($row['statusPago']=='1' and $row['afecta']=='S' )
	        {
	            $pagado=$pagado+$row['montoDolar'];
	            $pagos++;
	        }
	    }
	    $morosida=$morosida-$pagado;
		$morosida = ($morosida<0) ? 0 : $morosida ;
		if ($pasa>0) {
			$moroTot=$moroTot+$morosida;
			$pagoTot=$pagoTot+$pagado;
	    	$totTot=$totTot+$deudatotal;
	    	$descTot=$descTot+$descuento;
			if($l==1)
			{
				$pdf->Cell(25,5, $cedula,0,0,'L');
				$pdf->Cell(65,5, $alumno,0,0,'L');
				$pdf->Cell(20,5, number_format($deudatotal,2,',','.').' $',0,0,'R');
				$pdf->Cell(20,5, number_format($pagado,2,',','.').' $',0,0,'R');
				$pdf->Cell(20,5, number_format($morosida,2,',','.').' $',0,0,'R');
				$pdf->Cell(20,5, number_format($descuento,2,',','.').' $',0,0,'R');
				$pdf->Cell(20,5, substr($nombreGrado,0,10),0,1,'L');
				$l=2;
			}else
			{
				$pdf->Cell(25,5, $cedula,0,0,'L',1);
				$pdf->Cell(65,5, $alumno,0,0,'L',1);
				$pdf->Cell(20,5, number_format($deudatotal,2,',','.').' $',0,0,'R',1);
				$pdf->Cell(20,5, number_format($pagado,2,',','.').' $',0,0,'R',1);
				$pdf->Cell(20,5, number_format($morosida,2,',','.').' $',0,0,'R',1);
				$pdf->Cell(20,5, number_format($descuento,2,',','.').' $',0,0,'R',1);
				$pdf->Cell(20,5, substr($nombreGrado,0,10),0,1,'L',1);
				
				$l=1;
			}
		}
	}
}
$pdf->SetFillColor(205,246,204);
$pdf->Ln(1);
$pdf->SetX(70);
$pdf->Cell(30,5, 'TOTALES-->',0,0,'L',1);
$pdf->Cell(20,5, number_format($totTot,2,',','.').' $',0,0,'R',1);
$pdf->Cell(20,5, number_format($pagoTot,2,',','.').' $',0,0,'R',1);
$pdf->Cell(20,5, number_format($moroTot,2,',','.').' $',0,0,'R',1);
$pdf->Cell(20,5, number_format($descTot,2,',','.').' $',0,0,'R',1);
$pdf->SetFillColor(232,232,232);
mysqli_free_result($grado_query);
mysqli_free_result($resultado);
mysqli_free_result($resultado2);
mysqli_free_result($montos_query);
mysqli_free_result($pagos_query);
mysqli_close($link);
$pdf->Output();


?>