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
$cedula = $_POST['ced'];
if(!empty($cedula))
{
	$alumno_query=mysqli_query($link,"SELECT * FROM alumcer Where cedula = '$cedula' "); 
	if(mysqli_num_rows($alumno_query)>0)
	{
		while($row=mysqli_fetch_array($alumno_query))
	  	{
	  		$idAlum = encriptar($row['idAlum']);  
	  		$idAlumBusca = $row['idAlum'];
	  		$nacion = $row['nacion'];  
	        $clave = $row['clave'];
	        $nombre = ($row['nombre']);  
	        $apelli = ($row['apellido']);
	        $sexo = $row['sexo'];
	        $FechaNac = $row['FechaNac'];
	        $locali = $row['locali'];
	        $estado = $row['estado'];
	        $municip = $row['municip'];
	        $pais=$row['pais'];
	        $miUsuario = $row['miUsuario'];
	        $direccion=$row['direccion'];
	        $tlf = $row['telefono'];
	        $ced_rep = $row['ced_rep'];
	        $parentesco = $row['parentesco'];
	        $correo = $row['correo'];
	        $ruta = '../../../fotoalu/'.$row['ruta'];
	        $editable = $row['editable'];
	  	}
	  	$repre_query=mysqli_query($link,"SELECT * FROM represe Where cedula = '$ced_rep' ");
	  	while($row=mysqli_fetch_array($repre_query))
	  	{
	  		$nomRep=$row['representante'];
	  		$maiRep=$row['correo'];
	  		$dirRep=$row['direccion'];
	  		$tlfRep=$row['telefono'];
	  		$fotRep='../../../fotorep/'.$row['ruta'];
	  		$celRep=$row['tlf_celu'];
	  	}
	  	$periodo_query=mysqli_query($link,"SELECT * FROM periodos ORDER BY id_periodo "); 
	  	$deuda=0;
	  	while($row=mysqli_fetch_array($periodo_query))
	  	{
	  		$tabla=$row['tablaPeriodo'];
	  		$periodo=$row['nombre_periodo'];
	  		
	  		$matri_query=mysqli_query($link,"SELECT grado,desc1,desc2,desc3,desc4,desc5,desc6,desc7,desc8,desc9,desc10,desc11,desc12,desc13 FROM matri".$tabla." WHERE idAlumno='$idAlumBusca' "); 
	  		if(mysqli_num_rows($matri_query) > 0)
			{
				$row2=mysqli_fetch_array($matri_query);
				$grado=$row2['grado'];
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
				$agosto_query = mysqli_query($link,"SELECT SUM(A.montoDolar) as monto FROM pagos".$tabla." A, conceptos B WHERE A.idAlum ='$idAlumBusca' and A.id_concepto=B.id and B.agosto='S' and A.statusPago='1' GROUP BY A.recibo "); 
			    $agosto=0;
			    while ($row = mysqli_fetch_array($agosto_query))
			    {
			        $agosto=$agosto+$row['monto'];
			    }
				$pagos_query=mysqli_query($link,"SELECT A.montoDolar,B.afecta FROM pagos".$tabla." A, conceptos B WHERE A.idAlum='$idAlumBusca' and A.statusPago='1' and A.id_concepto=B.id "); 
				$pagado=0;
				while($row4=mysqli_fetch_array($pagos_query))
				{
					$afecta=$row4['afecta'];
					if($afecta=='S')
					{ $pagado=$pagado+$row4['montoDolar']; }
				}
				$deuda=$totalPeriodo-($pagado+$agosto);
				if($deuda>0){break;}
			}else
			{
				$matri_query=mysqli_query($link,"SELECT grado,desc1,desc2,desc3,desc4,desc5,desc6,desc7,desc8,desc9,desc10,desc11,desc12,desc13 FROM notaprimaria".$tabla." WHERE idAlumno='$idAlumBusca' "); 
				if(mysqli_num_rows($matri_query) > 0)
				{
					$row2=mysqli_fetch_array($matri_query);
					$grado=$row2['grado'];
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
					$agosto_query = mysqli_query($link,"SELECT SUM(A.montoDolar) as monto FROM pagos".$tabla." A, conceptos B WHERE A.idAlum ='$idAlumBusca' and A.id_concepto=B.id and B.agosto='S' and A.statusPago='1' GROUP BY A.recibo "); 
				    $agosto=0;
				    while ($row = mysqli_fetch_array($agosto_query))
				    {
				        $agosto=$agosto+$row['monto'];
				    }
					$pagos_query=mysqli_query($link,"SELECT A.montoDolar,B.afecta FROM pagos".$tabla." A, conceptos B WHERE A.idAlum='$idAlumBusca' and A.statusPago='1' and A.id_concepto=B.id "); 
					$pagado=0;
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
	  	}
	  	$json = ['isSuccessful' => TRUE , 'id'=>$idAlum,'nacion'=>$nacion,'clave'=>$clave,'nombre' => $nombre, 'apelli' => $apelli,'sexo'=>$sexo,'FechaNac'=>$FechaNac,'locali'=>$locali,'estado'=>$estado,'municip'=>$municip,'pais'=>$pais, 'miUsuario'=>$miUsuario, 'dire'=>$direccion,'tlf'=>$tlf,'ced_rep'=>$ced_rep,'parentesco'=>$parentesco,'correo'=>$correo,'ruta'=>$ruta,'editable'=>$editable,'nomRep'=>$nomRep,'maiRep'=>$maiRep,'dirRep'=>$dirRep,'tlfRep'=>$tlfRep,'fotRep'=>$fotRep,'celRep'=>$celRep,'deuda'=>$deuda, 'periodoDeb'=>$periodo ] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);
 ?>