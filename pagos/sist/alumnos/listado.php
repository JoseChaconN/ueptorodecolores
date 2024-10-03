<?php
include_once "../include/header.php";
$link = Conectarse();
if(!isset($_POST['gradoVer']) && !isset($_POST['secciVer']))
{
    $periodo_query = mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos WHERE activoPeriodo='1' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $nombre_periodo=$row['nombre_periodo'];
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    $grado1_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." WHERE grado>60 ORDER BY grado LIMIT 1 ");
    while($row = mysqli_fetch_array($grado1_query))
    {
        $gradoVer=$row['grado'];
    }
    $secci1_query = mysqli_query($link,"SELECT * FROM secciones ORDER BY id LIMIT 1 ");
    while($row1 = mysqli_fetch_array($secci1_query))
    {
        $secciVer=$row1['id'];
    }
} else
{
    $nombre_periodo=$_POST['periodoVer'];
    $periodo_query = mysqli_query($link,"SELECT tablaPeriodo FROM periodos WHERE nombre_periodo='$nombre_periodo' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    
    $gradoVer = $_POST['gradoVer'];
    $secciVer = $_POST['secciVer'];
}
if($gradoVer=='0' || $secciVer=='0')
{

    $query = mysqli_query($link,"SELECT A.idAlum, D.statusAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.ced_rep,A.id_quienPaga, B.nombreGrado as 'nomgra', A.apellido, D.grado, D.idSeccion, D.pagado, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and 
        IF('$gradoVer'='0' and '$secciVer'='0',D.grado=B.grado and D.idSeccion=C.id,A.idAlum=D.idAlumno) and 
        IF('$gradoVer'='0' and '$secciVer'>'0',D.grado=B.grado and C.id=D.idSeccion and D.idSeccion='$secciVer',A.idAlum=D.idAlumno) and
        IF('$gradoVer'>'0' and '$secciVer'='0',D.grado='$gradoVer' and D.grado=B.grado and D.idSeccion=C.id,A.idAlum=D.idAlumno) ORDER BY D.grado,D.idSeccion, A.apellido ASC ");
}else
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.ced_rep,A.id_quienPaga, B.nombreGrado as 'nomgra', A.apellido, D.statusAlum, D.grado, D.idSeccion,D.pagado, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." D WHERE A.idAlum=D.idAlumno and D.grado='$gradoVer' and D.idSeccion='$secciVer' and D.grado=B.grado and D.idSeccion=C.id ORDER BY D.grado,D.idSeccion, A.apellido ASC ");
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Listado de Estudiantes (Bachillerato) </h1>    
        </div>    
        <div class="col-md-3">
            <button type="button" class="btn btn-primary" onclick='window.open("listado-pdf.php?idG=<?= $gradoVer ?>&idS=<?= $secciVer ?>&peri=<?= $tablaPeriodo ?>&nomP=<?= $nombre_periodo ?>")' style="width: 100%"><i class="fas fa-print"></i> Imprimir Listado</button>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-success" onclick='window.open("perfil-alumno.php?guarda=nuevo&peri=<?= $tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")' style="width: 100%"><i class="fas fa-plus"></i> Nuevo Alumno</button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="listado.php">
                    <div class="form-row">
                        <div class="col-md-3 col-md-offset-3 col-xs-6 col-sm-6">
                            <select name="periodoVer" class="form-control" onchange="pulsaBuscar()"><?php
                                $periodo_query = mysqli_query($link,"SELECT tablaPeriodo,nombre_periodo FROM periodos WHERE pagos='S' ORDER BY nombre_periodo ");
                                while($row = mysqli_fetch_array($periodo_query))
                                {
                                    $nombre_periodo1=$row['nombre_periodo'];
                                    $selected = ($nombre_periodo1==$nombre_periodo) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$nombre_periodo1.'">'.$nombre_periodo1."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-md-offset-3 col-xs-6 col-sm-6">
                            <select name="gradoVer" class="form-control" onchange="pulsaBuscar()">                              
                                <option value="0">Año/Grado: Todos</option><?php
                                $gradoVer_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." WHERE grado>60 ORDER BY grado ");
                                while($row = mysqli_fetch_array($gradoVer_query))
                                {
                                    $nom_gradsd=($row['nombreGrado']);
                                    $id_gradsd=$row['grado'];
                                    $selected = ($id_gradsd==$gradoVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_gradsd.'">'.$nom_gradsd."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-6" style="margin-bottom: 2%;">
                            <select name="secciVer" class="form-control" onchange="pulsaBuscar()">                              
                                <option value="0">Seccion: Todas</option><?php
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
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Grado/Sección</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Grado/Sección</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $grado=$row['grado'];
                            $nomgra=($row['nomgra']);
                            $alumno=$row["nomalu"].' '.$row['apellido'];
                            $nomsec=$row['nomsec'];
                            $ced_alu=$row['cedalu'];
                            $statusAlum=$row['statusAlum'];
                            $idAlum=$row['idAlum'];
                            $pagado=$row['pagado'];
                            $id_quienPaga=$row['id_quienPaga'];
                            $btn_class = ($statusAlum == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($statusAlum == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($statusAlum== 1) ? 'ACTIVO en este Periodo' : 'DESACTIVADO en este Periodo';
                            $son++;
                            $ced_rep=$row['ced_rep'];
                            $represe_query = mysqli_query($link,"SELECT representante,correo,direccion FROM represe WHERE cedula='$ced_rep' ");
                            if(mysqli_num_rows($represe_query) > 0)
                            {
                                $row2=mysqli_fetch_array($represe_query);
                                $representante=$row2['representante'];
                                $correo=$row2['correo'];
                                $direccion=$row2['direccion'];
                            }else
                            {
                                $representante=''; $correo=''; $direccion='';
                            }
                            if($id_quienPaga<1)
                            {
                                $emite_query=mysqli_query($link,"SELECT id FROM emite_pago WHERE ced_reci='$ced_rep'");
                                if(mysqli_num_rows($emite_query) > 0)
                                {
                                    $row3=mysqli_fetch_array($emite_query);
                                    $id_quienPaga=$row3['id'];
                                    mysqli_query($link,"UPDATE alumcer SET id_quienPaga='$id_quienPaga' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO SSSS ".mysqli_error($link));
                                }else
                                {
                                    $ced_reci=$ced_rep;
                                    $nom_reci=$representante;
                                    $dir_reci=$direccion;
                                    mysqli_query($link,"INSERT INTO emite_pago (ced_reci,nom_reci,dir_reci) VALUES ('$ced_reci', '$nom_reci', '$dir_reci')") or die ("NO GUARDO ".mysqli_error($link));
                                    $nuevo_quienPaga_query = mysqli_query($link,"SELECT LAST_INSERT_ID(id) as nuevoQuien FROM emite_pago order by id desc limit 0,1  ");
                                    $row4=mysqli_fetch_array($nuevo_quienPaga_query);
                                    $id_quienPaga=$row4['nuevoQuien'];
                                    mysqli_query($link,"UPDATE alumcer SET id_quienPaga='$id_quienPaga' WHERE idAlum = '$idAlum'") or die ("NO ACTUALIZO ALUMNO SSSS ".mysqli_error($link));
                                }
                            } ?>
                            <tr id="linea<?= $son ?>">
                                <td><?= $son; ?></td>
                                <td><?php if($idUserAct==1){ echo $ced_alu.' ('.$idAlum.')';}else{ echo $ced_alu; } ?></td>
                                <input type="hidden" name="" <?php echo "id='nom_pac$son'"; ?> value="<?php echo $row["nombre"]; ?>">
                                <input type="hidden" name="cedula" value="<?php echo $ced_alu ?>" <?php echo "id='ced$son'"; ?>>
                                <td style='cursor: pointer' onclick='window.open("perfil-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= substr($row['nomalu'], 0,22) ; ?></td>
                                <td style='cursor: pointer' onclick='window.open("perfil-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")'><?= substr($row['apellido'], 0,20); ?></td>
                                <td><?= $nomgra.' "'.$nomsec.'"' ?></td> <?
                                $fechanac=$row["fechanac"];
                                $edad = $fechahoy -$fechanac.' años'; ?>
                                <td>
                                    <div class="dropdown mb-4 btn-group">
                                        
                                        <button onclick='window.open("perfil-alumno.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$gradoVer.'&sec='.$secciVer.'&nomP='.$nombre_periodo ?>")' type="button" title='Editar ficha del estudiante' class="btn btn-info btn-circle" ><i class="fas fa-user-edit fa-lg" ></i></button>
                                        <button onclick='window.open("../factura/facturar.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$grado ?>")' type="button" title='Facturar ' class="btn btn-success btn-circle" ><i class="fas fa-dollar-sign fa-lg" ></i></button>

                                        <button onclick='window.open("../procesos/historia-pagos.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo ?>")' type="button" title='Historia de Pagos ' class="btn btn-secondary btn-circle" ><i class="fas fa-folder fa-lg" ></i></button>

                                        <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="statusAlum('<?= $idAlum ?>','<?= $son ?>','<?= $tablaPeriodo ?>','<?= $grado ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> fa-lg" ></i></button><?php 
                                        if($correo!='')
                                        {?>
                                            <button onclick="enviaMail('<?= $alumno ?>','<?= $representante ?>','<?= $correo ?>','<?= $nomgra ?>','<?= $nomsec ?>')" type="button" title='Enviar correo al representante' data-toggle="modal" data-target="#enviaMail" class="btn btn-info btn-circle" ><i class="fas fa-envelope fa-lg" ></i></button><?php 
                                        }
                                        if($idUserAct==1)
                                        { ?>
                                            <!--button onclick="borraAlum('<?= encriptar($idAlum) ?>','<?= $son ?>','<?= $ced_alu ?>')" type="button" title='Borrar el alumno de sistema completo' class="btn btn-danger btn-circle" ><i class="fas fa-trash-alt fa-lg" ></i></button--><?php
                                        }?>
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
<div class="modal fade" id="enviaMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Representante: <h4 id="aquien"></h4></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="POST" target="_blank" enctype="multipart/form-data" action="mailRepre.php">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="asunto" required id="asunto" placeholder="Asunto" class="form-control">
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <textarea placeholder="Mensaje a enviar" rows="6" class="form-control" name="mensaje" id="mensaje"></textarea>
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <label class="subtituloficha">Archivo adjunto</label>
                            <input type="file"  name="archivo" id="BSbtninfo" class="archivo" >
                        </div>
                        <input type="hidden" id="alumn_mail" name="alumn_mail">
                        <input type="hidden" id="repre_mail" name="repre_mail">
                        <input type="hidden" id="corre_mail" name="corre_mail">
                        <input type="hidden" id="grado_mail" name="grado_mail">
                        <input type="hidden" id="secci_mail" name="secci_mail">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" onclick="enviado()" class="btn btn-primary">Enviar</button>
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
            $('#listados').addClass("show");
        }
        $('#listadoAlum').addClass("active");
    });
    function cedAlum(ced) {
        $('#cedAlum').val(ced);
    }
    function enviado() {
        $('#enviaMail').modal('hide')
    }
    $('#BSbtninfo').filestyle({
      buttonName : 'btn-info',
      buttonText : ' Buscar Archivo'
    });
    function enviaMail(alu,rep,mai,gra,sec) {
        document.querySelector('#aquien').innerText = rep;
        $('#alumn_mail').val(alu)
        $('#repre_mail').val(rep)
        $('#corre_mail').val(mai)
        $('#grado_mail').val(gra)
        $('#secci_mail').val(sec)
    }
    function  statusAlum(idAlumno,Van,tablaP,gra)
    {
        idA = idAlumno;
        $.post('statusAlumno.php',{'idAlu':idA, 'tabPer':tablaP,'grado':gra},function(data)
        {
            if(data.isSuccessful)
            {
              if(data.status=='1')
              {
                $('#boton_'+Van).removeClass("btn-danger").addClass("btn-primary");
                $('#btnI_'+Van).removeClass("fa-lock").addClass("fa-check");
                $('#boton_'+Van).prop('title', 'ACTIVO en este Periodo');
              }else
              {
                $('#boton_'+Van).removeClass("btn-primary").addClass("btn-danger");
                $('#btnI_'+Van).removeClass("fa-check").addClass("fa-lock");
                $('#boton_'+Van).prop('title', 'DESACTIVADO en este Periodo');
              }
            } 
        }, 'json');
    }
    function borraAlum(id,lin,ced) {
        Swal.fire({
        title: 'Borrar?',
        text: "Elimina completamente el alumno de todas las bases de datos (ARCHIVO DE BORRADO INCOMPLETO NO USAR)",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si Eliminar!'
        }).then((result) => {
            $.post('alumno-borrar.php',{'idAlu':id,'cedula':ced},function(data)
            {
                if(data.isSuccessful)
                {
                  $('#linea'+lin).hide();
                  Swal.fire({
                  icon: 'success',
                  title: 'Excelente!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Alumno Eliminado!'
                })
                } 
            }, 'json');
        })
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";
mysqli_free_result($periodo_query);
mysqli_free_result($grado1_query);
mysqli_free_result($secci1_query);
mysqli_free_result($query);
mysqli_free_result($gradoVer_query);
mysqli_free_result($represe_query);
mysqli_free_result($emite_query);

?>
           