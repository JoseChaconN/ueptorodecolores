<?php
include_once "../include/header.php";
//include("../../../inicia.php");
$link = Conectarse();
if(!isset($_POST['desde']) && !isset($_POST['hasta']))
{
    $desde = strftime( "%Y-%m-%d") ;
    $hasta = strftime( "%Y-%m-%d") ;
} else
{
    $desde = $_POST['desde'] ;
    $hasta = $_POST['hasta'] ;
}
$provee_query=mysqli_query($link,"SELECT id_recibo FROM egresos_nro where desde>='$desde' and hasta<='$hasta'  "); 
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <form role="form" method="POST" enctype="multipart/form-data" action="desgloce-list.php">
        <div class="form-row">
            <div class="col-md-12 col-xs-12 col-sm-8">
                <h1 class="h3 mb-2 text-gray-800">Pagos a empleados segun recibos</h1>
            </div>
            <div class="col-md-4 col-xs-6 col-sm-6">
                <label>Desde el:</label>
                <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
            </div>
            <div class="col-md-4 col-xs-6 col-sm-6">
                <label>Hasta el:</label>
                <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
            </div>
            <div class="col-md-4 col-xs-12 col-sm-12">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
            </div>
        </div>        
    </form>
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
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Empleado</th>
                            <th>Pago en $</th>
                            <th>Transferen.</th>
                            <th>Pago Movil</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Empleado</th>
                            <th>Pago en $</th>
                            <th>Transferen.</th>
                            <th>Pago Movil</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($provee_query)) 
                        {
                            $recibo=$row['id_recibo'];
                            $datos_query=mysqli_query($link,"SELECT B.id_provee, B.operacion,B.montoDolar,B.montoBs, C.cedula,C.nombre,C.apellido,C.telefono,C.correo,C.direccion, D.tipo_egreso FROM egresos B, alumcer C, concep_egresos D where B.recibo='$recibo' and B.status_egreso=1 and B.id_provee=C.idAlum and B.id_concepto=D.id_concepto "); 
                            $pagEfe=0; $pagTra=0; $pagMov=0; $resEfe=0; $resTra=0; $resMov=0; $status=2;
                            while($row=mysqli_fetch_array($datos_query)) 
                            {
                                $id=$row['id_provee'];
                                $nombre=$row['nombre'];
                                $apellido=$row['apellido'];
                                $operacion=$row['operacion'];
                                $montoDolar=$row['montoDolar'];
                                $montoBs=$row['montoBs'];
                                $telefono=$row['telefono'];
                                $correo=$row['correo'];
                                $direccion=$row['direccion'];
                                $status=1;
                                $tip=$row['tipo_egreso'];
                                if($operacion==1 && $tip==1){$pagEfe=$pagEfe+$montoDolar;}
                                if($operacion==1 && $tip==2){$resEfe=$resEfe+$montoDolar;}

                                if($operacion==3 && $tip==1){$pagTra=$pagTra+$montoBs;}
                                if($operacion==3 && $tip==2){$resTra=$resTra+$montoBs;}

                                if($operacion==5 && $tip==1){$pagMov=$pagMov+$montoBs;}
                                if($operacion==5 && $tip==2){$resMov=$resMov+$montoBs;}
                            }

                            //$=$row[''];
                            if($status==1)
                            { 
                                $pagEfe=$pagEfe-$resEfe;
                                $pagTra=$pagTra-$resTra;
                                $pagMov=$pagMov-$resMov; ?>
                                <tr>
                                    <td><?= $son+=1; ?></td>
                                    <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT); ?></td>
                                    <td id="nom<?= $son ?>" data-toggle="modal" title="Datos Personales" data-target="#perfilProvee" style='cursor: pointer' onclick="editPerfil('<?= $son ?>','<?= $id ?>','<?= $cedula ?>','<?= $nombre ?>','<?= $apellido ?>','<?= $telefono ?>','<?= $correo ?>','<?= $direccion ?>')"><?= $nombre.' '.$apellido ; ?></td>
                                    <td align="right" ><?= number_format($pagEfe,2,'.',',').'$ ' ?></td>
                                    <td align="right" ><?= number_format($pagTra,2,'.',',').'Bs.' ?></td>
                                    <td align="right" ><?= number_format($pagMov,2,'.',',').'Bs.' ?></td>
                                    <td>
                                        <div class="dropdown mb-4 btn-group">
                                            <button  type="button" class="btn btn-info btn-circle" data-toggle="modal" title="Datos Personales" data-target="#perfilProvee" style='cursor: pointer' onclick="editPerfil('<?= $son ?>','<?= $id ?>','<?= $cedula ?>','<?= $nombre ?>','<?= $apellido ?>','<?= $telefono ?>','<?= $correo ?>','<?= $direccion ?>' )"  ><i class="fas fa-user-edit " ></i></button>
                                            <button onclick="verPago('<?= $recibo ?>','<?= $son ?>','<?= $nombre ?>','<?= $correo ?>')" type="button" title='Ver datos del Pago' data-toggle="modal" data-target="#verPago" class="btn btn-success btn-circle" ><i class="fas fa-eye fa-lg" ></i></button>
                                            <button  type="button" class="btn btn-primary btn-circle" title="Historial de pagos" onclick='window.open("../procesos/provedor-historia.php?id=<?= encriptar($id) ?>")'  ><i class="fas fa-folder" ></i></button>

                                             <button data-toggle="modal" data-target="#enviaMail" title="Enviar correo" onclick="preparaMail('<?= $nombre.' '.$apellido ?>','<?= $correo ?>')" type="button" class="btn btn-secondary btn-circle" onclick="" ><i class="fas fa-envelope " ></i></button>
                                            
                                        </div>
                                    </td>         
                                </tr><?php
                            }
                        } ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="perfilProvee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Datos del Empleado/Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  role="form" method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Cedula/Rif</label>
                                <input type="text" id="cedulaEdit" class="form-control">
                                <input type="hidden" id="idEdit">
                                <input type="hidden" id="cargoEdit">
                                <input type="hidden" id="lineaEdit">    
                            </div>
                            <div class="col-md-5">
                                <label>Nombre/Empresa</label>
                                <input type="text" id="nombreEdit" class="form-control">    
                            </div>
                            <div class="col-md-5">
                                <label>Apellido</label>
                                <input type="text" id="apellidoEdit" class="form-control">  
                            </div>
                            <div class="col-md-4">
                                <label>Telefono</label>
                                <input type="text" id="telefonoEdit" class="form-control">  
                            </div>
                            <div class="col-md-8">
                                <label>Email</label>
                                <input type="text" id="emailEdit" class="form-control"> 
                            </div>
                            <div class="col-md-12">
                                <label>Direccion</label>
                                <input type="text" id="direccEdit" name="direccEdit" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                    
                    <button type="button" onclick="guardaEdit()" class="btn btn-primary"><i class="fas fa-save fa-sm"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="enviaMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Envío de correo a: <span id="mailA"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formMail" target="_blank" method="POST" action="provedor-mail.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <label>Asunto:</label>
                                <input class="form-control" type="text" required name="asunto">
                            </div>
                            <div class="col-12">
                                <label>Mensaje:</label>
                                <textarea class="form-control" rows="4" required name="mensaje" placeholder="Escriba su mensaje aqui..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <label>Archivos Adjuntos:</label>
                                <input type="file"  name="archivo" id="BSbtninfo" class="archivo" >
                            </div>
                            <input type="hidden" id="recibe" name="recibe">
                            <input type="hidden" id="correo" name="correo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">Enviar</button>
                </div>
            </form>
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
                <div class="col-md-12 text-center" style="padding: 12px; ">
                    <label id="label_envia"><input type="checkbox" id="enviar" style="transform: scale(2);"> 
                    &nbsp;&nbsp;Marque aqui para enviar recibo al correo del proveedor luego haga clic en imprimir </label>
                    <label id="label_sincorreo">No puede enviar correo a este proveedor ya que no dispone en su ficha de un correo valido.</label>
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Cerrar Ventana</button><?php
                if($_SESSION['cargo']=='1')
                {?>
                    <button type="button" data-toggle="modal" data-target="#anulaRecibo"  id="btnAnula" class="btn btn-danger">Anular recibo</button>
                    <button type="button" data-toggle="modal" data-target="#recuperaRecibo" id="btnRecupera" style="display:none;" class="btn btn-info">Recuperar recibo</button>
                    <button type="button"  data-toggle="modal" data-target="#copiasRecibo" id="btnPrint" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button><?php 
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
        if (screen.width<500) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        if (screen.width>1024) {
            $('#movimiento').addClass("show");
        }
        $('#desgloceEgresos').addClass("active");
    });
    function verPago(reci,lin,nom,corr)
    {
        $("#cuerpo").html("");
        $('#recibo').val(reci);
        $('#linea').val(lin);
        $('#beneficia').val(nom);
        if (corr=='') {$('#label_envia').hide();}else{$('#label_envia').show();}
        if (corr=='') {$('#label_sincorreo').show();}else{$('#label_sincorreo').hide();}
        $.post('../procesos/provedor-historia-buscar.php',{'recib':reci},function(data)
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
        copi=$('#copiaPag').val()
        if($("#enviar").prop('checked')) {
            window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&envia=1&cop="+copi)    
        }else {window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&cop="+copi)}
        //window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci)
    }
    function anulaRecibo() {
        reci=$('#recibo').val()
        mot=$('#comenta').val()+' (Motivo de la Anulacion: '+$('#motivo').val()+')'
        lin=$('#linea').val()
        $.post('../procesos/provedor-historia-anula.php',{'recib':reci,'motivo':mot },function(data)
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
        $.post('../procesos/provedor-historia-recupera.php',{'recib':reci,'motivo':mot },function(data)
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
    function editPerfil(lin,id,ced,nom,ape,tlf,mai,dir) 
    {
        $('#idEdit').val(id)
        $('#cedulaEdit').val(ced)
        $('#nombreEdit').val(nom)
        $('#apellidoEdit').val(ape)
        $('#telefonoEdit').val(tlf)
        $('#emailEdit').val(mai)
        $('#direccEdit').val(dir)
        $('#lineaEdit').val(lin)
    }
    function guardaEdit() 
    {
        id=$('#idEdit').val()
        ced=$('#cedulaEdit').val()
        nom=$('#nombreEdit').val()
        ape=$('#apellidoEdit').val()
        tlf=$('#telefonoEdit').val()
        mai=$('#emailEdit').val()
        dir=$('#direccEdit').val()
        lin=$('#lineaEdit').val()
        $.post('../procesos/provedor-edit.php',{'id':id, 'cedula':ced,'nombre':nom,'apellido':ape,'telefono':tlf,'correo':mai,'direcc':dir},function(data)
        {
            if(data.isSuccessful)
            {
               //location.reload();
                $('#perfilProvee').modal('hide')
                document.getElementById('nom'+lin).innerHTML = data.nom+' '+data.ape;
                Swal.fire({
                    title: "Excelente!",
                    text: "Datos actualizados exitosamente!",
                    icon: "info",
                    button: "Entiendo",
                  });
            } 
        }, 'json');
    }
    function numLet(event) 
    {
        if($('#formaPag').val()!='div')
        {
            num = $('#montoPag').val().replace(/\./g,'');
        }else {num = $('#montoPag').val();}
        if($('#formaPag').val()=='div')
            { num2='DOLARES';}else{num2='BOLIVARES'}
        $('#montoLet').val(numeroALetras(num)+num2);
    }
    function formatear(event) 
    {
        if($('#formaPag').val()!='div')
        {
            $(event.target).val(function(index, value) 
            {
                return value.replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
            });
        }
    }
    
    function preparaMail(nom,mai) 
    {
        document.querySelector('#mailA').innerText = nom;
        $('#recibe').val(nom)
        $('#correo').val(mai)
    }
    $('#BSbtninfo').filestyle({
      buttonName : 'btn-info',
      buttonText : ' Buscar Archivos'
    });
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php"; 
mysqli_free_result($provee_query);
mysqli_free_result($datos_query);               
?>
           