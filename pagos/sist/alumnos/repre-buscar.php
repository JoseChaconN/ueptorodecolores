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
$cedula = $_POST['ced'];

if(!empty($cedula))
{
	$alumno_query=mysqli_query($link,"SELECT representante, direccion, correo, telefono, tlf_celu,ruta FROM represe Where cedula = '$cedula' "); 
	if(mysqli_num_rows($alumno_query)>0)
	{
		while($row=mysqli_fetch_array($alumno_query))
	  	{
	  		$repr = $row['representante'];  
	        $dire=$row['direccion'];
	        $corr = $row['correo'];
	        $telf = $row['telefono'];
	        $celu = $row['tlf_celu']; 
	        $ruta = '../../../fotorep/'.$row['ruta'] ;
	  	}
	  	$json = ['isSuccessful' => TRUE , 'repr'=>$repr,'dire'=>$dire, 'corr'=>$corr, 'telf' => $telf, 'celu'=>$celu, 'foto'=>$ruta ] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
 ?>