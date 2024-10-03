<?php 
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once ('../../include/funciones.php');
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$link = Conectarse();
$idAlum = desencriptar($_POST['idAlum']);
$ced_alu = $_POST['cedula'];
$gra_alu = $_POST['grado'];
$sec_alu = $_POST['secci'];
$tablaPeriodo = $_POST['tabla'];
//echo $idAlum.'<br>'.$ced_alu.'<br>'.$gra_alu.'<br>'.$sec_alu.'<br>'.$tablaPeriodo;
//$ = $_POST[''];
if($idAlum > 0)
{
	mysqli_query($link,"UPDATE alumcer SET grado='$gra_alu' WHERE idAlum = '$idAlum'");
	if($gra_alu>60){
		$matri_query=mysqli_query($link,"SELECT desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,morosida FROM notaprimaria".$tablaPeriodo." Where idAlumno = '$idAlum' ");
		while($row=mysqli_fetch_array($matri_query))
		{ 
			$totalPeriodo=$row['totalPeriodo'];
			$pagado=$row['pagado'];
			$fechaIngreso=$row['fechaIngreso'];
			$morosida=$row['morosida'];
			for ($i=1; $i <=13 ; $i++) { 
				${'desc'.$i}=$row['desc'.$i];	
			}
		}
		//echo $totalPeriodo.'<br>'.$pagado.'<br>'.$fechaIngreso.'<br>'.$morosida.'<br>'.$tablaPeriodo;
		mysqli_query($link,"INSERT INTO matri".$tablaPeriodo." (ced_alu, idAlumno, grado, idSeccion, creado, statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,escola,morosida ) VALUE ('$ced_alu','$idAlum','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado','$fechaIngreso','1','$morosida' ) ") or die ("NO SE CREO1 ".mysqli_error());
		mysqli_query($link,"DELETE FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlum'") or die ("NO SE BORRO ".mysqli_error());
	}else{
		$matri_query=mysqli_query($link,"SELECT desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,morosida FROM matri".$tablaPeriodo." Where idAlumno = '$idAlum' ");
		while($row=mysqli_fetch_array($matri_query))
		{
			$totalPeriodo=$row['totalPeriodo'];
			$pagado=$row['pagado'];
			$fechaIngreso=$row['fechaIngreso']; 
			$morosida=$row['morosida'];
			for ($i=1; $i <=13 ; $i++) { 
				${'desc'.$i}=$row['desc'.$i];	
			}
		}
		//echo $totalPeriodo.'<br>'.$pagado.'<br>'.$fechaIngreso.'<br>'.$morosida.'<br>'.$tablaPeriodo;
		mysqli_query($link,"INSERT INTO notaprimaria".$tablaPeriodo." (ced_alu, idAlumno, grado, idSeccion, creado, statusAlum, desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10, desc11, desc12, desc13,totalPeriodo,pagado,fechaIngreso,escola,morosida ) VALUE ('$ced_alu','$idAlum','$gra_alu','$sec_alu','$hoy','1', '$desc1', '$desc2', '$desc3', '$desc4', '$desc5', '$desc6', '$desc7', '$desc8', '$desc9', '$desc10', '$desc11', '$desc12', '$desc13','$totalPeriodo','$pagado','$fachaIngreso','1','$morosida' ) ") or die ("NO SE CREO1 ".mysqli_error());		
		mysqli_query($link,"DELETE FROM matri".$tablaPeriodo." WHERE idAlumno='$idAlum'") or die ("NO SE BORRO ".mysqli_error());
	}
	$json = ['isSuccessful' => TRUE ] ;
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>