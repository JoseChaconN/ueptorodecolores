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
$recibos_query = mysqli_query($link,"SELECT A.*,B.nombreUser,B.apellidoUser,C.nombrePago, D.nom_banco, E.nombre, E.apellido, F.tipo_egreso FROM egresos A, user B,formas_pago C, bancos D, alumcer E, concep_egresos F WHERE A.recibo='$recibo' and A.emitidoPor=B.idUser and A.operacion=C.id and A.banco=D.cod_banco and A.id_provee=E.idAlum and A.id_concepto = F.id_concepto ");
$totalDivisa=0; $totalBs=0; $i=0; $pagaDiv=0; $pagaBs=0; $restaDiv=0; $restaBs=0;
while($row=mysqli_fetch_array($recibos_query))
{ 
	$fecha=date("d-m-Y", strtotime($row['fecha_egreso']));
	$nombrePago=$row['nombre'].' '.$row['apellido'];
	$montoTasa=$row['tasaDolar'];
	$emitidoPor=$row['nombreUser'].' '.$row['apellidoUser'] ;
	$banco=$row['nom_banco'];
	$nrodeposito=$row['refePag'];
	$comentario=$row['comentario'];
	$statusPago=$row['status_egreso'];
	$operacion=$row['operacion'];
	$tipo_egreso=$row['tipo_egreso'];
	$monBs = ($operacion!=1) ? $row['montoBs'] : 0 ; 
	$monDiv = ($operacion==1) ? $row['montoDolar'] : 0 ; 
	if ($operacion==1 && $tipo_egreso==1) {
		$pagaDiv=$pagaDiv+$row['montoDolar'];
	}
	if ($operacion==1 && $tipo_egreso==2) {
		$restaDiv=$restaDiv+$row['montoDolar'];
	}
	if ($operacion!=1 && $tipo_egreso==1) {
		$pagaBs=$pagaBs+$row['montoBs'];
	}
	if ($operacion!=1 && $tipo_egreso==2) {
		$restaBs=$restaBs+$row['montoBs'];
	}
	$totalDivisa=$totalDivisa+$monDiv; 
	$totalBs=$totalBs+$monBs;
	$options[$i]=['conce' => ($row['concepto_pago']),'bolivar'=>number_format($monBs,2,',','.'),'dolar' => number_format($monDiv,2,',','.')];
	$i++;
}
$pagaDiv=$pagaDiv-$restaDiv;
$pagaBs=$pagaBs-$restaBs;
$totalDivisa=number_format($totalDivisa,2,',','.');
$totalBs=number_format($totalBs,2,',','.');
$reciboPrint=encriptar($recibo);
$json = ['isSuccessful' => true ,'fecha'=>$fecha ,'formaPag'=>$nombrePago, 'totalDolar'=>$totalDivisa, 'totalBs'=>$totalBs, 'tasa'=>$montoTasa, 'emitidoPor'=>$emitidoPor, 'banco'=>$banco, 'nrodeposito'=>$nrodeposito, 'comenta'=>$comentario, 'options'=>$options,'status'=>$statusPago,'recPrint'=>$reciboPrint, 'pagaDiv'=>number_format($pagaDiv,2,'.',','), 'pagaBs'=>number_format($pagaBs,2,'.',',') ] ;
echo json_encode($json);
?>