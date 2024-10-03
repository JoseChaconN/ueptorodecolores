<?php 
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
    include ('../include/sesion.php');
}else
{
    include ('../../../conexion.php');
}
include_once '../../../inicia.php';
$periodo=$_GET['peri'];
$link = Conectarse();
$grado=$_GET['grado'];
$secci=$_GET['secc'];
$idUser=$_SESSION['idUser'];

$periAct_query=mysqli_query($link,"SELECT id_periodo, nombre_periodo, tablaPeriodo FROM  periodos where nombre_periodo='$periodo' "); 
while($row=mysqli_fetch_array($periAct_query))
{
    $idPeriodo=trim($row['id_periodo']);
    $nombrePeriodo=$row['nombre_periodo'];
    $tablaPeriodo=trim($row['tablaPeriodo']);
}
$montos_query=mysqli_query($link,"SELECT * FROM montos".$tablaPeriodo." where id_grado='$grado' "); 
$va=1;
while($row=mysqli_fetch_array($montos_query))
{
    ${'monto'.$va}=$row['monto'];
    ${'total'.$va}=0;
    $va++;
}
// primaria con seccion
if($grado<60 && $secci>0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM notaprimaria".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado and A.grado='$grado' and A.idSeccion='$secci' ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
if($grado<60 && $secci==0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM notaprimaria".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado and A.grado='$grado' ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
if($grado>60 && $secci>0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM matri".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado and A.grado='$grado' and A.idSeccion='$secci' ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
if($grado>60 && $secci==0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM matri".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado and A.grado='$grado' ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
$titulo='<th>Cedula</th><th>Apellido</th><th>Nombre<th>Grado</th><th>Secc</th></th><th>Insc.</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dic</th><th>Ene</th><th>Feb</th><th>Mar</th><th>Abr</th><th>May</th><th>Jun</th><th>Jul</th><th>Ago</th>';
header('Content-type: application/vnd.ms-excel;charset=utf-8');
header("Content-Disposition: attachment; filename=pagado_".$grado."_".$secci.".xls");
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
			<h3><?= NKXS.' '.EKKS ?><br>
			Fecha: <?= date('d/m/Y'); ?><br>
			Periodo: <?= $nombrePeriodo ?><br>
			Montos en divisa pagados por Inscripción y mensualidades</h3>
		</div>
		<div class="col-xs-12">
			<table class="table table-bordered table-hover" border="1" >
				<thead >
					<tr style="background-color: rgb(58, 85, 134); color: white;"><?php 
						echo $titulo;?>
					</tr>
				</thead>
				<tbody><?php 
					while($row=mysqli_fetch_array($alumnos_query))
					{
						$idAlum=$row['idAlumno'] ;
						$nomGra=$row['nomGra'];
						$suma_a_pagado=$row['suma_a_pagado'];
						$nomSec=$row['nombreSeccion'];
						$agosto_query = mysqli_query($link,"SELECT SUM(montoDolar) as agosto FROM pagos".$tablaPeriodo."  WHERE idAlum='$idAlum' and id_concepto=3 and statusPago=1 ");
						if(mysqli_num_rows($agosto_query) > 0)
				        {
				            $row3=mysqli_fetch_array($agosto_query);
				            $agosto=$row3['agosto'];	
				        }else{
				            $agosto=0;
				        }
						$pagos_query = mysqli_query($link,"SELECT SUM(montoDolar) as pagado FROM pagos".$tablaPeriodo."  WHERE idAlum='$idAlum' and (id_concepto=1 or id_concepto=2 or id_concepto=8) and statusPago=1 ");
				        if(mysqli_num_rows($pagos_query) > 0)
				        {
				            $row2=mysqli_fetch_array($pagos_query);
				            $pagado=$row2['pagado'];	
				        }else{
				            $pagado=0;
				        }
				        $pagado=$pagado+$suma_a_pagado;
				        for ($i2=1; $i2 <=13 ; $i2++) { 
				            ${'desc'.$i2}=$row['desc'.$i2];
				            if ($pagado>0) {
				                ${'pago'.$i2}=${'monto'.$i2}-$row['desc'.$i2]; 
				                if($pagado<${'pago'.$i2}){
				                    ${'pago'.$i2}=$pagado;
				                }
				                $pagado=$pagado-${'pago'.$i2};
				            }else{
				                ${'pago'.$i2}=0;
				            }
				            ${'total'.$i2}=number_format(${'total'.$i2}+${'pago'.$i2},2,',','.');
				            ${'pago'.$i2}=${'pago'.$i2};
				        }?>
						<tr>
							<td><?= $row['cedula'] ?></td>
							<td><?= $row['apellido'] ?></td>
							<td><?= $row['nombre'] ?></td>
							<td><?= $nomGra ?></td>
							<td><?= $nomSec ?></td>
							<td><?= number_format($pago1,2,'.',',') ?></td>
							<td><?= number_format($pago2,2,'.',',') ?></td>
							<td><?= number_format($pago3,2,'.',',') ?></td>
							<td><?= number_format($pago4,2,'.',',') ?></td>
							<td><?= number_format($pago5,2,'.',',') ?></td>
							<td><?= number_format($pago6,2,'.',',') ?></td>
							<td><?= number_format($pago7,2,'.',',') ?></td>
							<td><?= number_format($pago8,2,'.',',') ?></td>
							<td><?= number_format($pago9,2,'.',',') ?></td>
							<td><?= number_format($pago10,2,'.',',') ?></td>
							<td><?= number_format($pago11,2,'.',',') ?></td>
							<td><?= number_format($pago12,2,'.',',') ?></td>
							<td><?= number_format(($pago13+$agosto),2,'.',',') ?></td>
						</tr><?php
					} ?>
						<tr>
							<td></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td >Totales -----></td>
							<td ><?= number_format($total1,2,'.',',') ?></td>
							<td ><?= number_format($total2,2,'.',',') ?></td>
							<td ><?= number_format($total3,2,'.',',') ?></td>
							<td ><?= number_format($total4,2,'.',',') ?></td>
							<td ><?= number_format($total5,2,'.',',') ?></td>
							<td ><?= number_format($total6,2,'.',',') ?></td>
							<td ><?= number_format($total7,2,'.',',') ?></td>
							<td ><?= number_format($total8,2,'.',',') ?></td>
							<td ><?= number_format($total9,2,'.',',') ?></td>
							<td ><?= number_format($total10,2,'.',',') ?></td>
							<td ><?= number_format($total11,2,'.',',') ?></td>
							<td ><?= number_format($total12,2,'.',',') ?></td>
							<td ><?= number_format($total13,2,'.',',') ?></td>
							
						</tr>
				</tbody>
			</table>
		</div>
		<h5></h5>
	</body>
</html>