<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
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
  $grado=desencriptar($_GET['gra']);
  $codMate=desencriptar($_GET['mat']);
  $secci=$_GET['sec'];
  $grado_query = mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." WHERE grado='$grado'");
  while($row = mysqli_fetch_array($grado_query))
  {
    $nombreGrado=($row['nombreGrado']);
  }
  $seccion_query = mysqli_query($link,"SELECT nombre FROM secciones WHERE id='$secci'");
  while($row = mysqli_fetch_array($seccion_query))
  {
    $nombreSec=$row['nombre'];
  }
  $materia_query = mysqli_query($link,"SELECT nombremate FROM materiass".$tablaPeriodo." WHERE codigo='$codMate'");
  while($row = mysqli_fetch_array($materia_query))
  {
    $nombreMate=$row['nombremate'];
  }
  $tareas_query = mysqli_query($link,"SELECT * FROM tareas".$tablaPeriodo."  WHERE codGrado='$grado' and codSecci='$secci' and cedProf='$cedula' and lapsoTarea='$lapsoActivo' and codMater='$codMate'  ORDER BY fechaPublica"); ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4>Listado de Material de Clases del <?= $nombreGrado.' Sección '.$nombreSec.'<br>Lapso Activo: '.$lapsoActivo.'° ('.$periodoActivo.')<br>'.$nombreMate ?></h4>
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
                <th scope="col">Fecha Desde</th>
                <!--th scope="col">Fecha Hasta</th-->
                <th scope="col">Titulo</th>
                <th scope="col">Descripción</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody>
              <input type="hidden" id='nombreGrado' value="<?= $nomGra ?>"><?php  
              $son=0;
              $lap='';
              if($lapsoActivo==1){$lap='1ero.';}
              if($lapsoActivo==2){$lap='2do.';}
              if($lapsoActivo==3){$lap='3ero.';} 
              while($row=mysqli_fetch_array($tareas_query)) 
              { 
                $fechaDesde=date("d-m-Y", strtotime($row['fechaPublica']));
                $fechaHasta=date("d-m-Y", strtotime($row['fechaMaxima']));
                $desde=$row['fechaPublica'];
                $hasta=$row['fechaMaxima'];
                $fecMaxima=$row['fechaMaxima'];
                $titulo=$row['tituloTarea'];
                $descriTarea=$row['descriTarea'];
                $nomarch='../tareas/'.utf8_encode($row['nombreArchivo']);
                $id_documento=$row['idTarea'];
                $todos=$row['todos'];
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td title="Fecha en la cual estara disponible este Material de Clases" id="fecD<?= $son ?>"><?= $fechaDesde ?></td>
                  <!--td title="Fecha maxima de entrega al docente" id="fecH<?= $son ?>"><?= $fechaHasta ?></td-->
                  <td id="titu<?= $son ?>"><?= $titulo; ?></td>
                  <td><?= $descriTarea ?></td>
                  
                  <td style="width: 15%;">
                    <button type="button" onclick="verTarea('<?= $son ?>','<?= encriptar($id_documento) ?>','<?= $nombreGrado.' '.$nombreSec ?>','<?= $nombreMate ?>','<?= $nomarch ?>','<?= $titulo ?>','<?= $descriTarea ?>','<?= $desde ?>','<?= $hasta ?>','<?= $lap ?>','<?= $todos ?>')" data-bs-toggle="modal" data-bs-target="#verTarea" class="btn btn-outline-success" title="Ver la Material"><i class="ri-eye-line"></i></button>

                    <button type="button" onclick="borraTarea('<?= encriptar($id_documento) ?>','<?= $nomarch ?>','<?= $son ?>')" class="btn btn-outline-danger" title="Borrar el Material de Clases"><i class="ri-delete-bin-2-line"></i></button>

                    <!--button type="button" onclick='window.open("list-entrego-tarea.php?id=<?= encriptar($id_documento) ?>&gra=<?= $nombreGrado ?>&sec=<?= $nombreSec ?>&cod=<?= $grado ?> ")' class="btn btn-outline-primary" title="Ver listado de alumnos Material de Clases"><i class="ri-check-line"></i></button-->
                  </td>
                </tr><?php 
              } ?>
              
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <div class="modal fade" id="verTarea" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Material de Clases para:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="col">
            <div class="row">
              <div class="col-md-4">
                <label>Grado/Secc.:</label>
                <input type="text" class="form-control" readonly id="gradoVer">
              </div>
              <div class="col-md-8">
                <label>Materia:</label>
                <input class="form-control" readonly="" type="text" id="materiaVer">
              </div>
              
              <div class="col-md-4">
                <label>Fecha de Publicación</label>
                <input type="date" class="form-control" id="fechaPublicaVer">
              </div>
              <!--div class="col-md-4">
                <label>Maxima de Recepción</label>
                <input type="date" class="form-control" id="fechaMaximaVer">
              </div-->
              <div class="col-md-4">
                <label>Lapso</label>
                <input type="text" id="lapsoTareaVer" readonly class="form-control">
              </div>
              <div class="col-md-12">
                <label>Titulo:</label>
                <input type="text" class="form-control" aria-describedby="basic-addon1" id="tituloVer">
              </div>
              <div class="col-md-12">
                <label>Descripcion:</label>
                <input type="text" class="form-control" aria-describedby="basic-addon1" id="descriVer">
              </div>
              <div class="col-md-12 col-12" id="divQuien" style="display:none;">
                <label>Material dirigido a:</label>
                <textarea class="form-control" id="quienes" readonly rows="3"></textarea>
              </div>
              <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge; margin-top: 2%;">
                  <iframe id="videoVer" style="width: 100%;" height="500" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
    function verTarea(lin,id,grad,mate,arch,titu,desc,desd,hast,laps,tod) 
    {
      $('#linVer').val(lin);
      $('#idTareaVer').val(id);
      $('#gradoVer').val(grad);
      $('#materiaVer').val(mate);
      $('#tituloVer').val(titu);
      $('#descriVer').val(desc);
      $('#fechaPublicaVer').val(desd);
      //$('#fechaMaximaVer').val(hast);
      $('#videoVer').attr('src', arch);
      $('#lapsoTareaVer').val(laps);
      if (tod=='N') {
        $.post('tareaQuien.php',{'id':id},function(data)
        {
          if(data.isSuccessful)
          {
            $('#divQuien').show();
            $('#quienes').val(data.quienes)
          }else{
            $('#divQuien').hide();
          }
        }, 'json');
      }
    }
    function guardar() 
    {
      id=$('#idTareaVer').val()
      lin=$('#linVer').val()
      fecP=$('#fechaPublicaVer').val()
      //fecM=$('#fechaMaximaVer').val()
      titu=$('#tituloVer').val()
      desc=$('#descriVer').val()
      $('#verTarea').modal('hide')

      $.post('actualTarea.php',{'id':id, 'fechaPublica':fecP, 'tituloTarea':titu,'descriTarea':desc},function(data){
        if(data.isSuccessful){
          document.getElementById("titu"+lin).innerHTML = titu;
          document.getElementById("fecD"+lin).innerHTML = data.fechaP;
          //document.getElementById("fecH"+lin).innerHTML = data.fechaM;
        }
      }, 'json');
    }
    function borraTarea(id,arch,lin) 
    {
      Swal.fire({
        title: 'Borrar Material?',
        text: "Esta seguro de eliminar este Material de Clases!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Borrar!',
        cancelButtonText: 'Me arrepenti'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('borraTarea.php',{'id':id, 'archivo':arch},function(data){
            if(data.isSuccessful){
              $('#linea'+lin).hide();
            }
          }, 'json');

          Swal.fire(
            'Borrada!',
            'Su Material de Clases fue eliminado.',
            'success'
          )
        }
      })
    }
  </script>

</body>

</html>