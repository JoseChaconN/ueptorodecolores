<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
include_once "../../include/funciones.php";
$link = Conectarse();
$id_concepto=$_POST['idC'];
$tablaPeriodo=$_POST['tab'];
$monto=$_POST['mont'];
$idAlum=desencriptar($_POST['idA']);
$conce_query = mysqli_query($link,"SELECT nro_pagos,concepto,monto FROM conceptos WHERE id='$id_concepto' ");
$row2=mysqli_fetch_array($conce_query);
$nro_pagos=$row2['nro_pagos'];
$concepto=$row2['concepto'];
$montoConcepto=$row2['monto'];
$pagos_query = mysqli_query($link,"SELECT SUM(montoDolar) as montoD FROM pagos".$tablaPeriodo." WHERE idAlum='$idAlum' and id_concepto='$id_concepto' and statusPago='1' ");
$pagado=0;
if(mysqli_num_rows($pagos_query) > 0)
{
	$row2=mysqli_fetch_array($pagos_query);
	$pagado=$row2['montoD'];
}
$pendiente=$montoConcepto-$pagado;
if($nro_pagos>0){$pagar=$monto-$pagado;}else{$pagar=$monto;}
if($monto>0 && $pagar<=0 ){$debe=2;}else{$debe=1;}
if($pagar<0){$pagar=0;}else{$pagar=number_format($pagar,2,'.',',');}
if($monto<$pendiente)
{
	$concepto='Ab. '.$concepto.' ( R.'.number_format($pendiente-$monto,2,'.',',').')';
}else
{
	$concepto='Paga '.$concepto;
	if ($nro_pagos>0) {
		$pagar=number_format($pendiente,2,'.',',');
	}
}	
$json = ['isSuccessful' => TRUE,'pagar'=>$pagar, 'debeMonto'=>$debe, 'nroPag'=>$nro_pagos, 'conceNue'=>$concepto];
echo json_encode($json);
?>