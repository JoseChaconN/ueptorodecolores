<?php
include_once "../include/header.php";
$link = Conectarse();
$query = mysqli_query($link,"SELECT * FROM impresora  "); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Listado de Impresoras para Facturación</h1>    
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
                        <button type="button" data-toggle="modal" data-target="#nuevoConcepto" class="btn btn-primary" style="width: 100%;"><span class="fas fa-plus fa-sm" ></span> Nueva Impresora</button><br><br>
                    </div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Impresora</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Impresora</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $id=$row['id'];
                            $nombre=$row['nombre'];

                            $superior_FIS=$row['superior_FIS'];
                            $izquierdo_FIS=$row['izquierdo_FIS'];
                            $copia_FIS=$row['copia_FIS'];

                            $superior_HB=$row['superior_HB'];
                            $izquierdo_HB=$row['izquierdo_HB'];
                            $copia_HB=$row['copia_HB'];
                            
                            $status=$row['status'];
                            $btn_class = ($status == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($status == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($status== 1) ? 'ACTIVO' : 'DESACTIVADO';
                            $son++; ?>
                            <tr id="linea<?= $son ?>">
                                <td><?= $son; ?></td>
                                <td id="concep<?= $son ?>"><?= $nombre; ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group">
                                        <button onclick="verConcepto('<?= $id ?>','<?= $son ?>','<?= $nombre ?>','<?= $superior_FIS ?>','<?= $izquierdo_FIS ?>','<?= $copia_FIS ?>','<?= $superior_HB ?>','<?= $izquierdo_HB ?>','<?= $copia_HB ?>')" title="Modificar Concepto" data-toggle="modal" data-target="#modiConcepto" type="button" class="btn btn-success btn-circle"  ><i class="fas fa-eye " ></i></button>
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nueva Impresora:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre de la Impresora:</label>
                            <input type="text" required class="form-control" id="nombre">
                        </div>
                        <div class="col-md-4">
                            <label>Margen Superior:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="supe_fis">
                        </div>
                        <div class="col-md-4">
                            <label>Margen Izquierdo:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="izqu_fis">
                        </div>
                        <div class="col-md-4">
                            <label>Separación Copia:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="copi_fis">
                        </div>

                        <!--div class="col-md-4">
                            <label>Superior Hoja Blanca:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="supe_hb">
                        </div>
                        <div class="col-md-4">
                            <label>Izquierdo Hoja Blanca:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="izqu_hb">
                        </div>
                        <div class="col-md-4">
                            <label>Copia Hoja Blanca:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="copi_hb">
                        </div-->
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="guardaConcepto()" class="btn btn-primary">Guardar Impresora</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modiConcepto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
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
                            <label>Nombre de la Impresora:</label>
                            <input type="text" required class="form-control" id="nombreVer">
                        </div>
                        <div class="col-md-4">
                            <label>Margen Superior:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="supe_fisVer">
                        </div>
                        <div class="col-md-4">
                            <label>Margen Izquierdo:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="izqu_fisVer">
                        </div>
                        <div class="col-md-4">
                            <label>Separación Copia:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="copi_fisVer">
                        </div>

                        <!--div class="col-md-4">
                            <label>Superior Hoja Blanca:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="supe_hbVer">
                        </div>
                        <div class="col-md-4">
                            <label>Izquierdo Hoja Blanca:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="izqu_hbVer">
                        </div>
                        <div class="col-md-4">
                            <label>Copia Hoja Blanca:</label>
                            <input class="form-control" onClick="this.select()" type="text" id="copi_hbVer">
                        </div-->
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
        $('#margenFactura').addClass("active");
    });
    function  status(id,Van)
    {
        $.post('print-status.php',{'id':id},function(data)
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
        nom=$('#nombre').val();
        sup_fis=$('#supe_fis').val();
        izq_fis=$('#izqu_fis').val();
        cop_fis=$('#copi_fis').val();
        sup_hb=0; //$('#supe_hb').val();
        izq_hb=0; //$('#izqu_hb').val();
        cop_hb=0; //$('#copi_hb').val();
        $.post('print-guarda.php',{'nombre':nom, 'sup_fis':sup_fis,'izq_fis':izq_fis,'cop_fis':cop_fis,'sup_hb':sup_hb,'izq_hb':izq_hb,'cop_hb':cop_hb },function(data)
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
    function verConcepto(id,lin,nom,s_fis,i_fis,c_fis,s_hb,i_hb,c_hb) 
    {
        $('#nombreVer').val(nom);
        $('#supe_fisVer').val(s_fis);
        $('#izqu_fisVer').val(i_fis);
        $('#copi_fisVer').val(c_fis);
        /*$('#supe_hbVer').val(s_hb);
        $('#izqu_hbVer').val(i_hb);
        $('#copi_hbVer').val(c_hb);*/
        $('#linVer').val(lin);
        $('#idVer').val(id);
    }
    function actualConcepto() 
    {
        lin=$('#linVer').val()
        id=$('#idVer').val()
        nom=$('#nombreVer').val();
        s_fis=$('#supe_fisVer').val();
        i_fis=$('#izqu_fisVer').val();
        c_fis=$('#copi_fisVer').val();
        s_hb=0; //$('#supe_hbVer').val();
        i_hb=0; //$('#izqu_hbVer').val();
        c_hb=0; //$('#copi_hbVer').val();
        $.post('print-actual.php',{'id':id,'nombre':nom,'sup_fis':s_fis,'izq_fis':i_fis,'cop_fis':c_fis,'sup_hb':s_hb,'izq_hb':i_hb,'cop_hb':c_hb },function(data)
        {
            if(data.isSuccessful)
            {
                document.getElementById("concep"+lin).innerHTML = nom;
                $('#modiConcepto').modal('hide')
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos actualizados satisfactoriamente!'
                })
                window.parent.location.reload();
            } 
        }, 'json');
    }
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           