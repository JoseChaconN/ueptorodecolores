<?php
include_once "../include/header.php";
$link = Conectarse();
?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Cambiar a Estudiante de Especialidad solo en (<?= PROXANOE ?>)</h1>
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
                    <label for="formGroupExampleInput">Grado o Año Nuevo</label>
                    <select name='gra_alu' id='gra_alum' onchange="activaBoton()" class="form-control">
                        
                    </select>
                </div>
                <div class="form-row" id="div_ced_exi" style="display: none;">
                    <div class="col-md-12 text-center" style="margin-top: 2%;">
                        <h4>ERROR! <br> nro. de cedula nueva ya esta registrada en el sistema con el estudiante<br><span id="alumnoExi"></span><br>Por favor verifique!</h4>
                    </div>
                </div>
                <input type="hidden" id="seccion" value="">
                <input type="hidden" id="tabla" value="">
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
                <div class="col-md-6">
                    <label for="">Estudiante</label>
                    <input type="text" class="form-control" readonly id="alumnoVer" >
                </div>
                <div class=" col-md-3">
                    <label for="">Cursando</label>
                    <input type="text" id="cursando" class="form-control" readonly>
                </div>
                <div class="col-md-4 offset-md-4" style="margin-top: 2%;">
                    <button type="button"  id="btn-perfil" onclick="guardaCambio()" style="width: 100%;" disabled class="btn btn-info"><i class="fas fa-user-edit fa-sm"></i> Guardar Cambio</button>
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
        $('#corregirEspecial').addClass("active");
    });
    function buscaAlumno() 
    {
        ced=$('#cedula').val()
        $.post('cambiar-especial-buscar-ajax.php',{'cedula':ced},function(data)
        {
            if(data.isSuccessful){
                $('#cedulaVer').val(ced)
                $('#alumnoVer').val(data.estudia)
                $('#idAlum').val(data.id)
                $('#cursando').val(data.cursa)
                $("#fotoVer").attr("src",data.foto);
                $('#div_ced_nue').show();
                $('#div_ced_exi').hide();
                $('#seccion').val(data.secci)
                $("#gra_alum").html(data.grados);
                $("#gra_alum").selectpicker('refresh')
                $('#tabla').val(data.tabla)
            }else
            {
                $('#cedulaVer').val('')
                $('#alumnoVer').val('')
                $('#idAlum').val('')
                $("#fotoVer").attr("src",'../img/usuario.png');
                $('#div_ced_nue').hide(); 
                $('#seccion').val("")
                $('#tabla').val("")
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Alumno no encontrado!'
                })
            }
          }, 'json');
    }
    function activaBoton() {
        gra=$('#gra_alum').val()
        if(gra>0){
            document.getElementById("btn-perfil").disabled = false;
        }else{document.getElementById("btn-perfil").disabled = true;}
    }
    function guardaCambio() 
    {
        id=$('#idAlum').val()
        gra=$('#gra_alum').val()
        ced=$('#cedula').val()
        sec=$('#seccion').val()
        tabl=$('#tabla').val()
        $.post('cambiar-especial-guarda-ajax.php',{'idAlum':id,'grado':gra,'cedula':ced,'secci':sec,'tabla':tabl},function(data)
        {
            if(data.isSuccessful){
                $('#cedula').val('')
                $('#cedulaVer').val('')
                $('#cursando').val('')
                $('#alumnoVer').val('')
                $("#fotoVer").attr("src",'../img/usuario.png');
                document.getElementById("btn-perfil").disabled = true;
                $('#div_ced_nue').hide(); 
                $('#div_ced_exi').hide(); 
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente',
                  text: 'Cambio de Especialidad realizado exitosamente!'
                })
            }else
            {
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
</script>
<?php
include_once "../include/footer.php";                
?>
           