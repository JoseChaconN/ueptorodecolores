<?php
session_start();
include_once ("conexion.php");
include_once ("inicia.php");
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
////////////////////DATOS DEL ALUMNO/////////////////////
$nac_alu=$_POST["nac_alu"];
$ced_alu=$_POST['ced_alu'];
$miUsuario=$_POST["loginUser"];
$cla_alu=$_POST["cla_alu"];
$gra_alu=$_POST["gra_alu"];
$ape_alu=strtolower($_POST['ape_alu']);
$nom_alu=strtolower($_POST['nom_alu']);
$fna_alu=$_POST["fna_alu"];
$edo_alu=$_POST["edo_alu"];
$loc_alu=$_POST["loc_alu"];
$muni_alu=$_POST["muni_alu"];
$pai_alu=strtoupper($_POST["pai_alu"]);
$sex_alu=$_POST["sex_alu"];
$tlf_alu=$_POST["tlf_alu"];
$dir_alu=$_POST["dir_alu"];
$mai_alu=$_POST["mai_alu"];
$peso=$_POST["peso_alu"];
$talla=$_POST["talla_alu"];

$ced_rep=$_POST['ced_rep'];
$a=' .-*/+,abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$b=strlen($a);
$cedLimpio=str_replace(".","",$ced_alu);
$repreLimpio=str_replace(".","",$ced_rep);
for ($i=0; $i < $b ; $i++) 
{ 
	$cedLimpio=str_replace($a[$i],"",$cedLimpio);
	$repreLimpio=str_replace($a[$i],"",$repreLimpio);
}
$ced_alu=$cedLimpio;
$ced_rep=$repreLimpio;

$ape_alu=str_replace('á', 'A', $ape_alu);
$ape_alu=str_replace('é', 'E', $ape_alu);
$ape_alu=str_replace('í', 'I', $ape_alu);
$ape_alu=str_replace('ó', 'O', $ape_alu);
$ape_alu=str_replace('ú', 'U', $ape_alu);
$ape_alu=str_replace('ñ', 'Ñ', $ape_alu);
$ape_alu=strtoupper($ape_alu);

$nom_alu=str_replace('á', 'A', $nom_alu);
$nom_alu=str_replace('é', 'E', $nom_alu);
$nom_alu=str_replace('í', 'I', $nom_alu);
$nom_alu=str_replace('ó', 'O', $nom_alu);
$nom_alu=str_replace('ú', 'U', $nom_alu);
$nom_alu=str_replace('ñ', 'Ñ', $nom_alu);
$nom_alu=strtoupper($nom_alu);

$periodo=PROXANOE;
$ape_alu= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $ape_alu);
$nom_alu= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $nom_alu);
$pai_alu= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $pai_alu);
$dir_alu= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $dir_alu);

$foto_alu=addslashes(file_get_contents($_FILES['foto_alu']['tmp_name']));
$nombrearchivo = $_FILES["foto_alu"]["name"];
$nombreruta = $_FILES["foto_alu"]["tmp_name"];
$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
$formatos = array('.jpg','.jpeg','.png' );
$ruta = "$ced_alu$ext";
$guardaRutaAlu='fotoalu/'.$ruta;	
if(in_array($ext, $formatos))
{
	move_uploaded_file($nombreruta, $guardaRutaAlu);
}
////////////////////DATOS DEL REPRESENTANTE/////////////////////

//$ced_rep=$_POST["ced_rep"];
$nom_rep=$_POST["nom_rep"];
$par_rep = $_POST["par_rep"];
$fnac_repre=$_POST["fna_rep"];
$dir_rep=$_POST["dir_rep"];
$mai_rep=$_POST["mai_rep"];
$tlf_hab_rep=$_POST["tlf_hab_rep"];
$tlf_cel_rep=$_POST["tlf_cel_rep"];
$lug_trab_rep=$_POST['lug_trab_rep'];
$ocup_rep=$_POST['ocup_rep'];

$nom_rep= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $nom_rep);
$mai_rep= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $mai_rep);
$dir_rep= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $dir_rep);
$ocup_rep= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $ocup_rep);
$lug_trab_rep= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $lug_trab_rep);

$foto_rep = addslashes(file_get_contents($_FILES['foto_rep']['tmp_name']));
$nombrearchivo1 =  $_FILES["foto_rep"]["name"];
$nombreruta1 =  $_FILES["foto_rep"]["tmp_name"];
$ext1 =  substr($nombrearchivo1, strrpos($nombrearchivo1, '.'));
$formatos1 =  array('.jpg','.jpeg','.png' );
$ruta1 =  "$ced_rep$ext";
$guardaRutaRep='fotorep/'.$ruta1;
if(in_array($ext1, $formatos1))
{
	move_uploaded_file($nombreruta1, $guardaRutaRep);
}

///////////////////DATOS DE LA MADRE//////////////////////
$ced_mama=$_POST["ced_mama"];
$nom_mama=$_POST["nom_mama"];
$dir_mama=$_POST["dir_mama"];
$lug_trab_mama=$_POST["lug_trab_mama"];
$ocup_mama=$_POST["ocup_mama"];
$dedica_mama=$_POST["dedica_mama"]; // crear en tabla mama
$tlf_hab_mama=$_POST["tlf_hab_mama"];
$tlf_cel_mama=$_POST["tlf_cel_mama"];

$nom_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $nom_mama);
$dir_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $dir_mama);
$ocup_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $ocup_mama);
$lug_trab_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $lug_trab_mama);

///////////////////DATOS DEL PADRE//////////////////////
$ced_papa=$_POST["ced_papa"];
$nom_papa=$_POST["nom_papa"];
$dir_papa=$_POST["dir_papa"];
$lug_trab_papa=$_POST["lug_trab_papa"];
$ocup_papa=$_POST["ocup_papa"];
$dedica_papa=$_POST["dedica_papa"]; // crear en tabla papa
$tlf_hab_papa=$_POST["tlf_hab_papa"];
$tlf_cel_papa=$_POST["tlf_cel_papa"];

$nom_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $nom_papa);
$dir_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $dir_papa);
$ocup_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $ocup_papa);
$lug_trab_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $lug_trab_papa);

///////// DATOS DE CONTACTO ///////////////////
$nom_emerg_1=$_POST["nom_emerg_1"];
$pare_emerg_1=$_POST["pare_emerg_1"];
$tlf_emerg_hab_1=$_POST["tlf_emerg_hab_1"];
$tlf_emerg_ofi_1=$_POST["tlf_emerg_ofi_1"];
$tlf_emerg_cel_1=$_POST["tlf_emerg_cel_1"];

$nom_emerg_2=$_POST["nom_emerg_2"];
$pare_emerg_2=$_POST["pare_emerg_2"];
$tlf_emerg_hab_2=$_POST["tlf_emerg_hab_2"];
$tlf_emerg_ofi_2=$_POST["tlf_emerg_ofi_2"];
$tlf_emerg_cel_2=$_POST["tlf_emerg_cel_2"];
/////// Ficha Medica 
$edo_civil_padres=$_POST['edoCivPadr'];
$nro_herma=$_POST['hermanos'];
$edad_efinteres=$_POST['controlPopo'];
$ducha_solo=$_POST['vaSolo'];
$moja_cama=$_POST['mojaCama'];
$alergico=$_POST['alergias'];
$defic_motora=$_POST['difiMotora'];
$examen_motora=$_POST['examMotor'];
$accidentes=$_POST['sufrioAcci'];
$bronquiti=$_POST['padeBroqui'];
$hepatitis=$_POST['padeHepa'];
$paperas=$_POST['padePape'];
$asma=$_POST['padeAsma'];
$varicela=$_POST['padeVaric'];
$resfrio=$_POST['padeResfri'];
$lateridad=$_POST['mano'];
$es_medicado=$_POST['medicado'];
$cardiologica=$_POST['enfeCardio'];
$respiratoria=$_POST['enfeRespi'];
$ve_bien=$_POST['veBien'];
$lentes=$_POST['anteojos'];
$oye_bien=$_POST['audio'];
$audifono=$_POST['aparatos'];
$pediatra=$_POST['atenPedia'];
$clinica=$_POST['atenClini'];
$tlfClinica=$_POST['atenTlf'];
$sangre=$_POST['tipoSangre'];
$bsc=$_POST['vacu_bsc'];
$polio=$_POST['vacu_polio'];
$penta=$_POST['vacu_penta'];
$anti_hepati=$_POST['vacu_hepat'];
$bacteriana=$_POST['vacu_bacte'];
$triple_viral=$_POST['vacu_trival'];
$amarilla=$_POST['vacu_amari'];
$doble_viral=$_POST['vacu_doble'];
$tetanico=$_POST['vacu_teta'];
$difterico=$_POST['vacu_difte'];
$influenza=$_POST['vacu_influ'];
$otras=$_POST['vacu_otras'];
if (isset($ced_alu) && !empty($ced_alu) )
{
	$link = Conectarse();
	$resultado = mysqli_query($link,"SELECT cedula FROM alumcer WHERE cedula = '$ced_alu'");
	if (mysqli_num_rows($resultado)>0)
	{ ?>
		<script language="Javascript">  
  			if (screen.width<768) { window.location='indexm.php?exis'; } else
  			{ window.location='index.php?exis'; }
		</script><?php
	}
	else
	{
		$_SESSION['fotoAlum']=$ruta;
		$_SESSION['correo']=$mai_alu ;
		$_SESSION['ced_papa']=$ced_papa ;
		$_SESSION['ced_mama']=$ced_mama ;
		$_SESSION['nom_emerg_1']=$nom_emerg_1 ;
		if($ced_rep>0)
		{
			$repre = mysqli_query($link,"SELECT cedula FROM represe WHERE cedula = $ced_rep");
			if (mysqli_num_rows($repre)==0)
			{
				mysqli_query($link,"INSERT INTO represe (cedula, representante, correo, direccion, telefono, ruta, fnac_repre, tlf_celu,lug_trabaj, ocupacion) VALUES ('$ced_rep', '$nom_rep', '$mai_rep', '$dir_rep', '$tlf_hab_rep', '$ruta1', '$fnac_repre', '$tlf_cel_rep', '$lug_trab_rep', '$ocup_rep')") or die ("NO GUARDO REPRESENTANTE".mysqli_error());
			}
		}
		if($ced_mama>0)
		{
			$madre=mysqli_query($link,"SELECT ced_mama FROM madres WHERE ced_mama ='$ced_mama'");
			if(mysqli_num_rows($madre) == 0 )
			{
				mysqli_query($link,"INSERT INTO madres (ced_mama, nom_ape_mama, dedicaMama, dire_mama, lug_trab_mama, ocupa_mama, tlf_cel_mama, tlf_hab_mama ) VALUES 
					('$ced_mama', '$nom_mama', '$dedica_mama', '$dir_mama', '$lug_trab_mama', '$ocup_mama', '$tlf_cel_mama', '$tlf_hab_mama')") or die ("NO GUARDO MAMA".mysqli_error());
			}
		}
		if($ced_papa>0)
		{
			$padre=mysqli_query($link,"SELECT ced_papa FROM padres WHERE ced_papa ='$ced_papa'");
			if(mysqli_num_rows($padre) == 0 )
			{
				mysqli_query($link,"INSERT INTO padres (ced_papa, nom_ape_papa, dedicaPapa, dire_papa, lug_trab_papa, ocupa_papa, tlf_cel_papa, tlf_hab_papa ) VALUES ('$ced_papa', '$nom_papa', '$dedica_papa', '$dir_papa', '$lug_trab_papa', '$ocup_papa', '$tlf_cel_papa','$tlf_hab_papa')") or die ("NO GUARDO PAPA".mysqli_error());
			}
		}
		if($ced_alu>0)
		{
			mysqli_query($link,"INSERT INTO alumcer ( nacion, cedula, miUsuario, clave, grado, apellido, nombre, FechaNac, estado, locali, municip, pais, sexo, telefono, direccion, correo, ced_rep, Periodo, parentesco, ruta, seccion, ced_papa, ced_mama, nom_emerg_1, pare_emerg_1, tlf_emerg_hab_1, tlf_emerg_ofi_1, tlf_emerg_cel_1, nom_emerg_2, pare_emerg_2, tlf_emerg_hab_2, tlf_emerg_ofi_2, tlf_emerg_cel_2,peso,talla) VALUES ( '$nac_alu', '$ced_alu', '$miUsuario', '$cla_alu', '$gra_alu', '$ape_alu', '$nom_alu', '$fna_alu', '$edo_alu', '$loc_alu', '$muni_alu', '$pai_alu', '$sex_alu', '$tlf_alu', '$dir_alu', '$mai_alu', '$ced_rep', '$periodo', '$par_rep', '$ruta','99', '$ced_papa', '$ced_mama', '$nom_emerg_1', '$pare_emerg_1', '$tlf_emerg_hab_1', '$tlf_emerg_ofi_1', '$tlf_emerg_cel_1', '$nom_emerg_2', '$pare_emerg_2', '$tlf_emerg_hab_2', '$tlf_emerg_ofi_2', '$tlf_emerg_cel_2','$peso','$talla' )") or die ("NO GUARDO EL ALUMNO".mysqli_error($link));

			$nuevo_query = mysqli_query($link,"SELECT LAST_INSERT_ID(idAlum) as nuevoCodigo FROM alumcer order by idAlum desc limit 0,1  ");
			$row=mysqli_fetch_array($nuevo_query);
			$idAlum=$row['nuevoCodigo'];

			mysqli_query($link,"INSERT INTO ficha_medica (idAlum,edo_civil_padres,nro_herma,edad_efinteres,ducha_solo,moja_cama,alergico,defic_motora,examen_motora,accidentes,bronquiti,hepatitis,paperas,asma,varicela,resfrio,lateridad,es_medicado,cardiologica,respiratoria,ve_bien,lentes,oye_bien,audifono,pediatra,clinica,tlfClinica,sangre,bsc,polio,penta,anti_hepati,bacteriana,triple_viral,amarilla,doble_viral,tetanico,difterico,influenza,otras ) VALUES ('$idAlum','$edo_civil_padres', '$nro_herma', '$edad_efinteres', '$ducha_solo', '$moja_cama', '$alergico', '$defic_motora', '$examen_motora','$accidentes','$bronquiti','$hepatitis','$paperas','$asma','$varicela','$resfrio','$lateridad','$es_medicado','$cardiologica','$respiratoria','$ve_bien','$lentes','$oye_bien','$audifono','$pediatra','$clinica','$tlfClinica','$sangre','$bsc','$polio','$penta','$anti_hepati','$bacteriana','$triple_viral','$amarilla','$doble_viral','$tetanico','$difterico','$influenza','$otras' )") or die ("NO AGREGO Ficha Medica".mysqli_error());
		}
	} ?>
	<script language="Javascript">  
		if (screen.width<768) { window.location='loginNue.php?ingre=movil&ced=<?= $ced_alu ?>&accs=<?= $cla_alu ?>'; } else
		{ window.location='loginNue.php?ingre=desktop&ced=<?= $ced_alu ?>&accs=<?= $cla_alu ?>'; }
	</script><?php
}
else
{ ?>
	<script language="Javascript">  
		if (screen.width<768) { window.location='indexm.php'; } else
		{ window.location='index.php'; }
	</script><?php 
} ?>
