<?php
include_once "../include/header.php";
$link = Conectarse();
$query = mysqli_query($link,"SELECT * FROM caja_chica_tipo  "); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Tipos de Movimientos en Caja Chica</h1>    
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <div class="form-row">
                    <div class="col-md-3 offset-md-9 col-xs-12 col-sm-12">
                        <button type="button" data-toggle="modal" data-target="#nuevoConcepto" class="btn btn-primary" style="width: 100%;"><span class="fas fa-plus fa-sm" ></span> Nuevo Tipo</button><br><br>
                    </div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Operacion</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Operacion</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=1;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $id=$row['id'];
                            $nombre_tipo=$row['nombre_tipo'];
                            $status=$row['status'];
                            $abrevia=$row['abrevia'];
                            $operacion=$row['operacion'];
                            $btn_class = ($status == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($status == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($status== 1) ? 'ACTIVO' : 'DESACTIVADO';
                            $nomOperacion = ($operacion== 1) ? 'SUMAR' : 'RESTAR'; ?>
                            <tr id="linea<?= $son ?>">
                                <td><?= $son; ?></td>
                                <td id="nombr<?= $son ?>"><?= $nombre_tipo; ?></td>
                                <td id="tip<?= $son ?>"><?= $nomOperacion; ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group">
                                        <button onclick="verConcepto('<?= $id ?>','<?= $nombre_tipo ?>','<?= $status ?>','<?= $operacion ?>','<?= $son ?>' )" title="Modificar Concepto" data-toggle="modal" data-target="#modiConcepto" type="button" class="btn btn-success btn-circle"  ><i class="fas fa-eye " ></i></button>

                                        <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="status('<?= $id ?>','<?= $son ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> fa-lg" ></i></button>

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
<div class="modal fade" id="nuevoConcepto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Tipo:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre de Operación:</label>
                            <input type="text" required class="form-control" id="nombreNuevo">
                        </div>
                        <div class="col-md-6">
                            <label>Funcion en Caja Chica:</label>
                            <select class="form-control" id="tipoNuevo">
                                <option value="1" selected>Sumar</option>
                                <option value="2" >Restar</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Status:</label>
                            <select class="form-control" id="statusNuevo">
                                <option value="1" selected>Activa</option>
                                <option value="2" >Inactivada</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="guardaConcepto()" class="btn btn-primary">Guardar Nuevo Tipo</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modiConcepto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edición de Tipo:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre de Operación:</label>
                            <input type="text" class="form-control" id="nombreVer">
                        </div>
                        <div class="col-md-6">
                            <label>Funcion en Caja Chica:</label>
                            <select class="form-control" id="tipoVer">
                                <option value="1" >Sumar</option>
                                <option value="2" >Restar</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Status:</label>
                            <select class="form-control" disabled="" id="statusVer">
                                <option value="1">Activa</option>
                                <option value="2">Inactivada</option>
                            </select>
                        </div>
                        <input type="hidden" id="idVer">
                        <input type="hidden" id="linVer">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="actualConcepto()" class="btn btn-primary">Guardar Cambios</button>
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
            $('#cajaChicaMenu').addClass("show");
        }
        $('#cajaChicaTip').addClass("active");
    });
    function  status(id,Van)
    {
        $.post('tipo-status.php',{'id':id},function(data)
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
                $('#boton_'+Van).prop('title', 'DESACTIVADO');
              }
            } 
        }, 'json');
    }
    function  guardaConcepto()
    {
        nom=$('#nombreNuevo').val();
        sta=$('#statusNuevo').val();
        ope=$('#tipoNuevo').val();
        $.post('tipo-guarda.php',{'nombre':nom, 'stat':sta,'oper':ope},function(data)
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
    function verConcepto(id,nom,sta,ope,lin) 
    {
        $('#linVer').val(lin);
        $('#idVer').val(id);
        $('#nombreVer').val(nom);
        $('#statusVer').val(sta);
        $('#tipoVer').val(ope)
    }
    function actualConcepto() 
    {
        lin=$('#linVer').val()
        id=$('#idVer').val()
        nom=$('#nombreVer').val()
        tip=$('#tipoVer').val()
        $.post('tipo-actual.php',{'id':id,'nombre':nom,'tipo':tip},function(data)
        {
            if(data.isSuccessful)
            {
                document.getElementById("nombr"+lin).innerHTML = nom;
                document.getElementById("tip"+lin).innerHTML = data.nomTip;
                $('#modiConcepto').modal('hide')
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
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";   
mysqli_free_result($query);             
?>
           