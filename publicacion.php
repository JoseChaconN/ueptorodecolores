<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php#publica");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse(); 
?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  $cedAlum=$_SESSION['usuario'];
  $grado=$_SESSION['grado'];
  $seccion=$_SESSION['seccion'];
  $tablaPeriodo=$_SESSION['tablaPeriodo'];
  $periodo=ANOESCM;
  
  $comuni_query = mysqli_query($link,"SELECT * FROM tbl_documentos WHERE adultos is NULL and (todos='S') or ( ('$grado'>=gradoDesde and '$grado'<=gradoHasta) and (('$seccion'>=seccionDesde and '$seccion'<=seccionHasta) or seccionDesde is NULL ) ) ORDER BY fecha_doc DESC ");

  $docente_query = mysqli_query($link,"SELECT * FROM comunica_docen WHERE codGrado='$grado' and codSecci='$seccion' and '$fechahoy'>=fechaPublica and '$fechahoy'<=fechaMaxima ORDER BY fechaPublica  "); ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Publicaciones Generales</h2>
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
                <th scope="col">Titulo</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody>
              <form>
              <input type="hidden" id='nombreGrado' value="<?= $nomGra ?>"><?php  
              $son=0;
              while($row=mysqli_fetch_array($comuni_query)) 
              { 
                $fecha_doc=date("d-m-Y", strtotime($row['fecha_doc']));
                $titulo=$row['titulo'];
                $todos=$row['todos'];
                $nomarch='archivos/'.utf8_encode($row['nombre_archivo']);
                $id_documento=$row['id_documento'];
                $descripcion=utf8_encode(substr($row['descripcion'], 0,80));
                $son++;
                 ?>
                <tr >
                  <td><?= $son; ?></td>
                  <td><?= $fecha_doc ?></td>
                  <td><?= $titulo ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick="verArchivo('<?= $nomarch ?>','<?= $titulo ?>','<?= $descripcion ?>')" data-bs-toggle="modal" data-bs-target="#verArchivo" class="btn btn-outline-success" title="Ver la Tarea"><i class="ri-eye-line"></i></button></a>
                  </td>
                </tr><?php 
              }
              while($row=mysqli_fetch_array($docente_query)) 
              { 
                $fecha_doc=date("d-m-Y", strtotime($row['fechaPublica']));
                $titulo=$row['tituloComunica'];
                $nomgra = '';
                $nomsec = '';
                $nomarch='comunicaDocen/'.$row['nombreArchivo'];
                $id_documento=$row['idComunica'];
                $descripcion='';
                $son++;
                 ?>
                <tr >
                  <td><?= $son; ?></td>
                  <td><?= $fecha_doc ?></td>
                  <td><?= $titulo ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick="verArchivo('<?= $nomarch ?>','<?= $titulo ?>','<?= $descripcion ?>')" data-bs-toggle="modal" data-bs-target="#verArchivo" class="btn btn-outline-success" title="Ver la Tarea"><i class="ri-eye-line"></i></button></a>
                  </td>
                </tr><?php 
              } ?>
              </form>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <div class="modal fade" id="verArchivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Comunicado:</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-12">
                    <label>Titulo:</label>
                    <input type="text" class="form-control" aria-describedby="basic-addon1" id="tituloVer" readonly>
                </div>
                <div class="col-md-12">
                    <label>Descripcion:</label>
                    <input type="text" class="form-control" aria-describedby="basic-addon1" id="descriVer" readonly="">
                </div>
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge; margin-top: 2%;">
                    <iframe id="archivoVer" style="width: 100%;" height="500" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
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
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  
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
    function verArchivo(arch,tit,des) 
    {
      $('#archivoVer').attr('src', arch);
      $('#tituloVer').val(tit);
      $('#descriVer').val(des);
    }
  </script><?php 
  mysqli_free_result($comuni_query);
  mysqli_free_result($docente_query);?>

</body>

</html>