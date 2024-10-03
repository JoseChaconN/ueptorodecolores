<?php 
include_once 'conexion.php';
include_once 'includes/funciones.php';
$link = Conectarse();
$cedAlumno = $_POST['ced'];
if(!empty($cedAlumno))
{
	$alumno_query=mysqli_query($link,"SELECT A.idAlum, A.nacion, A.apellido, A.nombre, A.Periodo, A.grado, B.nombre as seccion FROM alumcer A, secciones B Where cedula = '$cedAlumno'"); 
	if(mysqli_num_rows($alumno_query)>0)
	{
		while($row=mysqli_fetch_array($alumno_query))
	  	{
		    $idAlum=encriptar($row['idAlum']);
		    $nacionAlum=$row['nacion'];
	        $apellidoAlum=$row['apellido'];
	        $nombreAlum=$row['nombre'];
	        $periodo=$row['Periodo'];
	        $grado=$row['grado'];
	        $seccion=$row['seccion'];
	  	}
	  	$periodo_query=mysqli_query($link,"SELECT tablaPeriodo FROM periodos Where nombre_periodo = '$periodo'"); 
	  	while($row=mysqli_fetch_array($periodo_query))
	  	{
	  		$tablaPeriodo=$row['tablaPeriodo'];
	  	}
	  	$grado_query=mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." Where grado = '$grado'"); 
	  	while($row=mysqli_fetch_array($grado_query))
	  	{
	  		$grado=($row['nombreGrado']);
	  	}

		$json = ['isSuccessful' => TRUE , 'idAl'=>$idAlum, 'nacio' => $nacionAlum, 'apelli' => $apellidoAlum, 'nombre' => $nombreAlum, 'periCur'=>$periodo, 'grad'=>$grado, 'secci'=>$seccion, 'tabla'=>$tablaPeriodo ] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
 ?>