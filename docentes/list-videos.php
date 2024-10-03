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
    
  if(isset($_POST['actualiza']))
  {
    $idVideo = desencriptar($_POST['idVideoVer']);
    $fechaPublica = $_POST['fechaPublicaVer'];
    $tituloVideo = $_POST['tituloVer'];
    $descriVideo = $_POST['descriVer'];
    $videoLink1 = $_POST['videoLink1'];
    $videoLink2 = $_POST['videoLink2'];
    $videoLink3 = $_POST['videoLink3'];
    $videoLink4 = $_POST['videoLink4'];
    mysqli_query($link,"UPDATE video".$tablaPeriodo." SET fechaPublica = '$fechaPublica', tituloVideo='$tituloVideo', descriVideo='$descriVideo',videoLink1='$videoLink1',videoLink2='$videoLink2',videoLink3='$videoLink3',videoLink4='$videoLink4' WHERE idVideo = '$idVideo'");
  }
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
  $videos_query = mysqli_query($link,"SELECT tituloVideo, descriVideo, fechaPublica, videoLink1, videoLink2, videoLink3, videoLink4, idVideo, codMater, lapsoVideo FROM video".$tablaPeriodo." WHERE codGrado='$grado' and codSecci='$secci' and cedProf='$cedula' and lapsoVideo='$lapsoActivo' and codMater='$codMate' ORDER BY fechaPublica"); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4>Listado de Videos del <?= $nombreGrado.' Sección '.$nombreSec.'<br>Lapso Activo: '.$lapsoActivo.'° ('.$periodoActivo.')<br>'.$nombreMate ?></h4>
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
                <th scope="col">Publicación</th>
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
              while($row=mysqli_fetch_array($videos_query)) 
              { 
                $fechaDesde=date("d-m-Y", strtotime($row['fechaPublica']));
                $desde=$row['fechaPublica'];
                $titulo=$row['tituloVideo'];
                $descriVideo=$row['descriVideo'];
                $videoLink1=$row['videoLink1'];
                $videoLink2=$row['videoLink2'];
                $videoLink3=$row['videoLink3'];
                $videoLink4=$row['videoLink4'];
                $id_documento=$row['idVideo'];
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td title="Fecha en la cual esta disponible este video" id="fecD<?= $son ?>"><?= $fechaDesde ?></td>
                  <td id="titu<?= $son ?>"><?= $titulo; ?></td>
                  <td id="desc<?= $son ?>"><?= $descriVideo ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick="verVideo('<?= $son ?>','<?= encriptar($id_documento) ?>','<?= $nombreGrado.' '.$nombreSec ?>','<?= $nombreMate ?>','<?= $videoLink1 ?>','<?= $videoLink2 ?>','<?= $videoLink3 ?>','<?= $videoLink4 ?>','<?= $titulo ?>','<?= $descriVideo ?>','<?= $desde ?>','<?= $lap ?>')" data-bs-toggle="modal" data-bs-target="#verVideo" class="btn btn-outline-success" title="Ver el Video"><i class="ri-eye-line"></i></button>
                    <button type="button" onclick="borraVideo('<?= encriptar($id_documento) ?>','<?= $son ?>')" class="btn btn-outline-danger" title="Borrar el Video"><i class="ri-delete-bin-2-line"></i></button>
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <div class="modal fade" id="verVideo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Video para:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form role="form" method="POST" action="" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-3">
                  <label>Grado/Secc.:</label>
                  <input type="text" class="form-control" readonly id="gradoVer">
                </div>
                <div class="col-md-2">
                  <label>Lapso</label>
                  <input type="text" id="lapsoVideoVer" readonly class="form-control">
                </div>
                <div class="col-md-7">
                  <label>Materia:</label>
                  <input class="form-control" readonly="" type="text" id="materiaVer">
                </div>
                <div class="col-md-3">
                  <label>Fecha de Publicación</label>
                  <input type="date" class="form-control" id="fechaPublicaVer" name="fechaPublicaVer">
                </div>
                <div class="col-md-9">
                  <label>Titulo:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" id="tituloVer" name="tituloVer">
                </div>
                <div class="col-md-12" style="margin-bottom: 2%;">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" id="descriVer" name="descriVer">
                </div>
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="margin-top: 2%; margin-bottom: 2%;">
                  <label><h3>Link del video:&nbsp;&nbsp;</h3>
                    <button onclick='window.open("tutoYou.php")' class="btn btn-warning">como hacerlo en el computador</button>&nbsp;&nbsp;&nbsp;<button onclick='window.open("ayudaVideoBach.php")' class="btn btn-info">como hacerlo en el celular</button></label><br>
                  <span>Copiar link del video de youtube en la opcion (compartir), (copiar o copiar enlace) </span>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                  <input type="text" class="form-control" onchange="verNueVideo()" placeholder="Pegar aqui el link del video y hacer click en descripcion" maxlength="200" aria-describedby="basic-addon1" id="videoLink1" name="videoLink1" required="">
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                  <input type="text" class="form-control" onchange="verNueVideo()" placeholder="Pegar aqui el link del video y hacer click en descripcion" maxlength="200" aria-describedby="basic-addon1" id="videoLink2" name="videoLink2" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                    <iframe id="videoVer1" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                    <iframe id="videoVer2" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>

                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 3%;">
                  <input type="text" class="form-control" onchange="verNueVideo()" placeholder="Pegar aqui el link del video y hacer click en descripcion" maxlength="200" aria-describedby="basic-addon1" id="videoLink3" name="videoLink3">
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 3%;">
                  <input type="text" class="form-control" onchange="verNueVideo()" placeholder="Pegar aqui el link del video y hacer click en descripcion" maxlength="200" aria-describedby="basic-addon1" id="videoLink4" name="videoLink4" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                    <iframe id="videoVer3" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                    <iframe id="videoVer4" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <input type="hidden" id="idVideoVer" name="idVideoVer">
                <input type="hidden" id="linVer">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="actualiza" class="btn btn-success" >Guardar Cambio</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
          </div>
        </form>
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
    function verVideo(lin,id,grad,mate,link1,link2,link3,link4,titu,desc,desd,laps) 
    {
      $('#linVer').val(lin);
      $('#idVideoVer').val(id);
      $('#gradoVer').val(grad);
      $('#materiaVer').val(mate);
      $('#tituloVer').val(titu);
      $('#descriVer').val(desc);
      $('#fechaPublicaVer').val(desd);
      $('#videoVer1').attr('src', link1);
      $('#videoLink1').val(link1);
      $('#videoVer2').attr('src', link2);
      $('#videoLink2').val(link2);
      $('#videoVer3').attr('src', link3);
      $('#videoLink3').val(link3);
      $('#videoVer4').attr('src', link4);
      $('#videoLink4').val(link4);
      $('#lapsoVideoVer').val(laps);
    }
    function verNueVideo()
    {
      miLink1 = $('#videoLink1').val();
      miLink2 = $('#videoLink2').val();
      miLink3 = $('#videoLink3').val();
      miLink4 = $('#videoLink4').val();
      if(miLink1!=''){
        miLink1 = miLink1.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink1=miLink1+'?rel=0';
        $('#videoLink1').val(miLink1);
        $('#videoVer1').attr('src', miLink1);
        //$('#videoVer').reload();
      }
      if(miLink2!=''){
        miLink2 = miLink2.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink2=miLink2+'?rel=0';
        $('#videoLink2').val(miLink2);
        $('#videoVer2').attr('src', miLink2);
        //$('#videoVer').reload();
      }
      if(miLink3!=''){
        miLink3 = miLink3.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink3=miLink3+'?rel=0';
        $('#videoLink3').val(miLink3);
        $('#videoVer3').attr('src', miLink3);
        //$('#videoVer').reload();
      }
      if(miLink4!=''){
        miLink4 = miLink4.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink4=miLink4+'?rel=0';
        $('#videoLink4').val(miLink4);
        $('#videoVer4').attr('src', miLink4);
        //$('#videoVer').reload();
      }
    }
    
    function borraVideo(id,lin) 
    {
      Swal.fire({
        title: 'Borrar Video?',
        text: "Esta seguro de eliminar este Video!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Borrar!',
        cancelButtonText: 'Me arrepenti'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('borraVideo.php',{'id':id},function(data){
            if(data.isSuccessful){
              $('#linea'+lin).hide();
            }
          }, 'json');

          Swal.fire(
            'Borrado!',
            'Su Video fue eliminado.',
            'success'
          )
        }
      })
    }
  </script>

</body>

</html>