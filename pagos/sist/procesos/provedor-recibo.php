<?php
include_once "../include/header.php";
$link = Conectarse();
if(isset($_GET['id']))
{
    $idAlum=desencriptar($_GET['id']);
    $tasa_query = mysqli_query($link,"SELECT monto,paga_desde,paga_hasta,cuentaEgreso FROM tasa_dia WHERE idTasa='1' "); 
    $row2=mysqli_fetch_array($tasa_query);
    $tasa=$row2['monto'];
    $paga_desde=$row2['paga_desde'];
    $paga_hasta=$row2['paga_hasta'];
    $cuentaEgreso=$row2['cuentaEgreso'];
    
    $datos_query = mysqli_query($link,"SELECT nombre, cedula, apellido, telefono, correo FROM alumcer WHERE idAlum='$idAlum'  "); 
    while ($row = mysqli_fetch_array($datos_query))
    {   
        $cedula = $row['cedula'];
        $nombre = $row['nombre'].' '.$row['apellido'];
        $telefono=$row['telefono'];
        $correo=$row['correo'];
    }?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="form-row">
            <div class="col-md-8">
                <h1 class="h3 mb-2 text-gray-800">Emisión de Egreso </h1>
            </div>
            <div class="col-md-4">
                <button type="button" style="width: 100%; color: black;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
            </div>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
           <div class="card-body">
                <div class="table-responsive">
                    <div class="form-row"><!--CED NOMB GRADO-->
                        <div class="col-md-2 col-xs-12 col-sm-2">
                            <label>Cedula/Rif</label>
                            <input type="text" readonly value="<?= $cedula;?>" class="form-control">
                        </div>
                        <input type="hidden" name="ced_alu" value="<?= $cedula ?>">
                        <div class="col-md-4 col-xs-12 col-sm-5">
                            <label>Proveedor</label>
                            <input type="text" readonly value="<?= $nombre;?>" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12 col-sm-5">
                            <label>Telefono</label>
                            <input type="text" readonly value="<?= $telefono; ?>" class="form-control">
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-5">
                            <label>Correo</label>
                            <input type="text" readonly value="<?= $correo; ?>" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row" style="margin-top: 1%; "><!--TABLA DE HISTORIA-->
                        <div class="col-md-12" style="height: 40px;">
                            <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;    background: #5499C7; color: white;" onclick="fnShowSecciones('#historia_1','#btn_2');"><i class="fas fa-chevron-right f26em" id="btn_2"> </i>
                            <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Historia de Pagos</strong></button>
                        </div><?php 
                        $provee_query=mysqli_query($link,"SELECT * FROM egresos where id_provee='$idAlum' "); ?>
                            <div class="col-md-12" style="display: none;" id="historia_1">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Recibo</th>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Monto $</th>
                                            <th>Monto Bs.</th>
                                            <th>Status</th>
                                            <th>Boton</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Recibo</th>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Monto $</th>
                                            <th>Monto Bs.</th>
                                            <th>Status</th>
                                            <th>Boton</th>
                                        </tr>
                                    </tfoot>
                                    <tbody><?php 
                                        $son=0;
                                        mysqli_data_seek($provee_query, 0);
                                        while($row=mysqli_fetch_array($provee_query)) 
                                        {
                                            $recibo=$row['recibo'];
                                            $fecha=date("d-m-Y", strtotime($row['fecha_egreso']));
                                            $concepto=$row['concepto_pago'];
                                            $montoBs=$row['montoBs'];
                                            $montoDolar=$row['montoDolar'];
                                            $tasaDolar=$row['tasaDolar'];
                                            $statusPago=$row['status_egreso'];

                                            $nrodeposito=$row['nrodeposito'];
                                            $fechadepo=date("d-m-Y", strtotime($row['fechadepo']));
                                            $id_concepto=$row['id_concepto'];
                                            $comentario=$row['comentario'];
                                            $nombrePago=$row['nombrePago'];
                                            $emitidoPor=$row['emitidoPor'];
                                            
                                            $status = ($statusPago=='1') ? 'Activo' : 'Anulado' ;
                                            $nom_banco=$row['nom_banco'];
                                            $opera='Operación: '.$nombrePago.' Banco: '.$nom_banco.', Ref.: '.$nrodeposito;?>
                                            <tr <?php if($statusPago=='2'){ echo 'style="background-color:#FFCDD2; "';} ?> class="<?= 'trLin'.$recibo ?>">
                                                <td><?= $son+=1; ?></td>
                                                <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                                <td><?= $fecha ?></td>
                                                <td><span data-toggle="tooltip" data-placement="top" title="<?= $opera ?>"><?= substr($concepto,0,20) ?></span></td>
                                                <td align="right"><?= $montoDolar ?></td>
                                                <td align="right"><?= number_format($montoBs,2,',','.') ?></td>
                                                <td class="<?= 'sta'.$recibo ?>"><?= $status ?></td>
                                                <td>
                                                    <button onclick="verPago('<?= $recibo ?>','<?= $son ?>','<?= $nombre ?>')" type="button" title='Ver datos del Pago' data-toggle="modal" data-target="#verPago" class="btn btn-info btn-circle" ><i class="fas fa-eye fa-lg" ></i></button>
                                                </td>
                                            </tr><?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                    <form id="facturaForm" method="POST" target="_blank" action="provedor-recibo-nuevo.php" autocomplete="off" onsubmit="return validacion()">
                        <div class="form-row">
                            <div class="col-md-3"><!--TASA DEL DIA-->
                                <label>Tasa del Día Bs.</label>
                                <input type="text" onClick="this.select()" onchange="MASK(this,this.value,'-##,###,##0.00',1); totalRecibo(); actualTasa()" class="form-control" style="text-align: right; " name="tasaDolar" id="tasaDolar" value="<?= $tasa ?>">
                            </div>
                            <div class="col-md-3"><!--TASA DEL DIA-->
                                <label>Forma de Pago General:</label>
                                <select id="formaGen" onchange="todasForma();" class="form-control"><?php 
                                    $fpag_query = mysqli_query($link,"SELECT id,nombrePago FROM formas_pago WHERE status='1' ");
                                    while($row = mysqli_fetch_array($fpag_query))
                                    {
                                        $id=$row['id'];
                                        $nombrePago=$row['nombrePago'];
                                        echo '<option readonly value="'.$id.'" >'.utf8_encode($nombrePago)."</option>";
                                    }?>
                                </select>
                            </div>
                            <div class="col-md-3"><!--TASA DEL DIA-->
                                <label>Convierte Bs. a Dolar</label>
                                <input type="text" onClick="this.select()" onchange="MASK(this,this.value,'-##,###,##0.00',1); " placeholder="cantidad en Bs." onkeyup="calculaDolar()" class="form-control" style="text-align: center; " id="bs_x_dolar" >
                            </div>
                            <div class="col-md-3"><!--TASA DEL DIA-->
                                <label>Resultado en $.</label>
                                <input type="text" readonly class="form-control" style="text-align: center; " id="dolar_x_bs" value="0.00">
                            </div>
                        </div>
                        <div class="form-row text-center" style="background-color: #C5CAE9; margin-bottom: 1%; margin-top: 1%; "><!--TITULO-->
                            <div class="col-md-2">
                                <h5>Cod.</h5>
                            </div>
                            <div class="col-md-3">
                                <h5>Detalle</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>F.Pago</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Monto $</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Monto Bs.</h5>
                            </div>
                            <div class="col-md-1">
                                <h5></h5>
                            </div>
                        </div>
                        <div class="form-row"><?php  
                            for ($i=1; $i < 11; $i++) { ?>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none;"><!--CONCEPTOS-->
                                    <select name="<?= 'conce'.$i ?>" id="<?= 'conce'.$i ?>" onchange="datos('<?= $i ?>'); totalRecibo()" class="selectpicker form-control" data-live-search="true">
                                        <option value="">Seleccione </option><?php 
                                        $conce_query = mysqli_query($link,"SELECT * FROM concep_egresos WHERE status=1 ORDER BY cast(ordena AS unsigned) ASC ");
                                        while($row = mysqli_fetch_array($conce_query))
                                        {
                                            $id=$row['id_concepto'];
                                            $concepto=$row['concepto'];
                                            $monto=$row['monto'];
                                            $tipo_egreso=$row['tipo_egreso'];
                                            $nom_tipo = ($tipo_egreso==1) ? 'Suma' : 'Resta' ;
                                            echo '<option value="'.$concepto.'" data-id='.$id.' data-monto='.$monto.' data-tipo='.$tipo_egreso.' >'.$concepto.' ('.$nom_tipo.")</option>";
                                        }?>
                                    </select>
                                    <input type="hidden" name="<?= 'id_concepto'.$i ?>" id="<?= 'id_concepto'.$i ?>">
                                    <input type="hidden" name="<?= 'tipo_egreso'.$i ?>" id="<?= 'tipo_egreso'.$i ?>">
                                    <input type="hidden" name="<?= 'afecta'.$i ?>" id="<?= 'afecta'.$i ?>">
                                    <input type="hidden" name="<?= 'abrev'.$i ?>" id="<?= 'abrev'.$i ?>">
                                    <input type="hidden" name="<?= 'agosto'.$i ?>" id="<?= 'agosto'.$i ?>">
                                </div>
                                <div class="col-md-3 linea<?= $i ?>" style="display: none;">
                                    <textarea rows="1" name="<?= 'detalle'.$i ?>" id="<?= 'detalle'.$i ?>" class="form-control" readonly ></textarea>
                                </div>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none;"><!--FORMA DE PAGO-->
                                    <select name="<?= 'fpag'.$i ?>" id="<?= 'fpag'.$i ?>" onchange="muestraForma('<?= $i ?>'); totalRecibo() " class="form-control"><?php 
                                        //$fpag_query = mysqli_query($link,"SELECT id,nombrePago FROM formas_pago WHERE status='1' ");
                                        mysqli_data_seek($fpag_query, 0);
                                        while($row = mysqli_fetch_array($fpag_query))
                                        {
                                            $id=$row['id'];
                                            $nombrePago=$row['nombrePago'];
                                            echo '<option readonly value="'.$id.'" >'.utf8_encode($nombrePago)."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none; " ><!--MONTO DOLAR-->
                                    <input type="text" name="<?= 'montoDolar'.$i ?>" onClick="this.select();" onchange="MASK(this,this.value,'-##,###,##0.00',1); totalRecibo(); nuevaLin('<?= $i ?>')" onkeypress="return ValMon(event)" style="text-align: right; " id="<?= 'montoDolar'.$i ?>" disabled class="form-control">
                                </div>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none;"><!--MONTO BS-->
                                    <input type="text" class="form-control" readonly name="<?= 'montoBs'.$i ?>" id="<?= 'montoBs'.$i ?>" style="text-align: right; ">
                                </div>
                                <div class="col-md-1 linea<?= $i ?>" id="<?= 'btnTrash'.$i ?>" style="display: none; " >
                                    <button onclick="borraLinea('<?= $i ?>')" type="button" title='Borrar linea' class="btn btn-danger btn-circle" ><i class="fas fa-trash-alt fa-lg" ></i></button>
                                </div>
                                <div class="col-md-12" style="margin-top: 1%;"></div> <?php 
                            }?>
                        </div>
                        <div class="form-row"><!--TOTALES-->
                            <div class="col-md-3" style="background-color:#D1F2EB ; padding: 5px; ">
                                <label>Total Devengado $</label>    
                                <input type="text" id="devenga" style="text-align: center; " class="form-control" readonly value="0.00">
                            </div>
                            <div class="col-md-3" style="background-color:#D1F2EB ; padding: 5px; ">
                                <label>Total Devengado Bs.</label>    
                                <input type="text" id="devengaBs" style="text-align: center; " class="form-control" readonly value="0.00">
                            </div>
                            
                            <div class="col-md-3" style="background-color:#F2D7D5 ; padding: 5px; ">
                                <label>Total Deducido $</label>    
                                <input type="text" id="deduce" style="text-align: center; " class="form-control" readonly value="0.00">
                            </div>
                            <div class="col-md-3" style="background-color:#F2D7D5 ; padding: 5px; ">
                                <label>Total Deducido Bs.</label>    
                                <input type="text" id="deduceBs" style="text-align: center; " class="form-control" readonly value="0.00">
                            </div>

                            <div class="col-md-4 offset-md-2" style="background-color:#A2D9CE; padding: 5px; margin-top: 1%; margin-bottom: 1%; ">
                                <h4>Total a pagar en $</h4>
                                <input type="text" id="totPagaDiv" style="text-align: center; " readonly class="form-control" >    
                            </div>
                            <div class="col-md-4" style="background-color:#AED6F1; padding: 5px; margin-top: 1%; margin-bottom: 1%; ">
                                <h4>Total a pagar en Bs.</h4>
                                <input type="text" id="totPagaBs" style="text-align: center; " readonly class="form-control" >    
                            </div>
                            
                            <input type="hidden" readonly style="text-align: right; font-size: 18px; " name="totalReciboDolar" id="totalReciboDolar" class="form-control">
                        </div>
                        <div class="form-row" style="margin-top:1%;">
                            <div class="col-md-12">
                                <textarea class="form-control" rows="2" name="comentario" placeholder="Si desea colocar un comentario interno del recibo puede hacerlo en esta area." id="comentario"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" style="background-color: #5499C7; color: black; margin-top: 1%; "><h4>Datos del Pago</h4></div>
                        <div class="form-row" style="margin-bottom: 1%; ">
                            <div class="col-md-4 offset-md-2 bg-gradient-success" id="divDolar" style="padding: 5px; color: black; ">
                                <label>Total Efectivo en Dolares ($)</label>
                                <input type="text" name="totalDolar" style="text-align: center; font-size: 18px; " id="totalDolar" class="form-control" value="0.00" readonly>
                            </div>
                            <div class="col-md-4 bg-gradient-danger" id="divBolivar" style="color:black; padding: 5px; ">
                                <label>Total Efectivo en Bolivares (Bs.)</label>
                                <input type="text" name="totalBolivar" style="text-align: center; font-size: 18px; " id="totalBolivar" class="form-control" value="0.00" readonly>
                            </div>    
                        </div>
                        <div class="bg-gradient-light" id="divTransf" style="display: none; padding: 5px; color: black; "><!--TRANSF-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos de la Transferencia</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoTransf" id="bancoTransf" class="form-control">
                                        <option value="">Seleccione</option><?php 
                                        $transf_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($transf_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoTransf" id="montoTransf" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" name="nroTransf" id="nroTransf" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Transferencia</label>
                                    <input type="date" name="fechaTransf" id="fechaTransf" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-primary" id="divDebito" style="display: none; padding: 5px; color: black; "><!--DEBITO-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos Tarjeta Debito</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoDebito" id="bancoDebito" class="form-control">
                                        <option value="">Seleccione</option><?php 
                                        $debito_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($debito_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoDebito" id="montoDebito" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" name="nroDebito" id="nroDebito" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Pago Movil</label>
                                    <input type="date" name="fechaDebito" id="fechaDebito" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-info" id="divPagMovil" style="display: none; color: black; padding: 5px;"><!--P.MOV-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos del Pago Movil</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoPagMov" id="bancoPagMov" class="form-control">
                                        <option value="">Seleccione</option><?php 
                                        $p_movil_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($p_movil_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoPagMovil" id="montoPagMovil" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" name="nroPagMovil" id="nroPagMovil" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Pago Movil</label>
                                    <input type="date" name="fechaPagMovil" id="fechaPagMovil" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-success" id="divDesdeHasta" style="display: none; color: black; padding: 5px; margin-bottom: 1%; "><!--Fecha desde hasta-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Periodo Pagado</h4></div>
                                <div class="col-md-6">
                                    <label>Desde</label>
                                    <input type="date" onblur="cambiaDesde()" name="desde" id="desde" value="<?= $paga_desde  ?>" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Hasta</label>
                                    <input type="date" onblur="cambiaHasta()" name="hasta" id="hasta" value="<?= $paga_hasta  ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="idAlum" name="idAlum" value="<?= encriptar($idAlum) ?>">
                        <input type="hidden" name="pagaTransf" id="pagaTransf">
                        <input type="hidden" name="pagaDebito" id="pagaDebito">
                        <input type="hidden" name="pagaPagMov" id="pagaPagMov">
                        <input type="hidden" name="cedula" value="<?= $cedula ?>">
                        <input type="hidden" name="alumno" value="<?= $nombre ?>">
                        <input type="hidden" name="linea" id="linea">
                        <input type="hidden" id="formato" name="formato">
                        <div class="form-row" style="margin-bottom:2%;"><!--BOTONES IMPRIMIR Y CERRAR-->
                            <div class="col-md-4" style="padding: 12px; background-color: #EBDEF0; ">
                                <label>Fecha de Emisión</label>
                                <input type="date" class="form-control" name="fechaRecibo" value='<?= $fechaHoy ?>' >
                            </div>
                            <div class="col-md-4 text-center" style="padding: 12px; background-color: #D5F5E3; ">
                                <label><input type="checkbox" name="enviar" value='1' style="transform: scale(2);"> 
                                &nbsp;&nbsp;Marque aqui para enviar recibo al correo del proveedor </label>
                            </div>
                            <div class="col-md-4" style="padding: 12px; background-color:#D4E6F1;">
                                <label>Recibos por Página</label>
                                <select class="form-control" name="copia">
                                    <option value="1" selected>Original</option>
                                    <option value="2">Original y Copia</option>
                                </select>
                            </div>
                            <div class="col-md-4" style="margin-top: 2%">
                                <select id="cuentaEgreso" name="cuentaEgreso" class="form-control">
                                    <option value="">Elija Cuenta de Egreso</option><?php 
                                    $cuenta_query = mysqli_query($link,"SELECT * FROM cuentas WHERE status='1' ");
                                    while($row = mysqli_fetch_array($cuenta_query))
                                    {
                                        $id_cuenta=$row['id_cuenta'];
                                        $nombre_cuenta=$row['nombre_cuenta'];
                                        $selected='';
                                        if($id_cuenta == $cuentaEgreso)
                                        {
                                            $selected='selected';
                                        }
                                        echo '<option  readonly value="'.$id_cuenta.'" '.$selected.'>'.utf8_encode($nombre_cuenta)."</option>";
                                    }?>
                                </select>
                            </div>
                            <div class="col-md-4" style="margin-top: 2%">
                                <button class="btn btn-success" id="btnPrint" type="submit" style="width:100%;" disabled ><i class="fas fa-print fa-sm"></i> Procesar Recibo</button>
                            </div>
                            <div class="col-md-4" style="margin-top: 2%">
                                <button type="button" style="width: 100%; color: black; " class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><?php 
}?>
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
                            <textarea id="comenta" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6" style="background-color:#A2D9CE; padding: 5px; margin-top: 1%; margin-bottom: 1%; ">
                            <h4>Total a pagar en $</h4>
                            <input type="text" id="pagaDiv" class="form-control" >    
                        </div>
                        <div class="col-md-6" style="background-color:#AED6F1; padding: 5px; margin-top: 1%; margin-bottom: 1%; ">
                            <h4>Total a pagar en Bs.</h4>
                            <input type="text" id="pagaBs" class="form-control" >    
                        </div>
                        <div class="col-md-12" style="margin-top: 2%;">
                            <table class="table table-striped table-bordered " cellspacing="0" width="100%" >
                                <thead>
                                    <th >Concepto</th>
                                    <th style="text-align: center;">Monto $</th>
                                    <th style="text-align: center;">Monto Bs.</th>
                                </thead>
                                <tbody id="cuerpo">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Cerrar Ventana</button><?php
                if($_SESSION['cargo']=='1')
                {?>
                    <button type="button" data-toggle="modal" data-target="#anulaRecibo"  id="btnAnula" class="btn btn-danger">Anular recibo</button>
                    <button type="button" data-toggle="modal" data-target="#recuperaRecibo" id="btnRecupera" style="display:none;" class="btn btn-info">Recuperar recibo</button>
                    <button type="button" onclick="reimprimeRecibo()" id="btnPrint" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button><?php 
                }?>
            </div>
            <!--input type="hidden" id="recibo"-->
            <input type="hidden" id="linea">
        </div>
    </div>
</div>
<div class="modal fade" id="anulaRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-color: #FFCCBC; ">
            <div class="modal-header">
                <h5 class="modal-title">Anulación del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Motivo de Anulación</label>
                            <textarea class="form-control" placeholder="Por favor indique el motivo por el cual anula este recibo, para poder ejecutar la anulación" id="motivo" onkeyup="activaBoton()" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Rechazar Anulación</button>
                <button type="button" onclick="anulaRecibo()" disabled id="btnAnula2" class="btn btn-danger">Ejecutar Anulación</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="recuperaRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-color: #FFCCBC; ">
            <div class="modal-header">
                <h5 class="modal-title">Recuperación del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Motivo de Recuperación</label>
                            <textarea class="form-control" placeholder="Por favor indique el motivo por el cual recupera este recibo, despues de haber sido anulado" id="motivoRecupera" onkeyup="activaBotonRecuperar()" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Rechazar Recuperación</button>
                <button type="button" onclick="recuperarRecibo()" disabled id="btnRecupera2" class="btn btn-danger">Ejecutar Recuperación</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        $('#page-top').removeClass("sidebar-toggled");
        $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        $('#emisionFactura').addClass("active");
        $(".linea1"). css("display", "block")
    });
    function calculaDolar() {
        bs=$('#bs_x_dolar').val()
        tas=$('#tasaDolar').val()
        resul=bs/tas
        $('#dolar_x_bs').val(resul.toFixed(2))
    }
    function cambiaDesde() {
        fec=$('#desde').val()
        $.post('cambia-desde.php',{'fecha':fec},function(data)
        {
            if(data.isSuccessful)
            {
              
            }
        }, 'json');
    }
    function cambiaHasta() {
        fec=$('#hasta').val()   
        $.post('cambia-hasta.php',{'fecha':fec},function(data)
        {
            if(data.isSuccessful)
            {
              
            }
        }, 'json');
    }
    function todasForma() {
        son=$('#formaGen').val()
        $('#fpag1').val(son)
        $('#fpag2').val(son)
        $('#fpag3').val(son)
        $('#fpag4').val(son)
        $('#fpag5').val(son)
        $('#fpag6').val(son)
        $('#fpag7').val(son)
        $('#fpag8').val(son)
        $('#fpag9').val(son)
        $('#fpag10').val(son)
        for (var i = 1; i < 11; i++) {
            if ($('#fpag'+i).val()==3) {
                $("#divTransf"). css("display", "block");
                document.querySelector('#nroTransf').required = true;
                document.querySelector('#fechaTransf').required = true;
                $('#pagaTransf').val(1)
            }else
            {
                $("#divTransf"). css("display", "none");
                document.querySelector('#nroTransf').required = false;
                document.querySelector('#fechaTransf').required = false;
                $('#pagaTransf').val('')
            }
            if ($('#fpag'+i).val()==4) {
                $("#divDebito"). css("display", "block");
                document.querySelector('#nroDebito').required = true;
                document.querySelector('#fechaDebito').required = true;
                $('#pagaDebito').val(1)
            }else
            {
                $("#divDebito"). css("display", "none");
                document.querySelector('#nroDebito').required = false;
                document.querySelector('#fechaDebito').required = false;
                $('#pagaDebito').val('')
            }
            if ($('#fpag'+i).val()==5) {
                $("#divPagMovil"). css("display", "block");
                document.querySelector('#nroPagMovil').required = true;
                document.querySelector('#fechaPagMovil').required = true;
                $('#pagaPagMov').val(1)
            }else
            {
                $("#divPagMovil"). css("display", "none");
                document.querySelector('#nroPagMovil').required = false;
                document.querySelector('#fechaPagMovil').required = false;
                $('#pagaPagMov').val('')
            }
        }
        totalRecibo()
    }
    function verPago(reci,lin,nom)
    {
        $("#cuerpo").html("");
        $('#recibo').val(reci);
        $('#linea').val(lin);
        $('#beneficia').val(nom);
        $.post('provedor-historia-buscar.php',{'recib':reci},function(data)
        {
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
                $('#pagaDiv').val(data.pagaDiv);
                $('#pagaBs').val(data.pagaBs);
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
                    $("#cuerpo").append(tr)
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
    function reimprimeRecibo() {
        reci=$('#reciboPrint').val()
        window.open("provedor-recibo-pdf.php?recibo="+reci)
    }
    function limpiar() {
        setTimeout(function(){
            window.parent.location.reload();
        }, 2000);
    }
    function validacion() 
    {
        var cuenta = document.getElementById("cuentaEgreso");
        var bancoT = document.getElementById("bancoTransf");
        var bancoB = document.getElementById("bancoDebito");
        var bancoP = document.getElementById("bancoPagMov");
        if (bancoT.value.length == 0 && $('#pagaTransf').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco de la transferencia',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        if (bancoB.value.length == 0 && $('#pagaDebito').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco del pago con Tarjeta de Debito',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        if (bancoP.value.length == 0 && $('#pagaPagMov').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco del pago movil',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        if (cuenta.value.length == 0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione tipo de Cuenta para agrupar el egreso',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        limpiar()
    }
    function actualTasa() {
        tasa=$('#tasaDolar').val()
        $.post('../factura/tasa-actual-ajax.php',{'tasa':tasa,'mes':0},function(data)
        {
            if(data.isSuccessful){
                Swal.fire({
                    icon: 'success',
                    title: 'Tasa del día',
                    text: 'El monto de la tasa ha sido actualizado!',
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else
            {
                
            }
        }, 'json');
    }
    function muestraForma(lin) {
        fpag='fpag'
        for (var i = 1; i < 11; i++) {
            window[fpag+i] = $('#fpag'+i).val();
        }
        if(fpag1=='3'||fpag2=='3'||fpag3=='3'||fpag4=='3'||fpag5=='3'||fpag6=='3'||fpag7=='3'||fpag8=='3'||fpag9=='3'||fpag10=='3')
        {
            $("#divTransf"). css("display", "block");
            document.querySelector('#nroTransf').required = true;
            document.querySelector('#fechaTransf').required = true;
            $('#pagaTransf').val(1)
        }else
        {
            $("#divTransf"). css("display", "none");
            document.querySelector('#nroTransf').required = false;
            document.querySelector('#fechaTransf').required = false;
            $('#pagaTransf').val('')
        }
        if(fpag1=='4'||fpag2=='4'||fpag3=='4'||fpag4=='4'||fpag5=='4'||fpag6=='4'||fpag7=='4'||fpag8=='4'||fpag9=='4'||fpag10=='4')
        {
            $("#divDebito"). css("display", "block");
            document.querySelector('#nroDebito').required = true;
            document.querySelector('#fechaDebito').required = true;
            $('#pagaDebito').val(1)
        }else
        {
            $("#divDebito"). css("display", "none");
            document.querySelector('#nroDebito').required = false;
            document.querySelector('#fechaDebito').required = false;
            $('#pagaDebito').val('')
        }
        if(fpag1=='5'||fpag2=='5'||fpag3=='5'||fpag4=='5'||fpag5=='5'||fpag6=='5'||fpag7=='5'||fpag8=='5'||fpag9=='5'||fpag10=='5')
        {
            $("#divPagMovil"). css("display", "block");
            document.querySelector('#nroPagMovil').required = true;
            document.querySelector('#fechaPagMovil').required = true;
            $('#pagaPagMov').val(1)
        }else
        {
            $("#divPagMovil"). css("display", "none");
            document.querySelector('#nroPagMovil').required = false;
            document.querySelector('#fechaPagMovil').required = false;
            $('#pagaPagMov').val('')
        }
    }
    function datos(lin) {
        conce=$('#conce'+lin).val()
        id=$("#conce"+lin+" option:selected").attr('data-id')
        mon=$("#conce"+lin+" option:selected").attr('data-monto')
        tip=$("#conce"+lin+" option:selected").attr('data-tipo')
        $('#montoDolar'+lin).val(mon)    
        $('#id_concepto'+lin).val(id)
        $('#tipo_egreso'+lin).val(tip)
        if(id>0)
        { $("#detalle"+lin).removeAttr("readonly"); }else{ $("#detalle"+lin).attr("readonly","readonly"); }
        $('#detalle'+lin).val(conce)
        lin2=parseFloat(lin)+1
        if(conce!=''){ $(".linea"+lin2). css("display", "block") }
        boliv=parseFloat(mon)*parseFloat($('#tasaDolar').val())
        if(boliv>0){$('#montoBs'+lin).val(boliv.toFixed(2))}else{$('#montoBs'+lin).val('0.00')}
        if(id>0){$("#montoDolar"+lin).prop('disabled', false);}
    }
    function nuevaLin(lin) {
        van=0;
        idAlu=$('#idAlum').val()
        monLin=$('#montoDolar'+lin).val()
        $('#linea').val(lin)
        if(monLin==0)
        {
            $('#fpag'+lin).val(1)
            $('#conce'+lin).val('')
            $('#detalle'+lin).val('')
        }
    }
    function borraLinea(lin) {
        if (lin>1) 
        {
            $(".linea"+lin). css("display", "none")
            
        }
        $('#montoDolar'+lin).val('')
        $("#montoDolar"+lin).prop('disabled', true);
        $('#fpag'+lin).val(1)
        $('#conce'+lin).val('')
        $('#detalle'+lin).val('')
        fpag='fpag'
        for (var i = 1; i < 11; i++) {
            window[fpag+i] = $('#fpag'+i).val();
        }
        if(fpag1=='3'||fpag2=='3'||fpag3=='3'||fpag4=='3'||fpag5=='3'||fpag6=='3'||fpag7=='3'||fpag8=='3'||fpag9=='3'||fpag10=='3'){$("#divTransf"). css("display", "block");}else{$("#divTransf"). css("display", "none");}
        if(fpag1=='4'||fpag2=='4'||fpag3=='4'||fpag4=='4'||fpag5=='4'||fpag6=='4'||fpag7=='4'||fpag8=='4'||fpag9=='4'||fpag10=='4'){$("#divDebito"). css("display", "block");}else{$("#divDebito"). css("display", "none");}
        if(fpag1=='5'||fpag2=='5'||fpag3=='5'||fpag4=='5'||fpag5=='5'||fpag6=='5'||fpag7=='5'||fpag8=='5'||fpag9=='5'||fpag10=='5'){$("#divPagMovil"). css("display", "block");}else{$("#divPagMovil"). css("display", "none");}
        totalRecibo()
    }
    function totalRecibo() {
        var suma=0; totTra=0; totMov=0; totDeb=0; totDiv=0; totBol=0; deven=0; deduc=0;devenBs=0; deducBs=0;
        for (var i = 1; i < 11; i++) {
            monto=$('#montoDolar'+i).val()
            tip=$("#conce"+i+" option:selected").attr('data-tipo')
            if(monto>0){suma=suma+parseFloat(monto)}
            bsLin=monto*parseFloat($('#tasaDolar').val())
            if(bsLin>0){$('#montoBs'+i).val(bsLin.toFixed(2))}else{$('#montoBs'+i).val('0.00')}
            if($('#fpag'+i).val()=='3' && tip==1){totTra=totTra+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='3' && tip==2){totTra=totTra-parseFloat(bsLin)}
            if($('#fpag'+i).val()=='4' && tip==1){totDeb=totDeb+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='4' && tip==2){totDeb=totDeb-parseFloat(bsLin)}
            if($('#fpag'+i).val()=='5' && tip==1){totMov=totMov+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='5' && tip==2){totMov=totMov-parseFloat(bsLin)}
            if($('#fpag'+i).val()=='1' && tip==1){if (monto>0) {totDiv=totDiv+parseFloat(monto)} }
            if($('#fpag'+i).val()=='2' && tip==1){totBol=totBol+parseFloat(bsLin)}
            if(tip==1)
            {
                if($('#fpag'+i).val()!='1')
                {
                    devenBs=devenBs+bsLin; 
                }
                if($('#fpag'+i).val()=='1')
                {
                    deven=deven+parseFloat(monto);
                }
            }
            if(tip==2)
            {
                if($('#fpag'+i).val()!='1')
                {
                    deducBs=deducBs+bsLin; 
                }
                if($('#fpag'+i).val()=='1')
                {
                    deduc=deduc+parseFloat(monto);
                }
            }
            window['tip'+i]=$('#tipo_egreso'+i).val()
        }
        if (tip1==1 || tip2==1 || tip3==1 || tip4==1 || tip5==1 || tip6==1 || tip7==1 || tip8==1 || tip9==1 || tip10==1) 
        {
            $("#divDesdeHasta").css("display", "block");
            document.querySelector('#desde').required = true;
            document.querySelector('#hasta').required = true;
            $('#formato').val(2) // empleados
        }else
        {
            $("#divDesdeHasta").css("display", "none");
            document.querySelector('#desde').required = false;
            document.querySelector('#hasta').required = false;
            $('#formato').val(1)  // provedores
        }
        $('#devenga').val(deven.toFixed(2))
        $('#devengaBs').val(devenBs.toFixed(2))
        $('#deduce').val(deduc.toFixed(2))
        $('#deduceBs').val(deducBs.toFixed(2))
        $('#montoTransf').val(totTra.toFixed(2))
        $('#montoDebito').val(totDeb.toFixed(2))
        $('#montoPagMovil').val(totMov.toFixed(2))
        $('#totalDolar').val(totDiv.toFixed(2))
        $('#totalBolivar').val(totBol.toFixed(2))
        totBs=suma*parseFloat($('#tasaDolar').val())
        $('#totalReciboDolar').val(suma.toFixed(2));
        if(suma>0)
        {
            $('#totalReciboBs').val(totBs.toFixed(2))
            $("#btnPrint").prop('disabled', false);
        }else
        {
            $('#totalReciboBs').val('0.00')
            $("#btnPrint").prop('disabled', true);
        }
        totp1=deven-deduc
        totp2=devenBs-deducBs
        $('#totPagaDiv').val(totp1.toFixed(2))
        $('#totPagaBs').val(totp2.toFixed(2))
    }
    function fnShowSecciones(div,btn) 
    {
        $(div).slideToggle();
        $(btn).toggleClass("fas fa-chevron-right");
        $(btn).toggleClass("fas fa-chevron-down");
    }
    function anulaRecibo() {
        reci=$('#recibo').val()
        mot=$('#comenta').val()+' (Motivo de la Anulacion: '+$('#motivo').val()+')'
        lin=$('#linea').val()
        $.post('provedor-historia-anula.php',{'recib':reci,'motivo':mot },function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
                $('#verPago').modal('hide')
                $('#anulaRecibo').modal('hide')
                var elColor=document.getElementsByClassName("trLin"+reci);
                for (var i=0; i<elColor.length; i++) elColor[i].style.backgroundColor="#FFCDD2";
                var elTexto=document.getElementsByClassName("sta"+reci);
                for (var i=0; i<elTexto.length; i++) elTexto[i].innerText = 'Anulado';
                Swal.fire({
                  icon: 'success',
                  title: 'Realizado!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Recibo fue eliminado satisfactoriamente!'
                })   
                
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
    function activaBoton() {
        mot=$('#motivo').val().length
        if(mot<16){document.getElementById("btnAnula2").disabled = true;}else{document.getElementById("btnAnula2").disabled = false;}
    }
    function activaBotonRecuperar() {
        mot=$('#motivoRecupera').val().length
        if(mot<16){document.getElementById("btnRecupera2").disabled = true;}else{document.getElementById("btnRecupera2").disabled = false;}
    }
    function recuperarRecibo() {
        reci=$('#recibo').val()
        mot=$('#comenta').val()+' (Motivo de la Recuperacion: '+$('#motivoRecupera').val()+')'
        lin=$('#linea').val()
        $.post('provedor-historia-recupera.php',{'recib':reci,'motivo':mot },function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
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
<?php
include_once "../include/footer.php";
mysqli_free_result($tasa_query);
mysqli_free_result($datos_query);
mysqli_free_result($provee_query);
mysqli_free_result($fpag_query);
mysqli_free_result($conce_query);
mysqli_free_result($transf_query);
mysqli_free_result($debito_query);
mysqli_free_result($p_movil_query); ?>
           