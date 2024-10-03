<?php
include_once "../include/header.php";
$link=Conectarse();?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Buscar Estudiante por Cedula, Nombre o Apellido</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Estudiante a buscar</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="form-row col-md-12">
                    <div class="col-md-4">
                        <label for="formGroupExampleInput">Buscar por Cedula</label>
                        <input type="text" class="form-control" onkeypress="return ValCed(event)" id="cedula" placeholder="ingrese solo numeros">    
                    </div>
                    <div class="col-md-8 col-12">
                        <label for="formGroupExampleInput">Buscar por Apellido y Nombre</label>
                        <select class="form-control input-sm selectpicker" onchange="borraCed(); buscaAlumno()" id="alumno" name="alumno" data-live-search="true" >
                            <option value="" style="background-color: #90CAF9; " >Seleccione o ingrese apellidos o Nombres</option><?php
                            $alumnos_query = mysqli_query($link,"SELECT cedula,nombre,apellido,statusAlum FROM alumcer WHERE cargo IS NULL ORDER BY apellido ");                                
                                while ($row=mysqli_fetch_array($alumnos_query))
                                {
                                    $nombreAlum=$row['apellido'].' '.$row['nombre'];
                                    $cedulaNom=$row['cedula'];
                                    $statusAlum=$row['statusAlum'];
                                    if($statusAlum=='2'){$inac='*** INACTIVO **** ';}else{$inac='';}
                                    $nombreAlum=$nombreAlum.' '.$inac; ?>      
                                    <option <?php if($statusAlum=='2'){echo ' style="background-color:#FFEBEE; color: black; "';}else{echo ' style="background-color:#C8E6C9; color: black; "';} ?> value="<?= $cedulaNom ?>"><?= $nombreAlum ?></option><?php
                                } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 row" style="margin-top:10%;">
                    <div class="col-md-4 offset-md-2" style="margin-bottom: 2%;">
                        <button type="button" onclick='window.open("nuevo-ingreso.php")' class="btn btn-success" style="width: 100%;" title="Nota: Proceso solo para estudiantes no registrados en el sistema!"><i class="fas fa-plus fa-sm"></i> Nuevo Ingreso</button>
                    </div>    
                    <div class="form-group col-md-4">
                        <button type="button" onclick="buscaAlumno()" style="width: 100%;" class="btn btn-primary"><i class="fas fa-search fa-sm"></i> Buscar</button>
                    </div>
                </div>
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
                <div class="col-md-3">
                    <label for="">Grado, Sección, Periodo</label>
                    <select id="seleGrado" class="form-control">
                       
                    </select>
                </div>
                <div class="col-md-4 " style="margin-top: 2%;">
                    <button type="button" onclick="perfilAlum()" id="btn-perfil" style="width: 100%;" disabled class="btn btn-info"><i class="fas fa-user-edit fa-sm"></i> Perfil</button>
                </div>
                <div class="col-md-4" style="margin-top: 2%;">
                    <button type="button" style="width: 100%;" id="btn-pagos" onclick="verPagos()" class="btn btn-primary" disabled><i class="fas fa-search-dollar fa-sm"></i> Historia Pagos</button>
                </div>
                
                <div class="col-md-4" style="margin-top: 2%;">
                    <button type="button" style="width: 100%;" id="btn-reinscribe" class="btn btn-warning" disabled data-toggle="modal" data-target="#reinscribe"><i class="fas fa-user-plus fa-sm"></i> Reinscribir</button>
                </div>
                <div class="col-md-6" style="margin-top: 2%;">
                    <button type="button" style="width: 100%;" id="btn-factura" onclick="facturar()" class="btn btn-success" disabled><i class="fas fa-dollar-sign fa-sm"></i> Facturar</button>
                </div>
                <div class="col-md-6" style="margin-top: 2%;">
                    <button type="button" style="width: 100%;" onclick="facturarMisc()" id="btn-fact-misc" disabled class="btn btn-danger" ><i class="fas fa-dollar-sign fa-sm"></i> Miscelaneos</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reinscribe" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proceso de Reinscripción:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="POST" target="_blank" enctype="multipart/form-data" action="mailRepre.php">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 text-center" style="margin-bottom: 1%; background-color: green; color: white; ">
                            <h4>Estudiante Regular</h4>
                        </div>
                        <div class="col-md-4">
                            <label>Cedula</label>
                            <input type="text" id="cedulaRein" class="form-control" readonly>
                        </div>
                        <div class="col-md-8">
                            <label>Estudiante</label>
                            <input type="text" id="alumnoRein" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Periodo</label>
                            <select id="periodoNuevo" class="form-control"><?php
                                $periodo_query = mysqli_query($link,"SELECT nombre_periodo FROM periodos ORDER BY orden DESC LIMIT 1 ");
                                while($row = mysqli_fetch_array($periodo_query))
                                {
                                    $nombre_periodo1=$row['nombre_periodo'];
                                    //$selected = ($nombre_periodo1==$nombre_periodo) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$nombre_periodo1.'">'.$nombre_periodo1."</option>";
                                }?>
                            </select>
                        </div>
                        <div class="col-md-4" >
                            <label>Grado/Año</label>
                            <select id="gradoRein" class="form-control">
                                <option value="0">Seleccione</option><?php
                                $gradoVer_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado2223  ORDER BY grado ");
                                while($row = mysqli_fetch_array($gradoVer_query))
                                {
                                    $nom_gradsd=($row['nombreGrado']);
                                    $id_gradsd=$row['grado'];
                                    $selected = ($id_gradsd==$gradoVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_gradsd.'">'.$nom_gradsd."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-4" >
                            <label>Sección</label>
                            <select id="secciRein" class="form-control"><?php
                                $secciVer_query = mysqli_query($link,"SELECT * FROM secciones ORDER BY id ");
                                while($row1 = mysqli_fetch_array($secciVer_query))
                                {
                                    $nom_secdsd=utf8_encode($row1['nombre']);
                                    $id_secdsd=$row1['id'];
                                    $selected = ($id_secdsd==$secciVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                                }?>                                
                            </select> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="enviar()" class="btn btn-primary">Procesar Reinscripción</button>
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
        $('#buscaEstudiante').addClass("active");
    });
    function buscaAlumno() 
    {
        ced=$('#cedula').val()
        nom=$('#alumno').val()
        $.post('buscar-alum-ajax.php',{'cedula':ced,'cedNom':nom},function(data)
        {
            if(data.isSuccessful){
                $('#cedulaVer').val(data.ced)
                $('#alumnoVer').val(data.estudia)
                
                $('#cedulaRein').val(data.ced)
                $('#alumnoRein').val(data.estudia)
                
                $('#idAlum').val(data.id)
                //document.querySelector('#aquien').innerText = data.estudia;
                $("#fotoVer").attr("src",data.foto);
                document.getElementById("btn-perfil").disabled = false;
                document.getElementById("btn-pagos").disabled = false;
                document.getElementById("btn-factura").disabled = false;
                document.getElementById("btn-fact-misc").disabled = false;
                if(data.inscrito==2)
                {document.getElementById("btn-reinscribe").disabled = false;}else
                {document.getElementById("btn-reinscribe").disabled = true;} 
                $("#seleGrado").html(data.opcion);
                $("#seleGrado").selectpicker('refresh')
                //$("#periodoNuevo").html(data.periNue);
                //$("#periodoNuevo").selectpicker('refresh')
                $('#cedula').val('')
                $('#alumno').val('')
                $('#alumno').val('').trigger('change')
            }else
            {
                document.getElementById("btn-perfil").disabled = true;
                document.getElementById("btn-pagos").disabled = true;
                document.getElementById("btn-factura").disabled = true;
                document.getElementById("btn-fact-misc").disabled = true;
                document.getElementById("btn-reinscribe").disabled = false;
                $('#cedulaVer').val('')
                $('#alumnoVer').val('')
                $('#idAlum').val('')
                $("#fotoVer").attr("src",'../img/usuario.png');
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Alumno no encontrado!'
                })
                $("#seleGrado").html('');
                $("#seleGrado").selectpicker('refresh')
                //$("#periodoNuevo").html('');
                //$("#periodoNuevo").selectpicker('refresh')
            }
          }, 'json');
    }
    function borraCed() {
        $('#cedula').val('')
    }
    function perfilAlum() 
    {
        id=$('#idAlum').val()
        sele=$('#seleGrado').val()
        //pendiente cuando el colegio sea UECA la tabla cambia y hay que modificar los valores de substring
        tabl=sele.substring(0,4)
        peri=sele.substring(5,14)
        grad=sele.substring(15,17)
        sec=sele.substring(18,19)
        if(grad<61)
        {
            window.open("perfil-pri-alumno.php?id="+id+"&peri="+tabl+"&gra="+grad+"&sec="+sec+"&nomP="+peri)    
        }else
        {
            window.open("perfil-alumno.php?id="+id+"&peri="+tabl+"&gra="+grad+"&sec="+sec+"&nomP="+peri)
        }
    }
    function verPagos() 
    {
        id=$('#idAlum').val()
        sele=$('#seleGrado').val()
        tabl=sele.substring(0,4)
        grad=sele.substring(15,17)
        window.open("../procesos/historia-pagos.php?id="+id+"&peri="+tabl+"&gra="+grad)
    }
    function facturar() 
    {
        id=$('#idAlum').val()
        sele=$('#seleGrado').val()
        tabl=sele.substring(0,4)
        grad=sele.substring(15,17)
        window.open("../factura/facturar.php?id="+id+"&peri="+tabl+"&gra="+grad )
    }
    function facturarMisc() 
    {
        id=$('#idAlum').val()
        sele=$('#seleGrado').val()
        tabl=sele.substring(0,4)
        grad=sele.substring(15,17)
        window.open("../factura/facturar-misc.php?id="+id+"&peri="+tabl+"&gra="+grad )
    }
    function enviar() {
        gra=$('#gradoRein').val()
        id=$('#idAlum').val()
        sec=$('#secciRein').val()
        nomPeri=$('#periodoNuevo').val()
        tabl=nomPeri.substring(2,4)+''+nomPeri.substring(7,9)
        if(gra==0)
        {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Seleccione un grado o Año!'
            })
        }else
        {
            $.post('alumno-regular-deuda.php',{'id':id},function(data)
            {
                if(data.isSuccessful)
                {
                    deu=data.deuda
                    per=data.periodoDeb
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Estudiante no puede reinscribir, tiene deuda pendiente por '+deu+'$ en el periodo '+per
                    })
                }else
                {
                    window.open("alumno-regular.php?id="+id+"&peri="+tabl+"&gra="+gra+"&sec="+sec+"&nomP="+nomPeri )         
                }
            }, 'json');
            
        }
    }
</script>
<?php
include_once "../include/footer.php";                
?>

           