<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(!isset($_SESSION["usuario"]) && $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");

include("../inicia.php");
include("../conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  $idAlum=desencriptar($_GET['id']);
  $alumno=$_GET['alum'];
  $alumnos_query = mysqli_query($link,"SELECT * FROM correo_docente WHERE idAlum='$idAlum' and id_docente='$idDoc' ");
  ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h3><?= 'Correos al estudiante<br>'.$alumno ?></h3>
        <div class="col-md-4 offset-md-4 col-12">
          <button type="button" onclick="javascript:window.close();opener.window.focus();" style="width:100%;" class="btn btn-warning btn-lg"><i class="bi-door-open-fill"></i> Cerrar listado</button>  
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
                <th scope="col">Fecha/Hora</th>
                <th scope="col">Asunto</th>
                <th scope="col">Mensaje</th>
                <th scope="col">Archivo</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=0;
              while($row=mysqli_fetch_array($alumnos_query)) 
              { 
                $fecha=date("d-m-Y H:i", strtotime($row['fecha']));
                $asunto=$row['asunto'];
                $mensaje=$row['mensaje'];
                $archivo=$row['archivo'];
                $son++; ?>
                <tr>
                  <td><?= $son; ?></td>
                  <td><?= $fecha ?></td>
                  <td><?= $asunto ?></td>
                  <td><?= $mensaje ?></td>
                  <td><?= $archivo ?></td>
                  
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
        <form role="form" method="POST" onsubmit="return validacion()" enctype="multipart/form-data" action="">
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
  </script><?php 
  if(isset($_POST['idAlumn_mail']) && !empty($_POST['idAlumn_mail'])){ ?>
    <script type="text/javascript">
      alert('Correo enviado, exitosamente!')
    </script><?php
  }?>
</body>
</html>