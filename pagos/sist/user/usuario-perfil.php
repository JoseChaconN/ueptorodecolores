<?php
include_once "../include/header.php";
$link = Conectarse();
$idUser = (isset($_GET['id'])) ? desencriptar($_GET['id']) : '' ;
if($idUser>0)
{
    $user_query = mysqli_query($link,"SELECT * FROM user WHERE idUser ='$idUser' "); 
    while ($row = mysqli_fetch_array($user_query))
    {
        $idUser = $row['idUser'];  
        $nacionUser = $row['nacionUser'];  
        $cedulaUser = $row['cedulaUser'];
        $emailUser = $row['emailUser'];
        $claveUser = $row['claveUser'];
        $nombreUser = ($row['nombreUser']);  
        $apellidoUser = ($row['apellidoUser']);
        $cargoUser = $row['cargoUser'];
        $nombreCargo = $row['nombreCargo'];
        $telefonoUser = $row['telefonoUser'];
        $activoUser=$row['activoUser'];
        $fotoUser = $row['fotoUser'];
        $direccionUser=$row['direccionUser'];
        $fechaNacUser = $row['fechaNacUser'];
        $activo_hasta = $row['activo_hasta'];
        $impresora = $row['impresora'];
    }
    $guarda='1';
}else
{
    $nacionUser = '';  
    $cedulaUser = '';
    $emailUser = '';
    $claveUser = '';
    $nombreUser = '';  
    $apellidoUser = '';
    $cargoUser = '';
    $nombreCargo = '';
    $telefonoUser = '';
    $activoUser='';
    $fotoUser = '';
    $direccionUser='';
    $fechaNacUser = '';
    $guarda='2';
    $impresora = 1;
} ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Ficha del Usuario</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos Principales</h6>
        </div>
        <div class="card-body">
            <form role="form" id="userForm" onsubmit="usuario_actual(); return false">
                <div class="form-row"><?php 
                    if ($_SESSION['idUser']==1) {?>
                        <div class="col-md-4">
                            <label>Usuario activo hasta</label>
                            <input type="date" class="form-control" name="activo_hasta" value="<?= $activo_hasta ?>">
                        </div>                        
                        <div class="col-md-12"></div><?php 
                    }?>

                    <div class="col-md-3">
                        <label for="cedulaUser">Nacion / Cedula</label>
                        <div class="form-row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="nacionUser" name="nacionUser" value="<?= $nacionUser ?>" >    
                            </div>
                            <div class="col-md-9">
                                <input type="text" onkeyup="fnBuscarUser()" onkeypress="return ValCed(event)" class="form-control" name="cedulaUser" id="cedulaUser" placeholder="ingrese solo numeros" required value="<?= $cedulaUser ?>" >    
                            </div>
                        </div>
                    </div>                    
                    <div class="col-md-3">
                        <label for="claveUser">Contraseña</label>
                        <input type="text" class="form-control" id="claveUser" name="claveUser" value="<?= $claveUser ?>" >    
                    </div>
                    <div class="col-md-3">
                        <label for="fechaNacUser">Fecha Nac.</label>
                        <input type="date" class="form-control" id="fechaNacUser" name="fechaNacUser" value="<?= $fechaNacUser ?>" >    
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6">
                        <label for ="cargoUser">Cargo </label><br>
                        <select  name='cargoUser' id='cargoUser' class="form-control">
                            <option value="">Seleccione....</option><?php
                            if($cargoAct=='1')
                            {
                                $cargo_query = mysqli_query($link,"SELECT * FROM cargo_admin "); 
                            }else
                            {
                                $cargo_query = mysqli_query($link,"SELECT * FROM cargo_admin WHERE idcargo='$cargoUser' "); 
                            }
                            while ($row = mysqli_fetch_array($cargo_query))
                            {
                                $id_cargo=$row['idcargo'];
                                $nombre_cargo=$row['nomcargo'];
                                $selected='';
                                if($cargoUser == $id_cargo)
                                {
                                    $selected='selected';
                                }
                                echo '<option readonly value="'.$id_cargo.'"'.$selected.'>'.$nombre_cargo."</option>";
                            } ?>
                       </select>
                    </div>
                    <div class="col-md-6">
                        <label for="nombreUser">Nombres</label>
                        <input type="text" class="form-control" id="nombreUser" name="nombreUser" value="<?= $nombreUser ?>" >    
                    </div>
                    <div class="col-md-6">
                        <label for="apellidoUser">Apellidos</label>
                        <input type="text" class="form-control" id="apellidoUser" name="apellidoUser" value="<?= $apellidoUser ?>" >    
                    </div>
                    <div class="col-md-4 col-xs-6 col-sm-3">
                        <label for="telefonoUser" >Telefono</label>
                        <input type="text" name="telefonoUser" id="telefonoUser" maxlength="30" onkeypress="return valida(event)" class="form-control" value="<?= $telefonoUser ?>">
                    </div>
                    <div class="col-md-8 col-xs-12 col-sm-12">
                        <label for="emailUser">Email</label>
                        <input type="email" required title="Ingrese un correo valido ya que con el podra recuperar su contraseña" id="emailUser" name="emailUser" class="form-control" maxlength="50" value="<?= $emailUser ?>">
                    </div>
                    <div class="col-md-8 col-xs-12 col-sm-3">
                        <label for="direccionUser" >Direccion</label>
                        <input type="text" name="direccionUser" id="direccionUser" maxlength="100" class="form-control" value="<?= $direccionUser ?>">
                    </div>
                    <div class="col-md-4 ">
                        <label>Impresora Factura</label>
                        <select  name='impresora' id='impresora' class="form-control">
                            <option value="">Seleccione....</option><?php
                            $impresora_query = mysqli_query($link,"SELECT id,nombre FROM impresora WHERE status=1 "); 
                            while ($row = mysqli_fetch_array($impresora_query))
                            {
                                $idPrint=$row['id'];
                                $nomPrint=$row['nombre'];
                                $selected = ($idPrint==$impresora) ? 'selected' : '' ;
                                echo '<option value="'.$idPrint.'"'.$selected.'>'.$nomPrint."</option>";
                            }?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 col-12">
                        <label style="cursor: pointer;">Enviar información al correo del usuario?&nbsp;&nbsp;&nbsp;<input type="checkbox" style="cursor: pointer;transform: scale(2);" <?php if($url_actual=='localhost'){ echo "disabled";} ?> name="enviarInfo" value="1"></label>
                    </div>
                    <div class="col-md-4 offset-2" style="margin-top: 2%;">
                        <button type="button" style="width: 100%;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
                    </div>
                    <div class="col-md-4" style="margin-top: 2%;" id="divBtnGuardar">
                        <button type="submit"  style="width: 100%;" class="btn btn-primary"><i class="fas fa-save fa-sm"></i> Guardar</button>
                    </div>    
                </div>
                <input type="hidden" name="idUser" value="<?= encriptar($idUser) ?>">
                <input type="hidden" id="guarda" value="<?= $guarda ?>">
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        $('#page-top').removeClass("sidebar-toggled");
        $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
    });
    function usuario_actual()
    {
        if($('#guarda').val()=='1'){ archi='usuario-actual.php'; }else{archi='usuario-nuevo.php';}
        $.ajax({
        type: 'POST',
        url: archi,
        data: $('#userForm').serialize(),
        success: function(respuesta) 
        {
            if(respuesta=='ok')
            {
                opener.document.location.reload();
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos almacenados satisfactoriamente!'
                })
            }
            else {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Datos no almacenados!'
                })
            }
        }
        });
    } 
    function fnShowSecciones(div,btn) 
    {
        $(div).slideToggle();
        $(btn).toggleClass("fas fa-chevron-right");
        $(btn).toggleClass("fas fa-chevron-down");
    } 
    function  fnBuscarUser()
    {
        ced_buscar = $('#cedulaUser').val();
        if($('#guarda').val()=='2')
        {
            if(ced_buscar.length > 5 ){
              $.post('usuario-buscar.php',{'ced':ced_buscar},function(data){
                if(data.isSuccessful){
                  $('#idUser').val(data.idUser)
                  $('#nombreUser').val(data.nombre)
                  $('#apellidoUser').val(data.apelli)
                  $('#claveUser').val(data.clave)
                  $('#fechaNacUser').val(data.fnac)
                  $('#cargoUser').val(data.cargo)
                  $('#telefonoUser').val(data.tlf)
                  $('#emailUser').val(data.mail)
                  $('#direccionUser').val(data.dire)
                  $('#nacionUser').val(data.nacion)
                  $('#divBtnGuardar').hide();
                }else{
                  $('#idUser').val('')
                  $('#nombreUser').val('')
                  $('#apellidoUser').val('')
                  $('#claveUser').val('')
                  $('#fechaNacUser').val('')
                  $('#cargoUser').val('')
                  $('#telefonoUser').val('')
                  $('#emailUser').val('')
                  $('#direccionUser').val('')
                  $('#nacionUser').val('')
                  $('#divBtnGuardar').show();
                }
              }, 'json');
            }
        }
    }
    function ValCed(e)
    {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla==8)
        {
            return true;
        }
        patron =/[0-9]/;
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final);
    }
</script>
<?php
include_once "../include/footer.php";                
?>
           