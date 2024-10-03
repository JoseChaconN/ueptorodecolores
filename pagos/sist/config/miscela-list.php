<?php
include_once "../include/header.php";
$link = Conectarse();
$query = mysqli_query($link,"SELECT * FROM miscelaneos_conceptos  "); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Listado de Conceptos para Miscelaneos</h1>    
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
                        <button type="button" data-toggle="modal" data-target="#nuevoConcepto" class="btn btn-primary" style="width: 100%;"><span class="fas fa-plus fa-sm" ></span> Nuevo Concepto</button><br><br>
                    </div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Concepto</th>
                            <th>Monto $</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Cod.</th>
                            <th>Concepto</th>
                            <th>Monto $</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=1;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $id=$row['id'];
                            $concepto=$row['concepto'];
                            $monto=$row['monto'];
                            $status=$row['status'];
                            $articulo=$row['articulo'];
                            $editar=$row['editar'];
                            $btn_class = ($status == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($status == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($status== 1) ? 'ACTIVO' : 'DESACTIVADO'; ?>
                            <tr id="linea<?= $son ?>">
                                <td><?= $id; ?></td>
                                <td id="concep<?= $son ?>"><?= $concepto; ?></td>
                                <td id="mon<?= $son ?>"><?= $monto; ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group ">
                                        <button onclick="verConcepto('<?= $id ?>','<?= $concepto ?>','<?= $monto ?>','<?= $editar ?>','<?= $articulo ?>')" title="Modificar Concepto" data-toggle="modal" data-target="#modiConcepto" type="button" class="btn btn-success btn-circle"  ><i class="fas fa-eye " ></i></button>
                                        <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="status('<?= $id ?>','<?= $son ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> fa-lg" ></i></button>
                                        <button onclick="masStock('<?= $id ?>','<?= $concepto ?>')" title="Agregar al Stock" data-toggle="modal" data-target="#masStockModal" type="button" class="btn btn-warning btn-circle"  ><i class="fas fa-plus " ></i></button>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Concepto:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Concepto:</label>
                            <input type="text" required class="form-control" id="concepNuevo">
                        </div>
                        <div class="col-md-6">
                            <label>Monto: ($)</label>
                            <input class="form-control" onchange="MASK(this,this.value,'-##,###,##0.00',1);" onkeypress="return ValMon(event)" onClick="this.select()" type="text" id="montoNuevo">
                        </div>
                        <div class="col-md-6" title="Permitir editar el concepto al facturar?">
                            <label>Edición:</label>
                            <select class="form-control" id="editarNuevo">
                                <option value="S">SI</option>
                                <option value="N">NO</option>
                            </select>
                        </div>
                        <div class="col-md-6" title="Controla Inventario del articulo">
                            <label>Articulo:</label>
                            <select class="form-control" id="articuloNuevo">
                                <option value="1">SI</option>
                                <option value="2">NO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="guardaConcepto()" class="btn btn-primary">Guardar Concepto</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modiConcepto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edición de Concepto:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Concepto:</label>
                            <input type="text" required class="form-control" id="conceptoVer">
                            <input type="hidden" id="conceptoViejoVer">
                        </div>
                        <div class="col-md-6">
                            <label>Monto: ($)</label>
                            <input class="form-control" onchange="MASK(this,this.value,'-##,###,##0.00',1);" onkeypress="return ValMon(event)" onClick="this.select()" type="text" id="montoVer">
                        </div>
                        <div class="col-md-6" title="Permitir editar el concepto al facturar?">
                            <label>Edición:</label>
                            <select class="form-control" id="editarVer">
                                <option value="S">SI</option>
                                <option value="N">NO</option>
                            </select>
                        </div>
                        <div class="col-md-6" title="Controla Inventario del articulo">
                            <label>Articulo:</label>
                            <select class="form-control" id="articuloVer">
                                <option value="1">SI</option>
                                <option value="2">NO</option>
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
<div class="modal fade" id="masStockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Agregar Stock al Inventario:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Concepto:</label>
                            <input type="text" readonly class="form-control" id="conceptoMas">
                        </div>
                        <div class="col-md-6">
                            <label>Stock Agregado</label>
                            <input class="form-control" onClick="this.select()" type="text" id="stockMas">
                        </div>
                        <input type="hidden" id="idMas">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="actualStock()" class="btn btn-primary">Guardar Stock</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        if (screen.width<1025) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        if (screen.width>1024) {
            $('#configura').addClass("show");
        }
        $('#conceptoMisce').addClass("active");
    });
    function  status(id,Van)
    {
        $.post('miscela-status.php',{'id_con':id},function(data)
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
        con=$('#concepNuevo').val();
        mon=$('#montoNuevo').val();
        edi=$('#editarNuevo').val();
        art=$('#articuloNuevo').val();
        
        $.post('miscela-guarda.php',{'concepto':con, 'monto':mon,'edita':edi,'arti':art},function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
            }else
            {
                err=data.problema
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: err+'!'
                })
            } 
        }, 'json');
    }
    function verConcepto(id,con,mon,edi,art) 
    {
        $('#linVer').val(id);
        $('#idVer').val(id);
        $('#conceptoVer').val(con);
        $('#conceptoViejoVer').val(con);
        $('#montoVer').val(mon);
        $('#editarVer').val(edi);
        $('#articuloVer').val(art);
    }
    function actualConcepto() 
    {
        lin=$('#linVer').val()
        id=$('#idVer').val()
        con=$('#conceptoVer').val()
        vie=$('#conceptoViejoVer').val()
        mon=$('#montoVer').val()
        edi=$('#editarVer').val();
        art=$('#articuloVer').val();
        $.post('miscela-actual.php',{'id':id,'concepto':con,'monto':mon,'arti':art,'edita':edi,'viejo':vie},function(data)
        {
            if(data.isSuccessful)
            {
                document.getElementById("concep"+lin).innerHTML = data.conc;
                document.getElementById("mon"+lin).innerHTML = data.monto;
                $('#modiConcepto').modal('hide')
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos actualizados satisfactoriamente!'
                })
            }else
            {
                err=data.problema
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: err+'!'
                })
            }  
        }, 'json');
    }
    function masStock(id,con) {
        $('#idMas').val(id);
        $('#conceptoMas').val(con);
    }
    function actualStock() 
    {
        id=$('#idMas').val()
        sto=$('#stockMas').val()
        $.post('miscela-stock.php',{'id':id,'stoc':sto},function(data)
        {
            if(data.isSuccessful)
            {
                $('#masStockModal').modal('hide')
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Stock ingresado satisfactoriamente!'
                })
            } 
        }, 'json');
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           