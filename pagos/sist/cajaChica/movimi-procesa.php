<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$link = Conectarse();
$id_caja_chica=$_POST['id'];
$tipo_mov=$_POST['tipo'];
$concepto=$_POST['conce'];
$monto=$_POST['monto'];
$usuario=$_POST['usua'];
$moneda=$_POST['moned'];
$tipo_operacion=$_POST['opera'];
$disponible=$_POST['disp'];
if($id_caja_chica>0){
	if ($tipo_operacion==2 && $monto>$disponible) {
		$json = ['isSuccessful' => FALSE,'tipo'=>$tipo_operacion,'dispo'=>$disponible ] ;	
	}else
	{
		if(!empty($concepto) && $monto>0 )
		{
			mysqli_query($link,"INSERT INTO caja_chica_movi (id_caja_chica,tipo_mov,concepto,monto,usuario,moneda,fecha_mov,tipo_operacion) VALUES ('$id_caja_chica','$tipo_mov','$concepto','$monto','$usuario','$moneda','$hoy','$tipo_operacion' )") or die ("NO GUARDO CAJA CHICA".mysqli_error($link));	
			$json = ['isSuccessful' => TRUE ] ;
		}else
		{
			$json = ['isSuccessful' => FALSE ] ;
		}
	}
}else{
	$json = ['isSuccessful' => FALSE ] ;
}
echo json_encode($json);
?>