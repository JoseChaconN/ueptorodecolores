<?php
session_start();
if(!isset($_SESSION["usuario"])  || $_SESSION['cargo']<1) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$fecha_actual = date("Y-m-d");
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse();
$lapso_query=mysqli_query($link,"SELECT * FROM preinscripcion WHERE id = 2 ");
while($row=mysqli_fetch_array($lapso_query))
{
  $lapsoact=$row['lapso'];     
}
if(isset($_POST['subir']))
{
  $tablaPeriodo=$_SESSION['tablaPeriodo'];
  $fechaPublica=$_POST['fechaPublica'];
  $titulo=$_POST['titulo'];
  $descripcion=$_POST['descripcion'];
  $cedProf=$_SESSION['usuario'];
  $codGrado=$_POST['grado'];
  $codSecci=$_POST['seccion'];
  $codMater=$_POST['codMater'];
  $lapsoTarea=$lapsoact;

  $nombre = ($_FILES['archivo']['name']);
  function formatearNombre($nombre){
      $tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ·° !#$%&/()=?¡¿º,;";
      $replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn-_________________";
      $nombre = utf8_decode($nombre);     
      $nombre = strtr($nombre, utf8_decode($tofind), $replac); 
      $nombre = strtolower($nombre); 
      return utf8_encode($nombre);
  }
  $nombre = formatearNombre($nombre); 
  $tipo = $_FILES['archivo']['type'];
  $tamanio = $_FILES['archivo']['size'];
  $nombre=time().mt_rand(0, 999).$nombre;
  $destino = "../tareas/".$nombre;
  $ruta = $_FILES['archivo']['tmp_name'];

  $existe_query=mysqli_query($link,"SELECT * FROM tareas".$tablaPeriodo." WHERE tituloTarea='$titulo' and descriTarea='$descripcion' and cedProf='$cedProf' and codGrado='$codGrado' and codSecci='$codSecci' and codMater='$codMater'"); 
  if(mysqli_num_rows($existe_query) == 0)
  {
    if (copy($ruta, $destino)) 
    {
      mysqli_query($link,"INSERT INTO tareas".$tablaPeriodo."(tituloTarea, descriTarea, cedProf, codGrado, codSecci, codMater, nombreArchivo, tipo, tamanio, fechaPublica, lapsoTarea,todos) VALUES ('$titulo', '$descripcion', '$cedProf', '$codGrado', '$codSecci', '$codMater', '$nombre', '$tipo', '$tamanio', '$fechaPublica', '$lapsoTarea','S' )") or die ("NO GUARDO ARCHIVO".mysqli_error());
    }?>
    <script type="text/javascript">
      swal({
        title: "Excelente!",
        text: "El Documento fue almacenado correctamente",
        type: "success",
        confirmButtonText: "Entiendo"
        });
    </script> <?php
  }

}
$lapsoact = (!empty($lapsoMod)) ? $lapsoMod : $lapsoact ;
if ($lapsoact==1) {$nomlapso='Primero';}
if ($lapsoact==2) {$nomlapso='Segundo';}
if ($lapsoact==3) {$nomlapso='Tercero';} ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  $materias_query=mysqli_query($link,"SELECT * FROM trgsmp".$tablaPeriodo." WHERE cod_grado>60 and  ced_prof = '$cedula' ORDER BY cod_grado , cod_seccion , cod_materia "); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4>Materias y Aulas Asignadas (Material de Clases) <br>Prof. <?= $nombre.' '.$apelli.'<br>Lapso Activo: '.$lapsoActivo.'° ('.$periodoActivo.')' ?></h4>
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
                    <button type="button" onclick="subir('<?= ($nomgra) ?>','<?= $nomsec ?>','<?= $nommate ?>','<?= $cod_grado ?>','<?= $cod_seccion ?>','<?= $cod_materia ?>')" data-bs-toggle="modal" data-bs-target="#subir" class="btn btn-outline-primary" title="Subir Material de Clases General"><i class="ri-upload-cloud-2-fill"></i></button>
                    <button type="button" onclick='window.open("listaTareaBach.php?gra=<?= encriptar($cod_grado) ?>&sec=<?= $cod_seccion ?>&mat=<?= encriptar($cod_materia) ?> ")' class="btn btn-outline-warning" title="Subir Material de Clases por Estudiante"><i class="ri-upload-cloud-2-fill"></i></button>
                    <button type="button" onclick='window.open("list-tareas.php?gra=<?= encriptar($cod_grado) ?>&sec=<?= $cod_seccion ?>&mat=<?= encriptar($cod_materia) ?> ")' class="btn btn-outline-success" title="Ver listado de Material de Clases"><i class="ri-zoom-in-line"></i></button>
                  </td>
                </tr><?php 
              } ?>
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
          <h5 class="modal-title" id="exampleModalLabel">Nueva Material de Clases para:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form role="form" method="POST" action="" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-4">
                  <label>Grado/Secc.:</label>
                  <input type="text" class="form-control" readonly id="grado">
                </div>
                <div class="col-md-8">
                  <label>Materia:</label>
                  <input type="text" class="form-control" readonly id="materia" name="materia">
                </div>
                <div class="col-md-4">
                  <label>Fecha de Publicación</label>
                  <input type="date" class="form-control" min="<?= $fechahoy ?>" name="fechaPublica" value="<?= $fechahoy ?>">
                </div>
                <div class="col-md-4">
                  <label>Lapso</label>
                  <input type="text" readonly class="form-control" value="<?= $nomlapso ?>">
                </div>
                <div class="col-md-12">
                  <label>Titulo:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" name="titulo">
                </div>
                <div class="col-md-12">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" name="descripcion">
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

  </script>

</body>

</html>