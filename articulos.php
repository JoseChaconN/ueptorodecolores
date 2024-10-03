<?php
session_start();
include_once("inicia.php");
include_once "header.php";
include_once "conexion.php";
$link = Conectarse();
 ?>
<!DOCTYPE html>
<html lang="es"><?php
  $galeria_query=mysqli_query($link,"SELECT * FROM miscelaneos_conceptos where adultos is NULL and status='1' and publicar='S'  ");
   ?>
  <main id="main">
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Artículos Escolares</h2>
        <p>Le damos la mas cordial bienvenida de parte de toda la gran familia UEP <?= EKKS ?></p>
      </div>
    </div><!-- End Breadcrumbs -->
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      
      <div class="container" data-aos="fade-up">
        <div class="row mt-5"><?php
          $van=0;
          while($row=mysqli_fetch_array($galeria_query))
          {
            $concepto=$row['concepto'];
            $monto=$row['monto'];
            $foto_articulo=$row['foto_articulo'];
            $van++; ?>
            <div class="col-md-4 text-center">
              <img onclick="ver('<?= $concepto ?>','<?= $monto ?>','<?= $foto_articulo ?>')" src="<?= 'fotoArticulos/'.$foto_articulo ?>" style="width:100%; height: auto; cursor: pointer;">
            </div><?php
            if($van==3)
            {?>
              <div class="col-md-12" style="margin-bottom: 2%; ">
                <span> </span>
              </div><?php
              $van=0;
            }
            /*$galeria_query2=mysqli_query($link,"SELECT * FROM galeria where id_grupo='$id' ");
            while($row=mysqli_fetch_array($galeria_query2))
            {
              $id=$row['id'];
              $imagen=$row['imagen'];
              $van++;?>
              <div class="col-md-4 text-center">
                <img onclick="ver('<?= $id ?>','<?= $titulo ?>','<?= $descripcion ?>','<?= $fecha ?>','<?= $imagen ?>')" src="<?= 'galeria/'.$imagen ?>" style="width:100%; height: auto; cursor: pointer;">
              </div><?php 
              if($van==3)
              {?>
                <div class="col-md-12" style="margin-bottom: 2%; ">
                  <span> </span>
                </div><?php
                $van=0;
              }
            }*/
          }
          ?>
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