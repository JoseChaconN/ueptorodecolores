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
  
  $videos_query = mysqli_query($link,"SELECT A.tituloVideo, A.descriVideo, A.fechaPublica, A.videoLink1, A.videoLink2, A.videoLink3, A.videoLink4, A.idVideo, A.lapsoVideo, A.nomMater, C.nombre, C.apellido, D.nombreGrado, E.nombre as nomSec FROM videopri".$periodoAlum." A, alumcer C, grado".$periodoAlum." D, secciones E WHERE A.codGrado='$idGra' and A.codSecci='$idSec' and A.cedProf=C.cedula and A.fechaPublica<='$fechahoy' and A.codGrado=D.grado and A.codSecci=E.id  ORDER BY A.fechaPublica DESC"); ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Listado de Videos Periodo Escolar <?= $nombre_periodo ?></h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Fecha</th>
                <th scope="col">Materia</th>
                <th scope="col">Docente</th>
                <th scope="col">Titulo</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody>
              <form>
              <input type="hidden" id='nombreGrado' value="<?= $nomGra ?>"><?php  
              $son=0;
              while($row=mysqli_fetch_array($videos_query)) 
              { 
                $fechaDesde=date("d-m-Y", strtotime($row['fechaPublica']));
                $desde=$row['fechaPublica'];
                $nomGra=utf8_encode($row['nombreGrado']).' / '.$row['nomSec'];
                $titulo=$row['tituloVideo'];
                $descriVideo=$row['descriVideo'];
                $videoLink1=$row['videoLink1'];
                $videoLink2=$row['videoLink2'];
                $videoLink3=$row['videoLink3'];
                $videoLink4=$row['videoLink4'];
                $id_documento=$row['idVideo'];
                $nomMate=$row['nomMater'];
                $docente=$row['nombre'].' '.$row['apellido'];
                $lap=$row['lapsoVideo'];
                if($lap==1){$lap='1ero.';}
                if($lap==2){$lap='2do.';}
                if($lap==3){$lap='3ero.';} 
                $vio_query = mysqli_query($link,"SELECT statusVideo FROM vio_video WHERE idVideo='$id_documento' and idAlum='$idAlum' ");
                $statusVideo='2';
                $title='Ver el video';
                while($row2=mysqli_fetch_array($vio_query)) 
                {$statusVideo=$row2['statusVideo'];}
                if($statusVideo=='2'){$color='#DCEDC8';}else{$color='#FFF'; $title='Volver a ver el video';}
                $son++; ?>
                <tr style="background-color:<?= $color ?>" id="tr<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td><?= $fechaDesde ?></td>
                  <td><?= $nomMate ?></td>
                  <td><?= $docente ?></td>
                  <td><?= $titulo; ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick="verVideo('<?= encriptar($id_documento) ?>','<?= $nomGra ?>','<?= $nomMate ?>','<?= $docente ?>','<?= $videoLink1 ?>','<?= $titulo ?>','<?= $descriVideo ?>','<?= $desde ?>','<?= $statusVideo ?>','<?= $son ?>','<?= $videoLink2 ?>','<?= $videoLink3 ?>','<?= $videoLink4 ?>')" data-bs-toggle="modal" data-bs-target="#verVideo" class="btn btn-outline-success" title="<?= $title ?>"><i class="ri-eye-line"></i></button></a>
                  </td>
                </tr><?php 
              } ?>
              </form>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    
    <div class="modal fade" id="verVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLongTitle">&nbsp;&nbsp;Video Aula para:</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-3">
                <h4>Año:</h4>
                <input type="text" class="form-control" readonly id="grado">
              </div>
              <div class="col-md-5">
                <h4>Materia:</h4>
                <input type="text" class="form-control" readonly id="materia" >
              </div>
              <div class="col-md-4">
                <h4>Docente:</h4>
                <input type="text" class="form-control" readonly id="docente">
              </div>
              <div class="col-md-8">
                <label><h4>Titulo:</h4></label>
                <input type="text" class="form-control" aria-describedby="basic-addon1" id="tituloVideo" readonly="">
              </div>
              <div class="col-md-4">
                <label><h4>Publicación</h4></label>
                <input type="date" class="form-control" readonly="" id="fechaPublica" >
              </div>
              <div class="col-md-12" style="margin-bottom: 2%;">
                <label><h4>Descripcion:</h4></label>
                <input type="text" class="form-control" aria-describedby="basic-addon1" id="descriVideo" readonly="">
              </div>
              <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                <br><iframe id="videoAula" style="width: 100%; height: 600px;" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
              <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge;" id="divLink2">
                <br><iframe id="videoAula2" style="width: 100%; height: 600px;" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
              <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge;" id="divLink3">
                <br><iframe id="videoAula3" style="width: 100%; height: 600px;" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
              <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge;" id="divLink4">
                <br><iframe id="videoAula4" style="width: 100%; height: 600px;" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
              <input type="hidden" id="idVideo">
              <input type="hidden" id="linea">
            </div>
          </div>
          <div class="modal-footer">
            <div class="col-md-4 col-md-offset-4" id="marcarVideo" style="display:none;">
              <label class="form-control">Marcar como visto &nbsp; <input type="checkbox" style="transform: scale(1.5);" id="statusVideo" onclick="statusVideo()" ></label>
            </div>
            <div class="col-md-4">
              <button type="button" data-bs-dismiss="modal" class="btn btn-rectangular btn-warning" style="color: #000; width: 100%; ">Cerrar</button>
            </div>
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
    $(":file").filestyle(
      {
        btnClass : 'btn-info',
        text : 'Buscar Archivos'
      });
    function statusVideo() {
      id=$('#idVideo').val();
      lin=$('#linea').val();
      if( $('#statusVideo').prop('checked') ) 
      { sta='1';}else{ sta='2';}
      $.post('statusVideo.php',{'idVid':id},function(data)
      {
        if(data.isSuccessful)
        {
          if(sta=='2')
          {
            //alert('1111')
            document.getElementById('tr'+lin).style.backgroundColor = "#DCEDC8";
          }
          if(sta=='1')
          {
            //alert('2222')
            document.getElementById('tr'+lin).style.backgroundColor = "#FFF";
          }
        }
      }, 'json');
    }
    function verVideo(id,gra,mate,prof,link,titu,desc,fech,stat,lin,link2,link3,link4)
    {
      $('#idVideo').val(id);
      $('#grado').val(gra);
      $('#materia').val(mate);
      $('#docente').val(prof);
      $('#tituloVideo').val(titu);
      $('#fechaPublica').val(fech);
      $('#descriVideo').val(desc);
      $('#linea').val(lin);
      $('#videoAula').attr('src', link);
      if (link2!='') {
        $('#videoAula2').attr('src', link2);
        $('#divLink2').show();
      }else{
        $('#divLink2').hide();
      }
      if (link3!='') {
        $('#videoAula3').attr('src', link3);
        $('#divLink3').show();
      }else{
        $('#divLink3').hide();
      }
      if (link4!='') {
        $('#videoAula4').attr('src', link4);
        $('#divLink4').show();
      }else{
        $('#divLink4').hide();
      }
      $('#verVideo').modal('show');
      if(stat=='1')
      {$("#statusVideo").prop("checked", true); $('#marcarVideo').hide(); }else
      {$("#statusVideo").prop("checked", false); $('#marcarVideo').show(); }
    }
  </script><?php 
  mysqli_free_result($alumno_query);
  mysqli_free_result($videos_query);
  mysqli_free_result($vio_query);

?>

</body>

</html>