<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once("../../include/funciones.php");
}
$link = Conectarse();
$recibo=$_POST['recib'];
$tablaPeriodo=$_POST['tabla'];
$salio=$_POST['salio'];
$reciboTabla = ($salio==1) ? 'recibo' : 'recibo2' ;
$recibos_query = mysqli_query($link,"SELECT A.*,B.nombreUser,B.apellidoUser,C.nombrePago, D.nom_banco FROM pagos".$tablaPeriodo." A, user B,formas_pago C, bancos D WHERE A.$reciboTabla='$recibo' and A.emitidoPor=B.idUser and A.operacion=C.id and A.banco=D.cod_banco ");
$totalDivisa=0; $totalBs=0; $i=0;
while($row=mysqli_fetch_array($recibos_query))
{ 
	$fecha=date("d-m-Y", strtotime($row['fecha']));
	$nombrePago=$row['nombrePago'];
	$totalDivisa=$totalDivisa+$row['montoDolar'];
	$totalBs=$totalBs+$row['monto'];
	$montoTasa=$row['montoTasa'];
	$emitidoPor=$row['nombreUser'].' '.$row['apellidoUser'] ;
	$banco=$row['nom_banco'];
	$nrodeposito=$row['nrodeposito'];
	$comentario=$row['comentario'];
	$statusPago=$row['statusPago'];
	$options[$i]=['conce' => utf8_encode($row['concepto']),'bolivar'=>number_format($row['monto'],2,',','.'),'dolar' => number_format($row['montoDolar'],2,',','.')];
	$i++;
}
$totalDivisa=number_format($totalDivisa,2,',','.');
$totalBs=number_format($totalBs,2,',','.');
$reciboPrint=encriptar($recibo);
$json = ['isSuccessful' => true ,'fecha'=>$fecha ,'formaPag'=>$nombrePago, 'totalDolar'=>$totalDivisa, 'totalBs'=>$totalBs, 'tasa'=>$montoTasa, 'emitidoPor'=>$emitidoPor, 'banco'=>$banco, 'nrodeposito'=>$nrodeposito, 'comenta'=>$comentario, 'options'=>$options,'status'=>$statusPago,'recPrint'=>$reciboPrint ] ;

echo json_encode($json);
?>