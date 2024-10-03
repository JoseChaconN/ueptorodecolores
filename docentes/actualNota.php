<?php 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
include_once '../includes/funciones.php';
include_once '../conexion.php';
include_once '../inicia.php';
$link = Conectarse();

$tablaPeriodo=$_POST['tabla'];
$lap = $_POST['lapso'];
$ced_alu = $_POST['ced'];
$cod_materia = $_POST['mate'];
$nota = $_POST['nota'];
$corte = $_POST['corte'];
$seccion=$_POST['secci'];
$porce=$_POST['porce'];
$idAlumno=desencriptar($_POST['idAlu']);

if(!empty($ced_alu))
{
	if ($lap==3) {
		$periodo_query=mysqli_query($link,"SELECT fechaRF, fechaRev FROM periodos WHERE tablaPeriodo = '$tablaPeriodo' ");
		while($row=mysqli_fetch_array($periodo_query))
		{
			$fecRf=$row['fechaRF'];
			$fecRv=$row['fechaRev'];
		}
	}
	
	$cortes_query=mysqli_query($link,"SELECT id,nota11,nota21,nota31,nota41,nota51,nota12,nota22,nota32,nota42,nota52,nota13,nota23,nota33,nota43,nota53 FROM cortes".$tablaPeriodo." WHERE ced_alu='$ced_alu' and cod_materia='$cod_materia' ");
	if(mysqli_num_rows($cortes_query) > 0)
	{
		while($row=mysqli_fetch_array($cortes_query)) 
    {
			$id=$row['id'];
			$nota1 = ($corte=='1') ? $nota : $row['nota1'.$lap] ;
			$nota2 = ($corte=='2') ? $nota : $row['nota2'.$lap] ;
			$nota3 = ($corte=='3') ? $nota : $row['nota3'.$lap] ;
			$nota4 = ($corte=='4') ? $nota : $row['nota4'.$lap] ;
			$nota5 = ($corte=='5') ? $nota : $row['nota5'.$lap] ;

			//$=$row[''];
		}
		$porce_query=mysqli_query($link,"SELECT porcentaje11,porcentaje21,porcentaje31,porcentaje41,porcentaje51,porcentaje12,porcentaje22,porcentaje32,porcentaje42,porcentaje52,porcentaje13,porcentaje23,porcentaje33,porcentaje43,porcentaje53 FROM cortes1".$tablaPeriodo." WHERE cod_materia='$cod_materia' and cod_seccion='$seccion' ");
		while($row=mysqli_fetch_array($porce_query)) 
    {
    	$porce1 = ($corte=='1') ? $porce : $row['porcentaje1'.$lap] ;
    	$porce2 = ($corte=='2') ? $porce : $row['porcentaje2'.$lap] ;
    	$porce3 = ($corte=='3') ? $porce : $row['porcentaje3'.$lap] ;
    	$porce4 = ($corte=='4') ? $porce : $row['porcentaje4'.$lap] ;
    	$porce5 = ($corte=='5') ? $porce : $row['porcentaje5'.$lap] ;
    }
    $nota1=($nota1*$porce1)/100;
    $nota2=($nota2*$porce2)/100;
    $nota3=($nota3*$porce3)/100;
    $nota4=($nota4*$porce4)/100;
    $nota5=($nota5*$porce5)/100;
    $defi=$nota1+$nota2+$nota3+$nota4+$nota5;

		mysqli_query($link,"UPDATE cortes".$tablaPeriodo." SET nota$corte$lap='$nota' WHERE id='$id'") or die ("NO SE ACTUALIZO".mysqli_error());	

		$codMat=ltrim(substr($cod_materia,2,2),0);
		
		mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET nota$lap$codMat='$defi' WHERE idAlumno='$idAlumno'") or die ("NO SE ACTUALIZO".mysqli_error());	
		if ($lap==3) {
			$tbnotas=mysqli_query($link,"SELECT idMatri, grado, nota1".$codMat." as not1, nota2".$codMat." as not2, nota3".$codMat." as not3 FROM matri".$tablaPeriodo." WHERE idAlumno = '$idAlumno'");
			while($row=mysqli_fetch_array($tbnotas))
			{
				$grado=$row['grado'];
				$not1=$row['not1'];
				$not2=$row['not2'];
				$not3=$row['not3'];
				$defi=($not1+$not2+$not3)/3;
				$total=round($defi, 0, PHP_ROUND_HALF_UP);
			}
			$tipo = ($total<9.5) ? 'R' : 'F' ;
			$mesEv = ($total<9.5) ? substr($fecRv, 5,2) : substr($fecRf, 5,2) ;
			$anoEv = ($total<9.5) ? substr($fecRv, 0,4) : substr($fecRf, 0,4) ;
			$notas_query=mysqli_query($link,"SELECT notas, tipos, meses, anos, planteles FROM certifi WHERE idAlumno = '$idAlumno' and idGrado='$grado' ");
			if(mysqli_num_rows($notas_query)>0)
			{	
				foreach ($notas_query as $key => $value) {
					$notasArray = json_decode($value['notas'],TRUE);
					$tiposArray = json_decode($value['tipos'],TRUE);
					$mesesArray = json_decode($value['meses'],TRUE);
					$anosArray = json_decode($value['anos'],TRUE);
					$plantelArray = json_decode($value['planteles'],TRUE);
					
					$notasArray[$codMat]=($total<9.5) ? '  ' : $total ;
					$tiposArray[$codMat]=$tipo;
					$mesesArray[$codMat]=intval($mesEv);
					$anosArray[$codMat]=$anoEv;
					$plantelArray[$codMat]=CERTIPLAN;
				}
				$notasArray = json_encode($notasArray);
				$tiposArray = json_encode($tiposArray);
				$mesesArray = json_encode($mesesArray);
				$anosArray = json_encode($anosArray);
				$plantelArray = json_encode($plantelArray);
				mysqli_query($link,"UPDATE certifi SET notas='$notasArray', tipos='$tiposArray', meses='$mesesArray', anos='$anosArray', planteles='$plantelArray' WHERE idAlumno='$idAlumno' and idGrado='$grado' ") or die("No actualizo NOTAS".mysqli_error($link));
			}else
			{
				$notasArray=[''];
				$tiposArray=[''];
				$mesesArray=[''];
				$anosArray=[''];
				$plantelArray=[''];
				for ($is=0; $is <= 12; $is++) 
				{ 
					$notasArray[$is]='PE';
					$tiposArray[$is]=$tipo;
					$mesesArray[$is]=intval($mesEv);
					$anosArray[$is]=$anoEv;
					$plantelArray[$is]=CERTIPLAN;
				}
				
				$notasArray[$codMat]=($total<9.5) ? '  ' : $total ;
				$tiposArray[$codMat]=$tipo;
				$mesesArray[$codMat]=intval($mesEv);
				$anosArray[$codMat]=$anoEv;
				$plantelArray[$codMat]=CERTIPLAN;
				
				$notasArray = json_encode($notasArray);
				$tiposArray = json_encode($tiposArray);
				$mesesArray = json_encode($mesesArray);
				$anosArray = json_encode($anosArray);
				$plantelArray = json_encode($plantelArray);
				mysqli_query($link,"INSERT INTO certifi (idAlumno, idGrado, notas, tipos, meses, anos, planteles) VALUES ('$idAlumno', '$grado', '$notasArray', '$tiposArray','$mesesArray', '$anosArray', '$plantelArray')") or die ("NO creo Arrays".mysqli_error($link));
			}
		}
	}else
	{
		mysqli_query($link,"INSERT INTO cortes".$tablaPeriodo." (ced_alu,nota$corte$lap, cod_materia) VALUES ('$ced_alu','$nota','$cod_materia')") or die ("NO SE GUARDO".mysqli_error());

		$nota=($nota*$porce)/100;
		$codMat=ltrim(substr($cod_materia,2,2),0);
		mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET nota$lap$codMat='$nota' WHERE idAlumno='$idAlumno'") or die ("NO SE ACTUALIZO".mysqli_error());	
	}
	
	
	$json = ['isSuccessful' => TRUE] ;
	
}else
{
	$json = ['isSuccessful' => FALSE];
}
echo json_encode($json);
 ?>