<?php 
session_start();
if(!isset($_SESSION['usuario']) && !isset($_SESSION['password']))
{ ?>
  <script type='text/javascript'>                                
        window.location='../index.php';
  </script><?php
} 
include_once '../inicia.php';
include_once '../conexion.php';
include_once '../includes/funciones.php';
$link = Conectarse();
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$link = Conectarse();
$lapso=$_GET['lapso'];
$id_gra=desencriptar($_GET['grado']);
$id_sec=desencriptar($_GET['secc']);
$id_mat=desencriptar($_GET['mate']);
$nomb_Pro=$_GET['prof'];
$nomb_Mat=$_GET['nmate'];
$nomb_Gra=($_GET['ngra']);
$nomb_Sec=$_GET['nsec'];
$matCursa=intval(substr($id_mat, 2,2));
if($lapso=='1'){ $nomlapso='Primero';}
if($lapso=='2'){ $nomlapso='Segundo';}
if($lapso=='3'){ $nomlapso='Tercero';}
$periAct_query=mysqli_query($link,"SELECT B.id_periodo, B.nombre_periodo, B.tablaPeriodo FROM periodoactivo A, periodos B where B.tablaPeriodo='$tablaPeriodo'"); 
while($row=mysqli_fetch_array($periAct_query))
{
    $nombrePeriodo=$row['nombre_periodo'];
    $tablaPeriodo=trim($row['tablaPeriodo']);
}
$porce_query=mysqli_query($link,"SELECT * FROM cortes1".$tablaPeriodo." WHERE cod_materia='$id_mat' and cod_seccion='$id_sec'"); 
$porce1=''; $porce2=''; $porce3=''; $porce4=''; $porce5='';
$porc1=''; $porc2=''; $porc3=''; $porc4=''; $porc5='';
$fecha1=''; $fecha2=''; $fecha3=''; $fecha4='';$fecha5='';

while($row=mysqli_fetch_array($porce_query))
{
	$porce1 = ($row['porcentaje1'.$lapso]>1) ? $row['porcentaje1'.$lapso] : '0' ;
	$porce2 = ($row['porcentaje2'.$lapso]>1) ? $row['porcentaje2'.$lapso] : '0' ;
	$porce3 = ($row['porcentaje3'.$lapso]>1) ? $row['porcentaje3'.$lapso] : '0' ;
	$porce4 = ($row['porcentaje4'.$lapso]>1) ? $row['porcentaje4'.$lapso] : '0' ;
	$porce5 = ($row['porcentaje5'.$lapso]>1) ? $row['porcentaje5'.$lapso] : '0' ;
	$obser1=$row['obser1'.$lapso];
    $obser2=$row['obser2'.$lapso];
    $obser3=$row['obser3'.$lapso];
    $obser4=$row['obser4'.$lapso];
    $obser5=$row['obser5'.$lapso];
	$fecha1 = (empty($row['fecha1'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha1'.$lapso])) ;
	$fecha2 = (empty($row['fecha2'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha2'.$lapso])) ;
	$fecha3 = (empty($row['fecha3'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha3'.$lapso])) ;
	$fecha4 = (empty($row['fecha4'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha4'.$lapso])) ;
	$fecha5 = (empty($row['fecha5'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha5'.$lapso])) ;
}
$alumnos_query=mysqli_query($link,"SELECT A.*, B.nacion, B.cedula, B.apellido, B.nombre FROM matri".$tablaPeriodo." A, alumcer B where A.statusAlum='1' and A.idAlumno=B.idAlum and A.grado='$id_gra' and A.idSeccion='$id_sec' and IF(A.escola='2',A.mat".$matCursa."='X',A.grado='$id_gra') ORDER BY B.apellido"); 

header('Content-type: application/vnd.ms-excel;charset=utf-8');
header("Content-Disposition: attachment; filename=Estrategias de ".$id_gra.$nomb_Sec." ".$nomb_Mat.".xls");
header("Pragma: no-cache");
header("Expires: 0");
/*Se construye una tabla HTML*/
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
	<head><meta http-equiv="Content-type" content="text/html;charset=utf-8" /></head>
	<style type="text/css">
		.porci{ width: 60px;}
		.datos{ text-align: center; }
	</style>
	<body>
		<div class="col-xs-12">
			<table class="table table-bordered table-hover" border="0">
				<thead>
					<tr>
						<th colspan="2" align="left"><?= NKXS.' '.EKKS ?></th>
						<th colspan="5" align="left"><?= $nomb_Gra.' '.$nomb_Sec ?></th>
					</tr>
					<tr>
						<th colspan="2" align="left">Fecha <?= date('d/m/Y'); ?></th>
						<th colspan="5" align="left">Lapso <?= $nomlapso ?></th>
					</tr>
					<tr>
						<th colspan="2" align="left">Corte de Notas Periodo <?= $nombrePeriodo ?></th>
						<th colspan="5" align="left">Docente <?= $nomb_Pro ?></th>
					</tr>
					<tr>
						<th colspan="2"></th>
						<th colspan="5" align="left">Materia <?= $nomb_Mat ?></th>
					</tr>
					<tr>
						<th colspan="2"></th>
						<th colspan="5" align="left">Codigo Materia <?= $id_mat ?></th>
					</tr>
				</thead>
			</table>
			<table  border="1">
				<thead>
					<tr style="background-color: rgb(58, 85, 134); color: white;">
						<th colspan="2">Estrategia</th>
						<th>Fecha</th>
						<th>Ponderación</th>
					</tr><?php 
					for ($i=1; $i <6 ; $i++) { ?>
						<tr>
							<th colspan="2"><?= ${'obser'.$i} ?></th>
							<th><?= ${'fecha'.$i} ?></th>
							<th><?= ${'porce'.$i}.' %' ?></th>
						</tr><?php 
					}?>
				</thead>
			</table>
			<table border="1" >
				<thead >
					<tr style="background-color: rgb(58, 85, 134); color: white;" align="center">
						<td>Cedula</td>
						<td>Estudiante</td>
						<td>Nota 1</td>
						<td>Nota 2</td>
						<td>&nbsp;&nbsp;&nbsp;Nota 3&nbsp;&nbsp;&nbsp;</td>
						<td>&nbsp;&nbsp;&nbsp;Nota 4&nbsp;&nbsp;&nbsp;   </td>
						<td>&nbsp;&nbsp;&nbsp;Nota 5&nbsp;&nbsp;&nbsp;</td>
					</tr>
				</thead>
				<tbody><?php 
					while($row=mysqli_fetch_array($alumnos_query))
					{
						$nacion=$row['nacion'].'-'.$row['cedula'];
						$alumno=$row['apellido'].' '.$row['nombre'];
						$ced_alu=$row['cedula'];
						$corte_query=mysqli_query($link,"SELECT nota1".$lapso." as nota1, nota2".$lapso." as nota2, nota3".$lapso." as nota3, nota4".$lapso." as nota4, nota5".$lapso." as nota5, nota6".$lapso." as nota6 FROM cortes".$tablaPeriodo." WHERE ced_alu='$ced_alu' and cod_materia='$id_mat'"); 
						$nota1=''; $nota2=''; $nota3=''; $nota4=''; $nota5='';?>
						<tr>
							<td><?= $ced_alu ?></td>
							<td><?= $alumno ?></td>							
						<?php
						if(mysqli_num_rows($corte_query) > 0){
							while($row2=mysqli_fetch_array($corte_query))
							{
								$nota1=$row2['nota1'];
								$nota2=$row2['nota2'];
								$nota3=$row2['nota3'];
								$nota4=$row2['nota4'];
								$nota5=$row2['nota5'];
								?>
								<td style="background-color: #EAF2F8;" align="center"><?= $nota1 ?></td>
								<td style="background-color: #E8F8F5;" align="center"><?= $nota2 ?></td>
								<td style="background-color: #FEF5E7;" align="center"><?= $nota3 ?></td>
								<td style="background-color: #EBEDEF;" align="center"><?= $nota4 ?></td>
								<td style="background-color: #F9EBEA;" align="center"><?= $nota5 ?></td>
								<?php
							}
						}else{?>
							<td style="background-color: #EAF2F8;"></td>
							<td style="background-color: #E8F8F5;"></td>
							<td style="background-color: #FEF5E7;"></td>
							<td style="background-color: #EBEDEF;"></td>
							<td style="background-color: #F9EBEA;"></td>
							<?php
						}?>
						</tr><?php
					} ?>
				</tbody>
			</table>
		</div>
		<h5></h5>
	</body>
</html>