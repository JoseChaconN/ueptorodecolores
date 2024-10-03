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
	$repre_query=mysqli_query($link,"SELECT * FROM padres Where ced_papa = '$cedLimpio'"); 
	if(mysqli_num_rows($repre_query)>0){
		while($row=mysqli_fetch_array($repre_query))
	  	{
		    $nom_ape_papa=$row['nom_ape_papa'];
	        $dedicaPapa=$row['dedicaPapa'];
	        $tlf_hab_papa=$row['tlf_hab_papa'];
	        $tlf_cel_papa=$row['tlf_cel_papa'];
	        $dire_papa=$row['dire_papa'];
	        $ocupa_papa=$row['ocupa_papa'];
	        $lug_trab_papa=$row['lug_trab_papa'];
	  	}
		$json = ['isSuccessful' => TRUE , 'nombre' => $nom_ape_papa, 'dedica' => $dedicaPapa, 'tlf' => $tlf_hab_papa, 'celu' => $tlf_cel_papa, 'direcc' => $dire_papa, 'ocup' => $ocupa_papa, 'lugTra' => $lug_trab_papa] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json); ?>