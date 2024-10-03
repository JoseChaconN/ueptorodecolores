<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include ('../include/sesion.php');
}else
{
	include ('../../../conexion.php');
}
include_once "../../include/funciones.php";
$link = Conectarse();
$recibo=$_POST['recibo'];
$reciboVer=$_POST['reciboVer'];
$reciboTabla = ($reciboVer==1) ? 'recibo' : 'recibo2' ;

$recibo_query = mysqli_query($link,"SELECT * FROM ingresos WHERE $reciboTabla ='$recibo' "); 
if(mysqli_num_rows($recibo_query) > 0)
{
	while ($row = mysqli_fetch_array($recibo_query))
	{
		$tablaPeriodo=$row['tabla'];
		$fecha=$row['fecha'];
	}
	$pagos_query = mysqli_query($link,"SELECT A.*,B.nombreUser,B.apellidoUser,C.nombrePago, D.nom_banco, E.nombre as nomAlum, E.apellido as apeAlum, E.cedula, E.idAlum, F.nombre_periodo FROM pagos".$tablaPeriodo." A, user B,formas_pago C, bancos D, alumcer E, periodos F WHERE A.$reciboTabla='$recibo' and A.emitidoPor=B.idUser and A.operacion=C.id and A.banco=D.cod_banco and A.idAlum=E.idAlum and F.tablaPeriodo='$tablaPeriodo' ");
	$totalDivisa=0; $totalBs=0; $i=0;
	while($row=mysqli_fetch_array($pagos_query))
	{
		$idAlum=$row['idAlum'];
		$alumno=$row['nomAlum'].' '.$row['apeAlum'];
		$cedula=$row['cedula'];
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
		$nombre_periodo=$row['nombre_periodo'];
		$fechaNulo=date("d-m-Y", strtotime($row['fechaNulo']));
		$usuarioNulo=$row['usuarioNulo'];
		$options[$i]=['conce' => utf8_encode($row['concepto']),'bolivar'=>number_format($row['monto'],2,'.',','),'dolar' => number_format($row['montoDolar'],2,'.',','),'banco'=>$banco,'nroDepo'=>$nrodeposito,'forma'=>$nombrePago];
		$i++;
		$primaria_query = mysqli_query($link,"SELECT grado FROM notaprimaria".$tablaPeriodo." WHERE idAlumno ='$idAlum' "); 
		$grado='';
		if(mysqli_num_rows($primaria_query) > 0)
		{
			$row2=mysqli_fetch_array($primaria_query);
			$grado=$row2['grado'];
		}else{
			$bachi_query = mysqli_query($link,"SELECT grado FROM matri".$tablaPeriodo." WHERE idAlumno ='$idAlum' "); 
			if(mysqli_num_rows($bachi_query) > 0)
			{
				$row2=mysqli_fetch_array($bachi_query);
				$grado=$row2['grado'];
			}	
		}
	}
	$anulo_query = mysqli_query($link,"SELECT nombreUser,apellidoUser FROM user WHERE idUser='$usuarioNulo' ");
	$quienAnulo='';
	while($row=mysqli_fetch_array($anulo_query))
	{
		$quienAnulo=$row['nombreUser'].' '.$row['apellidoUser'];
	}
	$recPrint=encriptar($recibo);
	$json = ['isSuccessful' => TRUE,
	'recibo'=>$recibo,
	'fecha'=>$fecha,
	'tasa'=>number_format($montoTasa,2,'.',','),
	'dolar'=>number_format($totalDivisa,2,'.',','),
	'bolivar'=>number_format($totalBs,2,'.',','),
	'usuario'=>$emitidoPor, 
	'options'=>$options,
	'alumno'=>$alumno,
	'cedula'=>$cedula,
	'periodo'=>$nombre_periodo,
	'tabla'=>$tablaPeriodo,
	'status'=>$statusPago,
	'comentario'=>$comentario,
	'fechaNulo'=>$fechaNulo,
	'quienAnul'=>$quienAnulo,
	'recPrint'=>$recPrint,
	'grado'=>$grado];
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);

?>