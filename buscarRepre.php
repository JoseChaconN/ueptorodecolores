<?php 
include_once 'conexion.php';
$link = Conectarse();
$ced_cliente = $_POST['ced'];
$a=' .-*/+,abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$b=strlen($a);
$cedLimpio=str_replace(".","",$ced_cliente);
for ($i=0; $i < $b ; $i++) 
{ 
	$cedLimpio=str_replace($a[$i],"",$cedLimpio);
}
if(!empty($ced_cliente))
{
	$repre_query=mysqli_query($link,"SELECT * FROM represe Where cedula = '$cedLimpio'"); 
	if(mysqli_num_rows($repre_query)>0){
		while($row=mysqli_fetch_array($repre_query))
	  	{
		    $nombreRepre=$row['representante'];
	        $fnac_repre=$row['fnac_repre'];
	        $telefono=$row['telefono'];
	        $celularRepre=$row['tlf_celu'];
	        $emailRepre=$row['correo'];
	        $direccRepre=$row['direccion'];
	        $ocupacion=$row['ocupacion'];
	        $lug_trabaj=$row['lug_trabaj'];
	        
	  	}
		$json = ['isSuccessful' => TRUE , 'nombre' => $nombreRepre, 'fnac' => $fnac_repre, 'tlf' => $telefono, 'celu' => $celularRepre, 'email' => $emailRepre, 'direcc' => $direccRepre, 'ocup' => $ocupacion, 'lugTra' => $lug_trabaj] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json);


 ?>