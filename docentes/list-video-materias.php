<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$fecha_actual = date("Y-m-d");
$fecMax=date("Y-m-d",strtotime($fecha_actual."+ 5 days"));
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";

  if ($lapsoActivo==1) {$nomlapso='Primero';}
  if ($lapsoActivo==2) {$nomlapso='Segundo';}
  if ($lapsoActivo==3) {$nomlapso='Tercero';}
  if(isset($_POST['subir']))
  { 
    $grado=$_POST['grado'];
    $seccion=$_POST['seccion'];
    $codMater=$_POST['codMater'];
    $tituloVideo=$_POST['titulo'];
    $descriVideo=$_POST['descripcion'];
    $fechaPublica=$_POST['fechaPublica'];
    $videoLink1=$_POST['videoLink1'];
    $videoLink2=$_POST['videoLink2'];
    $videoLink3=$_POST['videoLink3'];
    $videoLink4=$_POST['videoLink4'];
    $existe_query=mysqli_query($link,"SELECT * FROM video".$tablaPeriodo." WHERE (videoLink1='$videoLink1' ) and cedProf='$cedula' and codGrado='$grado' and codSecci='$seccion' and codMater='$codMater' "); 
    if(mysqli_num_rows($existe_query) == 0)
    { 
      mysqli_query($link,"INSERT INTO video".$tablaPeriodo."(tituloVideo, descriVideo, cedProf, codGrado, codSecci, codMater, fechaPublica, lapsoVideo, videoLink1, videoLink2, videoLink3, videoLink4) VALUES ('$tituloVideo', '$descriVideo', '$cedula', '$grado', '$seccion', '$codMater', '$fechaPublica', '$lapsoActivo', '$videoLink1', '$videoLink2', '$videoLink3', '$videoLink4' )") or die ("NO GUARDO VIDEO".mysqli_error()); ?>
        <script type="text/javascript">
          swal({
            title: "Excelente!",
            text: "El video fue almacenado correctamente",
            type: "success",
            confirmButtonText: "Entiendo"
            });
        </script> <?php
    }
  }
  $materias_query=mysqli_query($link,"SELECT * FROM trgsmp".$tablaPeriodo." WHERE cod_grado>60 and  ced_prof='$cedula' ORDER BY cod_grado , cod_seccion , cod_materia "); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4>Materias y Aulas Asignadas (Videos) <br>Prof. <?= $nombre.' '.$apelli.'<br>Lapso Activo: '.$lapsoActivo.'° ('.$periodoActivo.')' ?></h4>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Año</th>
                <th scope="col">Sección</th>
                <th scope="col">Materia</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody>
              <form>
              <input type="hidden" id='nombreGrado' value="<?= $nomGra ?>"><?php  
              $son=0;
              
              while($row=mysqli_fetch_array($materias_query)) 
              { 
                $cod_grado=$row["cod_grado"];
                $cod_seccion=$row["cod_seccion"];
                $cod_materia=$row["cod_materia"];
                $porctotal = 0;
                $grado_query=mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." WHERE grado = '$cod_grado'");
                while($row=mysqli_fetch_array($grado_query))
                {$nomgra=$row["nombreGrado"];}
                $seccion=mysqli_query($link,"SELECT * FROM secciones WHERE id = '$cod_seccion'");
                while($row=mysqli_fetch_array($seccion))
                {$nomsec=$row["nombre"];}
                $materia=mysqli_query($link,"SELECT * FROM materiass".$tablaPeriodo." WHERE codigo = '$cod_materia'");
                while($row=mysqli_fetch_array($materia))
                {$nommate=trim($row["nombremate"]);}
                
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td><?= ($nomgra) ?></td>
                  <td><?= $nomsec ?></td>
                  <td><?= $nommate ?></td>
                  
                  <td style="width: 15%;">
                    <button type="button" onclick="subir('<?= ($nomgra) ?>','<?= $nomsec ?>','<?= $nommate ?>','<?= $cod_grado ?>','<?= $cod_seccion ?>','<?= $cod_materia ?>')" data-bs-toggle="modal" data-bs-target="#subir" class="btn btn-outline-primary" title="Publicar video"><i class="ri-upload-cloud-2-fill"></i></button>

                    <button type="button" onclick='window.open("list-videos.php?gra=<?= encriptar($cod_grado) ?>&sec=<?= $cod_seccion ?>&mat=<?= encriptar($cod_materia) ?> ")' class="btn btn-outline-success" title="Ver listado de videos"><i class="ri-zoom-in-line"></i></button>
                  </td>
                </tr><?php 
              } ?>
              </form>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <div class="modal fade" id="subir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nueva Video para:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form role="form" method="POST" action="" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-3">
                  <label>Grado/Secc.:</label>
                  <input type="text" class="form-control" readonly id="grado">
                </div>
                <div class="col-md-2">
                  <label>Lapso</label>
                  <input type="text" readonly class="form-control" value="<?= $nomlapso ?>">
                </div>
                <div class="col-md-7">
                  <label>Materia:</label>
                  <input type="text" class="form-control" readonly id="materia" name="materia">
                </div>
                <div class="col-md-3">
                  <label>Fecha de Publicación</label>
                  <input type="date" class="form-control" min="<?= $fechahoy ?>" name="fechaPublica" value="<?= $fechahoy ?>">
                </div>
                <div class="col-md-9">
                  <label>Titulo:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" name="titulo">
                </div>
                <div class="col-md-12">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" name="descripcion">
                </div>
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="margin-top: 2%; margin-bottom: 2%;">
                  <label><h3>Link del video:&nbsp;&nbsp;</h3>
                    <button onclick='window.open("tutoYou.php")' class="btn btn-warning">como hacerlo en el computador</button>&nbsp;&nbsp;&nbsp;<button onclick='window.open("ayudaVideoBach.php")' class="btn btn-info">como hacerlo en el celular</button></label><br>
                  <span>Copiar link del video de youtube en la opcion (compartir), (copiar o copiar enlace) </span>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink1" name="videoLink1" required="">
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink2" name="videoLink2" >
                </div>
                <div class="col-md-6" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula1" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula2" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>


                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 3%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink3" name="videoLink3" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 3%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink4" name="videoLink4" >
                </div>
                <div class="col-md-6" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula3" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula4" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>

                <input type="hidden" name="grado" id="codGrado">
                <input type="hidden" name="seccion" id="codSecci">
                <input type="hidden" name="codMater" id="codMater">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" name="subir" >Guardar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../assets/bootstrap_filestyle_2_1_0/src/bootstrap-filestyle.min.js"> </script>
  <script type="text/javascript">
    $(document).ready( function () 
    {
      $('#table_id').DataTable({
        "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
      });
      $(":file").filestyle(
      {
        btnClass : 'btn-info',
        text : 'Buscar Archivos'
      });
      
    } );
    function subir(grado, secc, mate, codgrado, codsecc, codmate)
    {
      ngrado=grado;
      nsecci=secc;
      nmate=mate;
      codG=codgrado;
      codS=codsecc;
      codM=codmate;
      $('#grado').val(ngrado+' '+nsecci);
      $('#seccion').val(nsecci);
      $('#materia').val(nmate);
      $('#codGrado').val(codG);
      $('#codSecci').val(codS);
      $('#codMater').val(codM);
      $('#subir').modal('show');
    }
    function verVideo()
    {
      miLink1 = $('#videoLink1').val();
      miLink2 = $('#videoLink2').val();
      miLink3 = $('#videoLink3').val();
      miLink4 = $('#videoLink4').val();
      if(miLink1!=''){
        miLink1 = miLink1.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink1=miLink1+'?rel=0';
        $('#videoLink1').val(miLink1);
        $('#videoAula1').attr('src', miLink1);
        //$('#videoAula1').reload();
      }
      if(miLink2!=''){
        miLink2 = miLink2.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink2=miLink2+'?rel=0';
        $('#videoLink2').val(miLink2);
        $('#videoAula2').attr('src', miLink2);
        //$('#videoAula2').reload();
      }
      if(miLink3!=''){
        miLink3 = miLink3.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink3=miLink3+'?rel=0';
        $('#videoLink3').val(miLink3);
        $('#videoAula3').attr('src', miLink3);
        //$('#videoAula3').reload();
      }
      if(miLink4!=''){
        miLink4 = miLink4.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink4=miLink4+'?rel=0';
        $('#videoLink4').val(miLink4);
        $('#videoAula4').attr('src', miLink4);
        //$('#videoAula4').reload();
      }
    }
    /*function verVideo()
    {
      miLink = $('#videoLink').val();
      miLink = miLink.replace("https://youtu.be/","https://youtube.com/embed/");
      miLink=miLink+'?rel=0';
      $('#videoLink').val(miLink);
      $('#videoAula').attr('src', miLink);
      $('#videoAula').reload();
    }*/

  </script>

</body>

</html>