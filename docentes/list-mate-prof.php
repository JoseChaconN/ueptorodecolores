<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
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
  if ($lapsoActivo==1) {$nomlapso='Primero'; $idFechas=3;}
  if ($lapsoActivo==2) {$nomlapso='Segundo'; $idFechas=4;}
  if ($lapsoActivo==3) {$nomlapso='Tercero'; $idFechas=5;}
  $profesor = $_SESSION["usuario"];
  $profe_query=mysqli_query($link,"SELECT nombre,apellido,editable FROM alumcer WHERE cedula = '$profesor' ");
  while($row=mysqli_fetch_array($profe_query))
  {
    $nomprofe = $row['nombre'].' '.$row['apellido'];
    $editable=$row['editable'];
  }
  $fechalimite=mysqli_query($link,"SELECT * FROM preinscripcion WHERE id = '$idFechas' ");
  while($row8=mysqli_fetch_array($fechalimite))
  {$desde=$row8['fecinicio']; $hasta=$row8['fecfinal'];}
  $fecha_desde=date("d-m-Y", strtotime($desde));
  $fecha_hasta=date("d-m-Y", strtotime($hasta));
  $materias_query=mysqli_query($link,"SELECT * FROM trgsmp".$tablaPeriodo." WHERE cod_grado>60 and ced_prof = '$cedula' ORDER BY cod_grado , cod_seccion , cod_materia "); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4>Materias y Aulas Asignadas (Calificaciones) <br>Prof. <?= $nombre.' '.$apelli.'<br>Lapso Activo: '.$lapsoActivo.'° ('.$periodoActivo.')' ?></h4>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ayudaExcel">Como usar el Excel</button>
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
                <th scope="col">Evaluado</th>
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
                $corte1_existe=mysqli_query($link,"SELECT * FROM cortes1".$tablaPeriodo." WHERE cod_materia='$cod_materia' AND cod_seccion='$cod_seccion' ");
                $lp=$lapsoActivo; $porc1=0; $porc2=0; $porc3=0; $porc4=0; $porc5=0;
                while($row=mysqli_fetch_array($corte1_existe))
                {
                  $porc1 = $row['porcentaje1'.$lp] ;
                  $porc2 = $row['porcentaje2'.$lp] ;
                  $porc3 = $row['porcentaje3'.$lp] ;
                  $porc4 = $row['porcentaje4'.$lp] ;
                  $porc5 = $row['porcentaje5'.$lp] ;
                  $porctotal=($porc1+$porc2+$porc3+$porc4+$porc5);
                }
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td><?= ($nomgra) ?></td>
                  <td><?= $nomsec ?></td>
                  <td><?= $nommate ?></td>
                  
                  <td >
                    <button type="button" title='1era. Estrategia (<?= $porc1 ?> %)' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=1")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-1"></i></button>
                    <button type="button" title='2da. Estrategia (<?= $porc2 ?> %)' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=2")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-2"></i></button>
                    <button type="button" title='3era. Estrategia (<?= $porc3 ?> %)' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=3")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-3"></i></button>
                    <button type="button" title='4ta. Estrategia (<?= $porc4 ?> %)' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=4")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-4"></i></button>
                    <button type="button" title='5ta. Estrategia (<?= $porc5 ?> %)' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=5")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-5"></i></button>

                    <button type="button" title='Estrategia 5' onclick='window.open("rep-cortes.php?grado=<?= $cod_grado; ?>&seccion=<?= $cod_seccion;?>&materia=<?= $cod_materia; ?>&ced_prof=<?= $cedula;?>&lapso=<?= $lapsoActivo ?>")' class="btn btn-outline-primary" target='_blank' title="Imprimir reporte de notas"><i class="ri-printer-line"></i></button><?php
                    if($porctotal==100)
                    { ?>
                      <button onclick="exporExcel('<?= $lapsoActivo ?>','<?= encriptar($cod_grado) ?>','<?= encriptar($cod_seccion) ?>','<?= encriptar($cod_materia) ?>','<?= $nomprofe ?>','<?= ($nomgra) ?>','<?= $nomsec ?>','<?= $nommate ?>')" data-bs-toggle="modal" data-bs-target="#comoUsar" type='button' class='btn btn-success' title='Descargar listado a Excel'><span class='ri-file-excel-line' aria-hidden='true'></span></button><?php
                    }else
                    { ?>
                      <button onclick="msjBajar()" type='button' class='btn btn-success' title='Descargar listado a Excel'><span class='ri-file-excel-line' aria-hidden='true'></span></button><?php
                    }
                    if(($porctotal==100 && $fechahoy >= $desde && $fechahoy <= $hasta) && $editable!='N' || !empty($_SESSION['admin']) )
                    { ?>
                      <button onclick="subeArchivo('<?= encriptar($lapsoActivo) ?>','<?= encriptar($cod_materia) ?>','<?= $porc1 ?>','<?= $porc2 ?>','<?= $porc3 ?>','<?= $porc4 ?>','<?= $porc5 ?>','<?= $cod_grado ?>','<?= $nomsec ?>','<?= $nommate ?>')" data-bs-toggle="modal" data-bs-target="#subeData" type='button' class='btn btn-primary' title='Subir notas a la Web'><span class='ri-upload-cloud-line' aria-hidden='true'></span></button><?php
                    }else
                    { ?>
                      <button onclick="msjSubir()" type='button' class='btn btn-primary' title='Subir notas a la Web'><span class='ri-upload-cloud-line' aria-hidden='true'></span></button><?php
                    } ?>
                  </td>
                  <td><?= $porctotal.'%' ?></td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <input type="hidden" id="desde" value="<?= $fecha_desde ?>">
    <input type="hidden" id="hasta" value="<?= $fecha_hasta ?>">
  </main><!-- End #main -->
  <div class="modal fade" id="ayudaExcel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Instructivo:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="col">
            <div class="row">
              <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge; margin-top: 2%;">
                  <iframe style="width: 100%;" height="500" src="subir_Excel.pdf" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
  <div class="modal fade" id="subeData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cargar archivo Excel de notas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <center><h3>Importante!</h3></center>
          <p style="text-align: justify; font-size: 18px;">El formato excel configurado para subir las notas de forma correcta es unicamente el descargado desde esta pagina en el boton de color verde con el icono de excel (exportar listado a Excel) al lado de este. </p>
          <p id="cualArch" style="background-color: red; color: white;"></p>
          <form role="form" method="POST" id="subirArchivo" action="subirEstrategias.php" enctype="multipart/form-data">
            <div class="form-group">                                     
              <label class="subtituloficha">Seleccione archivo Excel con notas a subir *</label>
              <input type="file" accept=".xlsx" required="" name="notas" id="btnFiles" class="filestyle"><br>
              <div clas="row" style="margin-bottom: 2%; padding-bottom: 2%;">
                <div class="col-md-12 row text-center ">
                  <div class="col-md-4 offset-md-4">
                    <button class="btn btn-warning" onclick='window.open("sesion.php")' style="width:100%" type="button">Verificar Sesión</button>
                  </div>
                  <div class="col-md-4">
                    <button class="btn btn-success" style="width: 100%;" type="button" onclick="comprueba()" name="enviar">Subir Archivo</button>
                  </div>
                </div>
              </div>                                   
            </div>
            <input type="hidden" name="lapso" id="lapso">
            <input type="hidden" name="mate" id="mate">
            <input type="hidden" name="porc1" id="porc1">
            <input type="hidden" name="porc2" id="porc2">
            <input type="hidden" name="porc3" id="porc3">
            <input type="hidden" name="porc4" id="porc4">
            <input type="hidden" name="porc5" id="porc5">
            <input type="hidden" name="ced_prof" id="ced_prof" value="<?= encriptar($profesor) ?>">
            <input type="hidden" name="nomprofe" value="<?= $nomprofe ?>">
            <input type="hidden" name="compr" id="compr">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="comoUsar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Archivo Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <center><h3>Consideraciones al usar el archivo descargado</h3></center>
          <p style="text-align: justify; font-size: 18px;">
            1.- El único archivo valido para subir las notas será el descargado en la plataforma.<br><br>
            2.- Solamente puede modificar las notas del estudiante en el Excel (cualquier otra modificación no será tomada en cuenta en la plataforma.)<br><br>
            3.- Solo cargue o modifique las notas según tantas estrategia haya creado (Ejemplo si usted creo (4) estrategias en la plataforma solo cargue en el Excel descargado las estrategias del 1 al 4).<br><br>
            4.- Los cambios en los nombres de las estrategias, fechas o ponderación debe hacerlo solo en la plataforma y luego volver a descargar el archivo Excel.<br><br>
            5.- No es necesario cargar todas las estrategias para poder subir el archivo (Ejemplo: si usted creo 4 estrategias y a evaluado solamente la 1era. puede cargar las notas respectivas y subir el archivo)<br><br>
            6.- Nunca modifique la estructura del Excel descargado ni ningún otro campo que no sean las notas de cada estudiante, ya que esto puede ocasionar la pérdida de su información.<br><br>
            7.- Recuerde una vez terminada la carga o modificación de las notas en el Excel, guardar el archivo en el mismo formato Excel antes de subirlo.
          </p>
          <div class="form-group">                                     
              
              
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
        </div>
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
    } );
    function comprueba() {
      arch=$('#btnFiles').val().replace(/.*(\/|\\)/, '')
      revi=$('#compr').val();
      if(arch==revi)
      {
        document.getElementById('subirArchivo').submit()
      }else 
      {
        alert('Archivo incorrecto Verifique')
      }
    }
    $(":file").filestyle(
      {
        btnClass : 'btn-info',
        text : 'Buscar Archivo'
      });
    function msjBajar()
    {
      Swal.fire({
        icon: 'info',
        title: 'Informacion Importante!',
        confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Entendido',
        text: 'Para poder usar esta funcion primero debe crear el 100% de las estrategias del lapso'
      })
    }
    function msjSubir()
    {
      des=$('#desde').val();
      has=$('#hasta').val();
      Swal.fire({
        icon: 'Error',
        title: 'Error!',
        confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Entendido',
        html: '<p style="text-align: justify;">Posibles motivos:<br> 1- No a creado el 100% de las estrategias del lapso. <br><br> 2- Ctrl. de Estudios inhabilito editar notas.<br><br> 3- Se encuentre fuera de rango en fecha de carga las cuales son: <br> Desde el: '+des+'<br> Hasta el:'+has+'</p>'
      })
    }
    function exporExcel(lap,gra,sec,mat,ndoc,ngra,nsec,nmate)
    {
      location.href = 'estrategia-excel.php?lapso='+lap+'&grado='+gra+'&secc='+sec+'&mate='+mat+'&prof='+ndoc+'&ngra='+ngra+'&nsec='+nsec+'&nmate='+nmate;
    }
    function subeArchivo(lap,mat,po1,po2,po3,po4,po5,gra,sec,mate)
    {
      arc=' Por favor seleccione el archivo: Estrategias de '+gra+sec+' '+mate+'.xlsx '
      document.querySelector('#cualArch').innerText = arc;
      comp='Estrategias de '+gra+sec+' '+mate+'.xlsx'
      $('#compr').val(comp);
      $('#lapso').val(lap);
      $('#mate').val(mat);
      $('#porc1').val(po1);
      $('#porc2').val(po2);
      $('#porc3').val(po3);
      $('#porc4').val(po4);
      $('#porc5').val(po5);
    }
  </script>
</body>
</html>