<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  $idTarea=desencriptar($_GET['id']);
  $grado=$_GET['cod'];
  $nombreGrado=$_GET['gra'];
  $nombreSec=$_GET['sec'];
  $lapsoActivo=$_SESSION['lapsoActivo'];
  if($grado<61)
  {
    $tarea_query = mysqli_query($link,"SELECT A.tituloTarea, A.descriTarea, A.fechaPublica, A.fechaMaxima, B.nombreGrado, C.nombre as nomsec FROM tareaspri".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C  WHERE A.idTarea='$idTarea' and A.codGrado=B.grado and A.codSecci=C.id");
  }else
  {
    $tarea_query = mysqli_query($link,"SELECT A.tituloTarea, A.descriTarea, A.fechaPublica, A.fechaMaxima, B.nombreGrado, C.nombre as nomsec FROM tareas".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C  WHERE A.idTarea='$idTarea' and A.codGrado=B.grado and A.codSecci=C.id");
  }
  while($row=mysqli_fetch_array($tarea_query)) 
  {
    $tituloTarea=$row['tituloTarea'];
    $descriTarea=$row['descriTarea'];
    $fechaMaxima=date("d-m-Y", strtotime($row['fechaMaxima']));
    $fechaPublica=date("d-m-Y", strtotime($row['fechaPublica']));
    $nombreGrado=utf8_encode($row['nombreGrado']);
    $nomsec=$row['nomsec'];
  }
  if($grado<61)
  {
    $alumnos_query = mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido, C.tlf_celu, C.representante FROM alumcer A, tareaspri".$tablaPeriodo." B, represe C  WHERE A.Periodo='$periodoActivo' and B.idTarea='$idTarea' and B.codGrado=A.grado and B.codSecci=A.seccion and A.ced_rep=C.cedula ORDER BY A.cedula ");
  }else
  {
    $alumnos_query = mysqli_query($link,"SELECT A.idAlum, A.cedula, A.nombre, A.apellido, C.tlf_celu, C.representante FROM alumcer A, tareas".$tablaPeriodo." B, represe C  WHERE A.Periodo='$periodoActivo' and B.idTarea='$idTarea' and B.codGrado=A.grado and B.codSecci=A.seccion and A.ced_rep=C.cedula ORDER BY A.cedula ");
  } ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4><?= $nombreGrado.' Sección '.$nombreSec.' Lapso : '.$lapsoActivo.'°<br>Plazo de entrega desde: '.$fechaPublica.' / hasta: '.$fechaMaxima.'<br>Tarea: '.$tituloTarea.'<br>'.$descriTarea ?></h4>
        <button class="btn btn-warning" type="button" onclick="javascript:window.close();opener.window.focus();" style="font-size: 18px;" ><i class="ri-close-line"></i> Cerrar Ventana</button>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Cedula</th>
                <th scope="col">Estudiante</th>
                <th scope="col">Representante</th>
                <th scope="col">Status</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=0;
              
              while($row=mysqli_fetch_array($alumnos_query)) 
              { 
                $ced_alu=$row['cedula'];
                $nom_alu=substr($row['nombre'],0,15).' '.substr($row['apellido'], 0,15);
                $nom_rep= substr($row['representante'],0,20);
                $tlf_celu=$row['tlf_celu'];
                $idAlum=$row['idAlum'];
                
                $son++;?>
                <tr>
                  <td><?= $son; ?></td>
                  <td><?= $ced_alu ?></td>
                  <td><?= $nom_alu ?></td>
                  <td><?= $nom_rep ?></td><?php
                    $entrego_query = mysqli_query($link,"SELECT idEntrega, observDocente FROM entrego_tarea".$tablaPeriodo."  WHERE idAlumno='$idAlum' and idTarea='$idTarea'");
                    if(mysqli_num_rows($entrego_query) > 0)
                    {$entrega='Entrego';}else{$entrega='Pendiente';} 
                    while($row=mysqli_fetch_array($entrego_query)) 
                    {
                      $idEntrega=$row['idEntrega'];
                      $observDocente=$row['observDocente'];
                    } ?>
                  <td id="entrega<?= $son ?>" class="text-center" <?php if($entrega=='Pendiente'){ echo 'style="background: #F1948A;"';}else{ echo 'style="background: #7DCEA0;"';}  ?>><?= strtoupper($entrega) ?></td>
                  <td style="width: 15%;"><?php
                    if(!empty($tlf_celu))
                    { ?>
                      <a target="_blank" title="Enviar mensaje wthasApp al representante" href="https://api.whatsapp.com/send?phone=<?= $tlf_celu ?>&text=Estimado(a)%20<?= $nom_rep ?>%20junto%20con%20saludarle%20desde%20la%20U.E.P.%20<?= EKKS ?>%20me dirijo a usted muy respetuosamente para informarle que su representado no ha entregado correctamente por esta via la Tarea <?= $tituloTarea ?> la cual podra ser recibida hasta la fecha <?= $fechaMaxima ?>, me despido de usted deseandole el mejor de los dias."><button  class="btn btn-outline-success" type="button"  aria-hidden="true"><i class="ri-whatsapp-line"></i></button></a><?php 
                    }
                    if($entrega=='Entrego')
                    { ?>
                      <button type="button" onclick="borraEntrega('<?= encriptar($idEntrega) ?>','<?= $son ?>')" class="btn btn-outline-danger" title="Borrar la Tarea"><i class="ri-delete-bin-2-line"></i></button>
                      <button type="button" data-bs-toggle="modal" data-bs-target="#comentarios" onclick="comentar('<?= $son ?>','<?= encriptar($idEntrega) ?>','<?= $nom_alu ?>')" class="btn btn-outline-info" title="Ver Observaciones del docente"><i class="ri-eye-line"></i></button><?php 
                    }else
                    { ?>
                      <a  onclick="entrega('<?= encriptar($idAlum) ?>','<?= encriptar($idTarea) ?>','<?= $son ?>')"><button type="button" class="btn btn-outline-primary" title="Marcar tarea como RECIBIDA"><i class="ri-asterisk"></i></button></a><?php

                    } ?>
                    <input type="hidden" name="observDocente" <?php echo "id='observDocente$son'"; ?> value="<?= $observDocente ?>">
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
          <input type="hidden" id="nombreGrado" value="<?= $nombreGrado ?>">
          <input type="hidden" id="nomsec" value="<?= $nomsec ?>">
          <input type="hidden" id="tituloTarea" value="<?= $tituloTarea ?>">
          <input type="hidden" id="descriTarea" value="<?= $descriTarea ?>">
          <input type="hidden" id="fechaPublica" value="<?= $fechaPublica ?>">
          <input type="hidden" id="fechaMaxima" value="<?= $fechaMaxima ?>">
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <div class="modal fade" id="comentarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Comentarios para:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="col">
            <div class="row">
              <div class="col-md-7">
                <label>Estudiante:</label>
                <input type="text" class="form-control" readonly id="alumnoVer">
              </div>
              <div class="col-md-3">
                <label>Grado:</label>
                <input type="text" class="form-control" readonly id="gradoVer" >
              </div>
              <div class="col-md-2">
                <label>Seccion:</label>
                <input class="form-control" readonly="" type="text" id="seccVer">
              </div>
              <div class="col-md-12">
                <label>Titulo Tarea:</label>
                <input class="form-control" readonly="" type="text" id="tituloVer">
              </div>
              <div class="col-md-12">
                <label>Descripcion:</label>
                <input type="text" class="form-control" aria-describedby="basic-addon1" id="descriVer" readonly>
              </div>
              <div class="col-md-6">
                <label>Fecha de Publicación</label>
                <input type="text" readonly class="form-control" id="publicaVer">
              </div>
              <div class="col-md-6">
                <label>Maxima de Recepción</label>
                <input type="text" readonly class="form-control" id="maximaVer">
                <input type="hidden" name="idEntregaOculta" id="idEntregaOculta">
              </div>
              <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge; margin-top: 2%;">
                <label>Comentarios:</label>
                <textarea class="form-control" rows="20" id="comentaVer" name="comentaVer" style="max-width:100%;"></textarea>
              </div>
              <input type="hidden" id="idTareaVer">
              <input type="hidden" id="linVer">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="guardar()" >Guardar Cambio</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    $(document).ready( function () 
    {
      $('#table_id').DataTable({
        "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
      });
      
    } );
    function borraEntrega(id,lin) 
    {
      Swal.fire({
        title: 'Borrar Entrega?',
        text: "Al borrar esta entrega le permite a el estudiante volver a realizar el envío",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Borrar!',
        cancelButtonText: 'Me arrepenti'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('borraEntrega.php',{'id':id},function(data){
            if(data.isSuccessful){
              document.getElementById("entrega"+lin).innerHTML = 'PENDIENTE';
              document.getElementById('entrega'+lin).style.backgroundColor = "#F1948A";
            }
          }, 'json');

          Swal.fire(
            'Borrada!',
            'Su Entrega fue eliminada.',
            'success'
          )
        }
      })
    }
    function entrega(idAlu,idTar,lin) 
    {
      Swal.fire({
        title: 'Recibir Tarea?',
        text: "En caso de que el estudiante entregue al docente la tarea en fisico o por alguna otra via ¿Cambiar status como ENTREGADO?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, recibida!',
        cancelButtonText: 'Me arrepenti'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('recibeTarea.php',{'idA':idAlu, 'idT':idTar},function(data){
            if(data.isSuccessful){
              document.getElementById("entrega"+lin).innerHTML = 'ENTREGO';
              document.getElementById('entrega'+lin).style.backgroundColor = "#7DCEA0";
            }
          }, 'json');

          Swal.fire(
            'Recibida!',
            'Su Tarea fue Actualizada.',
            'success'
          )
        }
      })
    }
    function comentar(son,id,nom)
    {
      $('#idEntregaOculta').val(id);
      $('#alumnoVer').val(nom);
      $('#gradoVer').val($('#nombreGrado').val());
      $('#seccVer').val($('#nomsec').val());
      $('#tituloVer').val($('#tituloTarea').val());
      $('#descriVer').val($('#descriTarea').val());
      $('#publicaVer').val($('#fechaPublica').val());
      $('#maximaVer').val($('#fechaMaxima').val());
      $.post('comentaDoc.php',{'id':id},function(data){
        if(data.isSuccessful){
          $('#comentaVer').val(data.comenta)
        }
      }, 'json');
    }
    function guardar() 
    {
      id=$('#idEntregaOculta').val()
      come=$('#comentaVer').val()
      $.post('comentaActual.php',{'id':id, 'comenta':come},function(data){
        if(data.isSuccessful){
          $('#comentarios').modal('hide')
        }
      }, 'json');
    }
  </script>

</body>

</html>