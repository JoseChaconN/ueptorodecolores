<?php 
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
    include_once ('../include/sesion.php');
}else
{
    include_once ('../../../conexion.php');
    include_once("../../include/funciones.php");
    include_once("../../../inicia.php");
}
$tablaSesion=$_SESSION['tablaPeriodo'];
$link = Conectarse();
$cedula = $_POST['cedula'];
$proxPeriodo=PROXANOE;
if($cedula>0)
{
	$a=' .-*/+,abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$b=strlen($a);
	$cedLimpio=str_replace(".","",$cedula);
	for ($i=0; $i < $b ; $i++) 
	{ 
		$cedLimpio=str_replace($a[$i],"",$cedLimpio);
	}
}else
{
	$cedLimpio=$_POST['cedNom'];	
}
if($cedLimpio>0)
{
	$ultimo_periodo_query = mysqli_query($link,"SELECT tablaPeriodo, nombre_periodo FROM periodos WHERE nombre_periodo='$proxPeriodo' ");
    $row2=mysqli_fetch_array($ultimo_periodo_query);
	$ultimo_periodo=$row2['nombre_periodo'];
	$tablaPeriodo=$row2['tablaPeriodo'];
	$inscrito=2;
	$alumno_query=mysqli_query($link,"SELECT idAlum, cedula, apellido, nombre, ruta FROM alumcer Where cedula = '$cedLimpio' and cargo IS NULL "); 
	if(mysqli_num_rows($alumno_query)>0)
	{
		while($row=mysqli_fetch_array($alumno_query))
	  	{
		    $idAlum=$row['idAlum'];
		    $estudiante=$row['apellido'].' '.$row['nombre'];
	        $fotoAlum = '../../../fotoalu/'.$row['ruta'];
	        if(!file_exists($fotoAlum)){$fotoAlum='../../../fotoalu/'.$row['ruta'];}
	  	}
	  	$matri_query=mysqli_query($link,"SELECT A.grado,A.idSeccion,B.nombreGrado,C.nombre as nomSec FROM notaprimaria".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C Where A.idAlumno = '$idAlum' and A.grado=B.grado and A.idSeccion=C.id ");
	  	$gradoNuevo='<option value="">Seleccione...</option>';
	  	if(mysqli_num_rows($matri_query)>0)
		{
			$row3=mysqli_fetch_array($matri_query);
			$cursando = ($row3['nombreGrado']).' "'.$row3['nomSec'].'"';
			$sec=$row3['idSeccion'];
			$grado_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." WHERE grado>60");
			while($row4=mysqli_fetch_array($grado_query))
		  	{
		  		$gradoNuevo.='<option value="'.$row4['grado'].'">'.($row4['nombreGrado']).'</option>';
		  	}
		  	//echo $gradoNuevo;
		}else{
			$matri_query=mysqli_query($link,"SELECT A.grado,A.idSeccion,B.nombreGrado,C.nombre as nomSec FROM matri".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C Where A.idAlumno = '$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
			if(mysqli_num_rows($matri_query)>0){
				$row3=mysqli_fetch_array($matri_query);
				$cursando = ($row3['nombreGrado']).' "'.$row3['nomSec'].'"';
				$sec=$row3['idSeccion'];
				$grado_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." WHERE grado<60");
				while($row4=mysqli_fetch_array($grado_query))
			  	{
			  		$gradoNuevo.='<option value="'.$row4['grado'].'">'.$row4['nombreGrado'].'</option>';
			  	}
			}else{
				$cursando='';
			}
		}
	  	/*$periodo_query=mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos ORDER BY id_periodo"); 
	  	$opcion=''; $perinuevos='';
	  	while($row=mysqli_fetch_array($periodo_query))
	  	{
	  		$tablaPeriodo=$row['tablaPeriodo'];
	  		$nombre_periodo=$row['nombre_periodo'];
	  		$matri_query=mysqli_query($link,"SELECT A.grado,A.idSeccion,B.nombreGrado,C.nombre as nomSec FROM matri".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C Where A.idAlumno = '$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
	  		if(mysqli_num_rows($matri_query) > 0)
			{
				while($row2=mysqli_fetch_array($matri_query))
		  		{
		  			$cursando = utf8_encode($row2['nombreGrado']).' "'.$row2['nomSec'].'"';
		  			$grado=$row2['grado'];
		  			$seccion=$row2['idSeccion'];
		  			$selected = ($ultima_tabla==$tablaPeriodo) ? 'selected' : '' ;
		  			$opcion.='<option '.$selected.' value="'.$tablaPeriodo.'/'.$nombre_periodo.'/'.$grado.'/'.$seccion.'">'.$cursando.' / '.$nombre_periodo.'</option>';
		  			if($nombre_periodo==$ultimo_periodo){$inscrito=1;}
		  		}
		  		
			}else
			{
				$matri_query=mysqli_query($link,"SELECT A.grado,A.idSeccion,B.nombreGrado,C.nombre as nomSec FROM notaprimaria".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C Where A.idAlumno = '$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
				$pasa=1;
				while($row2=mysqli_fetch_array($matri_query))
		  		{
		  			$cursando = utf8_encode($row2['nombreGrado']).' "'.$row2['nomSec'].'"';
		  			$grado=$row2['grado'];
		  			$seccion=$row2['idSeccion'];
		  			$pasa=2;
		  			$selected = ($ultima_tabla==$tablaPeriodo) ? 'selected' : '' ;
		  			$opcion.='<option '.$selected.' value="'.$tablaPeriodo.'/'.$nombre_periodo.'/'.$grado.'/'.$seccion.'">'.$cursando.' / '.$nombre_periodo.'</option>';
		  			if($nombre_periodo==$ultimo_periodo){$inscrito=1;}
		  		}
		  		if($pasa==1)
		  		{
		  			$perinuevos.='<option value="'.$tablaPeriodo.'">'.$nombre_periodo.'</option>';
		  		}
			}
	  		
	  		
	  	}*/
	  	$idAlum=encriptar($idAlum);
	  	$json = ['isSuccessful' => TRUE ,'ced'=>$cedLimpio, 'id' => $idAlum, 'estudia' => $estudiante, 'foto' => $fotoAlum, 'grados' => $gradoNuevo, 'cursa'=>$cursando,'secci'=>$sec,'tabla'=>$tablaPeriodo ] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
 ?>