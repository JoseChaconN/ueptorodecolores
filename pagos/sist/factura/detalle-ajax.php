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
$idAlum=desencriptar($_POST['idAlum']);
$pagado=$_POST['pagado'];
$tablaPeriodo=$_POST['tablaFactura'];
$grado=$_POST['grado'];
if ($grado<61) {
	$alumno_query = mysqli_query($link,"SELECT grado,desc1,desc2,desc3,desc4,desc5,desc6,desc7,desc8,desc9,desc10,desc11,desc12,desc13 FROM notaprimaria".$tablaPeriodo." WHERE idAlumno ='$idAlum' ");
}else
{
	$alumno_query = mysqli_query($link,"SELECT grado,desc1,desc2,desc3,desc4,desc5,desc6,desc7,desc8,desc9,desc10,desc11,desc12,desc13 FROM matri".$tablaPeriodo." WHERE idAlumno ='$idAlum' ");
}
$totDesc=0;
while ($row = mysqli_fetch_array($alumno_query))
{
	$grado=$row['grado'];
	for ($i=1; $i < 14; $i++) { 
		${'desc'.$i}=$row['desc'.$i];
		$totDesc=$totDesc+$row['desc'.$i];
	}
}
$total_pagado = $_POST['pagado'];
$montos_query = mysqli_query($link,"SELECT monto,mes,fecha_vence FROM montos".$tablaPeriodo." WHERE id_grado ='$grado' ORDER BY id_tabla "); 
$va=0;
$vas=0;
while ($row = mysqli_fetch_array($montos_query)){
	$va++;
	$pagado=$pagado-($row['monto']-${'desc'.$va});
	if($pagado<=0)
	{
		$fec=$row['fecha_vence'];
		if(($row['monto']-${'desc'.$va})>0)
		{
			$monMes=$row['monto']-${'desc'.$va};
			if ($pagado<=0) {
				$mens=$pagado*-1;
			}
			if (($pagado*-1)>($row['monto']-${'desc'.$va}) || $pagado>0)
			{
				$mens=$row['monto']-${'desc'.$va};
			}
			$array_conceptos[]=['mes' => $row['mes'] , 'monto' => $mens, 'fech'=>$fec, 'monMes'=>$monMes ];
			$mes=$mens*-1;
			if ($mes==0) {
				unset($array_conceptos[$vas]);
			}
			$vas++;
		}
	}else
	{
		//$pagado=$pagado-$row['monto']-${'desc'.$va};
	}
}
$montoDolar=0;
for ($a=1; $a < 11 ; $a++) { 
	$conceptos_resp[$a]='';
	${'montoDolar'.$a} = (isset($_POST['montoDolar'.$a])) ? $_POST['montoDolar'.$a] : 0 ;
} 
for ($i=0; $i < 11 ; $i++) { 
	if(!empty($_POST['afecta'.$i]) && $_POST['afecta'.$i] == 'S' && ${'montoDolar'.$i} > 0){
		$montoDolar=${'montoDolar'.$i};
		foreach ($array_conceptos as $key => $value) {
			$mes=substr($value['mes'],0,3);
			$anio=substr($value['fech'],2,2);
			$monMes=$value['monMes'];
			#$value['monto']= VALOR MENSUALIDAD;
			if($montoDolar > 0 && $montoDolar >= $value['monto'] ){	
				$montoDolar = $montoDolar - $value['monto'];
				$conceptos_resp[$i].='Pag.'.$mes.'/'.$anio.', ';
				unset($array_conceptos[$key]);
			}else if($montoDolar > 0 && $montoDolar < $value['monto']){
				if(($value['monto']-$montoDolar)>=$monMes)
				{
					$conceptos_resp[$i].='';	
				}else
				{
					$monX=$value['monto']-$montoDolar;
					if(number_format($monX,2)<number_format($monMes,2))
					{
						$conceptos_resp[$i].='(Ab.'.$mes.'/'.$anio.'.R. '.number_format($value['monto']-$montoDolar,2,'.',',').')';	
					}
				}
				$montoDolar = $montoDolar - $value['monto'];
				$array_conceptos[$key]['monto'] = $montoDolar*-1;#SE MODIFICA EN EL ARRAY DE CONCEPTOS EL VALOR DE LA MENSUALDAD POR EL RESTANTE.
			}else{
				break;
			}
		}
	}
}
$json = ['isSuccessful' => TRUE,'detalle'=>$conceptos_resp,'pagado'=>$pagado];
echo json_encode($json);
?>