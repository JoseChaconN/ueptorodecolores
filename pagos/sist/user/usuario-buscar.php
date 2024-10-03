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
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$link = Conectarse();
$cedula = $_POST['ced'];
if(!empty($cedula))
{
	$user_query=mysqli_query($link,"SELECT * FROM user Where cedulaUser = '$cedula' "); 
	if(mysqli_num_rows($user_query)>0)
	{
		while($row=mysqli_fetch_array($user_query))
	  	{
	  		$idUser=encriptar($row['idUser']);
	  		$nacionUser=$row['nacionUser'];
		    $apelli=$row['apellidoUser'];
		    $nombre=$row['nombreUser'];
	        $clave = $row['claveUser'];
	        $fnac=$row['fechaNacUser'];
	        $carg=$row['cargoUser'];
	        $tlf=$row['telefonoUser'];
	        $mail=$row['emailUser'];
	        $dire=$row['direccionUser'];
	        $foto = '../img/'.$row['fotoUser'];
	  	}
	  	$json = ['isSuccessful' => TRUE , 'id' => $idUser, 'apelli' => $apelli, 'nombre' => $nombre, 'foto' => $foto, 'clave'=>$clave,'fnac'=>$fnac, 'cargo'=>$carg,'tlf'=>$tlf,'mail'=>$mail, 'dire'=>$dire,'nacion'=>$nacionUser ] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
 ?>