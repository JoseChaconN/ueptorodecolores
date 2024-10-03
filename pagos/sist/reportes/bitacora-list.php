<?php
include_once "../include/header.php";
$link = Conectarse();
if(!isset($_POST['desde']) && !isset($_POST['hasta']))
{
    $desde = strftime( "%Y-%m-%d") ;
    $hasta = strftime( "%Y-%m-%d") ;
    $recDesde=0; $recHasta=0;
} else
{
    $desde = $_POST['desde'] ;
    $hasta = $_POST['hasta'] ;
    $recDesde=$_POST['recDesde'];
    if($recDesde==0) {$recHasta=0;}else {$recHasta=$_POST['recHasta'];}
}
$desBus=$desde.' 00:00:00';
$hasBus=$hasta.' 23:59:59';

$alumno_query = mysqli_query($link,"SELECT A.*, B.nombreUser,B.apellidoUser,C.nombre,C.apellido, D.nombreGrado FROM bitacora A,user B, alumcer C, grado2324 D  WHERE A.fecha>='$desBus' and A.fecha<='$hasBus' and A.campo_original!='' and A.id_user=B.idUser and A.idAlum=C.idAlum and A.grado=D.grado ");    
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9 col-xs-12 col-sm-8">
            <h1 class="h3 mb-2 text-gray-800">Bitacora</h1>
        </div>
    </div>        
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="bitacora-list.php">
                    <div class="form-row">
                        <div class="col-md-4 col-xs-12 col-sm-6 ">
                            <label>Desde el:</label>
                            <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-6 ">
                            <label>Hasta el:</label>
                            <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
                        </div>
                        <div class="col-md-4 col-12">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Fecha/hora</th>
                            <th>Estudiante</th>
                            <th>Campo Original</th>
                            <th>Campo modificado</th>
                            <th>Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Fecha/hora</th>
                            <th>Estudiante</th>
                            <th>Campo Original</th>
                            <th>Campo modificado</th>
                            <th>Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($alumno_query)) 
                        {
                            $alumno=$row['nombre'].' '.$row['apellido'];
                            $tabla=$row['tabla'];
                            $nomGrado=$row['nombreGrado'];
                            $nombreUser=$row['nombreUser'].' '.$row['apellidoUser'];
                            $fecha=date("d-m-Y H:i:s", strtotime($row['fecha']));
                            $campo_original=$row['campo_original'];
                            $campo_modificado=$row['campo_modificado']; ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><?= $nombreUser ; ?></td>
                                <td><?= $fecha ?></td>
                                <td><?= $alumno ?></td>
                                <td><?= $campo_original; ?></td>
                                <td><?= $campo_modificado ?></td>
                                <td>
                                    <button type="button" data-toggle="tooltip" data-placement="top" title='Ver Detalle' onclick="verDetalle('<?= $alumno ?>','<?= $tabla ?>','<?= $nomGrado ?>','<?= $nombreUser ?>','<?= $fecha ?>','<?= $campo_original ?>','<?= $campo_modificado ?>')" class="btn btn-info btn-circle" ><i class="fas fa-eye fa-lg" ></i></button>
                                </td>
                                
                            </tr><?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="verDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Bitacora: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Estudiante</label>
                            <input type="text" id="nomAlu" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>Periodo</label>
                            <input type="text" id="periodo" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Cursa</label>
                            <input type="text" id="gradoSecc" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <label>Modificado por</label>
                            <input type="text" id="nomUser" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha</label>
                            <input type="text" id="fechaMod" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label>Campo Original</label>
                            <textarea id="campOri" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>Campo Modificado</label>
                            <textarea id="campMod" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Cerrar Ventana</button>
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
        $('#bitaco-list').addClass("active");
    });
    function verDetalle(alu,per,gra,user,fec,ori,mod) {
        $('#verDetalle').modal('show')
        $('#nomAlu').val(alu)
        $('#periodo').val(per)
        $('#gradoSecc').val(gra)
        $('#nomUser').val(user)
        $('#fechaMod').val(fec)
        $('#campOri').val(ori)
        $('#campMod').val(mod)
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           