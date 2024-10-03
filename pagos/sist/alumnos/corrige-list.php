<?php
include_once "../include/header.php";
$link = Conectarse();
if(!isset($_POST['desde']) && !isset($_POST['hasta']))
{
    $fechahoy = strftime( "%Y-%m-%d");
    $desde=date("Y-m-d",strtotime($fechahoy."- 5 days")); 
    $hasta = strftime( "%Y-%m-%d");
    $desde2=date("Y-m-d",strtotime($fechahoy."- 5 days")).' 00:00:00' ; 
    $hasta2 = strftime( "%Y-%m-%d").' 23:59:59' ;
} else
{
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $desde2 = $_POST['desde'].' 00:00:00';
    $hasta2 = $_POST['hasta'].' 23:59:59';
}
$alumno_query = mysqli_query($link,"SELECT A.*,B.nombre,B.apellido FROM cambio_ced A, alumcer B  WHERE A.idAlum=B.idAlum and A.fecha>='$desde2' and A.fecha<='$hasta2' ORDER BY A.fecha ASC ");
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <form role="form" method="POST" enctype="multipart/form-data" action="corrige-list.php">
        <div class="row">
            <div class="col-md-9">
                <h1 class="h3 mb-2 text-gray-800">Listado de cedulas corregidas en el sistema</h1>  
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" type="button" onclick='window.open("corrige-pdf.php?des=<?= $desde2 ?>&has=<?= $hasta2 ?>")' style="width:100%;"><i class="fas fa-print"></i> Imprimir Listado</button>    
            </div>
            <div class="col-md-4 col-xs-12 col-sm-6">
                <label>Desde el:</label>
                <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
            </div>
            <div class="col-md-4 col-xs-12 col-sm-6">
                <label>Hasta el:</label>
                <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
            </div>
            <div class="col-md-4 col-xs-12 col-sm-12" >
                <label>&nbsp;</label><button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
            </div>
        </div>
    </form>
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
                            <th>Ced.Actual</th>
                            <th>Ced.Vieja</th>
                            <th>Estudiante</th>
                            <th>En Fecha</th>
                            <th>Procesado por</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Ced.Actual</th>
                            <th>Ced.Vieja</th>
                            <th>Estudiante</th>
                            <th>En Fecha</th>
                            <th>Procesado por</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($alumno_query)) 
                        {
                            $cedula=$row['cedula'];
                            $cedula_vie=$row['cedula_vie'];
                            $alumno=$row["apellido"].' '.$row["nombre"];
                            $fecha=$row['fecha'];
                            $usuario=$row['usuario'];
                            $idAlum=$row['idAlum']; ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><?= $cedula; ?></td>
                                <td><?= $cedula_vie; ?></td>
                                <td><?= $alumno ; ?></td>
                                <td><?= date("d-m-Y H:i", strtotime($fecha)) ?></td>
                                <td><?= $usuario; ?></td>
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
        $('#listadoCedCorrige').addClass("active");

        //////////////////////

        /*if (screen.width<1025) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        $('#cedulasCorregidas').addClass("active");*/
    });
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           