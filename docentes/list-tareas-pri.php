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
  if(isset($_POST['subir']))
  { 
    $nomMater=desencriptar($_POST['nomMater']);
    if($nomMater!=""){
      $fechaPublica=$_POST['fechaPublica'];
      $fechaMaxima=$_POST['fechaMaxima'];
      $titulo=$_POST['titulo'];
      $descripcion=$_POST['descripcion'];
      
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
      $existe_query=mysqli_query($link,"SELECT * FROM tareaspri".$tablaPeriodo." WHERE tituloTarea='$titulo' and descriTarea='$descripcion' and cedProf='$cedula' and codGrado='$grado' and codSecci='$seccion' and nomMater='$nomMater'
            "); 
      if(mysqli_num_rows($existe_query) == 0)
      {
        if (copy($ruta, $destino)) 
        {
          mysqli_query($link,"INSERT INTO tareaspri".$tablaPeriodo."(tituloTarea, descriTarea, cedProf, codGrado, codSecci, nomMater, nombreArchivo, tipo, tamanio, fechaPublica, fechaMaxima, lapsoTarea,todos) VALUES ('$titulo', '$descripcion', '$cedula', '$grado', '$seccion', '$nomMater', '$nombre', '$tipo', '$tamanio', '$fechaPublica', '$fechaMaxima', '$lapsoActivo','S' )") or die ("NO GUARDO ARCHIVO".mysqli_error());
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
    $tareas_query = mysqli_query($link,"SELECT * FROM tareaspri".$tablaPeriodo."  WHERE codGrado='$grado' and codSecci='$seccion' and cedProf='$cedula' and lapsoTarea='$lapsoActivo'  ORDER BY fechaPublica");  ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Listado de Material de Clases del <?= $nombreGrado.' Sección '.$nombreSec.'<br>Lapso Activo: '.$lapsoActivo.'°' ?></h2>
        <button class="btn btn-primary" title="Material dirigido a todos los estudiantes del grado/sección" type="button" onclick="subir('<?= $nombreGrado ?>','<?= $nombreSec ?>','<?= $grado ?>','<?= $seccion ?>')" style="font-size: 18px;" ><i class="ri-upload-line"></i> Subir Nuevo General</button>
        <button class="btn btn-info" title="Material dirigido a uno o mas estudiantes del grado/sección" type="button" onclick='window.open("listaTareaPrim.php?gra=<?= encriptar($grado) ?>&sec=<?= $seccion ?> ")' style="font-size: 18px;" ><i class="ri-upload-line"></i> Subir Nuevo Individual</button>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Fecha Publicación</th>
                <!--th scope="col">Fecha Hasta</th-->
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
                $nomMate=$row['nomMater'];
                $todos=$row['todos'];
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td title="Fecha en la cual esta disponible este Material de Clases" id="fecD<?= $son ?>"><?= $fechaDesde ?></td>
                  <!--td title="Fecha maxima de entrega al docente" id="fecH<?= $son ?>"><?= $fechaHasta ?></td-->
                  <td><?= $nomMate ?></td>
                  <td id="titu<?= $son ?>"><?= $titulo; ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick="verTarea('<?= $son ?>','<?= encriptar($id_documento) ?>','<?= $nombreGrado ?>','<?= $nomMate ?>','<?= $nomarch ?>','<?= $titulo ?>','<?= $descriTarea ?>','<?= $desde ?>','<?= $hasta ?>','<?= $lap ?>','<?= $todos ?>')" data-bs-toggle="modal" data-bs-target="#verTarea" class="btn btn-outline-success" title="Ver la Tarea"><i class="ri-eye-line"></i></button>
                    <button type="button" onclick="borraTarea('<?= encriptar($id_documento) ?>','<?= $nomarch ?>','<?= $son ?>')" class="btn btn-outline-danger" title="Borrar el Material de Clases"><i class="ri-delete-bin-2-line"></i></button>
                    <!--button type="button" onclick='window.open("list-entrego-tarea.php?id=<?= encriptar($id_documento) ?>&gra=<?= $nombreGrado ?>&sec=<?= $nombreSec ?>&cod=<?= $grado ?> ")' class="btn btn-outline-primary" title="Ver listado de alumnos Material de Clases"><i class="ri-check-line"></i></button-->
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
  <div class="modal fade" id="subir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nuevo Material de Clases para todos:</h5>
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
                  <select class="form-control" name="nomMater"><?php 
                    for ($i=1; $i <=$sonMate ; $i++) { ?>
                        <option <?php if(${'pro'.$i}==''){echo "disabled";} ?> value="<?= encriptar(${'mate2'.$i}) ?>"><?= ${'mate'.$i} ?></option><?php
                    }?>
                    
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Fecha de Publicación</label>
                  <input type="date" class="form-control" min="<?= $fechahoy ?>" name="fechaPublica" value="<?= $fechahoy ?>">
                </div>
                <!--div class="col-md-4">
                  <label>Maxima de Recepción</label>
                  <input type="date" title="Indique la fecha maxima de entrega para este Material de Clases" class="form-control" min="<?= $fechahoy ?>" name="fechaMaxima" value="<?= $fecMax ?>">
                </div-->
                <div class="col-md-4">
                  <label>Lapso</label>
                  <input type="text" readonly class="form-control" value="<?= $lap ?>">
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
    function verTarea(lin,id,grad,mate,arch,titu,desc,desd,hast,laps,tod) 
    {
      $('#linVer').val(lin);
      $('#idTareaVer').val(id);
      $('#gradoVer').val(grad);
      $('#materiaVer').val(mate);
      $('#tituloVer').val(titu);
      $('#descriVer').val(desc);
      $('#fechaPublicaVer').val(desd);
      $('#fechaMaximaVer').val(hast);
      $('#videoVer').attr('src', arch);
      $('#lapsoTareaVer').val(laps);
      if (tod=='N') {
        $.post('tareaPriQuien.php',{'id':id},function(data)
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
      fecM=$('#fechaMaximaVer').val()
      titu=$('#tituloVer').val()
      desc=$('#descriVer').val()
      $('#verTarea').modal('hide')

      $.post('actualTareaPri.php',{'id':id, 'fechaPublica':fecP, 'fechaMaxima':fecM, 'tituloTarea':titu,'descriTarea':desc},function(data){
        if(data.isSuccessful){
          document.getElementById("titu"+lin).innerHTML = titu;
          document.getElementById("fecD"+lin).innerHTML = data.fechaP;
          document.getElementById("fecH"+lin).innerHTML = data.fechaM;
        }
      }, 'json');
    }
    function borraTarea(id,arch,lin) 
    {
      Swal.fire({
        title: 'Borrar Tarea?',
        text: "Esta seguro de eliminar este Material de Clases!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Borrar!',
        cancelButtonText: 'Me arrepenti'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('borraTareaPri.php',{'id':id, 'archivo':arch},function(data){
            if(data.isSuccessful){
              $('#linea'+lin).hide();
            }
          }, 'json');

          Swal.fire(
            'Borrada!',
            'Su Material de Clases fue eliminada.',
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
  </script>

</body>

</html>