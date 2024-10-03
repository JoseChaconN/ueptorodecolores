<?php
session_start();
include_once("inicia.php");
include_once "header.php";
include_once "conexion.php";
$link = Conectarse();
$estudiante=$_SESSION['nomuser'].' '.$_SESSION['apelluser'];
 ?>
<!DOCTYPE html>
<html lang="es"><?php
  $grado_query=mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." where grado='$grado' ");
  $row=mysqli_fetch_array($grado_query);
  $nombreGrado=$row['nombreGrado'];

  $guias_query=mysqli_query($link,"SELECT A.ced_prof,B.nombre,B.apellido,B.atencion,B.ruta FROM trgsmp".$tablaPeriodo." A, alumcer B where A.cod_grado='$grado' and A.cod_seccion='$seccion' and A.ced_prof=B.cedula and A.doc_guia='1' ");
   ?>
  <main id="main">
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Atención al Representante </h2>
        <p><strong>Estudiante: <?= $estudiante ?><br>Cursante del <?= $nombreGrado ?></strong></p>
      </div>
    </div><!-- End Breadcrumbs -->
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">
        <div class="row mt-5"><?php
          $van=0;
          while($row=mysqli_fetch_array($guias_query))
          {
            $nombre=$row['nombre'];
            $apellido=$row['apellido'];
            $foto=$row['ruta'];
            if(empty($foto)){
              $foto='imagenes/usuario.png';
            }else{
              $foto='docentes/fotodoc/'.$foto;
            }
            $atencion=$row['atencion'];
            $van++; ?>
            <div class="col-md-6 col-12 row">
              <div class="col-md-4 col-6 text-center" style="margin-bottom: 1%; ">
                <img src="<?= $foto ?>" style="width:100%; height: auto; cursor: pointer;">
              </div>
              <div class="col-md-8 col-12" style="background-color: #E3F2FD; margin-bottom: 1%;">
                <h6>Docente:<br><strong><?= $nombre ?> <?= $apellido ?></strong></h6>
                <p style="text-align: justify;"><?= $atencion ?></p>
              </div>  
            </div><?php
          }?>
        </div>
      </div>
    </section>
    <div class="modal fade" id="verFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Articulo Escolar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
              <h5 id="concepto"></h5>
              <h6 id="monto"></h6>
            </div>
            <div class="col-md-12">
              <img id="foto" src="" style="width: 100%; height: auto; ">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div><!-- End Contact Section -->
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    jQuery(document).ready(function($) 
    {
      
    });
    function ver(tit,mon,fot) {
      $('#verFoto').modal('show')
      document.querySelector('#concepto').innerText = tit;
      document.querySelector('#monto').innerText = 'Ref.: '+mon+' $';
      $("#foto").attr("src",'fotoArticulos/'+fot);
    }
  </script>
</body>

</html>