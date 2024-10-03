<?php
include_once "../include/header.php";
$link = Conectarse();
if(!isset($_POST['gradoVer']) )
{
    $periodo_query = mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos WHERE activoPeriodo='1' and adultos='N' ");
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
} else
{
    $nombre_periodo=$_POST['periodoVer'];

    $periodo_query = mysqli_query($link,"SELECT tablaPeriodo FROM periodos WHERE nombre_periodo='$nombre_periodo' and adultos='N' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    $gradoVer = $_POST['gradoVer'];
}
$query = mysqli_query($link,"SELECT * FROM montos".$tablaPeriodo." WHERE id_grado='$gradoVer'  ");
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Listado de Meses</h1>    
        </div><?php
        if(mysqli_num_rows($query)==0)
        { ?>    
            <div class="col-md-3">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#nuevoTabla" style="width: 100%"><i class="fas fa-plus"></i> Nueva Tabla</button>
            </div><?php
        } ?>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="meses-list.php">
                    <div class="form-row">
                        <div class="col-md-3 col-xs-12 col-sm-6" style="margin-bottom: 2%; ">
                            <select name="periodoVer" id="periodoVer" onchange="pulsaBuscar()" class="form-control"><?php
                                $periodo_query = mysqli_query($link,"SELECT tablaPeriodo,nombre_periodo FROM periodos WHERE pagos='S' and adultos='N' ORDER BY nombre_periodo ");
                                while($row = mysqli_fetch_array($periodo_query))
                                {
                                    $nombre_periodo1=$row['nombre_periodo'];
                                    $selected = ($nombre_periodo1==$nombre_periodo) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$nombre_periodo1.'">'.$nombre_periodo1."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-6" style="margin-bottom: 2%; ">
                            <select name="gradoVer" id="gradoVer" onchange="pulsaBuscar()" class="form-control"><?php
                                $gradoVer_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." WHERE grado<70 ORDER BY grado ");
                                while($row = mysqli_fetch_array($gradoVer_query))
                                {
                                    $nom_gradsd=($row['nombreGrado']);
                                    $id_gradsd=$row['grado'];
                                    $selected = ($id_gradsd==$gradoVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_gradsd.'">'.$nom_gradsd."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                        <div class="col-md-3 col-12">
                            <button type="button" data-toggle="modal" data-target="#nuevoMes" class="btn btn-primary" style="width: 100%;"><span class="fas fa-plus fa-sm" ></span> Agregar Mes</button><br><br>
                        </div>
                    </div>
                    <input type="hidden" id="tabla" value="<?= $tablaPeriodo ?>">
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Concepto</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Concepto</th>
                            <th>Fecha</th>
                            <th>Monto</th><th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=1;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $id_tabla=$row['id_tabla'];
                            $mes=$row['mes'];
                            $fecha_vence=$row['fecha_vence'];
                            $monto=$row['monto']; ?>
                            <tr id="linea<?= $son ?>">
                                <td><?= $son; ?></td>
                                <td id="mes<?= $son ?>"><?= $mes; ?></td>
                                <td id="fec<?= $son ?>"><?= date("d-m-Y", strtotime($fecha_vence)) ?></td>
                                <td id="mon<?= $son ?>"><?= $monto; ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group">
                                        <button onclick="verMes('<?= $son ?>','<?= encriptar($id_tabla) ?>','<?= $mes ?>','<?= $fecha_vence ?>','<?= $monto ?>')" title="Modificar mes" data-toggle="modal" data-target="#modiMes" type="button" class="btn btn-success btn-circle"  ><i class="fas fa-eye " ></i></button><?php 
                                        if($_SESSION['idUser']=='1')
                                        {?>
                                            <button  title="Eliminar Mes" onclick="eliminaMes('<?= encriptar($id_tabla) ?>',<?= $son ?>)" type="button" class="btn btn-danger" ><i class="fas fa-trash fa-lg" ></i></button><?php 
                                        }?>
                                    </div>
                                </td>         
                            </tr><?php
                            $son++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="nuevoTabla" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nueva Tabla:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" id="tablaNueva">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row"><?php
                            $conce='Ins,Sep,Oct,Nov,Dic,Ene,Feb,Mar,Abr,May,Jun,Jul,Ago';
                            $m=0;
                            for ($i=1; $i < 14; $i++) 
                            { 
                                $mes=substr($conce, $m,3); ?>
                                <div class="col-md-4">
                                    <label>Concepto:</label>
                                    <input type="text" class="form-control" name="mes<?= $i ?>" value="<?= $mes ?>">
                                </div>
                                <div class="col-md-4">
                                    <label>Fecha Vence:</label>
                                    <input class="form-control" type="date" name="fecha<?= $i ?>">
                                </div>
                                <div class="col-md-4">
                                    <label>Monto:</label>
                                    <input class="form-control" onClick="this.select()" type="text" name="monto<?= $i ?>">
                                </div><?php
                                $m=$m+4;
                            }?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                    <button type="button" onclick="tablaNueva()" class="btn btn-primary">Guardar Tabla</button>
                </div>
                <input type="hidden" name="periodo" value="<?= $nombre_periodo ?>">
                <input type="hidden" name="grado" value="<?= $gradoVer ?>">
                <input type="hidden" name="tablaPeriodo" value="<?= $tablaPeriodo ?>">
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="nuevoMes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Mes:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Concepto:</label>
                            <input type="text" class="form-control" id="mesNuevo">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha Vence:</label>
                            <input class="form-control" type="date" id="fechaNuevo">
                        </div>
                        <div class="col-md-4">
                            <label>Monto:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="montoNuevo">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="guardaMes()" class="btn btn-primary">Guardar Mes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modiMes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edicion de Mes:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Concepto:</label>
                            <input type="text" class="form-control" id="mesVer">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha Vence:</label>
                            <input class="form-control" type="date" id="fechaVer">
                        </div>
                        <div class="col-md-4">
                            <label>Monto:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="montoVer">
                        </div>
                        <input type="hidden" id="idVer">
                        <input type="hidden" id="linVer">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="actualMes()" class="btn btn-primary">Guardar Cambios</button>
                <button type="button" onclick="sumarAnio()" class="btn btn-primary">Sumar Año</button>
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
            $('#configura').addClass("show");
        }
        $('#montoMesListado').addClass("active");
    });
    function  eliminaMes(id, van)
    {
        tabla=$('#tabla').val()
        $.post('meses-borra.php',{'id_tabla':id,'tablaP':tabla},function(data)
        {
            if(data.isSuccessful)
            {
                $('#linea'+van).hide();
                Swal.fire({
                  icon: 'success',
                  title: 'Listo!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Mes eliminado satisfactoriamente!'
                })
            } 
        }, 'json');
    }
    function  guardaMes()
    {
        tabla=$('#tabla').val()
        mes=$('#mesNuevo').val();
        fec=$('#fechaNuevo').val();
        mon=$('#montoNuevo').val();
        gra=$('#gradoVer').val();
        $.post('meses-guarda.php',{'periodo':tabla, 'id_grado':gra, 'mes':mes, 'fecha_vence':fec, 'monto':mon},function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
            }else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Datos no almacenados!'
                })
            } 
        }, 'json');
    }
    function verMes(lin,id,mes,fec,mon) 
    {
        $('#linVer').val(lin);
        $('#idVer').val(id);
        $('#mesVer').val(mes);
        $('#fechaVer').val(fec);
        $('#montoVer').val(mon);
    }
    function actualMes() 
    {
        lin=$('#linVer').val()
        id=$('#idVer').val()
        fec=$('#fechaVer').val()
        mes=$('#mesVer').val()
        mon=$('#montoVer').val()
        peri=$('#tabla').val()
        
        $.post('meses-actual.php',{'id_tabla':id,'fecha_vence':fec,'mes':mes,'monto':mon,'perio':peri},function(data)
        {
            if(data.isSuccessful)
            {
                $('#modiMes').modal('hide')
                document.getElementById("mes"+lin).innerHTML = mes;
                document.getElementById("fec"+lin).innerHTML = data.fecha;
                document.getElementById("mon"+lin).innerHTML = data.monto;
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos actualizados satisfactoriamente!'
                })

            } 
        }, 'json');
    }
    function sumarAnio() {
        lin=$('#linVer').val()
        id=$('#idVer').val()
        fec=$('#fechaVer').val()
        peri=$('#tabla').val()
        $.post('meses-actual-anio.php',{'id_tabla':id,'fecha_vence':fec,'perio':peri},function(data)
        {
            if(data.isSuccessful)
            {
                $('#modiMes').modal('hide')
                document.getElementById("fec"+lin).innerHTML = data.fecha;
                /*Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos actualizados satisfactoriamente!'
                })*/

            } 
        }, 'json');
    }
    function tablaNueva() 
    {
        $.ajax({
        type: 'POST',
        url: 'tabla-nueva.php',
        data: $('#tablaNueva').serialize(),
        success: function(respuesta) 
        {
            if(respuesta=='ok')
            {
                window.parent.location.reload();
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos almacenados satisfactoriamente!'
                })
            }
            else {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Datos no almacenados!'
                })
            }
        }
        });
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           