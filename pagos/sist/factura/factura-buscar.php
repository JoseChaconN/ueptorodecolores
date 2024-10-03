<?php
include_once "../include/header.php";
?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Buscar Recibo</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form>
                
                    <div class="col-md-4 offset-md-4" style="margin-bottom: 2%; ">
                        <label for="formGroupExampleInput">Recibo Nro.</label>
                        <input type="text" class="form-control" onClick="this.select(); borraBusca()" id="recibo1" onkeypress="return ValCed(event)" placeholder="# de Recibo">
                    </div>
                    <!--div class="col-md-4 ">
                        <label for="formGroupExampleInput">Recibo Fiscal</label>
                        <input type="text" class="form-control" onClick="this.select(); borraBusca()" id="recibo2" onkeypress="return ValCed(event)" placeholder="# de Recibo">
                    </div-->    
                
                
                <div class="col-md-4 offset-md-4">
                    <button type="button" onclick="buscarRecibo()" style="width: 100%;" class="btn btn-primary"><i class="fas fa-search fa-sm"></i> Buscar</button>
                </div>
                <div class="form-row" id="divAnulado" style="display: none; background-color: #FFF59D; padding: 5px; ">
                    <div class="col-md-12 text-center">
                        <h3 style=" color: black; " >Recibo Anulado</h3>
                    </div>
                    <div class="col-md-12">
                        <label>Motivo</label>
                        <textarea class="form-control" readonly rows="2" id="motivoNulo"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Anulado por:</label>
                        <input type="text" class="form-control" id="anulaPor" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Fecha de Anulación:</label>
                        <input type="text" class="form-control" id="fechaAnula" readonly>
                    </div>    
                </div>
                <div class="col-md-12" id="divComenta">
                    <label>Comentario del Operador</label>
                    <textarea class="form-control" readonly rows="2" id="comenta"></textarea>
                </div>
            </form>
            <hr>
            <div class="form-row">
                <div class="col-md-3">
                    <label>Cedula</label>
                    <input type="text" id="cedulaVer" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Estudiante</label>
                    <input type="text" id="alumnoVer" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Periodo</label>
                    <input type="text" id="periodoVer" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Recibo Nro.</label>
                    <input type="text" id="reciboVer" class="form-control">
                    <input type="hidden" id="reciboPrint">
                    <input type="hidden" id="sale">
                </div>
                <div class="col-md-4">
                    <label>Fecha</label>
                    <input type="text" id="fecha" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Tasa Bs.</label>
                    <input type="text" id="tasa" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Monto $</label>
                    <input type="text" id="dolar" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Monto Bs.</label>
                    <input type="text" id="bolivar" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Procesado por:</label>
                    <input type="text" id="usuario" class="form-control">
                </div>
                <div class="col-md-12" style="margin-top: 2%;">
                    <table class="table table-striped table-bordered " cellspacing="0" width="100%" >
                        <thead>
                            <th >Concepto</th>
                            <th>F.Pago</th>
                            <th style="text-align: center;">Monto $</th>
                            <th style="text-align: center;">Monto Bs.</th>
                        </thead>
                        <tbody id="cuerpo">
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 offset-md-2">
                    <button type="button" onclick="reimprimeRecibo()" id="btnPrint" style="width: 100%;" disabled class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button>    
                </div>
                <div class="col-md-4"><?php  
                    if($cargoAct=='1')
                    {?>
                        <button type="button" data-toggle="modal" data-target="#anulaRecibo" id="btnAnula" style="width: 100%;" disabled class="btn btn-danger"><i class="fas fa-trash fa-sm"></i> Anular</button>
                        <button type="button" data-toggle="modal" data-target="#recuperaRecibo" id="btnRecupera" style="display: none; width: 100%; color: black;" class="btn btn-warning"><i class="fas fa-trash fa-sm"></i> Recuperar</button><?php 
                    }else{?>
                        <button type="button" id="btnAnula" style="width: 100%;" disabled class="btn btn-danger"><i class="fas fa-trash fa-sm"></i> Anular</button>
                        <button type="button" id="btnRecupera" style="display: none; width: 100%; color: black;" class="btn btn-warning"><i class="fas fa-trash fa-sm"></i> Recuperar</button><?php

                    }?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="cargoAct" value="<?= $cargoAct ?>" >
<input type="hidden" id="grado">
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
                <input type="hidden" id="tablaPeriodo">
            </div>
        </div>
    </div>
    <input type="hidden" id="usuarioAnula" value="<?= $usuarioAct ?>">
    <input type="hidden" id="fechaEsp" value="<?= $fechaEsp ?>">
</div>
<div class="modal fade" id="recuperaRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-color: #C8E6C9; ">
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
        if (screen.width<500) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        $('#buscarRecibo').addClass("active");
    });
    function activaBoton() {
        mot=$('#motivo').val().length
        if(mot<16){document.getElementById("btnAnula2").disabled = true;}else{document.getElementById("btnAnula2").disabled = false;}
    }
    function anulaRecibo() {
        peri=$('#tablaPeriodo').val()
        reci=$('#reciboVer').val()
        sale=$('#sale').val()
        gra=$('#grado').val()
        mot=$('#comenta').val()+' (Motivo de la Anulacion: '+$('#motivo').val()+')'
        $.post('../procesos/historia-anula.php',{'recib':reci,'tabla':peri,'motivo':mot,'salio':sale,'grado':gra },function(data)
        {
            if(data.isSuccessful)
            {
                $('#anulaRecibo').modal('hide')
                document.getElementById("btnPrint").disabled = true;
                document.getElementById("btnAnula").disabled = true;
                $('#btnAnula').hide();
                $('#btnRecupera').show();
                $('#divAnulado').show();
                $('#motivoNulo').val(mot);
                $('#anulaPor').val($('#usuarioAnula').val());
                $('#fechaAnula').val($('#fechaEsp').val());
                $('#divComenta').hide();
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
    function borraBusca() {
        $('#recibo1').val('')   
        $('#recibo2').val('')   
    }
    function buscarRecibo() 
    {
        nro1=$('#recibo1').val()
        nro2=$('#recibo2').val()
        if(nro1>0){
            nro=nro1;
            tip=1;
            $('#sale').val(1)
        }else{
            nro=nro2;
            tip=2;
            $('#sale').val(2)
        }
        $("#cuerpo").html("");
        $.post('factura-buscar-ajax.php',{'recibo':nro,'reciboVer':tip},function(data)
        {
            if(data.isSuccessful){
                $('#cedulaVer').val(data.cedula)
                $('#alumnoVer').val(data.alumno)
                $('#periodoVer').val(data.periodo)
                $('#reciboVer').val(data.recibo)
                $('#reciboPrint').val(data.recPrint)
                $('#fecha').val(data.fecha)
                $('#tasa').val(data.tasa)
                $('#dolar').val(data.dolar)
                $('#bolivar').val(data.bolivar)
                $('#usuario').val(data.usuario)
                $('#tablaPeriodo').val(data.tabla)
                $('#grado').val(data.grado)
                if (data.status=='2') 
                {
                    $('#motivoNulo').val(data.comentario)
                    $('#fechaAnula').val(data.fechaNulo)
                    $('#anulaPor').val(data.quienAnul)
                    if($('#cargoAct').val()=='1')
                    {
                        $('#btnAnula').hide();
                        $('#btnRecupera').show();
                        document.getElementById("btnAnula").disabled = true;
                        document.getElementById("btnPrint").disabled = true;
                    }
                    $('#divAnulado').show();
                    $('#divComenta').hide();
                }else
                {
                    $('#comenta').val(data.comentario)
                    if($('#cargoAct').val()=='1')
                    {
                        $('#btnAnula').show();
                        $('#btnRecupera').hide();
                        document.getElementById("btnAnula").disabled = false;
                    }
                    $('#divAnulado').hide();
                    $('#divComenta').show();
                    document.getElementById("btnPrint").disabled = false;
                }
                //$('#').val(data.)

                for(var i=0; i<data.options.length; i++)
                {

                    if(data.options[i].codigo<10){tipo='0'+data.options[i].codigo} else{tipo=data.options[i].codigo}
                    var tr = "<tr>"+
                      "<td >"+data.options[i].conce+"</td>"+
                      "<td title='Banco: "+data.options[i].banco+" Ref.: "+data.options[i].nroDepo+"'>"+data.options[i].forma+" / Ref. "+data.options[i].nroDepo+"</td>"+
                      "<td align='right'>"+data.options[i].dolar+" $</td>"+
                      "<td align='right'>"+data.options[i].bolivar+" Bs.</td>"+
                    "</tr>";
                    $("#cuerpo").append(tr)
                }
            }else
            {
                document.getElementById("btnPrint").disabled = true;
                document.getElementById("btnAnula").disabled = true;
                $('#cedulaVer').val('')
                $('#alumnoVer').val('')
                $('#periodoVer').val('')
                $('#reciboVer').val('')
                $('#fecha').val('')
                $('#tasa').val('')
                $('#dolar').val('')
                $('#bolivar').val('')
                $('#usuario').val('')
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Recibo no encontrado!'
                })
            }
        }, 'json');
    }
    function reimprimeRecibo() {
        reci=$('#reciboPrint').val()
        sale=$('#sale').val()
        window.open("factura-reimprime-pdf.php?recibo="+reci+"&sale="+sale)
    }
    function activaBotonRecuperar() {
        mot=$('#motivoRecupera').val().length
        if(mot<16){document.getElementById("btnRecupera2").disabled = true;}else{document.getElementById("btnRecupera2").disabled = false;}
    }

    function recuperarRecibo() {
        peri=$('#tablaPeriodo').val()
        reci=$('#reciboVer').val()
        sale=$('#sale').val()
        //alert(sale)
        mot=$('#comenta').val()+$('#motivoNulo').val()+' (Motivo de la Recuperacion: '+$('#motivoRecupera').val()+')'
        $.post('../procesos/historia-recupera.php',{'recib':reci,'tabla':peri,'motivo':mot,'sale':sale },function(data)
        {
            if(data.isSuccessful)
            {
                $('#recuperaRecibo').modal('hide')
                document.getElementById("btnPrint").disabled = false;
                document.getElementById("btnAnula").disabled = false;
                $('#btnAnula').show();
                $('#btnRecupera').hide();
                $('#divAnulado').hide();
                $('#divComenta').show();
                $('#comenta').val(mot);
                Swal.fire({
                  icon: 'success',
                  title: 'Realizado!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Recibo fue recuperado satisfactoriamente!'
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
</script>
<?php
include_once "../include/footer.php";                
?>
           