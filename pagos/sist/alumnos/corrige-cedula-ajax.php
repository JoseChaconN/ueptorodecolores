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
$cedula_vie = $_POST['cedulaVie'];
$cedula = $_POST['cedula'];
$idAlum = desencriptar($_POST['idAlum']);
$usuario=$_SESSION['nombreUser'];
$existe_nueva_query = mysqli_query($link,"SELECT idAlum FROM alumcer WHERE cedula='$cedula' ");
if(mysqli_num_rows($existe_nueva_query) == 0)
{
	if(!empty($cedula) && $cedula!=$cedula_vie)
	{
		mysqli_query($link,"UPDATE alumcer SET cedula='$cedula' WHERE idAlum = '$idAlum'");
		$periodo_query=mysqli_query($link,"SELECT tablaPeriodo FROM  periodos "); 
		while($row=mysqli_fetch_array($periodo_query))
		{
			$tablaPeri=trim($row['tablaPeriodo']);
			mysqli_query($link,"UPDATE cortes".$tablaPeri." SET ced_alu='$cedula' WHERE ced_alu = '$cedula_vie'");
		    mysqli_query($link,"UPDATE matri".$tablaPeri." SET ced_alu='$cedula' WHERE idAlumno = '$idAlum'");
		    mysqli_query($link,"UPDATE notaprimaria".$tablaPeri." SET ced_alu='$cedula' WHERE idAlumno = '$idAlum'");
		}
		mysqli_query($link,"UPDATE equivalencia SET ced_alu='$cedula' WHERE idAlumno = '$idAlum'");
		mysqli_query($link,"UPDATE ficha_medica SET ced_alu='$cedula' WHERE ced_alu = '$cedula_vie'");
		mysqli_query($link,"UPDATE historial SET ced_alu='$cedula' WHERE ced_alu = '$cedula_vie'");
		mysqli_query($link,"UPDATE pagos SET ced_alu='$cedula' WHERE ced_alu='$cedula_vie'");
		mysqli_query($link,"UPDATE participan SET cedulaAlumno='$cedula' WHERE cedulaAlumno='$cedula_vie'");	
		mysqli_query($link,"UPDATE quedada SET ced_alu='$cedula' WHERE idAlumno = '$idAlum'");
		mysqli_query($link,"UPDATE votos SET cedulaAlumno='$cedula' WHERE cedulaAlumno='$cedula_vie'");

		mysqli_query($link,"INSERT INTO cambio_ced (cedula,cedula_vie,fecha,usuario,idAlum ) VALUE ('$cedula','$cedula_vie','$hoy','$usuario','$idAlum' ) ") or die ("NO SE CREO ".mysqli_error());
		$json = ['isSuccessful' => TRUE, 'id'=>$idAlum, 'ced'=>$cedula, 'vie'=>$cedula_vie ] ;
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