<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
include_once("../../include/funciones.php");
include_once("../../../inicia.php");
$link=Conectarse();
$filtro = (!empty($_GET['filtro'])) ? '%'.$_GET['filtro'].'%' : '' ;
$recDesde=$_GET['recDes']; 
$recHasta=$_GET['recHas'];
$usuario = $_SESSION['usuario'];
$desde = $_GET["desde"];
$hasta = $_GET["hasta"];
$id_cuenta=$_GET['cuent'];
if ($id_cuenta>0) {
	$cuenta_query = mysqli_query($link,"SELECT nombre_cuenta FROM cuentas WHERE id_cuenta='$id_cuenta' ");
	while($row=mysqli_fetch_array($cuenta_query)) 
	{
		$nombre_cuenta=$row['nombre_cuenta'];
	}
}
$desBus=$desde.' 00:00:00';
$hasBus=$hasta.' 23:59:59';

if($recDesde>0)
{
	if ($id_cuenta==0) {
    	$ingresos_query = mysqli_query($link,"SELECT * FROM egresos WHERE id_egreso>='$recDesde' and id_egreso<='$recHasta' GROUP BY recibo "); 
    }else
    {
    	$ingresos_query = mysqli_query($link,"SELECT * FROM egresos WHERE id_egreso>='$recDesde' and id_egreso<='$recHasta' and cuentaEgreso='$id_cuenta' GROUP BY recibo "); 
    }
}else
{
	if ($id_cuenta==0) {
    	$ingresos_query = mysqli_query($link,"SELECT * FROM egresos WHERE fecha_egreso>='$desde' and fecha_egreso<='$hasta' GROUP BY recibo ");
    }else{
    	$ingresos_query = mysqli_query($link,"SELECT * FROM egresos WHERE fecha_egreso>='$desde' and fecha_egreso<='$hasta' and cuentaEgreso='$id_cuenta' GROUP BY recibo ");
    }
}

$bancos_query = mysqli_query($link,"SELECT cod_banco,nom_banco FROM bancos WHERE banco_mio='X' ");
$van=0;
while($row=mysqli_fetch_array($bancos_query)) 
{
	$van++;
	${'banco'.$van}=$row['nom_banco'];
	${'cod_banco'.$van}=$row['cod_banco'];
	${'totBanco'.$van}=0;
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function AcceptPageBreak()
	{
		$this->Addpage();
		$this->SetFillColor(232,232,232);
		$this->SetFont('Arial','B',9);
		$this->SetX(5);
		$this->Cell(20,6, 'Fecha',1,0,'C',1);
		$this->Cell(30,6, 'Cedula',1,0,'C',1);
		$this->Cell(70,6, 'Proveedor',1,0,'C',1);
		$this->Cell(16,6, 'Recibo',1,0,'C',1);
		$this->Cell(20,6, 'Divisa',1,0,'C',1);
		$this->Cell(15,6, 'Tasa',1,0,'C',1);
		$this->Cell(25,6, 'Bolivares',1,0,'C',1);
		$this->Cell(60,6, 'Forma',1,0,'C',1);
		$this->Ln();
		$this->SetFont('Arial','',9);
		$this->SetX(12);
	}
	function Header()
	{
		global $desde;
		global $hasta;
		global $id_cuenta;
		$this->Image('../../../imagenes/logo.jpg',10,8,25);
		$this->SetFont('Arial','',15);
		$this->Cell(35);
		$this->Cell(30,6,NKXS,0,1,'L');
		$this->Cell(35);
		$this->Cell(30,6,utf8_decode(EKKS),0,1,'L');
		$this->Cell(35);
		$this->SetFont('Arial','',10);
		$this->Cell(30,5,'Inscrito en el M.P.P.E bajo el codigo '.CKLS,0,1,'L');
		$this->Cell(35);
		$this->Cell(30,5,'Rif.: '.RIFCOLM,0,1,'L');
		$this->Cell(35);
		$this->SetFont('Arial','',15);
		$this->Cell(30,6,'Egresos Procesados desde: '.date("d-m-Y", strtotime($desde)).' hasta: '.date("d-m-Y", strtotime($hasta)),0,1,'L');
		if($id_cuenta>0){
			global $nombre_cuenta;
			$this->SetFont('Arial','',11);
			$this->Cell(35);
			$this->Cell(30,6,'Cuenta: '.$nombre_cuenta,0,1,'L');
		}
		$this->Ln(3);
		$this->SetFont('Arial','',9);
	}
	function Footer()
	{
		$this->SetY(-25);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf = new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',9);
$pdf->SetX(5);
$pdf->Cell(20,6, 'Fecha',1,0,'C',1);
$pdf->Cell(30,6, 'Cedula',1,0,'C',1);
$pdf->Cell(70,6, 'Proveedor',1,0,'C',1);
$pdf->Cell(15,6, 'Recibo',1,0,'C',1);
$pdf->Cell(20,6, 'Divisa',1,0,'C',1);
$pdf->Cell(15,6, 'Tasa',1,0,'C',1);
$pdf->Cell(25,6, 'Bolivares',1,0,'C',1);
$pdf->Cell(60,6, 'Forma de Pago',1,0,'C',1);
$pdf->Ln();
$pdf->SetFont('Arial','',9);
$efec=0;$tran=0;$debi=0;$cred=0;$depo=0;$cheq=0;$vanBs=0;
$bsXdolar=0; $movil=0;
$l=1;
$subDiv=0; $subBs=0;$efecBs=0;
while ($row = mysqli_fetch_array($ingresos_query))
{
	$recibo=$row['recibo'];
    $fecha=$row['fecha_egreso'];
    $pagos_query = mysqli_query($link,"SELECT A.montoDolar,A.montoBs, A.tasaDolar,A.operacion,A.banco, A.status_egreso, B.cedula,B.nombre, B.apellido, C.abrev FROM egresos A, alumcer B, formas_pago C WHERE A.recibo='$recibo' and A.id_provee=B.idAlum and A.operacion=C.id  ");
	$fecha = date("d-m-Y", strtotime($fecha));
	$montoDolar=0; 	$montoBs=0; $fPago='';
	while($row2=mysqli_fetch_array($pagos_query)) 
    {
        $cedula=$row2['cedula'];
        $status=$row2['status_egreso'];
        $alumno=utf8_decode($row2['apellido'].' '.$row2['nombre']);
        if($status==1)
        {
        	if($row2['operacion']=='1'){$montoDolar=$montoDolar+$row2['montoDolar'];}
        	if($row2['operacion']!='1'){$montoBs=$montoBs+$row2['montoBs'];}
        	if($row2['operacion']=='2'){$efecBs=$efecBs+$row2['montoBs'];}
        }
        $tasaDolar=$row2['tasaDolar'];
        
    }
    $montos_query = mysqli_query($link,"SELECT montoDolar,montoBs,operacion,banco FROM egresos WHERE recibo='$recibo' ");
    while($row2=mysqli_fetch_array($montos_query)) 
    {
    	for ($i=1; $i <= $van; $i++) { 
        	if(${'cod_banco'.$i}==$row2['banco'] && $status==1){
        		${'totBanco'.$i}=${'totBanco'.$i}+$row2['montoBs'];
        	}
        }
    }

    $formaPago_query = mysqli_query($link,"SELECT C.abrev FROM egresos A, formas_pago C WHERE A.recibo='$recibo' and A.operacion=C.id GROUP BY A.operacion ");
    while($row3=mysqli_fetch_array($formaPago_query)) 
    {
    	$fPago.=$row3['abrev'].', ';
    }
    $subDiv=$subDiv+$montoDolar;
    $subBs=$subBs+$montoBs;
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(12);
	
	$totalBs=$efec;
	if($l==1)
	{
		$pdf->SetX(5);
		$pdf->Cell(20,5, $fecha,0,0,'C');
		$pdf->Cell(30,5, $cedula,0,0,'L');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,5, $alumno,0,0,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(15,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C');
		if($status==2)
		{
			$pdf->Cell(20,5, 'ANULADO',0,0,'R');
			$pdf->Cell(15,5, '0,00',0,0,'R');
			$pdf->Cell(25,5, 'ANULADO',0,0,'R');	
		}else
		{
			$pdf->Cell(20,5, number_format($montoDolar,2,',','.'),0,0,'R');
			$pdf->Cell(15,5, number_format($tasaDolar,2,',','.'),0,0,'R');
			$pdf->Cell(25,5, number_format($montoBs,2,',','.'),0,0,'R');
		}
		$pdf->Cell(60,5, $fPago,0,1,'L');
		$l=2;
	}else
	{
		$pdf->SetX(5);
		$pdf->Cell(20,5, $fecha,0,0,'C',1);
		$pdf->Cell(30,5, $cedula,0,0,'L',1);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,5, $alumno,0,0,'L',1);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(15,5, str_pad($recibo, 6, "0", STR_PAD_LEFT),0,0,'C',1);
		if($status==2)
		{
			$pdf->Cell(20,5, 'ANULADO',0,0,'R',1);
			$pdf->Cell(15,5, '0,00',0,0,'R',1);
			$pdf->Cell(25,5, 'ANULADO',0,0,'R',1);	
		}else
		{
			$pdf->Cell(20,5, number_format($montoDolar,2,',','.'),0,0,'R',1);
			$pdf->Cell(15,5, number_format($tasaDolar,2,',','.'),0,0,'R',1);
			$pdf->Cell(25,5, number_format($montoBs,2,',','.'),0,0,'R',1);
		}
		$pdf->Cell(60,5, $fPago,0,1,'L',1);
		$l=1;
	}
}
$pdf->SetX(100);
$pdf->Cell(40,5, 'Sub Totales-->',0,0,'R');
$pdf->Cell(20,5, number_format($subDiv,2,',','.'),0,0,'R');
$pdf->Cell(40,5, number_format($subBs,2,',','.'),0,1,'R');
$pdf->SetFont('Arial','',11);
$pdf->Ln(5);
$pdf->SetX(15);
$pdf->Cell(90,5, '*** TOTALES ***',1,1,'C',1);	
$pdf->SetX(15);
$pdf->Cell(60,5, 'Efectivo $ -> ',1,0,'L');	
$pdf->Cell(30,5, '$ '.number_format($subDiv,2,'.',','),1,1,'R');
$pdf->SetX(15);
$pdf->Cell(60,5, 'Efectivo Bs. -> ',1,0,'L');	
$pdf->Cell(30,5, 'Bs. '.number_format($efecBs,2,'.',','),1,1,'R');
for ($i=1; $i <=$van ; $i++) { 
	$pdf->SetX(15);
	$pdf->Cell(60,5, 'Banco '.${'banco'.$i}.'-> ',1,0,'L');	
	$pdf->Cell(30,5, 'Bs. '.number_format(${'totBanco'.$i},2,'.',','),1,1,'R');
}
mysqli_free_result($ingresos_query);
mysqli_free_result($bancos_query);
mysqli_free_result($montos_query);
mysqli_free_result($formaPago_query);

$pdf->Output();

?>