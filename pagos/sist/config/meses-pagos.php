<?php
include_once "../include/header.php";
$link = Conectarse();

if(!isset($_POST['gradoVer']) && !isset($_POST['secciVer']))
{
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
    $periodo_query = mysqli_query($link,"SELECT nombre_periodo FROM periodos WHERE tablaPeriodo='$tablaPeriodo' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $nombre_periodo=$row['nombre_periodo'];
    }
} else
{
    $nombre_periodo=$_POST['periodoVer'];
    $periodo_query = mysqli_query($link,"SELECT tablaPeriodo FROM periodos WHERE nombre_periodo='$nombre_periodo' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    
    $gradoVer = $_POST['gradoVer'];
    $secciVer = $_POST['secciVer'];
}
$tabla_query = mysqli_query($link,"SELECT * FROM montos".$tablaPeriodo." WHERE id_grado='$gradoVer' ORDER BY id_tabla ASC ");
$totPaga=0; $i=1;
while($row = mysqli_fetch_array($tabla_query))
{
    $totPaga=$totPaga+$row['monto'];
    ${'pago'.$i} = $row['monto'];
    $i++;
}

$query = mysqli_query($link,"SELECT idAlum, nombre, apellido, pagado, deudatotal, morosida FROM alumcer WHERE grado='$gradoVer' and seccion='$secciVer' and IF('$nombre_periodo'='0',grado='$gradoVer',Periodo='$nombre_periodo') ORDER BY apellido ASC ");

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Meses Pagados</h1>    
        </div>    
        <div class="col-md-3">
            <button type="button" class="btn btn-success" onclick='window.open("meses-pagos-excel.php?grado=<?= $gradoVer ?>&secc=<?= $secciVer ?>&peri=<?= $nombre_periodo ?>")' style="width: 100%"><i class="fas fa-print"></i> Exportar Excel</button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="meses-pagos.php">
                    <div class="form-row">
                        <div class="col-md-3 col-md-offset-3 col-xs-6 col-sm-6">
                            <select name="periodoVer" class="form-control"><?php
                                $periodo_query = mysqli_query($link,"SELECT tablaPeriodo,nombre_periodo FROM periodos ORDER BY nombre_periodo ");
                                while($row = mysqli_fetch_array($periodo_query))
                                {
                                    $nombre_periodo1=$row['nombre_periodo'];
                                    $selected = ($nombre_periodo1==$nombre_periodo) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$nombre_periodo1.'">'.$nombre_periodo1."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-md-offset-3 col-xs-6 col-sm-6">
                            <select name="gradoVer" class="form-control" id="gradoVer"><?php
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
                        <div class="col-md-3 col-xs-6 col-sm-6" style="margin-bottom: 2%;">
                            <select name="secciVer" class="form-control" id="secciVer"><?php
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
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>ins</th>
                            <th>sep</th>
                            <th>oct</th>
                            <th>nov</th>
                            <th>dic</th>
                            <th>ene</th>
                            <th>feb</th>
                            <th>mar</th>
                            <th>abr</th>
                            <th>may</th>
                            <th>jun</th>
                            <th>jul</th>
                            <th>ago</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>ins</th>
                            <th>sep</th>
                            <th>oct</th>
                            <th>nov</th>
                            <th>dic</th>
                            <th>ene</th>
                            <th>feb</th>
                            <th>mar</th>
                            <th>abr</th>
                            <th>may</th>
                            <th>jun</th>
                            <th>jul</th>
                            <th>ago</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $idAlum=$row['idAlum'];
                            $alumno=$row['apellido'].' '.$row['nombre'];
                            $pagado=$row['pagado'];
                            $deudatotal=$row['deudatotal'];
                            $morosida=$row['morosida']; ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-alumno.php?id=<?= encriptar($idAlum) ?>")'><span title="<?= 'Deuda total:'.$deudatotal.' Pagaddo:'.$pagado.' Morosidad:'.$morosida ?>" data-toggle="tooltip" data-placement="right"><?= $alumno ?></span></td><?php
                                    for ($i=1; $i < 14; $i++) 
                                    { 
                                        if($pagado>=${'pago'.$i})
                                        { ?>
                                            <td style="background-color: #7DCEA0; text-align: center;">SI</td><?php
                                        }else
                                        { ?>
                                            <td style="background-color: #FADBD8; text-align: center;">NO</td><?php
                                        }
                                        $pagado=$pagado-${'pago'.$i};
                                    } ?>
                                
                            </tr><?php
                        } ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        if($('#gradoVer').val()==0 || $('#secciVer').val()==0)
        {
            $('.oculta').show(); 
        }
    });
    function  statusAlum(idAlumno, Van)
    {
        idA = idAlumno;
        tabPer=$('#tabla').val();
        $.post('../alumnos/statusAlumno.php',{'idAlu':idA, 'tabPer':tabPer},function(data)
        {
            if(data.isSuccessful)
            {
              if(data.status=='1')
              {
                $('#boton_'+Van).removeClass("btn-danger").addClass("btn-primary");
                $('#btnI_'+Van).removeClass("fa-lock").addClass("fa-check");
                $('#boton_'+Van).prop('title', 'ACTIVO');
              }else
              {
                $('#boton_'+Van).removeClass("btn-primary").addClass("btn-danger");
                $('#btnI_'+Van).removeClass("fa-check").addClass("fa-lock");
                $('#boton_'+Van).prop('title', 'ALUMNO DESACTIVADO NO TIENE ACCESO A LA PAGINA WEB');
              }
            } 
        }, 'json');
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           