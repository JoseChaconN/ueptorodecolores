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
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  $grado=$_POST['grado'];
  $seccion=$_POST['seccion'];
  $lapsoActivo=$_SESSION['lapsoActivo'];
  $lap='';
  if($lapsoActivo==1){$lap='1ero.';}
  if($lapsoActivo==2){$lap='2do.';}
  if($lapsoActivo==3){$lap='3ero.';} 
  $dicta_query = mysqli_query($link,"SELECT * FROM trgsmp".$tablaPeriodo." WHERE cod_grado='$grado' and cod_seccion='$seccion' ORDER BY cod_materia ");
  $van=0;
  while ($row = mysqli_fetch_array($dicta_query))
  {
    $van++;
    if($docente==$row['ced_prof']){
      ${'pro'.$van}=substr($row['cod_materia'],2,2);  
      ${'idMat'.$van}=$row['cod_materia'];  
    }else{
      ${'pro'.$van}="";
      ${'idMat'.$van}="";
    }
  }
  $grado_query = mysqli_query($link,"SELECT A.nombreGrado as nomgr, A.mate1, A.mate2, A.mate3, A.mate4, A.mate5, A.mate6, A.mate7, A.mate8, A.mate9, A.mate10,A.sonMate, B.nombre as nomsec FROM grado".$tablaPeriodo." A, secciones B WHERE A.grado='$grado' and B.id='$seccion'"); 
  while($row=mysqli_fetch_array($grado_query)) 
  { 
    $nombreGrado=$row['nomgr'];
    $nombreSec=$row['nomsec'];
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

  $alumnos_query=mysqli_query($link,"SELECT idAlum,cedula,apellido,nombre,ced_rep FROM alumcer WHERE grado='$grado' AND seccion='$seccion' and Periodo='$periodoActivo' ORDER BY apellido ASC"); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h3><?= 'Estudiantes del '.$nombreGrado.' '.$nombreSec.'<br>Momento : '.$lapsoActivo.'°' ?></h3>
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
                <th scope="col">Boton</th>
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
                  <td><?php 
                    if ($correoRep!="") {?>
                      <button type="button" onclick="enviaMail('<?= encriptar($idAlum) ?>')" data-bs-toggle="modal" data-bs-target="#enviarMail" title='Enviar Correo' class="btn btn-outline-primary" ><i class="bi-envelope" ></i></button><?php 
                    }?>
                    <button type="button" onclick="mensajePara('<?= encriptar($idAlum) ?>')" data-bs-toggle="modal" data-bs-target="#escojeMate" title='Enviar mensaje al estudiante' class="btn btn-outline-success" ><i class="bi-messenger"></i></button>
                    <button type="button" onclick='window.open("mailEnviados.php?id=<?= encriptar($idAlum) ?>&alum=<?= $nombre.' '.$apellido ?> ")' title='Correos enviados a este estudiante' class="btn btn-outline-info" ><i class="bi-envelope-open-fill"></i></button>
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
          <input type="hidden" id="nombreGrado" value="<?= $nombreGrado ?>">
          <input type="hidden" id="nomsec" value="<?= $nombreSec ?>">
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <div class="modal fade" id="escojeMate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Seleccione Materia:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="col">
            <div class="row">
              <div class="col-md-12">
                <label>Materia:</label>
                <select class="form-control" id="idMateMsj"><?php 
                  for ($i=1; $i <=$sonMate ; $i++) { ?>
                      <option <?php if(${'pro'.$i}==''){echo "disabled";} ?> value="<?= encriptar(${'idMat'.$i}) ?>"><?= ${'mate'.$i} ?></option><?php
                  }?>
                </select>
              </div>
              <input type="hidden" id="gradoMsj" value="<?= $grado ?>">
              <input type="hidden" id="secciMsj" value="<?= $seccion ?>">
              <input type="hidden" id="idAlumMsj">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="enviaMsj()" >Enviar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="enviarMail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Representante:</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form role="form" method="POST" target="_blank" onsubmit="return validacion()" enctype="multipart/form-data" action="mailRepre.php">
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-12">
                    <input type="text" name="asunto" required id="asunto" placeholder="Asunto" class="form-control">
                </div>
                <div class="col-md-12" style="margin-top:1%;">
                    <textarea placeholder="Mensaje a enviar" rows="6" class="form-control" name="mensaje" id="mensaje"></textarea>
                </div>
                <div class="col-md-12" style="margin-top:1%;">
                    <label class="subtituloficha">Archivo adjunto</label>
                    <input type="file"  name="archivo" id="BSbtninfo" class="archivo" >
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="idAlumn_mail" name="idAlumn_mail">
          <input type="hidden" name="grado" value="<?= $grado ?>">
          <input type="hidden" name="seccion" value="<?= $seccion ?>">
          <div class="modal-footer">
            <button type="submit" onclick="enviado()" class="btn btn-success" >Enviar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; 
  ?>
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
    function mensajePara(id) {
      $('#idAlumMsj').val(id)
    }
    function enviaMsj() {
      id=$('#idAlumMsj').val()
      idMat=$('#idMateMsj').val()
      window.open("chatAlumno.php?id="+id+"&idMat="+idMat)
    }
    function enviaMail(id) {
      $('#idAlumn_mail').val(id)
    }
    function enviado() {
      $('#enviarMail').modal('hide')
    }
  </script>
</body>
</html>