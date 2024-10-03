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
	$repre_query=mysqli_query($link,"SELECT * FROM madres Where ced_mama = '$cedLimpio'"); 
	if(mysqli_num_rows($repre_query)>0){
		while($row=mysqli_fetch_array($repre_query))
	  	{
		    $nom_ape_mama=$row['nom_ape_mama'];
	        $dedicaMama=$row['dedicaMama'];
	        $tlf_hab_mama=$row['tlf_hab_mama'];
	        $tlf_cel_mama=$row['tlf_cel_mama'];
	        $dire_mama=$row['dire_mama'];
	        $ocupa_mama=$row['ocupa_mama'];
	        $lug_trab_mama=$row['lug_trab_mama'];
	  	}
		$json = ['isSuccessful' => TRUE , 'nombre' => $nom_ape_mama, 'dedica' => $dedicaMama, 'tlf' => $tlf_hab_mama, 'celu' => $tlf_cel_mama, 'direcc' => $dire_mama, 'ocup' => $ocupa_mama, 'lugTra' => $lug_trab_mama] ;
	}else{
		$json = ['isSuccessful' => FALSE];
	}
}
echo json_encode($json); ?>