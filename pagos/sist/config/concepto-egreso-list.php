<?php
include_once "../include/header.php";
$link = Conectarse();
$query = mysqli_query($link,"SELECT * FROM concep_egresos ORDER BY cast(ordena AS unsigned) ASC "); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Listado de Conceptos por Egresos</h1>    
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
                            <th>Motivo</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Cod.</th>
                            <th>Concepto</th>
                            <th>Monto $</th>
                            <th>Motivo</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $id=$row['id_concepto'];
                            $concepto=$row['concepto'];
                            $monto=$row['monto'];
                            $status=$row['status'];
                            $tipo_egreso=$row['tipo_egreso'];
                            $ordena=$row['ordena'];
                            $tipo = ($tipo_egreso==1) ? 'Devenga' : 'Deducción' ;
                            $btn_class = ($status == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($status == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($status== 1) ? 'ACTIVO' : 'DESACTIVADO';
                            $son++; ?>
                            <tr id="linea<?= $son ?>">
                                <td><?= $son; ?></td>
                                <td id="concep<?= $son ?>"><?= $concepto; ?></td>
                                <td id="mon<?= $son ?>"><?= $monto; ?></td>
                                <td id="tip<?= $son ?>"><?= $tipo ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group ">
                                        <button onclick="verConcepto('<?= $id ?>','<?= $concepto ?>','<?= $monto ?>','<?= $tipo_egreso ?>','<?= $ordena ?>','<?= $son ?>')" title="Modificar Concepto" data-toggle="modal" data-target="#modiConcepto" type="button" class="btn btn-success btn-circle"  ><i class="fas fa-eye " ></i></button>

                                        <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="status('<?= $id ?>','<?= $son ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> fa-lg" ></i></button>

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
                        <div class="col-md-10">
                            <label>Concepto:</label>
                            <input type="text" required class="form-control" id="concepNuevo">
                        </div>
                        <div class="col-md-2">
                            <label>Orden:</label>
                            <input type="text" required class="form-control" id="ordenNuevo">
                        </div>
                        <div class="col-md-6">
                            <label>Monto $:</label>
                            <input class="form-control" onchange="MASK(this,this.value,'-##,###,##0.00',1);" onkeypress="return ValMon(event)" onClick="this.select()" type="text" id="montoNuevo">
                        </div>
                        <div class="col-md-6">
                            <label>Motivo:</label>
                            <select class="form-control" id="tipoNuevo">
                                <option value="1">Devenga (Empleado)</option>
                                <option value="2" selected >Deducción</option>
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
                        <div class="col-md-10">
                            <label>Concepto:</label>
                            <input type="text" class="form-control" id="conceptoVer">
                        </div>
                        <div class="col-md-2">
                            <label>Orden:</label>
                            <input type="text" required class="form-control" id="ordenVer">
                        </div>
                        <div class="col-md-6">
                            <label>Monto $:</label>
                            <input class="form-control" onchange="MASK(this,this.value,'-##,###,##0.00',1);" onkeypress="return ValMon(event)" onClick="this.select()" type="text" id="montoVer">
                        </div>
                        <div class="col-md-6">
                            <label>Motivo:</label>
                            <select class="form-control" id="tipoVer">
                                <option value="1">Devenga (Empleado)</option>
                                <option value="2">Deducción</option>
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
            $('#configura').addClass("show");
        }
        $('#conceptoEgreso').addClass("active");
    });
    function  status(id,Van)
    {
        $.post('concepto-egreso-status.php',{'id_con':id},function(data)
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
        tip=$('#tipoNuevo').val();
        ord=$('#ordenNuevo').val();
        $.post('concepto-egreso-guarda.php',{'concepto':con, 'monto':mon,'tipo':tip,'orden':ord},function(data)
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
    function verConcepto(id,con,mon,tip,ord,lin) 
    {
        $('#linVer').val(lin);
        $('#idVer').val(id);
        $('#conceptoVer').val(con);
        $('#montoVer').val(mon);
        $('#tipoVer').val(tip);
        $('#ordenVer').val(ord);
    }
    function actualConcepto() 
    {
        lin=$('#linVer').val()
        id=$('#idVer').val()
        con=$('#conceptoVer').val()
        mon=$('#montoVer').val()
        tip=$('#tipoVer').val();
        ord=$('#ordenVer').val();   
        $.post('concepto-egreso-actual.php',{'id':id,'concepto':con,'monto':mon,'tipo':tip,'orden':ord},function(data)
        {
            if(data.isSuccessful)
            {
                document.getElementById("concep"+lin).innerHTML = data.conc;
                document.getElementById("mon"+lin).innerHTML = data.monto;
                document.getElementById("tip"+lin).innerHTML = data.tipo;
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
?>
           
