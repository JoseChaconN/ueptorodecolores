<?php
include_once "../include/header.php";
$link = Conectarse();
if(!isset($_POST['gradoVer']) && !isset($_POST['secciVer']))
{
    $periodo_query = mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos WHERE activoPeriodo='1' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $nombre_periodo=$row['nombre_periodo'];
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    $grado1_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." ORDER BY grado LIMIT 1 ");
    while($row = mysqli_fetch_array($grado1_query))
    {
        $gradoVer=$row['grado'];
    }
    $secci1_query = mysqli_query($link,"SELECT * FROM secciones ORDER BY id LIMIT 1 ");
    while($row1 = mysqli_fetch_array($secci1_query))
    {
        $secciVer=$row1['id'];
    }
    $concepto1_query = mysqli_query($link,"SELECT * FROM conceptos WHERE id>0 ORDER BY id LIMIT 1 ");
    while($row1 = mysqli_fetch_array($concepto1_query))
    {
        $concepVer=$row1['id'];
    }
} else
{
    $concepVer=$_POST['concepVer'];
    $nombre_periodo=$_POST['periodoVer'];
    $periodo_query = mysqli_query($link,"SELECT tablaPeriodo FROM periodos WHERE nombre_periodo='$nombre_periodo' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    
    $gradoVer = $_POST['gradoVer'];
    $secciVer = $_POST['secciVer'];
}
if($gradoVer==1)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.ced_rep, B.nombreGrado as 'nomgra', A.apellido, D.statusAlum, D.grado, D.idSeccion,D.pagado, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.grado<60 and D.idSeccion='$secciVer' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion, A.apellido ASC ");
}
if($gradoVer==2)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.ced_rep, B.nombreGrado as 'nomgra', A.apellido, D.statusAlum, D.grado, D.idSeccion,D.pagado, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.grado>60 and D.idSeccion='$secciVer' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion, A.apellido ASC ");
}
if($gradoVer>40 && $gradoVer<61)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.ced_rep, B.nombreGrado as 'nomgra', A.apellido, D.statusAlum, D.grado, D.idSeccion,D.pagado, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.grado='$gradoVer' and D.idSeccion='$secciVer' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion, A.apellido ASC ");
}
if($gradoVer>60)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.ced_rep, B.nombreGrado as 'nomgra', A.apellido, D.statusAlum, D.grado, D.idSeccion,D.pagado, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.grado='$gradoVer' and D.idSeccion='$secciVer' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion, A.apellido ASC ");
} ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Pagos por conceptos </h1>    
        </div>    
        <div class="col-md-3">
            <button type="button" class="btn btn-primary" onclick='window.open("pagosConcepto-pdf.php?idG=<?= $gradoVer ?>&idS=<?= $secciVer ?>&peri=<?= $tablaPeriodo ?>&nomP=<?= $nombre_periodo ?>&conc=<?= $concepVer ?> ")' style="width: 100%"><i class="fas fa-print"></i> Imprimir Listado</button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="pagosConcepto.php">
                    <div class="form-row">
                        <div class="col-md-3 col-md-offset-md-3 col-12" style="margin-bottom:2%;">
                            <select name="periodoVer" class="form-control" onchange="pulsaBuscar()"><?php
                                $periodo_query = mysqli_query($link,"SELECT tablaPeriodo,nombre_periodo FROM periodos WHERE pagos='S' ORDER BY nombre_periodo ");
                                while($row = mysqli_fetch_array($periodo_query))
                                {
                                    $nombre_periodo1=$row['nombre_periodo'];
                                    $selected = ($nombre_periodo1==$nombre_periodo) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$nombre_periodo1.'">'.$nombre_periodo1."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-md-offset-md-3 col-12" style="margin-bottom:2%;">
                            <select name="gradoVer" class="form-control" onchange="pulsaBuscar()">
                                <option value="1" <?php if($gradoVer==1){echo "selected";} ?>>Toda Inicial y Primaria</option>
                                <option value="2" <?php if($gradoVer==2){echo "selected";} ?>>Toda Media General</option><?php
                                $gradoVer_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." ORDER BY grado ");
                                while($row = mysqli_fetch_array($gradoVer_query))
                                {
                                    $nom_gradsd=($row['nombreGrado']);
                                    $id_gradsd=$row['grado'];
                                    $selected = ($id_gradsd==$gradoVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_gradsd.'">'.$nom_gradsd."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-12" style="margin-bottom: 2%;">
                            <select name="secciVer" class="form-control" onchange="pulsaBuscar()"><?php
                                $secciVer_query = mysqli_query($link,"SELECT * FROM secciones ORDER BY id ");
                                while($row1 = mysqli_fetch_array($secciVer_query))
                                {
                                    $nom_secdsd=utf8_encode($row1['nombre']);
                                    $id_secdsd=$row1['id'];
                                    $selected = ($id_secdsd==$secciVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                                }?>                                
                            </select>  
                        </div>
                        <div class="col-md-3 col-12" style="margin-bottom: 2%;">
                            <select name="concepVer" class="form-control" onchange="pulsaBuscar()"><?php
                                $concepto_query = mysqli_query($link,"SELECT * FROM conceptos WHERE id>0 ORDER BY id ");
                                while($row1 = mysqli_fetch_array($concepto_query))
                                {
                                    $concepto=($row1['concepto']);
                                    $id=$row1['id'];
                                    $monto = ($row1['monto']>0) ? $row1['monto'].'$' : '' ;
                                    $selected = ($id==$concepVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id.'">'.$concepto.' '.$monto."</option>";
                                }?>                                
                            </select>  
                        </div>
                        <div class="col-md-12 col-12">
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Pagado</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Pagado</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $grado=$row['grado'];
                            $nomgra=utf8_encode($row['nomgra']);
                            $alumno=$row["nomalu"].' '.$row['apellido'];
                            $nomsec=$row['nomsec'];
                            $ced_alu=$row['cedalu'];
                            $statusAlum=$row['statusAlum'];
                            $idAlum=$row['idAlum'];
                            $pagado=$row['pagado'];
                            $btn_class = ($statusAlum == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($statusAlum == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($statusAlum== 1) ? 'ACTIVO en este Periodo' : 'DESACTIVADO en este Periodo';
                            $son++;
                            $pagos_query = mysqli_query($link,"SELECT SUM(montoDolar) as monto FROM pagos".$tablaPeriodo."  WHERE idAlum ='$idAlum' and id_concepto='$concepVer' and statusPago='1' GROUP BY recibo "); 
                            $pago=0;
                            while($rowx=mysqli_fetch_array($pagos_query)) 
                            {   
                                $pago=$pago+$rowx['monto'];
                            }?>
                            <tr id="linea<?= $son ?>" <?php if($gradoVer<3){ echo 'title="Cursante del: '.$nomgra.'"'; } ?> >
                                <td ><?= $son; ?></td>
                                <td><?php if($idUserAct==1){ echo $ced_alu.' ('.$idAlum.')';}else{ echo $ced_alu; } ?></td>
                                <input type="hidden" name="" <?php echo "id='nom_pac$son'"; ?> value="<?php echo $row["nombre"]; ?>">
                                <input type="hidden" name="cedula" value="<?php echo $ced_alu ?>" <?php echo "id='ced$son'"; ?>><?php 
                                if($grado<60)
                                {?>
                                    <td style='cursor: pointer' onclick='window.open("perfil-pri-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= substr($row['nomalu'], 0,22) ; ?></td>
                                    <td style='cursor: pointer' onclick='window.open("perfil-pri-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= substr($row['apellido'], 0,20); ?></td><?php
                                }else
                                {?>
                                    <td style='cursor: pointer' onclick='window.open("perfil-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= substr($row['nomalu'], 0,22) ; ?></td>
                                    <td style='cursor: pointer' onclick='window.open("perfil-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= substr($row['apellido'], 0,20); ?></td><?php
                                }?>
                                <td align="right" ><?= number_format($pago,2,'.',',') ?></td> 
                                <td>
                                    <div class="dropdown mb-4 btn-group">
                                        <button onclick='window.open("../factura/facturar.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$grado ?>")' type="button" title='Facturar ' class="btn btn-success btn-circle" ><i class="fas fa-dollar-sign fa-lg" ></i></button>
                                        <button onclick='window.open("../procesos/historia-pagos.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo ?>")' type="button" title='Historia de Pagos ' class="btn btn-secondary btn-circle" ><i class="fas fa-folder fa-lg" ></i></button>
                                    </div>
                                </td>         
                            </tr><?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        if (screen.width<500) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        if (screen.width>1024) {
            $('#listados').addClass("show");
        }
        $('#pagosConcepto').addClass("active");
    });
    function cedAlum(ced) {
        $('#cedAlum').val(ced);
    }
    $('#BSbtninfo').filestyle({
      buttonName : 'btn-info',
      buttonText : ' Buscar Archivo'
    });
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";
mysqli_free_result($periodo_query);
mysqli_free_result($grado1_query);
mysqli_free_result($secci1_query);
mysqli_free_result($concepto1_query);
mysqli_free_result($query);
mysqli_free_result($gradoVer_query);
mysqli_free_result($secciVer_query);
mysqli_free_result($concepto_query);
?>
           