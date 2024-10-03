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
if ($verRecibos==0) {
    $recibos_query = mysqli_query($link,"SELECT recibo,recibo2, fecha, tabla FROM ingresos WHERE fecha>='$desBus' AND fecha<='$hasBus' ");
}
if ($verRecibos==1) {
    $recibos_query = mysqli_query($link,"SELECT recibo,recibo2, fecha, tabla FROM ingresos WHERE recibo>0 and fecha>='$desBus' AND fecha<='$hasBus' ");
}
if ($verRecibos==2) {
    $recibos_query = mysqli_query($link,"SELECT recibo,recibo2, fecha, tabla FROM ingresos WHERE recibo2>0 and fecha>='$desBus' AND fecha<='$hasBus' ");
}
$miscela_query = mysqli_query($link,"SELECT id, fecha, tabla FROM miscelaneos_ingresos WHERE fecha>='$desBus' AND fecha<='$hasBus' ");
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Resumen de Ingresos Diarios</h1>    
        </div>    
        <div class="col-md-3">
            <button type="button" class="btn btn-danger" onclick="btnPdf()" style="width: 100%"><i class="fas fa-file-pdf"></i> Exporta a PDF</button>
        </div>
        <!--div class="col-md-3">
            <button type="button" class="btn btn-success" onclick="excelBtn()" style="width: 100%"><i class="fas fa-file-excel"></i> Exporta a EXCEL</button>
        </div-->
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="ingresos-list.php">
                    <div class="form-row">
                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <label>Desde el:</label>
                            <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
                        </div>
                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <label>Hasta el:</label>
                            <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
                        </div>
                        <!--div class="col-md-4 col-6">
                            <label>Recibos</label>
                            <select class="form-control" name="verRecibos" value="<?= $verRecibos ?>">
                                <option value="0" <?php if($verRecibos==0){echo "selected";} ?>>Todos</option>
                                <option value="1" <?php if($verRecibos==1){echo "selected";} ?>>H.Blanca</option>
                                <option value="2" <?php if($verRecibos==2){echo "selected";} ?>>Fiscales</option>
                            </select>
                        </div-->
                        <input type="hidden" name="verRecibos" value="0">
                        <div class="col-md-4 col-12" >
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Estudiante</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Operador</th>
                            <th>Boton</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Estudiante</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Operador</th>
                            <th>Boton</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($recibos_query)) 
                        {
                            $recibo = ($row['recibo']>0) ? $row['recibo'] : $row['recibo2'] ;
                            $reciboTabla = ($row['recibo']>0) ? 'recibo' : 'recibo2' ;
                            $salio = ($row['recibo']>0) ? '1' : '2' ;
                            $tablaPeriodo=$row['tabla'];
                            $fecha=$row['fecha'];
                            $pagos_query = mysqli_query($link,"SELECT sum(A.montoDolar) as total,A.idAlum,A.statusPago,B.cedula,B.nombre, B.apellido, B.grado,B.seccion,B.Periodo, C.nombreUser FROM pagos".$tablaPeriodo." A, alumcer B, user C WHERE A.$reciboTabla='$recibo' and A.idAlum=B.idAlum and A.emitidoPor=C.idUser ");
                            //$=$row[''];
                            while($row2=mysqli_fetch_array($pagos_query)) 
                            {
                                $idAlum=$row2['idAlum'];
                                $cedula=$row2['cedula'];
                                $alumno=($row2['apellido'].' '.$row2['nombre']);
                                $total=$row2['total'];
                                $statusPago=$row2['statusPago'];
                                $total = ($statusPago==2) ? 'Nulo' : $total.' $' ;
                                $emitidoPor=$row2['nombreUser'];
                                $gradoVer=$row2['grado'];
                                $secciVer=$row2['seccion'];
                                $nombre_periodo=$row2['Periodo'];
                            }?>
                            <tr <?php if($total=='Nulo'){ echo 'style="background-color:#F2D7D5;"';} ?>>
                                <td><?= $son+=1; ?></td>
                                <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                <?php if ($gradoVer<61) {?>
                                    <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-pri-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= $alumno ; ?></td><?php
                                }else
                                {?>
                                    <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= $alumno ; ?></td><?php
                                }?>
                                
                                <td><?= date("d-m-Y H:i", strtotime($fecha)) ?></td>
                                <td align="right"><?= $total ?></td>
                                <td><?= $emitidoPor ?> </td> 
                                <td>
                                    <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#verDetalle" onclick="buscarRecibo('<?= $recibo ?>','<?= $cedula ?>','<?= $alumno ?>','<?= $tablaPeriodo ?>','<?= $emitidoPor ?>','<?= $statusPago ?>','<?= $salio ?>')" ><span style="font-weight: bold;" class="fas fa-eye fa-lg" aria-hidden="true" title="Ver Detalle"></span></button>
                                </td>        
                            </tr><?php
                        } ?>
                    </tbody>
                </table><?php 
                if(mysqli_num_rows($miscela_query) > 0)
                {?>
                    <div class="col-md-12" style="background-color:#16A085; color: white; padding: 2px; margin-top: 2%; "><h3>Ingresos por Miscelaneos</h3> </div>
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Recibo</th>
                                <th>Estudiante</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Operador</th>
                                <th>Boton</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Recibo</th>
                                <th>Estudiante</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Operador</th>
                                <th>Boton</th>
                            </tr>
                        </tfoot>
                        <tbody><?php 
                            $son=0;
                            while($row=mysqli_fetch_array($miscela_query)) 
                            {
                                $recibo=$row['id'];
                                $tablaPeriodo=$row['tabla'];
                                $fecha=$row['fecha'];
                                $pagos_query = mysqli_query($link,"SELECT sum(A.montoDolar) as total,A.idAlum,A.statusPago,B.cedula,B.nombre, B.apellido, B.grado,B.seccion,B.Periodo, C.nombreUser FROM miscelaneos A, alumcer B, user C WHERE A.recibo='$recibo' and A.idAlum=B.idAlum and A.emitidoPor=C.idUser ");
                                //$=$row[''];
                                while($row2=mysqli_fetch_array($pagos_query)) 
                                {
                                    $idAlum=$row2['idAlum'];
                                    $cedula=$row2['cedula'];
                                    $alumno=($row2['apellido'].' '.$row2['nombre']);
                                    $total=$row2['total'];
                                    $statusPago=$row2['statusPago'];
                                    $total = ($statusPago==2) ? 'Nulo' : $total.' $' ;
                                    $emitidoPor=$row2['nombreUser'];
                                    $gradoVer=$row2['grado'];
                                    $secciVer=$row2['seccion'];
                                    $nombre_periodo=$row2['Periodo'];
                                }?>
                                <tr <?php if($total=='Nulo'){ echo 'style="background-color:#F2D7D5;"';} ?>>
                                    <td><?= $son+=1; ?></td>
                                    <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                    <?php if ($gradoVer<61) {?>
                                        <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-pri-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= $alumno ; ?></td><?php
                                    }else
                                    {?>
                                        <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= $alumno ; ?></td><?php
                                    }?>
                                    
                                    <td><?= date("d-m-Y H:i", strtotime($fecha)) ?></td>
                                    <td align="right"><?= $total ?></td>
                                    <td><?= $emitidoPor ?> </td> 
                                    <td>
                                        <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#verDetalleMisc" onclick="buscarMiscRecibo('<?= $recibo ?>','<?= $cedula ?>','<?= $alumno ?>','<?= $emitidoPor ?>','<?= $statusPago ?>')" ><span style="font-weight: bold;" class="fas fa-eye fa-lg" aria-hidden="true" title="Ver Detalle"></span></button>
                                    </td>        
                                </tr><?php
                            } ?>
                        </tbody>
                    </table><?php 
                }?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="verDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Detalle de Recibo:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Recibo #</label>
                            <input type="text" readonly id="nro_recibo" class="form-control">
                            <input type="hidden" id="reciboPrint">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha #</label>
                            <input type="text" class="form-control" readonly id="fechaRecibo">
                        </div>
                        <div class="col-md-4">
                            <label>Monto Total</label>
                            <input type="text" class="form-control" readonly id="montoRecibo">
                        </div>
                        <div class="col-md-4">
                            <label>Cedula</label>
                            <input type="text" class="form-control" readonly id="cedReci">
                        </div>
                        <div class="col-md-8">
                            <label>Estudiante</label>
                            <input type="text" class="form-control" readonly id="alumReci">
                        </div>
                        <div class="col-md-4">
                            <label>Tipo Operacion</label>
                            <input type="text" class="form-control" readonly id="tipoOpera">
                        </div>
                        <div class="col-md-4">
                            <label>Nro.Operacion</label>
                            <input type="text" class="form-control" readonly id="nroOpera">
                        </div>
                        <div class="col-md-4">
                            <label>Banco</label>
                            <input type="text" class="form-control" readonly id="bancoOpera">
                        </div>
                        <div class="col-md-12">
                            <h4 id="msjNulo" style="margin-top: 1%; background-color: #FFCDD2; display:none; text-align: center;">*** RECIBO ANULADO ***</h4>
                            <label>Comentario: </label>
                            <textarea id="comenta" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>Recibo generado por:</label>
                            <input type="text" class="form-control" readonly id="emitidoPor">
                        </div>
                        <div class="col-md-12" style="margin-top: 2%;">
                            <table class="table table-striped table-bordered " cellspacing="0" width="100%" >
                                <thead>
                                    <th style="text-align: center;">Tipo</th>
                                    <th style="text-align: center;">Concepto</th>
                                    <th style="text-align: center;">Monto</th>
                                </thead>
                                <tbody id="cuerpo">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="sale">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" onclick="reimprimeRecibo()" id="btnPrint" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprime Recibo</button>
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
        if (screen.width<500) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        if (screen.width>1024) {
            $('#movimiento').addClass("show");
        }
        $('#listadoIngresos').addClass("active");
    });
    function reimprimeRecibo() {
        reci=$('#reciboPrint').val()
        sale=$('#sale').val();
        window.open("../factura/factura-reimprime-pdf.php?recibo="+reci+"&sale="+sale)
    }
    function reimprimeMiscRecibo() {
        reci=$('#reciboPrintMisc').val()
        window.open("../factura/factura-reimprime-misc-pdf.php?recibo="+reci)
    }
    function buscarRecibo(reci,ced,alu,tabl,emit,sta,sale)
    {
        $("#cuerpo").html("");
        $('#nro_recibo').val(reci);
        $('#cedReci').val(ced);
        $('#alumReci').val(alu);
        $('#emitidoPor').val(emit); 
        $('#sale').val(sale);   
        if(sta==2)
        {
            document.getElementById("btnPrint").disabled = true;
            $('#msjNulo').show(); 
        }else 
        {
            document.getElementById("btnPrint").disabled = false;
            $('#msjNulo').hide(); 
        }
        $.post('buscarRecibo.php',{'recib':reci,'tabla':tabl,'salida':sale},function(data)
        {
            if(data.isSuccessful)
            {
                $('#fechaRecibo').val(data.fechadepo);
                $('#montoRecibo').val(data.total);
                $('#tipoOpera').val(data.operacion);
                $('#nroOpera').val(data.nrodeposito);
                $('#bancoOpera').val(data.banco);
                $('#reciboPrint').val(data.recPrin);
                $('#comenta').val(data.comenta);
                for(var i=0; i<data.options.length; i++)
                {
                    if(data.options[i].codigo<10){tipo='0'+data.options[i].codigo} else{tipo=data.options[i].codigo}
                    var tr = "<tr>"+
                      "<td align='center'>"+tipo+"</td>"+
                      "<td>"+data.options[i].conce+"</td>"+
                      "<td align='right'>"+data.options[i].mont+"</td>"+
                    "</tr>";
                    $("#cuerpo").append(tr)
                }                
            } else
            {
                swal("Error!", "Datos nno encontrados!", "error");
            }
        }, 'json');
    }
    function buscarMiscRecibo(reci,ced,alu,emit,sta)
    {
        $("#cuerpoMisc").html("");
        $('#nro_reciboMisc').val(reci);
        $('#cedReciMisc').val(ced);
        $('#alumReciMisc').val(alu);
        $('#emitidoPorMisc').val(emit);  
        if(sta==2)
        {
            document.getElementById("btnPrintMisc").disabled = true;
            $('#msjNuloMisc').show(); 
        }else 
        {
            document.getElementById("btnPrintMisc").disabled = false;
            $('#msjNuloMisc').hide(); 
        }
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
    function btnPdf()
    {
        window.open('ingresos-pdf.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>&recDes=<?= $recDesde ?>&recHas=<?= $recHasta ?>&salen=<?= $verRecibos ?>&filtro='+$('#example_filter').find('input').val())
    }
    /*function excelBtn() 
    {
        window.open('ingresos-excel.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>&recDes=<?= $recDesde ?>&recHas=<?= $recHasta ?>')
    }*/
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php"; 
mysqli_free_result($recibos_query);
mysqli_free_result($miscela_query);
mysqli_free_result($pagos_query);
?>
           