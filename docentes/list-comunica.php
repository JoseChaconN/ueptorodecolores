<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$idProf=$_SESSION['idAlum'];
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
  if(isset($_POST['subir']))
  { 
    $grado=substr($_POST['anioSec'],0,2);
    $seccion=substr($_POST['anioSec'],2,1);
  
    $fechaPublica=$_POST['fechaPublica'];
    $fechaMaxima=$_POST['fechaMaxima'];
    $titulo=$_POST['titulo'];
    
    $nombre = ($_FILES['archivo']['name']);
    function formatearNombre($nombre){
        $tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ·° !#$%&/()=?¡¿";
        $replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn-______________";
        $nombre = utf8_decode($nombre);     
        $nombre = strtr($nombre, utf8_decode($tofind), $replac); 
        $nombre = strtolower($nombre); 
        return utf8_encode($nombre);
    }
    $nombre = formatearNombre($nombre); 
    $tipo = $_FILES['archivo']['type'];
    $tamanio = $_FILES['archivo']['size'];
    $nombre=time().mt_rand(0, 999).$nombre;
    $destino = "../comunicaDocen/".$nombre;
    $ruta = $_FILES['archivo']['tmp_name'];
    $existe_query=mysqli_query($link,"SELECT idComunica FROM comunica_docen WHERE tituloComunica='$titulo' and idProf='$idProf' and codGrado='$grado' and codSecci='$seccion' "); 
    if(mysqli_num_rows($existe_query) == 0)
    {
      if (copy($ruta, $destino)) 
      {
        mysqli_query($link,"INSERT INTO comunica_docen (tituloComunica, idProf, codGrado, codSecci, nombreArchivo, tipo, tamanio, fechaPublica, fechaMaxima, lapsoComunica) VALUES ('$titulo', '$idProf', '$grado', '$seccion', '$nombre', '$tipo', '$tamanio', '$fechaPublica', '$fechaMaxima', '$lapsoActivo' )") or die ("NO GUARDO ARCHIVO".mysqli_error());
      } 
    }
  }
  
  $comunica_query = mysqli_query($link,"SELECT A.tituloComunica, A.fechaPublica, A.fechaMaxima, A.nombreArchivo, A.idComunica, A.lapsoComunica, B.nombreGrado, C.nombre as nomSec FROM comunica_docen A, grado".$tablaPeriodo." B, secciones C WHERE idProf='$idProf' and A.lapsoComunica='$lapsoActivo' and A.codGrado=B.grado and A.codSecci=C.id and A.codGrado>60 ORDER BY A.fechaPublica");  ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Listado de Comunicados del <?= $lapsoActivo.'° lapso' ?></h2>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#subir" style="font-size: 18px;" ><i class="ri-upload-line"></i> Subir Nuevo Documento</button>
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
                <th scope="col">Fecha Hasta</th>
                <th scope="col">Año/Seccion</th>
                <th scope="col">Titulo</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=0;
              $lap='';
              if($lapsoActivo==1){$lap='1ero.';}
              if($lapsoActivo==2){$lap='2do.';}
              if($lapsoActivo==3){$lap='3ero.';} 
              while($row=mysqli_fetch_array($comunica_query)) 
              { 
                $fechaDesde=date("d-m-Y", strtotime($row['fechaPublica']));
                $fechaHasta=date("d-m-Y", strtotime($row['fechaMaxima']));
                $desde=$row['fechaPublica'];
                $hasta=$row['fechaMaxima'];
                $fecMaxima=$row['fechaMaxima'];
                $titulo=$row['tituloComunica'];
                $grado=($row['nombreGrado']).' '.$row['nomSec'];
                $nomarch='../comunicaDocen/'.utf8_encode($row['nombreArchivo']);
                $id_documento=$row['idComunica'];
                
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td title="Fecha en la cual esta disponible este documento" id="fecD<?= $son ?>"><?= $fechaDesde ?></td>
                  <td title="Fecha maxima visible al alumno" id="fecH<?= $son ?>"><?= $fechaHasta ?></td>
                  <td><?= $grado ?></td>
                  <td id="titu<?= $son ?>"><?= $titulo; ?></td>
                  
                  <td style="width: 15%;">
                    <button type="button" onclick="verComunica('<?= $son ?>','<?= encriptar($id_documento) ?>','<?= $grado ?>','<?= $nomarch ?>','<?= $titulo ?>','<?= $desde ?>','<?= $hasta ?>','<?= $lap ?>')" data-bs-toggle="modal" data-bs-target="#verComunica" class="btn btn-outline-success" title="Ver la Tarea"><i class="ri-eye-line"></i></button>

                    <button type="button" onclick="borraComunica('<?= encriptar($id_documento) ?>','<?= $nomarch ?>','<?= $son ?>')" class="btn btn-outline-danger" title="Borrar documento"><i class="ri-delete-bin-2-line"></i></button>
                    
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <div class="modal fade" id="verComunica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Comunicado para:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="col">
            <div class="row">
              <div class="col-md-3">
                <label>Grado/Secc.:</label>
                <input type="text" class="form-control" readonly id="gradoVer">
              </div>
              <div class="col-md-3">
                <label>Fecha de Publicación</label>
                <input type="date" class="form-control" id="fechaPublicaVer">
              </div>
              <div class="col-md-3">
                <label>Maxima de Recepción</label>
                <input type="date" class="form-control" id="fechaMaximaVer">
              </div>
              <div class="col-md-3">
                <label>Lapso</label>
                <input type="text" id="lapsoTareaVer" readonly class="form-control">
              </div>
              <div class="col-md-12">
                <label>Titulo:</label>
                <input type="text" class="form-control" aria-describedby="basic-addon1" id="tituloVer">
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
  <div class="modal fade" id="subir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nueva Comunicado para:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form role="form" method="POST" action="" onsubmit="return validacion()" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-4">
                  <label>Año/Sección:</label>
                  <select class="form-control" id="anioSec" name="anioSec">
                    <option value="">Seleccione</option><?php 
                      $grados_query = mysqli_query($link,"SELECT B.grado,B.nombreGrado,C.id as idSec,C.nombre as nomSec FROM trgsmp".$tablaPeriodo." A, grado".$tablaPeriodo." B, secciones C WHERE A.ced_prof='$cedula' and A.cod_grado=B.grado and A.cod_seccion=C.id GROUP BY A.cod_grado, A.cod_seccion ORDER BY B.grado ");
                      while($row = mysqli_fetch_array($grados_query))
                      {
                          $nom_gra=($row['nombreGrado']);
                          $id_gra=$row['grado'];
                          $nomSec=$row['nomSec'];
                          $idSec=$row['idSec'];
                          echo '<option value="'.$id_gra.$idSec.'">'.$nom_gra.' / '.$nomSec."</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Fecha de Publicación</label>
                  <input type="date" class="form-control" min="<?= $fechahoy ?>" name="fechaPublica" value="<?= $fechahoy ?>">
                </div>
                <div class="col-md-4">
                  <label>Fecha de Vencimiento</label>
                  <input type="date" title="Hasta que dia podra ser visible este comunicado por el alumno" class="form-control" min="<?= $fechahoy ?>" name="fechaMaxima" value="<?= $fecMax ?>">
                </div>
                
                <div class="col-md-12">
                  <label>Titulo:</label>
                  <input type="text" class="form-control" required aria-describedby="basic-addon1" name="titulo">
                </div>
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                    <label class="subtituloficha">Seleccione el archivo a subir</label>
                    <input type="file" accept=".image/*,.pdf" name="archivo" id="BSbtninfo" class="archivo" required="">
                </div>
                <div class="col-md-12" style="background: red; font-size: 18px; padding-top: 6px; margin-top: 1%;">
                  <label style="color: #FFF; text-align: justify;" >IMPORTANTE!<br> 
                  1ero. Se debe convertir el documento a PDF antes de subirlo <br>
                  2do. El nombre del archivo a subir no debe tener acentos áéíóú ni caracteres especiales como ñÑ°!"#$%&/()=?¡.,' solo puede llevar en el NOMBRE del mismo letras o numeros, si no toma en cuenta esto el archivo no sera visible por el alumno.<br>
                  Ejemplo:<br>
                  nombre de archivo incorrecto.: Matemática 2do año <br>
                  nombre de archivo correcto...: matematica 2do anio</label>
                </div>
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
    function validacion() {
      var grado = $('#anioSec').val();
      if (grado=='') 
      {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Año y sección'
        })
        return false;
      }
    }
    function verComunica(lin,id,grad,arch,titu,desd,hast,laps) 
    {
      $('#linVer').val(lin);
      $('#idTareaVer').val(id);
      $('#gradoVer').val(grad);
      
      $('#tituloVer').val(titu);
      $('#fechaPublicaVer').val(desd);
      $('#fechaMaximaVer').val(hast);
      $('#videoVer').attr('src', arch);
      
      $('#lapsoTareaVer').val(laps);
    }
    function guardar() 
    {
      id=$('#idTareaVer').val()
      lin=$('#linVer').val()
      fecP=$('#fechaPublicaVer').val()
      fecM=$('#fechaMaximaVer').val()
      titu=$('#tituloVer').val()
      $('#verComunica').modal('hide')

      $.post('actualComuniPri.php',{'id':id, 'fechaPublica':fecP, 'fechaMaxima':fecM, 'titulo':titu},function(data){
        if(data.isSuccessful){
          document.getElementById("titu"+lin).innerHTML = titu;
          document.getElementById("fecD"+lin).innerHTML = data.fechaP;
          document.getElementById("fecH"+lin).innerHTML = data.fechaM;
        }
      }, 'json');
    }
    function borraComunica(id,arch,lin) 
    {
      Swal.fire({
        title: 'Borrar Comunicado?',
        text: "Esta seguro de eliminar este documento!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Borrar!',
        cancelButtonText: 'Me arrepenti'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('borraComuniPri.php',{'id':id, 'archivo':arch},function(data){
            if(data.isSuccessful){
              $('#linea'+lin).hide();
            }
          }, 'json');

          Swal.fire(
            'Borrado!',
            'Documento fue eliminado.',
            'success'
          )
        }
      })
    }
  </script><?php 
  if(isset($_POST['subir']) && mysqli_num_rows($existe_query) == 0)
  {?>
    <script type="text/javascript"> alert('Documento almacenado exitosamente') </script><?php
  } ?>


</body>

</html>