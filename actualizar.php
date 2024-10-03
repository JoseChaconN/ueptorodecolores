<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
include_once ("conexion.php");
include_once ("inicia.php");
include_once("includes/funciones.php");
////////////////////DATOS DEL ALUMNO/////////////////////
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$hoy = date("Y-m-d H:i:s");
$idAlum=desencriptar($_POST['id']);
//echo $idAlum;
$nac_alu=$_POST["nac_alu"];
$ced_alu=$_POST["ced_alu"];
$miUsuario=$_POST["loginUser"];
$ape_alu=strtolower($_POST['ape_alu']);
$ape_alu=str_replace('á', 'A', $ape_alu);
$ape_alu=str_replace('é', 'E', $ape_alu);
$ape_alu=str_replace('í', 'I', $ape_alu);
$ape_alu=str_replace('ó', 'O', $ape_alu);
$ape_alu=str_replace('ú', 'U', $ape_alu);
$ape_alu=str_replace('ñ', 'Ñ', $ape_alu);
$ape_alu=strtoupper($ape_alu);

$nom_alu=strtolower($_POST['nom_alu']);
$nom_alu=str_replace('á', 'A', $nom_alu);
$nom_alu=str_replace('é', 'E', $nom_alu);
$nom_alu=str_replace('í', 'I', $nom_alu);
$nom_alu=str_replace('ó', 'O', $nom_alu);
$nom_alu=str_replace('ú', 'U', $nom_alu);
$nom_alu=str_replace('ñ', 'Ñ', $nom_alu);
$nom_alu=strtoupper($nom_alu);

$sex_alu=$_POST["sex_alu"];
$fna_alu=$_POST["fna_alu"];
$loc_alu=$_POST["loc_alu"];
$muni_alu=$_POST["muni_alu"];
$edo_alu=$_POST["edo_alu"];
$pai_alu=strtoupper($_POST["pai_alu"]);
$tlf_alu=$_POST["tlf_alu"];
$dir_alu=$_POST["dir_alu"];
$cla_alu=$_POST["cla_alu"];
$mai_alu=$_POST["mai_alu"];
$escolaridad=$_POST["escolaridad"];
$peso=$_POST["peso_alu"];
$talla=$_POST["talla_alu"];
//$=$_POST[""];

$foto_alu = (empty($_FILES['foto_alu']['tmp_name'])) ? '' : addslashes(file_get_contents($_FILES['foto_alu']['tmp_name']));	
$nombrearchivo = $_FILES["foto_alu"]["name"];
$nombreruta = $_FILES["foto_alu"]["tmp_name"];
$ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
$formatos = array('.jpg','.jpeg','.png' );
$ruta = "$ced_alu$ext";
$guardaRutaAlu='fotoalu/'.$ruta;	
if(!empty($foto_alu) && in_array($ext, $formatos))
{
	move_uploaded_file($nombreruta, $guardaRutaAlu);
}

////////////////////DATOS DEL REPRESENTANTE/////////////////////
$ced_rep=$_POST["ced_rep"];
$nom_rep=$_POST["nom_rep"];
$par_rep = $_POST["par_rep"];	
$mai_rep=$_POST["mai_rep"];
$fnac_repre=$_POST["fna_rep"];
$dir_rep=$_POST["dir_rep"];
$ocup_rep=$_POST['ocup_rep'];
$lug_trab_rep=$_POST['lug_trab_rep'];
$tlf_hab_rep=$_POST["tlf_hab_rep"];
$tlf_cel_rep=$_POST["tlf_cel_rep"];
$foto_rep = (empty($_FILES['foto_rep']['tmp_name'])) ? '' : addslashes(file_get_contents($_FILES['foto_rep']['tmp_name']));	
$nombrearchivo1 = $_FILES["foto_rep"]["name"];
$nombreruta1 = $_FILES["foto_rep"]["tmp_name"];
$ext1 = substr($nombrearchivo1, strrpos($nombrearchivo1, '.'));
$formatos1 = array('.jpg','.jpeg','.png' );
$ruta1 = "$ced_rep$ext1";
$guardaRutaRep='fotorep/'.$ruta1;
if(in_array($ext1, $formatos1) && !empty($foto_rep))
{
	move_uploaded_file($nombreruta1, $guardaRutaRep);
}
////////////// DATOS DE MAMA //////////////////////
$ced_mama=$_POST["ced_mama"];
$nom_mama=$_POST["nom_mama"];
$fnac_mama=$_POST["fecNac_mama"];
$tlf_cel_mama=$_POST["tlf_cel_mama"];
$tlf_hab_mama=$_POST["tlf_hab_mama"];
$lugar_nac_mama=$_POST["nacio_mama"];
$ocup_mama=$_POST["ocup_mama"];
$tlf_ofi_mama=$_POST["tlf_ofi_mama"];
$dir_mama=$_POST["dir_mama"];
$lug_trab_mama=$_POST["lug_trab_mama"];
$estudio_mama=$_POST['est_mama'];

$nom_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $nom_mama);
$mai_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $mai_mama);
$dir_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $dir_mama);
$ocup_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $ocup_mama);
$lug_trab_mama= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $lug_trab_mama);

////////////// DATOS DE PAPA //////////////////////
$ced_papa=$_POST["ced_papa"];
$nom_papa=$_POST["nom_papa"];
$fnac_papa=$_POST["fecNac_papa"];
$tlf_cel_papa=$_POST["tlf_cel_papa"];
$tlf_hab_papa=$_POST["tlf_hab_papa"];
$lugar_nac_papa=$_POST["nacio_papa"];
$ocup_papa=$_POST["ocup_papa"];
$tlf_ofi_papa=$_POST["tlf_ofi_papa"];
$dir_papa=$_POST["dir_papa"];
$lug_trab_papa=$_POST["lug_trab_papa"];
$estudio_papa=$_POST['est_papa'];

$nom_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $nom_papa);
$mai_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $mai_papa);
$dir_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $dir_papa);
$ocup_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $ocup_papa);
$lug_trab_papa= str_replace(array('"',"'",':','/','sudo su','*','[',']','{','}','#','_'), '', $lug_trab_papa);

//////// DATOS DE CONTACTO ///////////////////
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
$link = conectarse();
$query = mysqli_query($link,"SELECT editable,ced_rep FROM alumcer WHERE idAlum = '$idAlum'");
while($row=mysqli_fetch_array($query))
{
	$editable=$row['editable'];
	$ced_rep_1=$row['ced_rep'];
}
if ($ced_rep <> $ced_rep_1)
{
	$repnuevo = mysqli_query($link,"SELECT cedula FROM represe WHERE cedula = '$ced_rep'");
	if(mysqli_num_rows($repnuevo) == 0 )
	{
		mysqli_query($link,"INSERT INTO represe (cedula, representante, correo, direccion, telefono, ruta, fnac_repre, tlf_celu, lug_trabaj, ocupacion) VALUES ('$ced_rep', '$nom_rep', '$mai_rep', '$dir_rep', '$tlf_hab_rep', '$ruta1', '$fnac_repre', '$tlf_cel_rep', '$lug_trab_rep', '$ocup_rep')") or die ("NO GUARDO REPRESENTANTE".mysqli_error($link));
	}
	else
	{
		mysqli_query($link,"UPDATE represe SET representante='$nom_rep', correo='$mai_rep', direccion='$dir_rep', telefono='$tlf_hab_rep', fnac_repre='$fnac_repre', tlf_celu='$tlf_cel_rep', lug_trabaj='$lug_trab_rep', ocupacion='$ocup_rep' WHERE cedula='$ced_rep'") or die ("NO ACTUALIZO EL REPRESENTANTE".mysqli_error($link));
		if (!empty($foto_rep) && in_array($ext1, $formatos1))
		{
			mysqli_query($link,"UPDATE represe SET ruta='$ruta1' WHERE cedula = '$ced_rep'");
		}
	}
	mysqli_query($link,"UPDATE alumcer SET ced_rep = '$ced_rep' WHERE idAlum = '$idAlum'");
}else
{
	mysqli_query($link,"UPDATE represe SET representante='$nom_rep', correo='$mai_rep', direccion='$dir_rep', telefono='$tlf_hab_rep', fnac_repre='$fnac_repre', tlf_celu='$tlf_cel_rep', lug_trabaj='$lug_trab_rep', ocupacion='$ocup_rep' WHERE cedula='$ced_rep_1'") or die ("NO ACTUALIZO EL REPRESENTANTE".mysqli_error($link));
	if (!empty($foto_rep) && in_array($ext1, $formatos1))
	{
		mysqli_query($link,"UPDATE represe SET ruta='$ruta1' WHERE cedula = '$ced_rep_1'");
	}
}
$mama_query = mysqli_query($link,"SELECT ced_mama FROM madres WHERE ced_mama = '$ced_mama'");
if(mysqli_num_rows($mama_query) > 0)
{
	mysqli_query($link,"UPDATE madres SET ced_mama='$ced_mama', nom_ape_mama='$nom_mama', fnac_mama='$fnac_mama', dire_mama='$dir_mama', lug_trab_mama='$lug_trab_mama', ocupa_mama='$ocup_mama', tlf_cel_mama='$tlf_cel_mama', tlf_hab_mama='$tlf_hab_mama', lugar_nac_mama='$lugar_nac_mama', tlf_ofi_mama='$tlf_ofi_mama',estudio_mama='$estudio_mama' WHERE ced_mama = '$ced_mama' ") or die ("NO ACTUALIZO MADRES ".mysqli_error($link));	
}else
{
	mysqli_query($link,"INSERT INTO madres (ced_mama, nom_ape_mama, fnac_mama,  dire_mama, lug_trab_mama, ocupa_mama, tlf_cel_mama, tlf_hab_mama,lugar_nac_mama,tlf_ofi_mama,estudio_mama ) VALUES ('$ced_mama', '$nom_mama', '$fnac_mama', '$dir_mama', '$lug_trab_mama', '$ocup_mama', '$tlf_cel_mama', '$tlf_hab_mama','$lugar_nac_mama','$tlf_ofi_mama','$estudio_mama' )") or die ("NO AGREGO MAMA".mysqli_error());
}
$papa_query = mysqli_query($link,"SELECT ced_papa FROM padres WHERE ced_papa = '$ced_papa'");
if(mysqli_num_rows($papa_query) > 0)
{
	mysqli_query($link,"UPDATE padres SET ced_papa='$ced_papa', nom_ape_papa='$nom_papa', fnac_papa='$fnac_papa', dire_papa='$dir_papa', lug_trab_papa='$lug_trab_papa', ocupa_papa='$ocup_papa', tlf_cel_papa='$tlf_cel_papa', tlf_hab_papa='$tlf_hab_papa', lugar_nac_papa='$lugar_nac_papa', tlf_ofi_papa='$tlf_ofi_papa',estudio_papa='$estudio_papa' WHERE ced_papa = '$ced_papa' ") or die ("NO ACTUALIZO PADRES ".mysqli_error($link));	
}else
{
	mysqli_query($link,"INSERT INTO padres (ced_papa, nom_ape_papa, fnac_papa,  dire_papa, lug_trab_papa, ocupa_papa, tlf_cel_papa, tlf_hab_papa,lugar_nac_papa,tlf_ofi_papa,estudio_papa ) VALUES ('$ced_papa', '$nom_papa', '$fnac_papa', '$dir_papa', '$lug_trab_papa', '$ocup_papa', '$tlf_cel_papa', '$tlf_hab_papa','$lugar_nac_papa','$tlf_ofi_papa','$estudio_papa')") or die ("NO GUARDO PAPA".mysqli_error());
}
$medico_query = mysqli_query($link,"SELECT id FROM ficha_medica WHERE idAlum = '$idAlum'");
if(mysqli_num_rows($medico_query) > 0)
{
	mysqli_query($link,"UPDATE ficha_medica SET edo_civil_padres='$edo_civil_padres', nro_herma='$nro_herma', edad_efinteres='$edad_efinteres', ducha_solo='$ducha_solo', moja_cama='$moja_cama', alergico='$alergico', defic_motora='$defic_motora', examen_motora='$examen_motora', accidentes='$accidentes', bronquiti='$bronquiti',hepatitis='$hepatitis',paperas='$paperas', asma='$asma',varicela='$varicela', resfrio='$resfrio',lateridad='$lateridad', es_medicado='$es_medicado', cardiologica='$cardiologica',respiratoria='$respiratoria', ve_bien='$ve_bien', lentes='$lentes',oye_bien='$oye_bien',audifono='$audifono',pediatra='$pediatra',clinica='$clinica',tlfClinica='$tlfClinica',sangre='$sangre',bsc='$bsc',polio='$polio',penta='$penta',anti_hepati='$anti_hepati',bacteriana='$bacteriana',triple_viral='$triple_viral',amarilla='$amarilla',doble_viral='$doble_viral',tetanico='$tetanico',difterico='$difterico',influenza='$influenza',otras='$otras'
	 WHERE idAlum = '$idAlum' ") or die ("NO ACTUALIZO Ficha Medica ".mysqli_error($link));	
}else
{
	mysqli_query($link,"INSERT INTO ficha_medica (idAlum,edo_civil_padres,nro_herma,edad_efinteres,ducha_solo,moja_cama,alergico,defic_motora,examen_motora,accidentes,bronquiti,hepatitis,paperas,asma,varicela,resfrio,lateridad,es_medicado,cardiologica,respiratoria,ve_bien,lentes,oye_bien,audifono,pediatra,clinica,tlfClinica,sangre,bsc,polio,penta,anti_hepati,bacteriana,triple_viral,amarilla,doble_viral,tetanico,difterico,influenza,otras ) VALUES ('$idAlum','$edo_civil_padres', '$nro_herma', '$edad_efinteres', '$ducha_solo', '$moja_cama', '$alergico', '$defic_motora', '$examen_motora','$accidentes','$bronquiti','$hepatitis','$paperas','$asma','$varicela','$resfrio','$lateridad','$es_medicado','$cardiologica','$respiratoria','$ve_bien','$lentes','$oye_bien','$audifono','$pediatra','$clinica','$tlfClinica','$sangre','$bsc','$polio','$penta','$anti_hepati','$bacteriana','$triple_viral','$amarilla','$doble_viral','$tetanico','$difterico','$influenza','$otras' )") or die ("NO AGREGO Ficha Medica".mysqli_error());
}
if($editable!='N')
{
	mysqli_query($link,"UPDATE alumcer SET nacion='$nac_alu', miUsuario='$miUsuario', apellido='$ape_alu', nombre='$nom_alu', sexo='$sex_alu', FechaNac='$fna_alu', locali='$loc_alu', estado='$edo_alu', pais='$pai_alu', direccion='$dir_alu', telefono='$tlf_alu', correo='$mai_alu', clave='$cla_alu', parentesco='$par_rep', municip='$muni_alu', nom_emerg_1='$nom_emerg_1', pare_emerg_1='$pare_emerg_1', tlf_emerg_hab_1='$tlf_emerg_hab_1', tlf_emerg_ofi_1='$tlf_emerg_ofi_1', tlf_emerg_cel_1='$tlf_emerg_cel_1', nom_emerg_2='$nom_emerg_2', pare_emerg_2='$pare_emerg_2', tlf_emerg_hab_2='$tlf_emerg_hab_2', tlf_emerg_ofi_2='$tlf_emerg_ofi_2', tlf_emerg_cel_2='$tlf_emerg_cel_2',ced_mama='$ced_mama',	ced_papa='$ced_papa',	peso='$peso',talla='$talla' WHERE idAlum = '$idAlum' ") or die ("NO ACTUALIZO ALUMNO SSSS1 ".mysqli_error($link));
}else
{
	mysqli_query($link,"UPDATE alumcer SET miUsuario='$miUsuario', direccion='$dir_alu', telefono='$tlf_alu', correo='$mai_alu', clave='$cla_alu', parentesco='$par_rep', nom_emerg_1='$nom_emerg_1', pare_emerg_1='$pare_emerg_1', tlf_emerg_hab_1='$tlf_emerg_hab_1', tlf_emerg_ofi_1='$tlf_emerg_ofi_1', tlf_emerg_cel_1='$tlf_emerg_cel_1', nom_emerg_2='$nom_emerg_2', pare_emerg_2='$pare_emerg_2', tlf_emerg_hab_2='$tlf_emerg_hab_2', tlf_emerg_ofi_2='$tlf_emerg_ofi_2', tlf_emerg_cel_2='$tlf_emerg_cel_2',ced_mama='$ced_mama',ced_papa='$ced_papa',	peso='$peso',talla='$talla' WHERE idAlum = '$idAlum' ") or die ("NO ACTUALIZO ALUMNO SSSS2 ".mysqli_error($link));
}
mysqli_query($link,"INSERT INTO actualiza_datos (idAlum,fecha ) VALUES ('$idAlum', '$hoy')") or die ("NO GUARDO Actualizar".mysqli_error());
if (!empty($foto_alu) && in_array($ext, $formatos))
{
	mysqli_query($link,"UPDATE alumcer SET ruta='$ruta' WHERE idAlum = '$idAlum'");
}
$_SESSION['fotoAlum']=$ruta;
$_SESSION['correo']=$mai_alu;
$_SESSION['ced_papa']=$ced_papa;
$_SESSION['ced_mama']=$ced_mama;
$_SESSION['nom_emerg_1']=$nom_emerg_1;
header("location:consulta.php?actual"); 

?> 
