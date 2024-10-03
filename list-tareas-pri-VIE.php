<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php#popular-courses");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse();
$periodoAlum=$_SESSION['periodoAlum'];
$nombre_periodo=$_SESSION['nombre_periodo'];
$cedAlum=$_SESSION['usuario'];
if (isset($_GET['lapso'])) {
  $lapso=$_GET['lapso'];
}else
{
  $lapso_query=mysqli_query($link,"SELECT lapso FROM preinscripcion WHERE id=2 ");
  while($row=mysqli_fetch_array($lapso_query))
  {
    $lapso=$row['lapso'];     
  }
}
$alumno_query = mysqli_query($link,"SELECT A.idAlum, A.nombre as nomalu, A.apellido, A.morosida, B.nombreGrado as nomGra, B.grado as idGra, C.nombre as nomSec, C.id as idSec FROM alumcer A, grado".$periodoAlum." B, secciones C  WHERE A.cedula='$cedAlum' and A.grado=B.grado and A.seccion=C.id "); 
while($row=mysqli_fetch_array($alumno_query)) 
{
  $idAlum=$row['idAlum'];
  $alumno=$row['apellido'].' '.$row['nomalu'];
  $idGra=$row['idGra'];
  $nomSec=$row['nomSec'];
  $nomGra=utf8_encode($row['nomGra']).' / '.$row['nomSec'];
  $idSec=$row['idSec'];
  $morosida=0; //$row['morosida'];
} 

 ?>
<!DOCTYPE html>
<html lang="es">
  <!--link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"-->
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  $tareas_query = mysqli_query($link,"SELECT A.*, B.nombre, B.apellido FROM tareaspri".$periodoAlum." A, alumcer B WHERE A.lapsoTarea='$lapso' and A.codGrado='$idGra' and A.codSecci='$idSec' and A.cedProf=B.cedula and A.fechaPublica<='$fechahoy' ORDER BY A.fechaPublica"); ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <div class="col-md-12 col-12">
          <h2>Material de clases periodo escolar <?= $nombre_periodo ?></h2>  
        </div>
        <div class="col-md-12 col-12">
          <button type="button" onclick='window.open("contactoPri.php")' class="btn btn-primary" title="Enviar mensaje al docente"><i class="fas fa-comments fa-2x"></i> Enviar Mensaje al Docente</button>
        </div>
        <div class="col-md-4 offset-md-4 col-12" style="margin-top:1%;">
          <select class="form-control" onchange="cambiaLap()" id="lapso">
            <option value="1" <?php if($lapso=='1'){ echo 'selected';} ?>>1er.Momento</option>
            <option value="2" <?php if($lapso=='2'){ echo 'selected';} ?>>2do.Momento</option>
            <option value="3" <?php if($lapso=='3'){ echo 'selected';} ?>>3er.Momento</option>
          </select>
        </div>

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
                <th scope="col">Materia</th>
                <th scope="col">Docente</th>
                <th scope="col">Titulo</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody>
              <input type="hidden" id='nombreGrado' value="<?= $nomGra ?>"><?php  
              $son=0;
              while($row=mysqli_fetch_array($tareas_query)) 
              {
                $todos=$row['todos'];
                $fechaDesde=date("d-m-Y", strtotime($row['fechaPublica']));
                $fechaHasta=date("d-m-Y", strtotime($row['fechaMaxima']));
                $desde=$row['fechaPublica'];
                $hasta=$row['fechaMaxima'];
                $fecMaxima=$row['fechaMaxima'];
                $titulo=$row['tituloTarea'];
                $descriTarea=$row['descriTarea'];
                $nomarch='tareas/'.utf8_encode($row['nombreArchivo']);
                $id_documento=$row['idTarea'];
                $nomMate=$row['nomMater'];
                $docente=$row['nombre'].' '.$row['apellido'];
                $lap=$row['lapsoTarea'];
                if($lap==1){$lap='1ero.';}
                if($lap==2){$lap='2do.';}
                if($lap==3){$lap='3ero.';} 
                $son++;
                $pasa=1;
                if($todos=='N'){
                  $dirigida_query = mysqli_query($link,"SELECT id_tabla FROM tarea_indpri_".$periodoAlum." WHERE idAlum='$idAlum' and idTarea='$id_documento' ");
                  if(mysqli_num_rows($dirigida_query) == 0)
                  {$pasa=2;}
                } 
                if($pasa==1){
                  $sinVer=1;
                  $vio_query = mysqli_query($link,"SELECT id_tabla FROM vio_tarea WHERE id_alum='$idAlum' and id_tarea='$id_documento' ");
                  if(mysqli_num_rows($vio_query)==0)
                  {$sinVer=0;} ?>
                  <tr <?php if ($sinVer==0) { echo 'style="background-color:#CE93D8"'; } ?> >
                    <td><?= $son; ?></td>
                    <td title="Fecha en la cual esta disponible esta tarea"><?= $fechaDesde ?></td>
                    <!--td title="Fecha maxima de entrega al docente"><?= $fechaHasta ?></td-->
                    <td><?= $nomMate ?></td>
                    <td><?= $docente ?></td>
                    <td><?= $titulo; ?></td>
                    <td style="width: 15%;">
                      <button type="button" onclick="verTarea('<?= $son ?>','<?= encriptar($id_documento) ?>','<?= $nomGra ?>','<?= $nomMate ?>','<?= $docente ?>','<?= $nomarch ?>','<?= $titulo ?>','<?= $descriTarea ?>','<?= $desde ?>','<?= $hasta ?>','<?= $lap ?>')" data-bs-toggle="modal" data-bs-target="#verTarea" class="btn btn-outline-success" title="Ver Contenido"><i class="ri-eye-line"></i></button></a>
                    </td>
                  </tr><?php 
                }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <div class="modal fade" id="verTarea" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Material de clases para:</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-4">
                    <label>Año/Secc.:</label>
                    <input type="text" class="form-control" readonly id="gradoVer">
                </div>
                <div class="col-md-8">
                    <label>Materia:</label>
                    <input class="form-control" readonly="" type="text" id="materiaVer">
                </div>
                <div class="col-md-4">
                    <label>Docente:</label>
                    <input class="form-control" readonly="" type="text" id="docenteVer">
                </div>
                <div class="col-md-8">
                    <label>Titulo:</label>
                    <input type="text" class="form-control" aria-describedby="basic-addon1" id="tituloVer" readonly>
                </div>
                
                <div class="col-md-4">
                    <label>Fecha de Publicación</label>
                    <input type="date" disabled class="form-control" id="fechaPublicaVer">
                </div>
                <!--div class="col-md-4">
                    <label>Maxima de Recepción</label>
                    <input type="date" disabled class="form-control" id="fechaMaximaVer">
                </div-->
                <div class="col-md-4">
                    <label>Lapso</label>
                    <input type="text" id="lapsoTareaVer" readonly class="form-control">
                </div>
                <div class="col-md-12">
                    <label>Descripcion:</label>
                    <input type="text" class="form-control" aria-describedby="basic-addon1" id="descriVer" readonly="">
                </div>
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge; margin-top: 2%;">
                    <iframe id="videoVer" style="width: 100%;" height="500" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <input type="hidden" id="idTareaVer">
                <input type="hidden" id="linVer">
                <input type="hidden" id="idAlu" value="<?= encriptar($idAlum) ?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
          </div>
        </div>
      </div>
    </div>
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <!--script src="assets/vendor/datatables/jquery.dataTables.js"></script-->

  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="assets/bootstrap_filestyle_2_1_0/src/bootstrap-filestyle.min.js"> </script>
  <script type="text/javascript">
    $(document).ready( function () {
        $('#table_id').DataTable({
        "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
        });
    } );
    function cambiaLap() {
      lap=$('#lapso').val()
      location.href="list-tareas-pri.php?lapso="+lap
    }
    function verTarea(lin,id,grad,mate,prof,arch,titu,desc,desd,hast,laps) 
    {
      $('#linVer').val(lin);
      $('#idTareaVer').val(id);
      $('#gradoVer').val(grad);
      $('#materiaVer').val(mate);
      $('#docenteVer').val(prof);
      $('#tituloVer').val(titu);
      $('#descriVer').val(desc);
      $('#fechaPublicaVer').val(desd);
      $('#videoVer').attr('src', arch);
      $('#lapsoTareaVer').val(laps);
      idAlu=$('#idAlu').val()
      $.post('vio-tarea.php',{'id_tar':id,'id_al':idAlu},function(data)
      {
        if(data.isSuccessful)
        {
          
        }
      }, 'json');
    }
    
  </script><?php 
  if(isset($_POST['idEnvia']))
  {?>
    <script type="text/javascript">
      Swal.fire({
          icon: 'success',
          title: 'Excelente!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Proceso completado, por favor verifique el status de la tarea enviada'
        })
    </script><?php 
  }
  mysqli_free_result($lapso_query);
  mysqli_free_result($alumno_query);
  mysqli_free_result($prof_query);
  mysqli_free_result($reinsert_query);
  mysqli_free_result($tareas_query);?>

</body>

</html>