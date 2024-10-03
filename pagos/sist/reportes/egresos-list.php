<?php
include_once "../include/header.php";
$link = Conectarse();
if(!isset($_POST['desde']) && !isset($_POST['hasta']))
{
    $desde = strftime( "%Y-%m-%d") ;
    $hasta = strftime( "%Y-%m-%d") ;
    $recDesde=0; $recHasta=0;
    $id_cuenta=0;
} else
{
    $desde = $_POST['desde'] ;
    $hasta = $_POST['hasta'] ;
    $recDesde=$_POST['recDesde'];
    $id_cuenta=$_POST['cuentaEgreso'];
    if($recDesde==0) {$recHasta=0;}else {$recHasta=$_POST['recHasta'];}
}
$desBus=$desde.' 00:00:00';
$hasBus=$hasta.' 23:59:59';
if($recDesde>0)
{
    if ($id_cuenta==0) {
        $recibos_query = mysqli_query($link,"SELECT * FROM egresos_nro WHERE id_recibo>='$recDesde' and id_recibo<='$recHasta' ");    
    }else
    {
        $recibos_query = mysqli_query($link,"SELECT * FROM egresos_nro WHERE id_recibo>='$recDesde' and id_recibo<='$recHasta' and cuentaEgreso='$id_cuenta' ");
    }
}else
{
    if ($id_cuenta==0) {
        $recibos_query = mysqli_query($link,"SELECT * FROM egresos_nro WHERE fecha>='$desBus' AND fecha<='$hasBus' ");
    }else
    {
        $recibos_query = mysqli_query($link,"SELECT * FROM egresos_nro WHERE fecha>='$desBus' AND fecha<='$hasBus' and cuentaEgreso='$id_cuenta' ");
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Resumen de Egresos Diarios</h1>    
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
                <form role="form" method="POST" enctype="multipart/form-data" action="egresos-list.php">
                    <div class="form-row">
                        <div class="col-md-3 col-xs-6 col-sm-6">
                            <label>Desde el:</label>
                            <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-6">
                            <label>Hasta el:</label>
                            <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-6">
                            <label>Recibo Desde:</label>
                            <input type="text" name="recDesde" onclick="this.select()" class="form-control" value="0">
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-6">
                            <label>Recibo Hasta:</label>
                            <input type="text" name="recHasta" onclick="this.select()" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 offset-md-2 col-12" style="margin-top: 2%;">
                            <select id="cuentaEgreso" name="cuentaEgreso" class="form-control">
                                <option value="0">Todas las Cuentas</option><?php 
                                $cuenta_query = mysqli_query($link,"SELECT * FROM cuentas WHERE status='1' ");
                                while($row = mysqli_fetch_array($cuenta_query))
                                {
                                    $id_cuenta2=$row['id_cuenta'];
                                    $nombre_cuenta=$row['nombre_cuenta'];
                                    $selected = ($id_cuenta==$id_cuenta2) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_cuenta2.'">'.$nombre_cuenta."</option>";
                                }?>
                            </select><br><br>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-12" style="margin-top: 2%;">
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Proveedor</th>
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
                            <th>Proveedor</th>
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
                            $recibo=$row['id_recibo'];
                            $fecha=$row['fecha'];
                            $pagos_query = mysqli_query($link,"SELECT sum(A.montoDolar) as total,A.id_provee,A.status_egreso,B.cedula,B.nombre, B.apellido,B.telefono,B.correo,B.direccion, C.nombreUser FROM egresos A, alumcer B, user C WHERE A.recibo='$recibo' and A.id_provee=B.idAlum and A.emitidoPor=C.idUser ");
                            //$=$row[''];
                            while($row2=mysqli_fetch_array($pagos_query)) 
                            {
                                $idAlum=$row2['id_provee'];
                                $cedula=$row2['cedula'];
                                $alumno=($row2['apellido'].' '.$row2['nombre']);
                                $nombre=$row2['apellido'];
                                $apellido=$row2['nombre'];
                                $total=$row2['total'];
                                $telefono=$row2['telefono'];
                                $correo=$row2['correo'];
                                $direccion=$row2['direccion'];
                                //$=$row[''];
                                $emitidoPor=$row2['nombreUser'];
                                $status_egreso=$row2['status_egreso'];
                            }?>
                            <tr <?php if($status_egreso=='2'){echo "style='background:#EC7063; color:white; ' ";}?> >
                                <td><?= $son+=1; ?></td>
                                <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                <td style='cursor: pointer' data-toggle="modal" title="Datos Personales" data-target="#perfilProvee" onclick="editPerfil('<?= $son ?>','<?= $idAlum ?>','<?= $cedula ?>','<?= $nombre ?>','<?= $apellido ?>','<?= $telefono ?>','<?= $correo ?>','<?= $direccion ?>' )"><?= $alumno ; ?></td>
                                <td><?= date("d-m-Y H:i", strtotime($fecha)) ?></td>
                                <td align="right"><?php if($status_egreso=='2'){echo "ANULADO";}else {echo $total.' $';} ?></td>
                                <td><?= $emitidoPor ?> </td> 
                                <td>
                                    <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#verPago" onclick="verPago('<?= $recibo ?>','<?= $son ?>','<?= $alumno ?>','<?= $correo ?>')" ><span style="font-weight: bold;" class="fas fa-eye fa-lg" aria-hidden="true" title="Ver Detalle"></span></button>
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
                            <textarea id="comenta" class="form-control" readonly rows="3"></textarea>
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
                if($cargoAct=='1')
                {?>
                    <button type="button" data-toggle="modal" data-target="#anulaRecibo"  id="btnAnula" class="btn btn-danger">Anular recibo</button>
                    <button type="button" data-toggle="modal" data-target="#recuperaRecibo" id="btnRecupera" style="display:none;" class="btn btn-info">Recuperar recibo</button>
                    <?php 
                }?>
                <button type="button" data-toggle="modal" data-target="#copiasRecibo" id="btnPrint" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button>
            </div>
            <!--input type="hidden" id="recibo"-->
            <input type="hidden" id="linea">
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
        $('#listadoEgresos').addClass("active");
    });
    function reimprimeRecibo() {
        reci=$('#reciboPrint').val()
        copi=$('#copiaPag').val()
        if($("#enviar").prop('checked')) {
            window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&envia=1&cop="+copi)    
        }else {window.open("../procesos/provedor-recibo-pdf.php?recibo="+reci+"&cop="+copi)}
    }
    function verPago(reci,lin,nom,corr)
    {
        $("#cuerpo").html("");
        $('#recibo').val(reci);
        $('#linea').val(lin);
        if (corr=='') {$('#label_envia').hide();}else{$('#label_envia').show();}
        if (corr=='') {$('#label_sincorreo').show();}else{$('#label_sincorreo').hide();}
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
                document.getElementById('ced'+lin).innerHTML = ced;
                document.getElementById('nom'+lin).innerHTML = data.nom+' '+data.ape;
                document.getElementById('tlf'+lin).innerHTML = tlf;
                document.getElementById('mai'+lin).innerHTML = mai;
                Swal.fire({
                    title: "Excelente!",
                    text: "Datos actualizados exitosamente!",
                    icon: "info",
                    button: "Entiendo",
                  });
            } 
        }, 'json');
    }
    function activaBoton() {
        mot=$('#motivo').val().length
        if(mot<16){document.getElementById("btnAnula2").disabled = true;}else{document.getElementById("btnAnula2").disabled = false;}
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
    function btnPdf()
    {
        window.open('egresos-pdf.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>&recDes=<?= $recDesde ?>&recHas=<?= $recHasta ?>&cuent=<?= $id_cuenta ?>&filtro='+$('#example_filter').find('input').val())
    }
    /*function excelBtn() 
    {
        window.open('ingresos-excel.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>&recDes=<?= $recDesde ?>&recHas=<?= $recHasta ?>')
    }*/
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";
mysqli_free_result($recibos_query);
mysqli_free_result($pagos_query);


?>
           