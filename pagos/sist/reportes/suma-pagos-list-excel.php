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
if ($grado>60) {
    $alumnos_query = mysqli_query($link,"SELECT A.idAlum,A.cedula,A.nombre,A.apellido,E.suma_a_pagado,E.pagado,B.nombreGrado,C.nombre as nomSec, E.grado FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." E WHERE E.grado='$grado' and E.idSeccion='$secci' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");
}
if ($grado>40 && $grado<60) {
    $alumnos_query = mysqli_query($link,"SELECT A.idAlum,A.cedula,A.nombre,A.apellido,E.suma_a_pagado,E.pagado,B.nombreGrado,C.nombre as nomSec, E.grado FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." E WHERE E.grado='$grado' and E.idSeccion='$secci' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");
}
if($grado==1)
{
    $alumnos_query = mysqli_query($link,"SELECT A.idAlum,A.cedula,A.nombre,A.apellido,E.suma_a_pagado,E.pagado,B.nombreGrado,C.nombre as nomSec, E.grado FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." E WHERE E.grado<60 and E.idSeccion='$secci' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");   
}
if($grado==2)
{
    $alumnos_query = mysqli_query($link,"SELECT A.idAlum,A.cedula,A.nombre,A.apellido,E.suma_a_pagado,E.pagado,B.nombreGrado,C.nombre as nomSec, E.grado FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." E WHERE E.grado>60 and E.idSeccion='$secci' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");
}

$titulo='<th>Cedula</th><th>Apellido</th><th>Nombre</th><th>Grado</th><th>Sumado</th><th>Pagado</th><th>Total</th>';
header('Content-type: application/vnd.ms-excel;charset=utf-8');
header("Content-Disposition: attachment; filename=suma_a_pagado_".$grado."_".$secci.".xls");
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
			Monto en divisa Sumado y Pagado por Inscripción y Mensualidad</h3>
		</div>
		<div class="col-xs-12">
			<table class="table table-bordered table-hover" border="1" >
				<thead >
					<tr style="background-color: rgb(58, 85, 134); color: white;"><?php 
						echo $titulo;?>
					</tr>
				</thead>
				<tbody><?php 
				$total1=0;$total2=0;$total3=0;
					while($row=mysqli_fetch_array($alumnos_query))
					{
						$ced_alu=$row['cedula'];
                        $nombre=$row['nombre'];
                        $apellido=$row['apellido'];
                        $nomgra=($row['nombreGrado']).' '.$row['nomSec'];
                        $pagado=$row['pagado'];
                        $suma_a_pagado=$row['suma_a_pagado'];
                        $idAlum=$row['idAlum'];
                        $grado=$row['grado'];
                        $total=$pagado+$suma_a_pagado;
                        $total1=$total1+$suma_a_pagado;
                        $total2=$total2+$pagado;
						?>
						<tr>
							<td><?= $ced_alu ?></td>
							<td><?= $apellido ?></td>
							<td><?= $nombre ?></td>
							<td><?= $nomgra ?></td>
							<td style="text-align:right;"><?= number_format($suma_a_pagado,2,'.',',') ?></td>
							<td style="text-align:right;"><?= number_format($pagado,2,'.',',') ?></td>
							<td style="text-align:right;"><?= number_format($total,2,'.',',') ?></td>
						</tr><?php
					} ?>

						<tr>
							<td></td>
							<td></td>
							<td ></td>
							<td  style="background-color: #A5D6A7; text-align: right;">Totales -----></td>
							<td style="background-color: #A5D6A7; text-align: right;" ><?= number_format($total1,2,'.',',') ?></td>
							<td style="background-color: #A5D6A7; text-align: right;" ><?= number_format($total2,2,'.',',') ?></td>
							<td style="background-color: #A5D6A7; text-align: right;" ><?= number_format($total1+$total2,2,'.',',') ?></td>
						</tr>
						<tr>
							<td rowspan="2" style="text-align:center; background-color: #E4A11B; font-size: 22px; vertical-align: middle; ">Nota:</td>
							<td colspan="2">El monto en la columna Sumado corresponde al facturado en otro sistema</td>
						</tr>
						<tr>
							<td colspan="2">El monto en la columna Pagado correponde al facturado en FacilFact</td>
						</tr>
				</tbody>
			</table>
		</div>
		<h5></h5>
	</body>
</html>