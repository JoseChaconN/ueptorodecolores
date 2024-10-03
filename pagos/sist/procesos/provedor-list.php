<?php
include_once "../include/header.php";
//include("../../../inicia.php");
$link = Conectarse();
$provee_query=mysqli_query($link,"SELECT idAlum as id, cedula, nombre, apellido, telefono, correo, direccion, statusAlum FROM  alumcer where cargo>0 "); 
if(isset($_GET['msj']) && $_GET['msj']=='2')
{ ?>
    <script type="text/javascript">
        Swal.fire({
            title: "Error!",
            text: "Cedula o rif de Proveedor ya existe!",
            icon: "error",
            button: "Entiendo",
          });
    </script><?php
}
if(isset($_GET['msj']) && $_GET['msj']=='1')
{ ?>
    <script type="text/javascript">
        Swal.fire({
            title: "Excelente!",
            text: "Proveedor creado correctamente!",
            icon: "success",
            button: "Entiendo",
          });
    </script><?php
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9 col-xs-12 col-sm-8">
            <h1 class="h3 mb-2 text-gray-800">Listado de Empleados y Proovedores</h1>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-4">
            <button type="button" data-toggle="modal" data-target="#nuevoProvee" class="btn btn-success" style="width: 100%;"><span class="fas fa-plus fa-sm"></span> Nuevo Proveedor</button><br><br>
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
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($provee_query)) 
                        {
                            $id=$row['id'];
                            $cedula=$row['cedula'];
                            $nombre=$row['nombre'];
                            $apellido=$row['apellido'];
                            $telefono=$row['telefono'];
                            $correo=$row['correo'];
                            $direccion=$row['direccion'];
                            $statusAlum=$row['statusAlum'];
                            $btn_class = ($statusAlum == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($statusAlum == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($statusAlum== 1) ? 'ACTIVO' : 'DESACTIVADO'; ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td id="ced<?= $son ?>"><?= $cedula; ?></td>
                                <td id="nom<?= $son ?>" data-toggle="modal" title="Datos Personales" data-target="#perfilProvee" style='cursor: pointer' onclick="editPerfil('<?= $son ?>','<?= $id ?>','<?= $cedula ?>','<?= $nombre ?>','<?= $apellido ?>','<?= $telefono ?>','<?= $correo ?>','<?= $direccion ?>')"><?= $nombre.' '.$apellido ; ?></td>
                                <td id="tlf<?= $son ?>"><?= $telefono ?></td>
                                <td id="mai<?= $son ?>"><?= $correo ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group">
                                        <button  type="button" class="btn btn-info btn-circle" data-toggle="modal" title="Datos Personales" data-target="#perfilProvee" style='cursor: pointer' onclick="editPerfil('<?= $son ?>','<?= $id ?>','<?= $cedula ?>','<?= $nombre ?>','<?= $apellido ?>','<?= $telefono ?>','<?= $correo ?>','<?= $direccion ?>' )"  ><i class="fas fa-user-edit " ></i></button>

                                        <button  type="button" class="btn btn-success btn-circle" data-toggle="modal" title="Nuevo Pago" onclick='window.open("provedor-recibo.php?id=<?= encriptar($id) ?>")'  ><i class="fas fa-dollar-sign " ></i></button>

                                        <button  type="button" class="btn btn-primary btn-circle" title="Historial de pagos" onclick='window.open("provedor-historia.php?id=<?= encriptar($id) ?>")'  ><i class="fas fa-folder" ></i></button>

                                         <button data-toggle="modal" data-target="#enviaMail" title="Enviar correo" onclick="preparaMail('<?= $nombre ?>','<?= $correo ?>')" type="button" class="btn btn-secondary btn-circle" onclick="" ><i class="fas fa-envelope " ></i></button>
                                        <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> fa-lg" ></i></button>
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
<div class="modal fade" id="nuevoProvee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Proveedor </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="provedor-nuevo.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 style="background-color:#F7DC6F; color: black; ">CUIDADO! No ingresar por aqui nuevos Maestros o Docentes</h4>
                            </div>
                            <div class="col-md-3">
                                <label>Cedula/Rif</label>
                                <input type="text" required id="cedulaNew" name="cedulaNew" onkeyup="buscaCed(event)" class="form-control">
                                <!--input type="hidden" id="cedLimpioNew" name="cedLimpioNew"-->
                            </div>
                            <div class="col-md-5">
                                <label>Nombre/Empresa</label>
                                <input type="text" required id="nombreNew" name="nombreNew" class="form-control">   
                            </div>
                            <div class="col-md-4">
                                <label>Apellido</label>
                                <input type="text" id="apellidoNew" name="apellidoNew" class="form-control">    
                            </div>
                            <div class="col-md-4">
                                <label>Telefono</label>
                                <input type="text" id="telefonoNew" name="telefonoNew" class="form-control">    
                            </div>
                            <div class="col-md-8">
                                <label>Email</label>
                                <input type="text" id="emailNew" name="emailNew" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label>Direccion</label>
                                <input type="text" id="direccNew" name="direccNew" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                    
                    <button type="submit" id="btn-guarda" class="btn btn-primary"><i class="fas fa-save fa-sm"></i> Guardar Cambios</button>
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
            $('#procesos').addClass("show");
        }
        $('#provedores').addClass("active");
    });
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
        $.post('provedor-edit.php',{'id':id, 'cedula':ced,'nombre':nom,'apellido':ape,'telefono':tlf,'correo':mai,'direcc':dir},function(data)
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
    function buscaCed(event) 
    {
        ced=$('#cedulaNew').val()
        if(ced.length > 5 )
        {
            $.post('provedor-buscar.php',{'cedula':ced},function(data){
            if(data.isSuccessful){
              if(data.nombre != ''){
                $('#nombreNew').val(data.nombre)
                $('#apellidoNew').val(data.apellido)
                $('#emailNew').val(data.correo)
                $('#telefonoNew').val(data.telefono)
                $('#direccNew').val(data.direcc)
                document.getElementById("btn-guarda").disabled = true;
              }          
            }else{
                $('#nombreNew').val('')
                $('#apellidoNew').val('')
                $('#emailNew').val('')
                $('#telefonoNew').val('')
                document.getElementById("btn-guarda").disabled = false;
                $('#direccNew').val('')
            }
          }, 'json');
        }
    }
    function nuevoPago(id,ced,ben) 
    {
        $('#idProvePag').val(id)
        $('#cedulaPag').val(ced)
        $('#nombrePag').val(ben)
        $('#montoPag').val('')
        $('#montoLet').val('')
        $('#refePag').val('')
        $('#formaPag').val('tra')
        $('#bancoPag').val('')
        $('#motivoPag').val('')
        document.getElementById("bancoPag").disabled = false;
        document.getElementById("refePag").disabled = false;
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
    function textMonto() 
    {
        if($('#formaPag').val()=='div')
        {
            document.getElementById('textMonto').innerHTML = 'Monto en $.';
            document.getElementById("bancoPag").disabled = true;
            document.getElementById("refePag").disabled = true;
            $('#bancoPag').val('');
            $('#refePag').val('');
        }else
        {
            document.getElementById('textMonto').innerHTML = 'Monto en Bs.';
            document.getElementById("bancoPag").disabled = false;
            document.getElementById("refePag").disabled = false;
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
?>
           