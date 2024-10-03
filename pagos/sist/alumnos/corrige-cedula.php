<?php
include_once "../include/header.php";
?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Corregir Cedula de Estudiante</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Estudiante a buscar</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group col-md-4 offset-md-4">
                    <label for="formGroupExampleInput">Cedula</label>
                    <input type="text" class="form-control" id="cedula" onkeypress="return ValCed(event)" placeholder="ingrese solo numeros">
                </div>
                <div class="form-group col-md-4 offset-md-4">
                    <button type="button" onclick="buscaAlumno()" style="width: 100%;" class="btn btn-primary"><i class="fas fa-search fa-sm"></i> Buscar</button>
                </div>
                <div class="form-group col-md-4 offset-md-4" style="display: none;" id="div_ced_nue">
                    <label for="formGroupExampleInput">Cedula Nueva</label>
                    <input type="text" class="form-control" id="cedulaNue" onkeypress="return ValCed(event)" placeholder="ingrese solo numeros" onkeyup="fnBuscarAlum()">
                </div>
                <div class="form-row" id="div_ced_exi" style="display: none;">
                    <div class="col-md-12 text-center" style="margin-top: 2%;">
                        <h4>ERROR! <br> nro. de cedula nueva ya esta registrada en el sistema con el estudiante<br><span id="alumnoExi"></span><br>Por favor verifique!</h4>
                    </div>
                    
                </div>
                <input type="hidden" id="dominio" value="<?= $domiVer ?>">
            </form>
            <hr>
            <div class="form-row">
                <div class=" col-md-4 offset-md-4 text-center">
                    <img width="200px" height="200px" id="fotoVer" src="../img/usuario.png" class="img-circle" /><br>
                    <span>Foto Alumno</span> 
                </div>
                <div class="col-md-12"></div>
                <div class=" col-md-3">
                    <label for="">Cedula</label>
                    <input type="text" class="form-control" readonly id="cedulaVer" >
                    <input type="hidden" id="idAlum" >
                </div>
                <div class="col-md-9">
                    <label for="">Estudiante</label>
                    <input type="text" class="form-control" readonly id="alumnoVer" >
                </div>
                <div class="col-md-4 offset-md-4" style="margin-top: 2%;">
                    <button type="button" onclick="guardaCambio()" id="btn-perfil" style="width: 100%;" disabled class="btn btn-info"><i class="fas fa-user-edit fa-sm"></i> Guardar Cambio</button>
                </div>
                
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
        $('#corregirCedula').addClass("active");
    });
    function buscaAlumno() 
    {
        ced=$('#cedula').val()
        $.post('buscar-alum-ajax.php',{'cedula':ced},function(data)
        {
            if(data.isSuccessful){
                $('#cedulaVer').val(ced)
                $('#alumnoVer').val(data.estudia)
                $('#idAlum').val(data.id)
                $("#fotoVer").attr("src",data.foto);
                document.getElementById("btn-perfil").disabled = false;
                $('#div_ced_nue').show();
                $('#div_ced_exi').hide();
                $('#cedulaNue').val('')
            }else
            {
                document.getElementById("btn-perfil").disabled = true;
                $('#cedulaVer').val('')
                $('#alumnoVer').val('')
                $('#idAlum').val('')
                $("#fotoVer").attr("src",'../img/usuario.png');
                $('#div_ced_nue').hide(); 
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Alumno no encontrado!'
                })
            }
          }, 'json');
    }
    function guardaCambio() 
    {
        id=$('#idAlum').val()
        ced=$('#cedulaNue').val()
        cedV=$('#cedula').val()
        $.post('corrige-cedula-ajax.php',{'cedula':ced,'idAlum':id,'cedulaVie':cedV},function(data)
        {
            if(data.isSuccessful){
                $('#cedula').val('')
                $('#cedulaVer').val(ced)
                document.getElementById("btn-perfil").disabled = true;
                $('#div_ced_nue').hide(); 
                $('#div_ced_exi').hide(); 
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente',
                  text: 'Cambio de cedula realizado exitosamente!'
                })
            }else
            {
                $('#cedulaNue').val('')
                document.getElementById("btn-perfil").disabled = true;
                $('#div_ced_nue').hide(); 
                $('#div_ced_exi').hide(); 
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Cambio no realizado!'
                })
            }
        }, 'json');

    }
    function verPagos() 
    {
        id=$('#idAlum').val()
        window.open("historia-pagos.php?id="+id)
    }
    function  fnBuscarAlum()
    {
        ced_buscar = $('#cedulaNue').val();
        if(ced_buscar.length > 7 ){
          $.post('alumno-nuevo-buscar.php',{'ced':ced_buscar},function(data){
            if(data.isSuccessful){
                $('#div_ced_exi').show(); 
                document.querySelector('#alumnoExi').innerText = data.nombre+' '+data.apelli;
                //$('#alumnoExi').val(data.nombre+' '+data.apelli)
                document.getElementById("btn-perfil").disabled = true;
                Swal.fire({
                icon: 'error',
                title: 'Error!',
                confirmButtonText:
                '<i class="fa fa-thumbs-up"></i> Entendido',
                text: 'Numero de cedula ya registrado en el sistema!'
                })
              
            }else{
              $('#div_ced_exi').hide(); 
              document.getElementById("btn-perfil").disabled = false;
            }
          }, 'json');
        }
    }
</script>
<?php
include_once "../include/footer.php";                
?>
           