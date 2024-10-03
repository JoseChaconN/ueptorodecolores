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
$link = Conectarse();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaYa = date("Y-m-d");
$fechaHoy = date("Y-m-d H:i:s");
$recibo=desencriptar($_GET['recibo']);
$enviar = (isset($_GET['envia'])) ? $_GET['envia'] : '' ;
$copia = (isset($_GET['cop'])) ? $_GET['cop'] : 2 ;
$plantel_query = mysqli_query($link,"SELECT * FROM colegio WHERE id='1'"); 
while ($row = mysqli_fetch_array($plantel_query))
{
	$administra = $row['administra'];
    $ced_admin = $row['ced_admin'];
}
$recibo_query = mysqli_query($link,"SELECT * FROM egresos_nro WHERE id_recibo='$recibo'  ");
while($row=mysqli_fetch_array($recibo_query)) 
{
	$hora=date("H:i", strtotime($row['fecha']));
	$fechaRecibo=date("Y-m-d", strtotime($row['fecha']));
	if($row['desde']==NULL || $row['desde']=='0000-00-00')
	{
		$recSale=1;
	}else
	{
		$recSale=2;
		$desde=date("d-m-Y", strtotime($row['desde']));
		$hasta=date("d-m-Y", strtotime($row['hasta']));
	}
}

$egresos_query = mysqli_query($link,"SELECT A.*,B.cedula,B.nombre,B.apellido,B.direccion,B.correo, C.tipo_egreso, G.nombreUser FROM egresos A, alumcer B, concep_egresos C, user G WHERE A.recibo='$recibo' and A.id_provee=B.idAlum and A.emitidoPor=G.idUser and A.id_concepto = C.id_concepto  ");
$van=0;
while($row=mysqli_fetch_array($egresos_query)) 
{
	$van++;
	${'codigo'.$van}=$row['id_concepto'];
	${'concepto'.$van}=$row['concepto_pago'];
	${'montoRec'.$van}=$row['montoBs'];
	${'tipo_egreso'.$van}=$row['tipo_egreso'];
	$cedula=$row['cedula'];
	$provedor=$row['nombre'].' '.$row['apellido'];
	$operador=$row['nombreUser'];
	$idAlum=$row['id_provee'];
	$montoTasa=$row['tasaDolar'];
	$status_egreso=$row['status_egreso'];
	$direccion=$row['direccion'];
	$correo=$row['correo'];
	$fecha=date("d-m-Y", strtotime($row['fecha_egreso']));
}

$bancos_query = mysqli_query($link,"SELECT A.banco,A.operacion,A.refePag,B.nom_banco,C.abrev FROM egresos A, bancos B, formas_pago C WHERE A.recibo='$recibo' and A.banco=B.cod_banco and A.operacion=C.id GROUP BY A.operacion ");
$operacion=''; $banco=''; $fpag='';
while($row=mysqli_fetch_array($bancos_query)) 
{
	if($row['banco']>0)
	{
		$banco.=$row['nom_banco'].', ';
		$operacion.=$row['refePag'].', ';
	}
	$fpag.=$row['abrev'].',';
}
require('../include/fpdf/fpdf.php');
class PDF extends FPDF 
{
	function Header()
	{}
	function Footer()
	{}
}
$mgIzq=10;
$mgSup=0;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->Addpage();
$pdf->SetFillColor(232,232,232);
if($recSale==1)
{
	for ($i=0; $i <$copia ; $i++) { 
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln($mgSup);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,4, NKXS.' "'.EKKS.'"',0,1,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,4, DIRECCM,0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(150,4, ' RIF.'.RIFCOLM.' Tlf.: '.TELEMPM,0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(30,4, 'Egreso ',0,1,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq+150);
		
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(30,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,1,'L');
		$pdf->SetFont('Arial','',9);
		//$pdf->Ln(2);
		$pdf->SetFont('Arial','',8);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, utf8_decode('A Nombre de:'.$provedor),0,0,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(30,3, utf8_decode('Fecha: '.$fecha),0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, utf8_decode('Rif./Cedula: '.$cedula),0,0,'L');
		$pdf->Cell(30,3, utf8_decode('Hora: '.$hora),0,1,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->setX($mgIzq);
		$pdf->MultiCell(130,3, utf8_decode('Domicilio Fiscal: '.$direccion),0,'J');
		$pdf->SetFont('Arial','',9);
			
		$pdf->Ln(1);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,4, utf8_decode('Conceptos '),1,0,'C');
		$pdf->Cell(30,4, utf8_decode('Monto'),1,1,'C');
		
		$pdf->SetFont('Arial','',9);
		$subTot1=0; $subTot2=0;
		for ($b1=1; $b1 <= $van; $b1++) { 
			$pdf->setX($mgIzq);
			$pdf->MultiCell(150,4, utf8_decode(${'concepto'.$b1}),0,'J');
			$pdf->Ln(-4);
			$pdf->setX($mgIzq+150);
			$pdf->Cell(30,4,${'montoRec'.$b1} ,0,1,'R');
			$subTot1=$subTot1+${'montoRec'.$b1};
		}
		if ($van<10) {
			for ($c=$van; $c <=10 ; $c++) { 
				$pdf->setX($mgIzq);
				//$pdf->Cell(10,4, '',0,0,'C');
				$pdf->Cell(150,4, '',0,0,'L');
				$pdf->Cell(30,4,'',0,1,'R');		
			}
		}
		$pdf->SetFont('Arial','',8);
		$pdf->SetFillColor(248,249,249);
		$pdf->setX($mgIzq);
		$pdf->Cell(180,14,'' ,1,1,'L',1);
		$pdf->Ln(-13);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, 'Forma de Pago: '.$fpag ,0,0,'L');
		$pdf->Cell(20,3, 'Total Bs. ',0,0,'R');
		$pdf->Cell(10,3, number_format($subTot1,2,'.',',') ,0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, 'Ref: '.$operacion ,0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, 'Banco: '.$banco ,0,0,'L');
		
		$pdf->setX($mgIzq+85);
		$pdf->Cell(65,3, '__________________________________' ,0,1,'C');
		$pdf->setX($mgIzq);
		$pdf->Cell(85,3, 'Procesado por: '.$operador ,0,0,'L');
		$pdf->Cell(65,3, 'Firma del Beneficiario: '.$cedula ,0,1,'C');
		if($i==0)
		{
			$pdf->Cell(180,14, '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -' ,0,1,'C');
		}
	}
	if($status_egreso==2)
	{
		$pdf->Image('../../img/anulado2.png',10,10,180,120);
		$pdf->Image('../../img/anulado2.png',10,130,180,120);
	}
}else
{
	for ($i=0; $i <$copia ; $i++) { 
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln($mgSup);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,4, NKXS.' "'.EKKS.'"',0,0,'L');
		$pdf->Cell(30,4, 'Egreso ',0,1,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,4, DIRECCM,0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(30,4, '# '.str_pad($recibo, 6, "0", STR_PAD_LEFT),0,1,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,4, ' RIF.'.RIFCOLM.' Tlf.: '.TELEMPM,0,0,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq+150);
		$pdf->Cell(30,3, utf8_decode('Fecha: '.$fecha),0,1,'L');
		$pdf->setX($mgIzq+150);
		$pdf->Cell(30,3, utf8_decode('Hora: '.$hora),0,1,'L');
		
		$pdf->Cell(180,4, utf8_decode('HONORARIOS POR CONTRATO POR TIEMPO DETERMINADO Y PARA'),0,1,'C');
		$pdf->Cell(180,4, utf8_decode(' UNA OBRA DETERMINADA DE SERVICIOS PROFESIONALES'),0,1,'C');
		$pdf->Ln(2);

		$pdf->SetFont('Arial','',8);
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, utf8_decode('Rif./Cedula: '.$cedula.' Empleado: '.$provedor),0,1,'L');
		
		$pdf->setX($mgIzq);
		$pdf->MultiCell(150,3, utf8_decode('Domicilio Fiscal: '.$direccion),0,'J');
		$pdf->SetFont('Arial','',9);
		$pdf->setX($mgIzq);
		$pdf->Cell(130,3, utf8_decode('Descripción del concepto'),0,0,'C',1);
		$pdf->Cell(25,3, 'Devengado',0,0,'C',1);
		$pdf->Cell(25,3, utf8_decode('Deducción'),0,1,'C',1);

		$pdf->SetFont('Arial','',9);
		$devenTot=0; $deducTot=0;$subTot1=0; $subTot2=0;
		for ($b1=1; $b1 <= $van; $b1++) { 
			$pdf->setX($mgIzq);
			$pdf->MultiCell(130,4, utf8_decode(${'concepto'.$b1}),0,'J');
			$pdf->Ln(-4);
			$pdf->setX($mgIzq+130);
			if(${'tipo_egreso'.$b1}==1 )
			{
				$pdf->Cell(25,4,${'montoRec'.$b1} ,0,0,'R');	
				$pdf->Cell(25,4,'0.00' ,0,1,'R');
				$devenTot=$devenTot+${'montoRec'.$b1};
			}
			if(${'tipo_egreso'.$b1}==2 )
			{
				$pdf->Cell(25,4,'0.00' ,0,0,'R');
				$pdf->Cell(25,4,${'montoRec'.$b1} ,0,1,'R');	
				$deducTot=$deducTot+${'montoRec'.$b1};
			}
			$subTot1=$subTot1+${'montoRec'.$b1};
		}
		if ($van<10) {
			for ($c=$van; $c <10 ; $c++) { 
				$pdf->setX($mgIzq+130);
				$pdf->Cell(25,4,'0.00' ,0,0,'R');
				$pdf->Cell(25,4,'0.00' ,0,1,'R');
			}
		}
		$pdf->setX($mgIzq);
		$pdf->Cell(105,4,'He recibido la cantidad especificada correspondiente al periodo',0,0,'L');
		$pdf->Cell(25,4,'Sub Totales -->' ,0,0,'R');
		$pdf->Cell(25,4,number_format($devenTot,2,'.',',') ,0,0,'R',1);
		$pdf->Cell(25,4,number_format($deducTot,2,'.',',') ,0,1,'R',1);

		$pdf->setX($mgIzq);
		$pdf->Cell(105,4,'desde el: '.$desde.' hasta el: '.$hasta.' de acuerdo al detalle indicado',0,1,'L');

		$pdf->setX($mgIzq);
		$pdf->Cell(105,3,'Forma de Pago: '.$fpag,0,0,'L');
		$pdf->Cell(25,3,'Neto a Cobrar',0,0,'R');
		$pdf->Cell(25,3,number_format($devenTot-$deducTot,2,'.',','),0,1,'R');
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, 'Ref: '.$operacion ,0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(150,3, 'Banco: '.$banco ,0,1,'L');

		$pdf->Ln(6);
		$pdf->setX($mgIzq);
		$pdf->Cell(60,4,'___________________________',0,0,'L');
		$pdf->Cell(60,4,'___________________________',0,0,'L');
		$pdf->Cell(60,4,'___________________________',0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(60,4,'Recibi Conforme',0,0,'L');
		$pdf->Cell(60,4,'Elaborado Por',0,0,'L');
		$pdf->Cell(60,4,'Aprobado Por',0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(60,4, ucwords(strtolower($provedor)) ,0,0,'L');
		$pdf->Cell(60,4,$operador,0,0,'L');
		$pdf->Cell(60,4,$administra,0,1,'L');
		$pdf->setX($mgIzq);
		$pdf->Cell(60,4,$cedula ,0,1,'L');
		if($i==0)
		{
			$pdf->Cell(180,14, '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -' ,0,1,'C');
		}
	}
	if($status_egreso==2)
	{
		$pdf->Image('../../img/anulado2.png',10,10,180,120);
		$pdf->Image('../../img/anulado2.png',10,130,180,120);
	}
}
$pdf->Output('recibo.pdf','I');
$pdfdoc = $pdf->Output("", "S");
if($enviar=='1')
{
	include("../../../inicia.php");
  	require '../../include/PHPMailerMaster/src/PHPMailer.php';
  	require '../../include/PHPMailerMaster/src/Exception.php';
  	require '../../include/PHPMailerMaster/src/SMTP.php';
  	$ekks=utf8_decode(EKKS);
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	$email = htmlspecialchars($correo);
	try {
    $mail->Host = 'localhost';
    $mail->SMTPAuth = false;
    $mail->SMTPAutoTLS = false; 
    $mail->Port = 25;
    $mail->SMTPDebug = 0;
    $mail->Username = CORREOM;
    $mail->Password = CLAVEMAIL;
    $mail->setFrom(CORREOM,$ekks);  //de donde envia
    $mail->addAddress($correo,utf8_decode($provedor)); //Destinatario
    //$mail->addAddress('sisjch.tlf@gmail.com',$nombre); //Destinatario
    $mail->isHTML(true);
    $mail->Subject =  "Recibo de Pago ".$ekks ;
    $asunto='Pago por servicios prestados';
    $mimensaje='Estimado(a) '.$provedor.', reciba un cordial saludo de parte de nuestra institución, le hacemos llegar a usted en archivo adjunto a este correo, el detalle por el pago realizado.';

    $mensaje =  "<div style='float:left'><img src='https://".DOMINIO."/imagenes/logop.jpg' width='100' height='100'></div>";
    $mensaje .= "<div>".utf8_decode(NKXS)."<BR>";
    $mensaje .=  "<div style='font-size: 20px;'>".EKKS."</div>";
    $mensaje .=  "M.P.P.P.E.: ".CKLS." Rif.: " . RIFCOLM . "<BR>";
    $mensaje .=  "Tlf.: " . TELEMPM ." ". CIUDADM . " - " . ESTADOM."<br><BR></div>";
    $mensaje .= "<DIV align='right'>Fecha: ".strftime("%d de %B de %Y")."<br><BR></div>";
    $mensaje .= "Sr(a) : ".utf8_decode($provedor)."<br>";
    $mensaje .= "Cedula: " . $cedula . "<br><br>";
    $mensaje .= "<center><h2>".utf8_decode($asunto)."</h2></center><br>";
    $mensaje .= utf8_decode($mimensaje);
    $mensaje .= "<br><br><center>Atentamente.<br><br>";
    $mensaje .= utf8_decode("Dpto.de Administración</center><br><br><br><br>");
    $mensaje .= "Esta es una cuenta no monitoreada, por favor no responda este correo.";
    $mail->Body = $mensaje;
    
    $mail->AddStringAttachment($pdfdoc, 'recibo.pdf', 'base64', 'application/pdf');
    $mail->send();
    $manda++;
    $enviado++;
  } catch (Exception $e) 
  {
      echo "Error", $mail->ErrorInfo;
  }
  //$enviando++;
  $mail->ClearAddresses();
}
?>
