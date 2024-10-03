<?php 
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
include_once 'conexion.php';
include_once 'includes/funciones.php';
$link = Conectarse();
$idEleccion = desencriptar($_POST['id']);
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$cedula=$_SESSION['usuario'];
if(!empty($idEleccion))
{
	$votante_query=mysqli_query($link,"SELECT idVotos FROM votos Where idEleccion = '$idEleccion' and cedulaAlumno='$cedula' "); 
	$eleccion_query=mysqli_query($link,"SELECT nombreEleccion,fotoEleccion FROM eleccion Where idEleccion = '$idEleccion'"); 
	if(mysqli_num_rows($eleccion_query)>0)
	{
		$candi='';
		while($row=mysqli_fetch_array($eleccion_query))
	  	{
		    $nombreEleccion=$row['nombreEleccion'];
	        $fotoEleccion=$row['fotoEleccion'];
	        $candidatos_query=mysqli_query($link,"SELECT A.idParticipan, B.nombre,B.apellido,B.ruta,C.nombreGrado,D.nombre as nomSec FROM participan A, alumcer B, grado".$tablaPeriodo." C, secciones D Where A.idEleccion = '$idEleccion' and A.cedulaAlumno=B.cedula and B.grado=C.grado and B.seccion=D.id ORDER BY B.grado,B.seccion "); 
	        while($row=mysqli_fetch_array($candidatos_query))
	  		{
	  			$idParticipan=$row['idParticipan'];
	  			$nombre=$row['nombre'];
	  			$apellido=$row['apellido'];
	  			$ruta=$row['ruta'].'?'.time().mt_rand(0, 99999);
	  			$nombreGrado=($row['nombreGrado']);
	  			$nomSec=$row['nomSec'];
	  			$alumno=$nombre.' '.$apellido;
	  			$vari="'$alumno','$idParticipan','$idEleccion'";
	  			if(mysqli_num_rows($votante_query)==0)
				{
		  			$candi.='
		  			<div class="col-md-4 text-center">
		  				<button class="btn eligex" style="width: 80%; text-align:center;" onClick="cerrar('.$vari.')"><img src="fotoalu/'.$ruta.'" style="width: 100%; height:300px;" /></button><br>
		  				<p style="text-align:center;">'.$nombre.'<br>'.$apellido.'<br>'.$nombreGrado.' '.$nomSec.'</p>
		  			</div>';
		  		}else
		  		{
		  			$candi.='
		  			<div class="col-md-4 text-center">
		  				<button class="btn eligex" style="width: 80%; text-align:center;" disabled><img src="fotoalu/'.$ruta.'" style="width: 100%; height:300px;" /></button><br>
		  				<p style="text-align:center;">'.$nombre.'<br>'.$apellido.'<br>'.$nombreGrado.' '.$nomSec.'</p>
		  			</div>';
		  		}
	  		}
	        //$=$row[''];
	  	}
	}else{
		$json = ['isSuccessful' => FALSE];
	}
	if(mysqli_num_rows($votante_query)==0)
	{
		$json = ['isSuccessful' => TRUE , 'eleccion'=>$nombreEleccion, 'foto' => $fotoEleccion, 'candidato'=>$candi ] ;
	}else
	{
		$json = ['isSuccessful' => FALSE , 'eleccion'=>$nombreEleccion, 'foto' => $fotoEleccion, 'candidato'=>$candi ] ;
		//$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
 ?>