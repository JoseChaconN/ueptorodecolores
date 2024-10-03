<?php
include_once "../include/header.php";
$link = Conectarse();
if(!isset($_POST['desde']) && !isset($_POST['hasta']))
{
    $desde = strftime( "%Y-%m-%d") ;
    $hasta = strftime( "%Y-%m-%d") ;
    $id=desencriptar($_GET['id']);
    $concepto=$_GET['con'];
} else
{
    $desde = $_POST['desde'] ;
    $hasta = $_POST['hasta'] ;
    $id=desencriptar($_POST['id']);
    $concepto=$_POST['con'];
}

$stock_query = mysqli_query($link,"SELECT SUM(IF(proceso=1 and statusPago=1,cantidad,0)) as suma, SUM(IF(proceso=2 and statusPago=1,cantidad,0)) as resta FROM miscelaneos WHERE id_concepto='$id'  ");
$sto=mysqli_fetch_array($stock_query);
$dispo=$sto['suma']-$sto['resta']; ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Movimientos de <?= $concepto ?><br>Stock Disponible (<?= $dispo.' Unid.' ?>)</h1>    
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger" onclick="btnPdf()" style="width: 100%"><i class="fas fa-file-pdf"></i> Exporta a PDF</button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="inventario-movimiento.php">
                    <div class="form-row">
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <label>Desde el:</label>
                            <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <label>Hasta el:</label>
                            <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-12" >
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <label>&nbsp;</label>
                            <button type="button" style="width: 100%; color: black;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button><br><br>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?= encriptar($id) ?>">
                    <input type="hidden" name="con" value="<?= $concepto ?>">
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Operación</th>
                            <th>Procesado por</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Operación</th>
                            <th>Procesado por</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=1;
                        $stock_query = mysqli_query($link,"SELECT A.idAlum,A.recibo,A.fecha,A.proceso,A.cantidad,B.nombreUser FROM miscelaneos A, user B WHERE A.statusPago=1 and A.id_concepto='$id' and A.emitidoPor=B.idUser and A.fecha>='$desde' and A.fecha<='$hasta' ");
                        while($row=mysqli_fetch_array($stock_query)) 
                        {
                            $recibo = ($row['recibo']>0) ? str_pad($row['recibo'], 6, "0", STR_PAD_LEFT) : 'N/A' ;
                            $nroRecibo=$row['recibo'];
                            $fecha=$row['fecha'];
                            $proceso=$row['proceso'];
                            $idAlum=$row['idAlum'];
                            if($proceso==2)
                            {
                                $alumno_query = mysqli_query($link,"SELECT cedula,nombre, apellido FROM alumcer WHERE idAlum='$idAlum' ");
                                $row2=mysqli_fetch_array($alumno_query);
                                $cedula=$row2['cedula'];
                                $alumno=($row2['apellido'].' '.$row2['nombre']);
                            }else
                            {
                                $cedula=''; $alumno='';
                            }
                            $cantidad=$row['cantidad'];
                            $nombreUser=$row['nombreUser'];
                            $opera = ($proceso==1) ? 'Agregado' : 'Vendido' ;
                            //$=$row['']; ?>
                            <tr id="linea<?= $son ?>">
                                <td><?= $son; ?></td>
                                <td ><?= $recibo ?></td>
                                <td><?= date("d-m-Y ", strtotime($fecha)) ?></td>
                                <td align="center" ><?= $cantidad.' Unid.'; ?></td>
                                <td align="center" ><?= $opera ?></td>
                                <td><?= $nombreUser ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group ">
                                        <button type="button" <?php 
                                        if ($proceso==1) {echo 'disabled';} ?> class="btn btn-success btn-circle" data-toggle="modal" data-target="#verDetalleMisc" onclick="buscarMiscRecibo('<?= $nroRecibo ?>','<?= $cedula ?>','<?= $alumno ?>','<?= $nombreUser ?>')" ><span style="font-weight: bold;" class="fas fa-eye fa-lg" aria-hidden="true" title="Ver Detalle"></span></button>
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
<div class="modal fade" id="verDetalleMisc" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Detalle de Miscelaneos:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Recibo #</label>
                            <input type="text" readonly id="nro_reciboMisc" class="form-control">
                            <input type="hidden" id="reciboPrintMisc">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha #</label>
                            <input type="text" class="form-control" readonly id="fechaReciboMisc">
                        </div>
                        <div class="col-md-4">
                            <label>Monto Total</label>
                            <input type="text" class="form-control" readonly id="montoReciboMisc">
                        </div>
                        <div class="col-md-4">
                            <label>Cedula</label>
                            <input type="text" class="form-control" readonly id="cedReciMisc">
                        </div>
                        <div class="col-md-8">
                            <label>Estudiante</label>
                            <input type="text" class="form-control" readonly id="alumReciMisc">
                        </div>
                        <div class="col-md-4">
                            <label>Tipo Operacion</label>
                            <input type="text" class="form-control" readonly id="tipoOperaMisc">
                        </div>
                        <div class="col-md-4">
                            <label>Nro.Operacion</label>
                            <input type="text" class="form-control" readonly id="nroOperaMisc">
                        </div>
                        <div class="col-md-4">
                            <label>Banco</label>
                            <input type="text" class="form-control" readonly id="bancoOperaMisc">
                        </div>
                        <div class="col-md-12">
                            <h4 id="msjNuloMisc" style="margin-top: 1%; background-color: #FFCDD2; display:none; text-align: center;">*** RECIBO ANULADO ***</h4>
                            <label>Comentario: </label>
                            <textarea id="comentaMisc" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>Recibo generado por:</label>
                            <input type="text" class="form-control" readonly id="emitidoPorMisc">
                        </div>
                        <div class="col-md-12" style="margin-top: 2%;">
                            <table class="table table-striped table-bordered " cellspacing="0" width="100%" >
                                <thead>
                                    <th style="text-align: center;">Tipo</th>
                                    <th style="text-align: center;">Concepto</th>
                                    <th style="text-align: center;">Monto</th>
                                </thead>
                                <tbody id="cuerpoMisc">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="reimprimeMiscRecibo()" id="btnPrintMisc" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprime</button>
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
            $('#movimiento').addClass("show");
        }
        $('#inventarioList').addClass("active");
    });
    function btnPdf()
    {
        window.open('inventario-movimiento-pdf.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>&idCon=<?= $id ?>&conce=<?= $concepto ?>')
    }
    function buscarMiscRecibo(reci,ced,alu,emit)
    {
        $("#cuerpoMisc").html("");
        $('#nro_reciboMisc').val(reci);
        $('#cedReciMisc').val(ced);
        $('#alumReciMisc').val(alu);
        $('#emitidoPorMisc').val(emit);  
        
        document.getElementById("btnPrintMisc").disabled = false;
        $('#msjNuloMisc').hide(); 
        
        $.post('buscarMiscRecibo.php',{'recib':reci},function(data)
        {
            if(data.isSuccessful)
            {
                $('#fechaReciboMisc').val(data.fechadepo);
                $('#montoReciboMisc').val(data.total);
                $('#tipoOperaMisc').val(data.operacion);
                $('#nroOperaMisc').val(data.nrodeposito);
                $('#bancoOperaMisc').val(data.banco);
                $('#reciboPrintMisc').val(data.recPrin);
                $('#comentaMisc').val(data.comenta);
                for(var i=0; i<data.options.length; i++)
                {
                    if(data.options[i].codigo<10){tipo='0'+data.options[i].codigo} else{tipo=data.options[i].codigo}
                    var tr = "<tr>"+
                      "<td align='center'>"+tipo+"</td>"+
                      "<td>"+data.options[i].conce+"</td>"+
                      "<td align='right'>"+data.options[i].mont+"</td>"+
                    "</tr>";
                    $("#cuerpoMisc").append(tr)
                }                
            } else
            {
                swal("Error!", "Datos no encontrados!", "error");
            }
        }, 'json');
    }
    function reimprimeMiscRecibo() {
        reci=$('#reciboPrintMisc').val()
        window.open("../factura/factura-reimprime-misc-pdf.php?recibo="+reci)
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           