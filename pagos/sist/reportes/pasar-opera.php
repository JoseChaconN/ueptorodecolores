<?php
include_once "../include/header.php";
$link = Conectarse();
$opera_query = mysqli_query($link,"SELECT idAlum,banco,nrodeposito,operacion,fecha,emitidoPor,recibo,fechadepo FROM pagos2223 WHERE fechadepo!='0000-00-00' and (operacion=3 or operacion=4 or operacion=5) and statusPago=1  "); 
    
while ($row = mysqli_fetch_array($opera_query))
{
	$idAlum=$row['idAlum'];
	$banco=$row['banco'];
	$nrodeposito=$row['nrodeposito'];
	$operacion=$row['operacion'];
	$fecha=$row['fecha'];
	$emitidoPor=$row['emitidoPor'];
	$recibo=$row['recibo'];
	$fechadepo=$row['fechadepo'];
	// 3=Transf / 4=Debito / 5=Pag Movil
	if ($operacion=='3' ) {
		mysqli_query($link,"UPDATE ingresos SET fechaTransf='$fechadepo' WHERE id='$recibo' ") or die ("NO ACTUALIZO ".mysqli_error());
	}
	if ($operacion=='4' ) {
		mysqli_query($link,"UPDATE ingresos SET fechaDebito='$fechadepo' WHERE id='$recibo' ") or die ("NO ACTUALIZO ".mysqli_error());
	}
	if ($operacion=='5' ) {
		mysqli_query($link,"UPDATE ingresos SET fechaPagMovil='$fechadepo' WHERE id='$recibo' ") or die ("NO ACTUALIZO ".mysqli_error());
	}

	//mysqli_query($link,"INSERT INTO operaciones (idAlum,recibo, banco, referencia, tipo, fecha, usuario, periodo ) VALUE ('$idAlum','$recibo', '$banco','$nrodeposito','$operacion','$fecha','$emitidoPor','2022-2023' ) ") or die ("NO SE CREO ".mysqli_error());
}
?>