<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
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
$salida=$_POST['salida'];
$reciboTabla = ($salida==1) ? 'recibo' : 'recibo2' ;
$recibos_query = mysqli_query($link,"SELECT sum(A.monto) as total, A.fecha, A.emitidoPor,A.banco,A.comentario, B.abrev, C.nom_banco, A.nrodeposito FROM pagos".$tablaPeriodo." A, formas_pago B, bancos C WHERE A.$reciboTabla = '$recibo' and A.banco=C.cod_banco and A.operacion=B.id group by A.operacion");
$op=''; $nroOpe='';$banco='';
while($row=mysqli_fetch_array($recibos_query))
{ 
	$total=number_format($row['total'],2,',','.');
	$fechadepo=date("d-m-Y", strtotime($row['fecha']));
	$comentario=$row['comentario'];
	$op.=$row['abrev'].',';
	if($row['banco']>0){$nroOpe.=$row['nrodeposito'].', ' ; $banco.=$row['nom_banco'].', ';}
}

$buscarRecibo= mysqli_query($link,"SELECT id_concepto,concepto,monto FROM pagos".$tablaPeriodo." WHERE $reciboTabla = '$recibo' ");

$i=0;
while($row=mysqli_fetch_array($buscarRecibo))
{ 
	$options[$i]=['codigo' => $row['id_concepto'],'conce' => utf8_encode($row['concepto']),'mont'=>number_format($row['monto'],2,',','.')];
	$i++;
}
$reciboPrint=encriptar($recibo);
$json = ['isSuccessful' => true , 'total'=>$total , 'fechadepo'=>$fechadepo , 'operacion'=>$op , 'banco'=>$banco , 'nrodeposito'=>$nroOpe , 'options'=>$options, 'recPrin'=>$reciboPrint,'comenta'=>$comentario ] ;

echo json_encode($json);
?>