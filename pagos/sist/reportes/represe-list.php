<?php
include_once "../include/header.php";
$link = Conectarse();
$periodo_query = mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos WHERE activoPeriodo='1' ");
while($row = mysqli_fetch_array($periodo_query))
{
    $nombre_periodo=$row['nombre_periodo'];
    $tablaPeriodo=$row['tablaPeriodo'];
}
$represe_query = mysqli_query($link,"SELECT A.cedula,A.representante, A.tlf_celu  FROM represe A, alumcer B WHERE A.cedula=B.ced_rep and B.Periodo='$nombre_periodo' and B.statusAlum='1' and B.cargo is NULL GROUP BY A.representante ORDER BY A.representante ASC ");   
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Listado de Representantes <?= $nombre_periodo ?></h1>    
        </div>    
        <div class="col-md-3 col-xs-12 col-sm-12">
            <button type="button"  onclick='window.open("represe-list-pdf.php?peri=<?= $tablaPeriodo ?>&nomP=<?= $nombre_periodo ?>")' class="btn btn-primary" style="width: 100%;"><span class="fas fa-print fa-sm" ></span> Imprimir</button><br><br>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Representante</th>
                            <th>Telefono</th>
                            <th>Alumnnos</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Representante</th>
                            <th>Telefono</th>
                            <th>Alumnnos</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($represe_query)) 
                        {
                            $ced_rep=$row['cedula'];
                            $nom_rep=$row['representante'];
                            $tlf_celu=$row['tlf_celu'];
                            $alumnos_query = mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido,A.grado, B.nombreGrado, C.nombre as nomSec FROM alumcer A, grado".$tablaPeriodo." B, secciones C WHERE A.ced_rep='$ced_rep' and A.grado=B.grado and A.seccion=C.id and A.Periodo='$nombre_periodo' ");
                            $nroAlum=0; $cursa=''; $boton='';
                            while($row2=mysqli_fetch_array($alumnos_query)) 
                            {
                                $nroAlum++;
                                $idAlum=$row2['idAlum'];
                                $cedula=$row2['cedula'];
                                $nombre=$row2['nombre'];
                                $apellido=$row2['apellido'];
                                $grado=$row2['grado'];
                                $nombreGrado=$row2['nombreGrado'];
                                $nomSec=$row2['nomSec'];
                                if ($grado>60) {
                                    $gra=substr($grado, 1,1).'Año';
                                }
                                if($grado>40 && $grado<50){
                                    $gra=substr($grado, 1,1).'Nv.';
                                }
                                if($grado>50 && $grado<60){
                                    $gra=substr($grado, 1,1).'Gr.';
                                }
                                $boton.='<button title="'.$nombreGrado.' '.$nomSec.'" class="btn btn-info btn-circle" onclick="datosAlum('.$idAlum.')" >'.$gra.' </button>&nbsp;';
                                //$=$row2[''];
                            }?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><?= $ced_rep; ?></td>
                                <td><?= $nom_rep; ?></td>
                                <td><?= $tlf_celu; ?></td>
                                <td><?= $nroAlum.') '.$boton ?></td>
                            </tr><?php
                        } ?>
                    </tbody>
                </table>
                <input type="hidden" id="tabla" value="<?= $tablaPeriodo ?>">
                <input type="hidden" id="nombre_periodo" value="<?= $nombre_periodo ?>" >
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="datoAlum" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos alumno/representante: <h4 id="aquien"></h4></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Cedula</label>
                            <input type="text" id="ced_alu" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Grado/año</label>
                            <input type="text" id="gra_alu" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Sección</label>
                            <input type="text" id="sec_alu" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Apellido</label>
                            <input type="text" id="ape_alu" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Nombre</label>
                            <input type="text" id="nom_alu" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Cedula Rep.</label>
                            <input type="text" id="ced_rep" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Nombre</label>
                            <input type="text" id="nom_rep" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Telefono</label>
                            <input type="text" id="tlf_rep" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Cerrar</button>
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
        $('#represeList').addClass("active");
    });
    function datosAlum(id) {
        $('#datoAlum').modal('show')
        tabl=$('#tabla').val()
        $.post('buscar-datos.php',{'idAlu':id,'tabla':tabl},function(data)
        {
            if(data.isSuccessful)
            {
                $('#ced_alu').val(data.ced)
                $('#nom_alu').val(data.nomb)
                $('#ape_alu').val(data.apel)
                $('#gra_alu').val(data.grad)
                $('#sec_alu').val(data.secc)
                $('#ced_rep').val(data.cedR)
                $('#tlf_rep').val(data.tlfRep)
                $('#nom_rep').val(data.repre)
            } 
        }, 'json');
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";
mysqli_free_result($periodo_query);
mysqli_free_result($represe_query);
mysqli_free_result($alumnos_query);
?>
           