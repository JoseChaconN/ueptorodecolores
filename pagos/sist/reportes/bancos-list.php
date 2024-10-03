<?php
include_once "../include/header.php";
$link = Conectarse();

if(!isset($_POST['desde']) && !isset($_POST['hasta']))
{
    $desde = strftime( "%Y-%m-%d") ;
    $hasta = strftime( "%Y-%m-%d") ;
    $banco_query = mysqli_query($link,"SELECT cod_banco FROM bancos WHERE banco_mio='X' LIMIT 1 ");
    while($row = mysqli_fetch_array($banco_query))
    {
        $cod_banco1=$row['cod_banco'];
    }
} else
{
    $desde = $_POST['desde'] ;
    $hasta = $_POST['hasta'] ;
    $cod_banco1=$_POST['bancoIngreso'];
}
$desBus=$desde.' 00:00:00';
$hasBus=$hasta.' 23:59:59';

$recibos_query = mysqli_query($link,"SELECT recibo,recibo2, tabla FROM ingresos WHERE (fechaTransf>='$desBus' and fechaTransf<='$hasBus') or (fechaDebito>='$desBus' and fechaDebito<='$hasBus') or (fechaPagMovil>='$desBus' and fechaPagMovil<='$hasBus') ");

$miscela_query = mysqli_query($link,"SELECT id, tabla FROM miscelaneos_ingresos WHERE (fechaTransf>='$desBus' and fechaTransf<='$hasBus') or (fechaDebito>='$desBus' and fechaDebito<='$hasBus') or (fechaPagMovil>='$desBus' and fechaPagMovil<='$hasBus') ");

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Movimiento de Ingresos y Egresos en Bancos</h1>    
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
                <form role="form" method="POST" enctype="multipart/form-data" action="bancos-list.php">
                    <div class="form-row">
                        <div class="col-md-3 col-12">
                           <label>Banco:</label>
                            <select name="bancoIngreso" id="bancoIngreso" class="form-control"><?php 
                                $transf_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                while($row = mysqli_fetch_array($transf_query))
                                {
                                    $cod_banco=$row['cod_banco'];
                                    $nom_banco=$row['nom_banco'];
                                    $cuenta_nro=$row['cuenta_nro'];
                                    $selected ='';
                                    if($cod_banco1 == $cod_banco){$selected='selected';}
                                    echo '<option readonly value="'.$cod_banco.'"'.$selected.' >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                }?>
                            </select> 
                        </div>
                        <div class="col-md-3 col-6">
                            <label>Desde el:</label>
                            <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
                        </div>
                        <div class="col-md-3 col-6">
                            <label>Hasta el:</label>
                            <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
                        </div>
                        <div class="col-md-3 col-12" style="margin-top: 3%;">
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <div class="col-md-12" style="background-color:#ABEBC6; padding: 2px; margin-bottom: 1%; "><h3>Ingresos al banco por Transferencia, Tarj.Debito, Pag.Movil</h3> </div>
                <table class="table table-bordered claseNueva" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th># Operación</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th># Operación</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($recibos_query)) 
                        {
                            //$recibo=$row['id'];
                            $tablaPeriodo=$row['tabla'];
                            $recibo = ($row['recibo']>0) ? $row['recibo'] : $row['recibo2'] ;
                            $reciboTabla = ($row['recibo']>0) ? 'recibo' : 'recibo2' ;
                            $tipRecibo = ''; //($row['recibo']>0) ? 'H-' : 'F-' ;
                            $impresion = ($row['recibo']>0) ? '1' : '2' ;
                            $sist = ''; //(strlen($tablaPeriodo)==4) ? '-Reg' : '-Adu' ;
                            $pagos_query = mysqli_query($link,"SELECT sum(A.monto) as todo,A.fechadepo,A.nrodeposito,A.idAlum,B.cedula,B.nombre, B.apellido, B.grado,B.seccion,B.Periodo, C.nombreUser,D.abrev FROM pagos".$tablaPeriodo." A, alumcer B, user C, formas_pago D WHERE A.statusPago=1 and A.$reciboTabla='$recibo' and A.banco='$cod_banco1' and A.idAlum=B.idAlum and A.emitidoPor=C.idUser and A.operacion=D.id GROUP BY A.recibo,A.operacion  ");
                            //$=$row[''];
                            $monto=0;
                            while($row2=mysqli_fetch_array($pagos_query)) 
                            {
                                $monto=$row2['todo'];
                                $nrodeposito=$row2['nrodeposito'];
                                $fecDep=$row2['fechadepo'];
                                $fechadepo=date("d-m-Y", strtotime($row2['fechadepo']));
                                $idAlum=$row2['idAlum'];
                                $cedula=$row2['cedula'];
                                $alumno=($row2['apellido'].' '.$row2['nombre']);
                                $emitidoPor=$row2['nombreUser'];
                                $nombrePago=$row2['abrev'];
                            
                                if($monto>0 && $fecDep>=$desde && $fecDep<=$hasta)
                                {?>
                                    <tr>
                                        <td><?= $son+=1; ?></td>
                                        <td><?= $tipRecibo.str_pad($recibo, 6, "0", STR_PAD_LEFT).$sist ?></td>
                                        <td><?= $nrodeposito.'/'.$nombrePago ?> </td> 
                                        <td><?= $fechadepo ?></td>
                                        <td align="right"><?= $monto ?></td>
                                        
                                        <td>
                                            <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#verDetalle" onclick="buscarRecibo('<?= $recibo ?>','<?= $cedula ?>','<?= $alumno ?>','<?= $tablaPeriodo ?>','<?= $emitidoPor ?>','<?= 1 ?>','<?= $impresion ?>')" ><span style="font-weight: bold;" class="fas fa-eye fa-lg" aria-hidden="true" title="Ver Detalle"></span></button>
                                        </td>        
                                    </tr><?php
                                }
                            }
                        } ?>
                    </tbody>
                </table>
                <div class="col-md-12" style="background-color:#16A085; color: white; padding: 2px; margin-bottom: 1%; "><h3>Ingresos al banco Miscelaneos por Transfer., Tarj.Deb., Pag.Movil</h3> </div>
                <table class="table table-bordered claseNueva" id="dataTable2" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th># Operación</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th># Operación</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row3=mysqli_fetch_array($miscela_query)) 
                        {
                            $recibo=$row3['id'];
                            $tablaPeriodo=$row3['tabla'];
                            $pagos_miscela_query = mysqli_query($link,"SELECT sum(A.monto) as todo,A.fechadepo,A.nrodeposito,A.idAlum,B.cedula,B.nombre, B.apellido, B.grado,B.seccion,B.Periodo, C.nombreUser,D.abrev FROM miscelaneos A, alumcer B, user C, formas_pago D WHERE A.statusPago=1 and A.recibo='$recibo' and A.banco='$cod_banco1' and A.idAlum=B.idAlum and A.emitidoPor=C.idUser and A.operacion=D.id GROUP BY A.recibo,A.operacion  ");
                            //$=$row[''];
                            $monto=0;
                            while($row4=mysqli_fetch_array($pagos_miscela_query)) 
                            {
                                $monto=$row4['todo'];
                                $nrodeposito=$row4['nrodeposito'];
                                $fecDep=$row4['fechadepo'];
                                $fechadepo=date("d-m-Y", strtotime($row4['fechadepo']));
                                $idAlum=$row4['idAlum'];
                                $cedula=$row4['cedula'];
                                $alumno=($row4['apellido'].' '.$row2['nombre']);
                                $emitidoPor=$row4['nombreUser'];
                                $nombrePago=$row4['abrev'];
                            
                                if($monto>0 && $fecDep>=$desde && $fecDep<=$hasta)
                                {?>
                                    <tr>
                                        <td><?= $son+=1; ?></td>
                                        <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                        <td><?= $nrodeposito.'/'.$nombrePago ?> </td> 
                                        <td><?= $fechadepo ?></td>
                                        <td align="right"><?= $monto ?></td>
                                        
                                        <td>
                                            <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#verDetalleMisc" onclick="buscarMiscRecibo('<?= $recibo ?>','<?= $cedula ?>','<?= $alumno ?>','<?= $emitidoPor ?>','<?= 1 ?>')" ><span style="font-weight: bold;" class="fas fa-eye fa-lg" aria-hidden="true" title="Ver Detalle"></span></button>
                                        </td>        
                                    </tr><?php
                                }
                            }
                        } ?>
                    </tbody>
                </table>
                <div class="col-md-12" style="background-color:#FC7A6D; padding: 2px; color: white; margin-bottom: 1%; "><h3>Egresos por pagos desde el banco con Transferencia o Pag.Movil</h3> </div>
                <table class="table table-bordered claseNueva" id="dataTable3" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th># Operación</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th># Operación</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        $egresos_query = mysqli_query($link,"SELECT sum(A.montoBs) as todo,A.recibo, A.fecha_depo,A.refePag,A.id_provee,B.cedula,B.nombre, B.apellido, C.nombreUser, D.abrev FROM egresos A, alumcer B, user C, formas_pago D WHERE A.status_egreso='1' and A.fecha_depo>='$desBus' and A.fecha_depo<='$hasBus' and  A.banco='$cod_banco1' and A.id_provee=B.idAlum and A.emitidoPor=C.idUser and A.operacion=D.id GROUP BY A.recibo,A.operacion  ");
                        $monto2=0;
                        while($row3=mysqli_fetch_array($egresos_query)) 
                        {
                            $recibo=$row3['recibo'];
                            $monto2=$row3['todo'];
                            $nrodeposito=$row3['refePag'];
                            $fechadepo=date("d-m-Y", strtotime($row3['fecha_depo']));
                            $idAlum=$row3['id_provee'];
                            $cedula=$row3['cedula'];
                            $alumno=($row3['apellido'].' '.$row3['nombre']);
                            $emitidoPor=$row3['nombreUser'];
                            $nombrePago=$row3['abrev']; ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                <td><?= $nrodeposito.' / '.$nombrePago ?> </td> 
                                <td><?= $fechadepo ?></td>
                                <td align="right"><?= $monto2 ?></td>
                                
                                <td>
                                    <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#verPago" onclick="verPago('<?= $recibo ?>','<?= $son ?>','<?= $alumno ?>')" ><span style="font-weight: bold;" class="fas fa-eye fa-lg" aria-hidden="true" title="Ver Detalle"></span></button>
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
                        <input type="hidden" id="sale">
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
<div class="modal fade" id="verPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Beneficiario</label>
                            <input type="text" id="beneficia" readonly class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Recibo Nro.</label>
                            <input type="text" id="recibo" readonly class="form-control">
                            <input type="hidden" id="reciboPrint">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha</label>
                            <input type="text" id="fecha" readonly class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tasa Bs.</label>
                            <input type="text" id="tasa" readonly class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Monto $</label>
                            <input type="text" id="dolar" readonly class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Monto Bs.</label>
                            <input type="text" id="bolivar" readonly class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Procesado por:</label>
                            <input type="text" id="usuario" readonly class="form-control">
                        </div>
                        <div class="col-md-12">
                            <h4 id="msjNulo" style="margin-top: 1%; background-color: #FFCDD2; display:none; text-align: center;">*** RECIBO ANULADO ***</h4>
                            <label>Comentario: </label>
                            <textarea id="comenta" class="form-control" readonly rows="3"></textarea>
                        </div>
                        <div class="col-md-12" style="margin-top: 2%;">
                            <table class="table table-striped table-bordered " cellspacing="0" width="100%" >
                                <thead>
                                    <th >Concepto</th>
                                    <th style="text-align: center;">Monto $</th>
                                    <th style="text-align: center;">Monto Bs.</th>
                                </thead>
                                <tbody id="cuerpo2">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center" style="padding: 12px; ">
                    <label><input type="checkbox" id="enviar" style="transform: scale(2);"> 
                    &nbsp;&nbsp;Marque aqui para enviar recibo al correo del proveedor luego haga clic en imprimir </label>
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Cerrar Ventana</button>
                <button type="button" data-toggle="modal" data-target="#copiasRecibo" id="btnPrint" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button>
            </div>
            <!--input type="hidden" id="recibo"-->
            <input type="hidden" id="linea">
        </div>
    </div>
</div>
<div class="modal fade" id="copiasRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: #FFCCBC; ">
            <div class="modal-header">
                <h5 class="modal-title">Copias del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Numero de Copia por Pagina</label>
                            <select class="form-control" id="copiaPag">
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Rechazar Reimpresión</button>
                <button type="button" onclick="reimprimeRecibo2()" class="btn btn-danger">Reimprimir</button>
            </div>
        </div>
    </div>
</div><?php 
include_once "../include/footer.php";?>
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
        $('#listadoIngresoBanco').addClass("active");
        $('.claseNueva').dataTable();
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
    function reimprimeRecibo2() {
        reci=$('#reciboPrint').val()
        copi=$('#copiaPag').val()
        if($("#enviar").prop('checked')) {
            window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&envia=1&cop="+copi)    
        }else {window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&cop="+copi)}
        
    }
    function buscarRecibo(reci,ced,alu,tabl,emit,sta,salio)
    {
        $("#cuerpo").html("");
        $('#nro_recibo').val(reci);
        $('#cedReci').val(ced);
        $('#alumReci').val(alu);
        $('#emitidoPor').val(emit);  
        $('#sale').val(salio);
        if(sta==2)
        {
            document.getElementById("btnPrint").disabled = true;
            $('#msjNulo').show(); 
        }else 
        {
            document.getElementById("btnPrint").disabled = false;
            $('#msjNulo').hide(); 
        }
        $.post('buscarRecibo.php',{'recib':reci,'tabla':tabl,'salida':salio},function(data)
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
        window.open('bancos-pdf.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>&banco=<?= $cod_banco1 ?>&filtro='+$('#example_filter').find('input').val())
    }
    /*function excelBtn() 
    {
        window.open('ingresos-excel.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>&recDes=<?= $recDesde ?>&recHas=<?= $recHasta ?>')
    }*/
    function verPago(reci,lin,nom)
    {
        $("#cuerpo2").html("");
        $('#recibo').val(reci);
        $('#linea').val(lin);
        $.post('../procesos/provedor-historia-buscar.php',{'recib':reci},function(data)
        {
            $('#beneficia').val(nom);
            if(data.isSuccessful)
            {
                $('#fecha').val(data.fecha);
                $('#forma').val(data.formaPag);
                $('#dolar').val(data.totalDolar);
                $('#bolivar').val(data.totalBs);
                $('#tasa').val(data.tasa);
                $('#referencia').val(data.nrodeposito);
                $('#banco').val(data.banco);
                $('#usuario').val(data.emitidoPor);  
                $('#comenta').val(data.comenta); 
                $('#reciboPrint').val(data.recPrint);
                if(data.status=='2')
                {
                    $('#btnAnula').hide();
                    $('#btnRecupera').show();
                    document.getElementById('msjNulo').style.display = 'block';
                } else 
                {
                    $('#btnAnula').show();
                    $('#btnRecupera').hide();
                    document.getElementById('msjNulo').style.display = 'none';
                }
                for(var i=0; i<data.options.length; i++)
                {
                    if(data.options[i].codigo<10){tipo='0'+data.options[i].codigo} else{tipo=data.options[i].codigo}
                    var tr = "<tr>"+
                      "<td>"+data.options[i].conce+"</td>"+
                      "<td align='right'>"+data.options[i].dolar+" $</td>"+
                      "<td align='right'>"+data.options[i].bolivar+" Bs.</td>"+
                    "</tr>";
                    $("#cuerpo2").append(tr)
                }
            } else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Alerta!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos no encontrados!'
                })
            }
        }, 'json');
    }
    
</script>
<!-- /.container-fluid --><?php

mysqli_free_result($banco_query);
mysqli_free_result($recibos_query);
mysqli_free_result($miscela_query);
mysqli_free_result($transf_query);
mysqli_free_result($pagos_query);
mysqli_free_result($pagos_miscela_query);
mysqli_free_result($egresos_query);
?>
           