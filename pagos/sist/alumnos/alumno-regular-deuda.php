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
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$link = Conectarse();
$idAlum = desencriptar($_POST['id']);
if(!empty($idAlum))
{
	
	$periodo_query=mysqli_query($link,"SELECT * FROM periodos ORDER BY id_periodo "); 
	$deuda=0;
  	while($row=mysqli_fetch_array($periodo_query))
  	{
  		$tabla=$row['tablaPeriodo'];
  		$periodo=$row['nombre_periodo'];
  		$matri_query=mysqli_query($link,"SELECT grado,desc1,desc2,desc3,desc4,desc5,desc6,desc7,desc8,desc9,desc10,desc11,desc12,desc13,suma_a_pagado FROM notaprimaria".$tabla." WHERE idAlumno='$idAlum' "); 
		if(mysqli_num_rows($matri_query) > 0)
		{
			$row2=mysqli_fetch_array($matri_query);
			$grado=$row2['grado'];
			$suma_a_pagado=$row2['suma_a_pagado'];
			for ($i=1; $i < 14; $i++) { 
				${'desc'.$i}=$row2['desc'.$i];
			}
			$monto_query=mysqli_query($link,"SELECT monto FROM montos".$tabla." WHERE id_grado='$grado' "); 
			$totalPeriodo=0;$van=0;
			while($row3=mysqli_fetch_array($monto_query))
			{
				$van++;
				$totalPeriodo=$totalPeriodo+($row3['monto']-${'desc'.$van});
			}
			$agosto_query = mysqli_query($link,"SELECT SUM(A.montoDolar) as monto FROM pagos".$tabla." A, conceptos B WHERE A.idAlum ='$idAlum' and A.id_concepto=B.id and B.agosto='S' and A.statusPago='1' GROUP BY A.recibo "); 
		    $agosto=0;
		    while ($row = mysqli_fetch_array($agosto_query))
		    {
		        $agosto=$agosto+$row['monto'];
		    }
			$pagos_query=mysqli_query($link,"SELECT A.montoDolar,B.afecta FROM pagos".$tabla." A, conceptos B WHERE A.idAlum='$idAlum' and A.statusPago='1' and A.id_concepto=B.id "); 
			$pagado=$suma_a_pagado;
			while($row4=mysqli_fetch_array($pagos_query))
			{
				$afecta=$row4['afecta'];
				if($afecta=='S')
				{ $pagado=$pagado+$row4['montoDolar']; }
			}
			$deuda=$totalPeriodo-($pagado+$agosto);
			if($deuda>0){break;}
		}
  	}
  	if($deuda>0)
  	{
  		$json = ['isSuccessful' => TRUE , 'id'=>$idAlum,'deuda'=>$deuda, 'periodoDeb'=>$periodo ] ;	
  	}else
  	{
 		$json = ['isSuccessful' => FALSE]; 		
  	}
	
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>