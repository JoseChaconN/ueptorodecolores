<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$fecha_actual = date("Y-m-d");
$docente=$_SESSION["usuario"];
$fecMax=date("Y-m-d",strtotime($fecha_actual."+ 5 days"));
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php"; 
  $grado=$_POST['grado'];
  $seccion=$_POST['seccion'];
  $lapsoActivo=$_SESSION['lapsoActivo'];
  if(isset($_POST['actualiza']))
  {
    $idVideo = desencriptar($_POST['idVideoVer']);
    $fechaPublica = $_POST['fechaPublicaVer'];
    $tituloVideo = $_POST['tituloVer'];
    $descriVideo = $_POST['descriVer'];
    $videoLink1 = $_POST['videoLinkVer1'];
    $videoLink2 = $_POST['videoLinkVer2'];
    $videoLink3 = $_POST['videoLinkVer3'];
    $videoLink4 = $_POST['videoLinkVer4'];
    mysqli_query($link,"UPDATE videopri".$tablaPeriodo." SET fechaPublica = '$fechaPublica', tituloVideo='$tituloVideo', descriVideo='$descriVideo',videoLink1='$videoLink1',videoLink2='$videoLink2',videoLink3='$videoLink3',videoLink4='$videoLink4' WHERE idVideo = '$idVideo'");
  }
  if(isset($_POST['subir']))
  { 
    echo '<br><br><br><br>1';
    $nomMater=desencriptar($_POST['nomMater']);
    if($nomMater!=""){
      echo "<br>2";
      $tituloVideo=$_POST['titulo'];
      $descriVideo=$_POST['descripcion'];
      
      $fechaPublica=$_POST['fechaPublica'];
      $videoLink1=$_POST['videoLink1'];
      $videoLink2=$_POST['videoLink2'];
      $videoLink3=$_POST['videoLink3'];
      $videoLink4=$_POST['videoLink4'];
      $existe_query=mysqli_query($link,"SELECT * FROM videopri".$tablaPeriodo." WHERE (videoLink1='$videoLink1'  ) and cedProf='$cedula' and codGrado='$grado' and codSecci='$seccion' and nomMater='$nomMater'  "); 
      if(mysqli_num_rows($existe_query) == 0)
      { 
        echo "<br>3";
        mysqli_query($link,"INSERT INTO videopri".$tablaPeriodo."(tituloVideo, descriVideo, cedProf, codGrado, codSecci, nomMater, fechaPublica, lapsoVideo, videoLink1, videoLink2, videoLink3, videoLink4) VALUES ('$tituloVideo', '$descriVideo', '$cedula', '$grado', '$seccion', '$nomMater', '$fechaPublica', '$lapsoActivo', '$videoLink1', '$videoLink2', '$videoLink3', '$videoLink4' )") or die ("NO GUARDO VIDEOPRI".mysqli_error()); ?>
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
  } 
 
  $grado_query = mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." WHERE grado='$grado'");
  while($row = mysqli_fetch_array($grado_query))
  {
    $nombreGrado=$row['nombreGrado'];
  }
  $seccion_query = mysqli_query($link,"SELECT nombre FROM secciones WHERE id='$seccion'");
  while($row = mysqli_fetch_array($seccion_query))
  {
    $nombreSec=$row['nombre'];
  }
  $dicta_query = mysqli_query($link,"SELECT * FROM trgsmp".$tablaPeriodo." WHERE cod_grado='$grado' and cod_seccion='$seccion' ORDER BY cod_materia ");
  $van=0;
  while ($row = mysqli_fetch_array($dicta_query))
  {
    $van++;
    if($docente==$row['ced_prof']){
      ${'pro'.$van}=substr($row['cod_materia'],2,2);  
    }else{
      ${'pro'.$van}="";
    }
  }
  $grado_query = mysqli_query($link,"SELECT A.nombreGrado as nomgr, A.mate1, A.mate2, A.mate3, A.mate4, A.mate5, A.mate6, A.mate7, A.mate8, A.mate9, A.mate10,A.sonMate, B.nombre as nomsec FROM grado".$tablaPeriodo." A, secciones B WHERE A.grado='$grado' and B.id='$seccion'"); 
  while($row=mysqli_fetch_array($grado_query)) 
  { 
    $nomgra=$row['nomgr'];
    $nomsec=$row['nomsec'];
    $sonMate=$row['sonMate'];
    for ($i=1; $i <=$sonMate ; $i++) { 
      ${'mate'.$i}=$row['mate'.$i];
      if(${'pro'.$i}==''){
        ${'mate2'.$i}="";
      }else{
        ${'mate2'.$i}=$row['mate'.$i];
      }
    }
  }
  $videos_query = mysqli_query($link,"SELECT tituloVideo, descriVideo, fechaPublica, videoLink1, videoLink2, videoLink3, videoLink4, idVideo, nomMater, lapsoVideo FROM videopri".$tablaPeriodo."  WHERE codGrado='$grado' and codSecci='$seccion' and cedProf='$cedula' and lapsoVideo='$lapsoActivo'  ORDER BY fechaPublica");  ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Video clase del <?= $nombreGrado.' Sección '.$nombreSec.'<br>Lapso Activo: '.$lapsoActivo.'° ('.$periodoActivo.')' ?></h2>
        <button class="btn btn-primary" type="button" onclick="subir('<?= $nombreGrado ?>','<?= $nombreSec ?>','<?= $grado ?>','<?= $seccion ?>')" style="font-size: 18px;" ><i class="ri-video-add-fill"></i> Publicar Nuevo Video</button>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Disponible</th>
                <th scope="col">Materia</th>
                <th scope="col">Titulo</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody>
              <form>
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
                $nomMate=$row['nomMater'];
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td title="Fecha en la cual esta disponible esta video" id="fecD<?= $son ?>"><?= $fechaDesde ?></td>
                  <td><?= $nomMate ?></td>
                  <td id="titu<?= $son ?>"><?= $titulo; ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick="verDatVideo('<?= $son ?>','<?= encriptar($id_documento) ?>','<?= $nombreGrado.' '.$nombreSec ?>','<?= $nomMate ?>','<?= $videoLink1 ?>','<?= $videoLink2 ?>','<?= $videoLink3 ?>','<?= $videoLink4 ?>','<?= $titulo ?>','<?= $descriVideo ?>','<?= $desde ?>','<?= $lap ?>')" data-bs-toggle="modal" data-bs-target="#verVideo" class="btn btn-outline-success" title="Ver el Video"><i class="ri-eye-line"></i></button>

                    <button type="button" onclick="borraVideo('<?= encriptar($id_documento) ?>','<?= $son ?>')" class="btn btn-outline-danger" title="Borrar Video"><i class="ri-delete-bin-2-line"></i></button>
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
                <div class="col-md-4">
                  <label>Grado/Secc.:</label>
                  <input type="text" class="form-control" readonly id="gradoVer">
                </div>
                <div class="col-md-8">
                  <label>Materia:</label>
                  <input class="form-control" readonly="" type="text" id="materiaVer">
                </div>
                
                <div class="col-md-3">
                  <label>Fecha de Publicación</label>
                  <input type="date" class="form-control" id="fechaPublicaVer" name="fechaPublicaVer">
                </div>
                <div class="col-md-2">
                  <label>Lapso</label>
                  <input type="text" id="lapsoVideoVer" readonly class="form-control">
                </div>
                <div class="col-md-7">
                  <label>Titulo:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" id="tituloVer" name="tituloVer">
                </div>
                <div class="col-md-12">
                  <label>Descripción:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" id="descriVer" name="descriVer">
                </div>
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="margin-top: 2%; margin-bottom: 2%;">
                  <label><h3>Link del video:</h3><button onclick='window.open("tutoYou.php")' class="btn btn-warning">Ver aqui como hacerlo</button></label><br>
                  <span>Copiar el link de youtube en la opcion (compartir), (insertar o incorporar) unicamente lo que se encuentre dentro de las comillas del (SRC="<span style="background-color:#1266F1; color:#FFF;">https://www.youtube_link_del_video</span>")</span><br>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                  <input type="text" class="form-control" onchange="verVideoVer()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLinkVer1" name="videoLinkVer1" required="">
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                  <input type="text" class="form-control" onchange="verVideoVer()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLinkVer2" name="videoLinkVer2" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge; ">
                    <iframe id="videoVer1" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge; ">
                    <iframe id="videoVer2" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 3%;">
                  <input type="text" class="form-control" onchange="verVideoVer()" placeholder="Pegar aqui el link del video y hacer click en descripcion" maxlength="200" aria-describedby="basic-addon1" id="videoLinkVer3" name="videoLinkVer3" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 3%;">
                  <input type="text" class="form-control" onchange="verVideoVer()" placeholder="Pegar aqui el link del video y hacer click en descripcion" maxlength="200" aria-describedby="basic-addon1" id="videoLinkVer4" name="videoLinkVer4" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                    <iframe id="videoVer3" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                    <iframe id="videoVer4" style="width: 100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <input type="hidden" name="grado" value="<?= $grado ?>">
                <input type="hidden" name="seccion" value="<?= $seccion ?>">
                <input type="hidden" id="idVideoVer" name="idVideoVer">
                <input type="hidden" id="linVer">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" name="actualiza">Guardar Cambio</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="subir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nuevo Video para:</h5>
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
                  <input type="text" readonly class="form-control" value="<?= $lap ?>">
                </div>
                <div class="col-md-7">
                  <label>Materia:</label>
                  <select class="form-control" name="nomMater"><?php 
                    for ($i=1; $i <=$sonMate ; $i++) { ?>
                        <option <?php if(${'pro'.$i}==''){echo "disabled";} ?> value="<?= encriptar(${'mate2'.$i}) ?>"><?= ${'mate'.$i} ?></option><?php
                    }?>
                  </select>
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
                  <label><h3>Link del video:</h3><button onclick='window.open("tutoYou.php")' class="btn btn-warning">como hacerlo en el computador</button>&nbsp;&nbsp;&nbsp;<button onclick='window.open("ayudaVideoPrim.php")' class="btn btn-info">como hacerlo en el celular</button></label><br>
                  <span>Copiar link del video de youtube en la opcion (compartir), (copiar o copiar enlace)</span><br>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-bottom: 2%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink1" name="videoLink1" required="">
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-bottom: 2%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink2" name="videoLink2" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula1" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula2" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>

                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%; margin-bottom: 2%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink3" name="videoLink3" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="margin-top: 2%; margin-bottom: 2%;">
                  <input type="text" class="form-control" onblur="verVideo()" placeholder="Pegar aqui link y hacer click fuera" maxlength="200" aria-describedby="basic-addon1" id="videoLink4" name="videoLink4" >
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula3" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-6 text-center col-xs-12 col-sm-12" style="border-style: ridge;">
                  <label style="font-size: 22px;">Video de Youtube (si no aparece el video aqui, el link no existe...)</label>
                  <iframe id="videoAula4" style="width:100%;" height="250" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <input type="hidden" name="grado" id="codGrado">
                <input type="hidden" name="seccion" id="codSecci">
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
  <script type="text/javascript">
    $(document).ready( function () 
    {
      $('#table_id').DataTable({
        "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
      });
      
    } );
    function verDatVideo(lin,id,grad,mate,link1,link2,link3,link4,titu,desc,desd,laps) 
    {
      $('#linVer').val(lin);
      $('#idVideoVer').val(id);
      $('#gradoVer').val(grad);
      $('#materiaVer').val(mate);
      $('#videoVer1').attr('src', link1);
      $('#videoVer2').attr('src', link2);
      $('#videoVer3').attr('src', link3);
      $('#videoVer4').attr('src', link4);
      $('#tituloVer').val(titu);
      $('#descriVer').val(desc);
      $('#fechaPublicaVer').val(desd);
      $('#lapsoVideoVer').val(laps);
      $('#videoLinkVer1').val(link1);
      $('#videoLinkVer2').val(link2);
      $('#videoLinkVer3').val(link3);
      $('#videoLinkVer4').val(link4);
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
          $.post('borraVideoPri.php',{'id':id},function(data){
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
    function subir(grado,secc,codgra,codsec)
    {
      $('#grado').val(grado+' / '+secc);
      $('#codGrado').val(codgra);
      $('#codSecci').val(codsec);
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
    function verVideoVer()
    {
      miLink1 = $('#videoLinkVer1').val();
      miLink2 = $('#videoLinkVer2').val();
      miLink3 = $('#videoLinkVer3').val();
      miLink4 = $('#videoLinkVer4').val();
      if(miLink1!=''){
        miLink1 = miLink1.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink1=miLink1+'?rel=0';
        $('#videoLinkVer1').val(miLink1);
        $('#videoVer1').attr('src', miLink1);
        //$('#videoVer1').reload();
      }
      if(miLink2!=''){
        miLink2 = miLink2.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink2=miLink2+'?rel=0';
        $('#videoLinkVer2').val(miLink2);
        $('#videoVer2').attr('src', miLink2);
        //$('#videoVer2').reload();
      }
      if(miLink3!=''){
        miLink3 = miLink3.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink3=miLink3+'?rel=0';
        $('#videoLinkVer3').val(miLink3);
        $('#videoVer3').attr('src', miLink3);
        //$('#videoVer3').reload();
      }
      if(miLink4!=''){
        miLink4 = miLink4.replace("https://youtu.be/","https://youtube.com/embed/");
        miLink4=miLink4+'?rel=0';
        $('#videoLinkVer4').val(miLink4);
        $('#videoVer4').attr('src', miLink4);
        //$('#videoVer4').reload();
      }
    }
  </script>

</body>

</html>