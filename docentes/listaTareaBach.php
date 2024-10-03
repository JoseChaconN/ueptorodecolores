<?php
session_start();
#error_reporting(E_ALL);
#ini_set('display_errors', '1');
if(!isset($_SESSION["usuario"]) && $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$docente=$_SESSION["usuario"];
$nomDocente=$_SESSION['nomuser'].' '.$_SESSION['apelluser'];
include("../inicia.php");
include("../conexion.php");
include_once "header.php";
$link = conectarse();
$lapsoActivo=$_SESSION['lapsoActivo'];
$msjGuar='';
if(isset($_POST['subir']))
{
  $nombreArch = ($_FILES['archivo']['name']);
  $titulo=$_POST['titulo'];
  if($titulo!='' && $nombreArch!='' ){
    $tablaPeriodo=$_SESSION['tablaPeriodo'];
    $fechaPublica=$_POST['fechaPublica'];
    $descripcion=$_POST['descripcion'];
    $cedProf=$_SESSION['usuario'];
    $codGrado=$_POST['grado'];
    $codSecci=$_POST['seccion'];
    $codMater=$_POST['codMater'];
    $lapsoTarea=$lapsoActivo;
    $cuantos=$_POST['enList'];
    function formatearNombre($nombreArch){
        $tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ·° !#$%&/()=?¡¿º,;";
        $replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn-_________________";
        $nombreArch = utf8_decode($nombreArch);     
        $nombreArch = strtr($nombreArch, utf8_decode($tofind), $replac); 
        $nombreArch = strtolower($nombreArch); 
        return utf8_encode($nombreArch);
    }
    $nombreArch = formatearNombre($nombreArch); 
    $tipo = $_FILES['archivo']['type'];
    $tamanio = $_FILES['archivo']['size'];
    $nombreArch=time().mt_rand(0, 999).$nombreArch;
    $destino = "../tareas/".$nombreArch;
    $ruta = $_FILES['archivo']['tmp_name'];
    $existe_query=mysqli_query($link,"SELECT * FROM tareas".$tablaPeriodo." WHERE tituloTarea='$titulo' and descriTarea='$descripcion' and cedProf='$cedProf' and codGrado='$codGrado' and codSecci='$codSecci' and codMater='$codMater'"); 
    if(mysqli_num_rows($existe_query) == 0)
    {
      if (copy($ruta, $destino)) 
      {
        mysqli_query($link,"INSERT INTO tareas".$tablaPeriodo."(tituloTarea, descriTarea, cedProf, codGrado, codSecci, codMater, nombreArchivo, tipo, tamanio, fechaPublica, lapsoTarea,todos) VALUES ('$titulo', '$descripcion', '$cedProf', '$codGrado', '$codSecci', '$codMater', '$nombreArch', '$tipo', '$tamanio', '$fechaPublica', '$lapsoTarea','N' )") or die ("NO GUARDO ARCHIVO".mysqli_error());
        
        $nuevo_query = mysqli_query($link,"SELECT LAST_INSERT_ID(idTarea) as nuevoCodigo FROM tareas".$tablaPeriodo." order by idTarea desc limit 0,1  ");
        $row=mysqli_fetch_array($nuevo_query);
        $idTarea=$row['nuevoCodigo'];
        for ($i=1; $i <=$cuantos ; $i++) { 
          if(isset($_POST['id'.$i])){
            $idA=desencriptar($_POST['id'.$i]);
            mysqli_query($link,"INSERT INTO tarea_ind_".$tablaPeriodo."(idTarea,idAlum) VALUES ('$idTarea', '$idA' )") or die ("NO GUARDO Individual".mysqli_error($link));
          }
        }

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
    $msjGuar=1;
  }else{
    $msjGuar=2;
  }
} ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  $grado=desencriptar($_GET['gra']);
  $seccion=$_GET['sec'];
  $cod_materia=desencriptar($_GET['mat']);
  $materia=mysqli_query($link,"SELECT * FROM materiass".$tablaPeriodo." WHERE codigo = '$cod_materia'");
  while($row=mysqli_fetch_array($materia))
  {$nommate=trim($row["nombremate"]);}
  
  $lap='';
  if($lapsoActivo==1){$lap='1ero.';}
  if($lapsoActivo==2){$lap='2do.';}
  if($lapsoActivo==3){$lap='3ero.';} 
  $grado_query = mysqli_query($link,"SELECT A.nombreGrado as nomgr, B.nombre as nomsec FROM grado".$tablaPeriodo." A, secciones B WHERE A.grado='$grado' and B.id='$seccion'"); 
  while($row=mysqli_fetch_array($grado_query)) 
  { 
    $nombreGrado=$row['nomgr'];
    $nombreSec=$row['nomsec'];
    $nomgra=$row['nomgr'];
    $nomsec=$row['nomsec'];
  }
  $alumnos_query=mysqli_query($link,"SELECT idAlum,cedula,apellido,nombre,ced_rep FROM alumcer WHERE grado='$grado' AND seccion='$seccion' and Periodo='$periodoActivo' ORDER BY apellido ASC"); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <div class="row col-md-12 col-12 ">
          <div class="col-md-12 col-12">
            <h3><?= 'Subir Material de clases individual<br>Estudiantes del '.$nombreGrado.' '.$nombreSec.'<br>Momento : '.$lapsoActivo.'°' ?></h3>  
          </div>
          <div class="col-md-4 offset-md-2 col-12">
            <button type="button" onclick="subir('<?= $nomgra ?>','<?= $nomsec ?>','<?= $nommate ?>','<?= $grado ?>','<?= $seccion ?>','<?= $cod_materia ?>')" data-bs-toggle="modal" data-bs-target="#subir" style="width:100%;" class="btn btn-primary btn-lg"><i class="ri-upload-cloud-2-fill"></i> Cargar Material</button>  
          </div>
          <div class="col-md-4 col-12">
            <button type="button" onclick="javascript:window.close();opener.window.focus();" style="width:100%;" class="btn btn-warning btn-lg"><i class="bi-door-open-fill"></i> Cerrar listado</button>  
          </div>
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
                <th scope="col">Cedula</th>
                <th scope="col">Apellido</th>
                <th scope="col">Nombre</th>
                <th scope="col" style="text-align: center;" >Seleccione</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=0;
              while($row=mysqli_fetch_array($alumnos_query)) 
              { 
                $ced_alu=$row['cedula'];
                $nombre=$row['nombre'];
                $apellido=$row['apellido'];
                $idAlum=$row['idAlum'];
                $ced_rep=$row['ced_rep'];
                $repre_query=mysqli_query($link,"SELECT correo FROM represe WHERE cedula='$ced_rep' ");
                if(mysqli_num_rows($repre_query) > 0)
                {
                  $row2=mysqli_fetch_array($repre_query);
                  $correoRep=$row2['correo'];
                }else{$correoRep='';}
                $son++; ?>
                <tr>
                  <td><?= $son; ?></td>
                  <td><?= $ced_alu ?></td>
                  <td><?= $apellido ?></td>
                  <td><?= $nombre ?></td>
                  <td align="center">
                    <input type="checkbox" style="transform: scale(2.5);" id="envia_<?= $son; ?>" value="<?= encriptar($idAlum) ?>" >
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
          <input type="hidden" id="cuantos" value="<?= $son ?>">
          <input type="hidden" id="nombreGrado" value="<?= $nombreGrado ?>">
          <input type="hidden" id="nomsec" value="<?= $nombreSec ?>">
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
        <form role="form" method="POST" id="formuSubir" action="" enctype="multipart/form-data">
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
                  <input type="text" readonly class="form-control" value="<?= $lap ?>">
                </div>
                <div class="col-md-12">
                  <label>Titulo:</label>
                  <input type="text" class="form-control" required aria-describedby="basic-addon1" name="titulo" id="titulo">
                </div>
                <div class="col-md-12">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" aria-describedby="basic-addon1" name="descripcion">
                </div>
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="margin-top: 2%;">
                    <label class="subtituloficha">Seleccione el archivo a subir</label>
                    <input type="file" accept=".image/*,.pdf" name="archivo" id="BSbtninfo" class="archivo" required>
                </div>
                <div class="col-md-12" style="background: red; font-size: 18px; padding-top: 6px; margin-top: 1%;">
                  <label style="color: #FFF; text-align: justify;" >IMPORTANTE!<br> 
                  1ero. Se debe convertir el documento a PDF antes de subirlo <br>
                  2do. El nombre del archivo a subir no debe tener acentos áéíóú ni caracteres especiales como ñÑ°!"#$%&/()=?¡.,' solo puede llevar en el NOMBRE del mismo letras o numeros, si no toma en cuenta esto el archivo no sera visible por el alumno.<br>
                  Ejemplo:<br>
                  nombre de archivo incorrecto.: Matemática 2do año <br>
                  nombre de archivo correcto...: matematica 2do anio</label>
                </div>
                <input type="hidden" name="subir">
                <input type="hidden" name="grado" value="<?= $grado ?>">
                <input type="hidden" name="seccion" value="<?= $seccion ?>">
                <input type="hidden" name="codMater" value="<?= $cod_materia ?>">
                <input type="hidden" name="enList" id="enList">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="revisar()" >Guardar</button>
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
    function revisar() {
      hay=$('#cuantos').val()
      $('#enList').val(hay)
      van=0
      if($('#titulo').val()!='' && $('#BSbtninfo').val()!='' ){
        for (var i = 1; i <=hay ; i++) {
          if($("#envia_"+i).prop('checked')) {
            van=van+1;
            idA=$("#envia_"+i).val()
            var formulario = document.getElementById("formuSubir");
            var nuevoInput = document.createElement("input");
            nuevoInput.type = "hidden";
            nuevoInput.name = "id"+i;
            nuevoInput.id = "id"+i;
            formulario.appendChild(nuevoInput);
            $('#id'+i).val(idA)
          }
        }
      }else{
        Swal.fire({
          icon: 'error',
          title: 'información!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Recuerde colocar un titulo y seleccionar el archivo a subir'
        })
      }
      if(van>0){
        if($('#titulo').val()!='' && $('#BSbtninfo').val()!=''){
          $("#formuSubir").submit();
        }
      }else{
        if($('#titulo').val()!='' && $('#BSbtninfo').val()!=''){
          Swal.fire({
            icon: 'error',
            title: 'información!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'No ha seleccionado a ningun estudiante'
          })
        }
      }
    }
  </script><?php
  if (isset($_POST['subir']) && $msjGuar==1 ) {?>
    <script type="text/javascript">
      opener.document.location.reload();
      Swal.fire({
          icon: 'success',
          title: 'Excelente!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          html: 'Su material ha sido almacenado exitosamente!'
        })
    </script><?php 
  }?>
</body>
</html>