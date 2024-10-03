<?php
include_once "../include/header.php";
//include("../../../inicia.php");
$link = Conectarse();
$id_provee=desencriptar($_GET['id']);

$provee_query=mysqli_query($link,"SELECT A.*, B.nombre, B.apellido, B.correo FROM egresos A, alumcer B where A.id_provee='$id_provee' and B.idAlum='$id_provee' "); 
$row2=mysqli_fetch_array($provee_query);
$proveedor=($row2['nombre'].' '.$row2['apellido']);
$correo=$row2['correo'];

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h4 class="h4 mb-2 text-gray-800">Pagos a: <?= $proveedor ?></h4>
        </div>
        <div class="col-md-3">
            <button type="button" style="width: 100%;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
        </div>
    </div>        
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
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Monto $</th>
                            <th>Monto Bs.</th>
                            <th>Status</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Monto $</th>
                            <th>Monto Bs.</th>
                            <th>Status</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        mysqli_data_seek($provee_query, 0);
                        while($row=mysqli_fetch_array($provee_query)) 
                        {
                            $id_egreso=$row['recibo'];
                            $fecha_egreso=$row['fecha_egreso'];
                            $concepto_pago=$row['concepto_pago'];
                            $monto_numero=$row['montoBs'];
                            $montoDolar=$row['montoDolar'];
                            //$monto_letra=$row['monto_letra'];
                            $forma_pago=$row['operacion'];
                            $bancoPag=$row['banco'];
                            $refePag=$row['refePag'];
                            $status_egreso = $row['status_egreso'];
                            $status = ($row['status_egreso']=='2') ? 'Anulado' : 'Emitido' ;
                            $moneda = ($forma_pago=='1') ? '$' : 'Bs.' ;
                            $son+=1; ?>
                            <tr <?php if($status_egreso=='2'){ echo 'style="background-color:#EC7063; color:white; "';} ?>>
                                <td><?= str_pad($id_egreso, 6, "0", STR_PAD_LEFT) ?></td>
                                <td><?= date("d-m-Y", strtotime($fecha_egreso)); ?></td>
                                <td><?= $concepto_pago ; ?></td>
                                <td style="text-align: right;"><?= number_format($montoDolar,2,',','.') ?></td>
                                <td style="text-align: right;"><?= number_format($monto_numero,2,',','.') ?></td>
                                <td id="status<?= $son ?>"><?= $status ?></td>
                                <td>
                                    <div class="dropdown mb-4">
                                        <button  type="button" class="btn btn-info btn-circle" data-toggle="modal" title="Reimprimir Pago" data-target="#verPago" onclick="verPago('<?= $id_egreso ?>','<?= $son ?>','<?= $proveedor ?>')" ><i class="fas fa-print" ></i></button>
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
<input type="hidden" id="cargoAct" value="<?= $cargoAct ?>" >
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
                            <textarea id="comenta" readonly class="form-control" rows="3"></textarea>
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
                <div class="col-md-12 text-center" style="padding: 12px; "><?php 
                    if (!empty($correo)) {?>
                        <label><input type="checkbox" id="enviar" style="transform: scale(2);"> 
                    &nbsp;&nbsp;Marque aqui para enviar recibo al correo del proveedor luego haga clic en imprimir </label><?php 
                    }else
                    {?>
                        <label>No puede enviar correo a este proveedor ya que no dispone en su ficha de un correo valido.</label><?php 
                    }?>
                    
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Cerrar Ventana</button><?php
                if($cargoAct=='1')
                {?>
                    <button type="button" data-toggle="modal" data-target="#anulaRecibo"  id="btnAnula" class="btn btn-danger">Anular recibo</button>
                    <button type="button" data-toggle="modal" data-target="#recuperaRecibo" id="btnRecupera" style="display:none;" class="btn btn-info">Recuperar recibo</button>
                    <?php 
                }?>
                <button type="button" data-toggle="modal" data-target="#copiasRecibo" id="btnPrint" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button>
            </div>
            <!--input type="hidden" id="recibo"-->
            <input type="hidden" id="tablaPeriodo" value="<?= $tablaPeriodo ?>">
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
                <button type="button" onclick="reimprimeRecibo()" class="btn btn-danger">Reimprimir</button>
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
    });
    function activaBoton() {
        mot=$('#motivo').val().length
        if(mot<16){document.getElementById("btnAnula2").disabled = true;}else{document.getElementById("btnAnula2").disabled = false;}
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
    function reimprimeRecibo() {
        reci=$('#reciboPrint').val()
        copi=$('#copiaPag').val()
        if($("#enviar").prop('checked')) {
            window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&envia=1&cop="+copi)    
        }else {window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&cop="+copi)}

        /*if($("#enviar").prop('checked')) {
            window.open("provedor-recibo-pdf.php?recibo="+reci+"&envia=1")    
        }else {window.open("provedor-recibo-pdf.php?recibo="+reci)}*/
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";    
mysqli_free_result($provee_query);            
?>
           