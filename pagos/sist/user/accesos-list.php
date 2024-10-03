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

$alumno_query = mysqli_query($link,"SELECT * FROM accesos WHERE fechaAcceso>='$desBus' and fechaAcceso<='$hasBus' ");    
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9 col-xs-12 col-sm-8">
            <h1 class="h3 mb-2 text-gray-800">Accesos al Sistema</h1>
        </div>
    </div>        
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="accesos-list.php">
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
                            <th>Correo</th>
                            <th>Sistema</th>
                            <th>Usuario</th>
                            <th>IP</th>
                            <th>Fecha/Hora</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Correo</th>
                            <th>Sistema</th>
                            <th>Usuario</th>
                            <th>IP</th>
                            <th>Fecha/Hora</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($alumno_query)) 
                        {
                            $idUser=$row['idUser'];
                            $emailUser=$row['emailUser'];
                            $nombreUser=$row['nombreUser'];
                            $fecha=date("d-m-Y H:i:s", strtotime($row['fechaAcceso']));
                            $ip_acceso=$row['ip_acceso'];
                            $sistema=$row['sistema'];  ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><?= $emailUser; ?></td>
                                <td><?= $sistema ?></td>
                                <td><?= $nombreUser ; ?></td>
                                <td><?= $ip_acceso ; ?></td>
                                <td><?= $fecha ?></td>
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
        $('#accesos-list').addClass("active");
    });
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           