<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php#features");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  <style type="text/css">
    .eligex:hover{
      background: #46E82D !important;
      transform: scale(1.1);
    }
  </style>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Elecciones Internas</h2>
      </div>
    </div><!-- End Breadcrumbs --><?php 
    $eleccion_query = mysqli_query($link,"SELECT * FROM eleccion WHERE adultos is NULL and fechadesde<='$fechahoy' and IF(gradoDesde>0, '$grado'>=gradoDesde and '$grado'<=gradoHasta , fechadesde<='$fechahoy' ) and IF(seccionDesde>0, '$seccion'>=seccionDesde and '$seccion'<=seccionHasta,fechadesde<='$fechahoy' ) ORDER BY fechadesde DESC "); ?>


    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="row"><?php
            while($row=mysqli_fetch_array($eleccion_query)) 
            {
              $idEleccion=$row['idEleccion'];
              $nombreEleccion=$row['nombreEleccion'];
              $fotoEleccion=$row['fotoEleccion'].'?'.time().mt_rand(0, 99999);
              $fechahasta=date("d-m-Y", strtotime($row['fechahasta']));?>
              <div class="col-md-4 form-group text-center">
                <button class="btn" onclick="votar('<?= encriptar($idEleccion) ?>')" data-bs-toggle="modal" data-bs-target="#votar">
                <img src="<?= 'elecciones/'.$fotoEleccion ?>" class="thumbx" style="width: 100%; height:auto; " /></button>
                <p><?= $nombreEleccion.'<br>Cierra el: '.$fechahasta ?></p>
              </div><?php 
            }?>
          </div>
          
        </div>
      </div>
    </section>
    <div class="modal fade" id="votar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" style="max-width: 95%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="titulo"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 offset-md-4 text-center col-xs-12 col-sm-12">
              <img class='from-group' id="fotoEleccion" src="" style=" width: 100%; height: auto;" />
            </div>
            <div class="col-md-12 text-center" style="margin-top:2%;">
              <h3>Participantes a Elegir para: <h4 id="titulo2"></h4></h3>
            </div>
            <div class="row" id="divCandi" style="margin-top:2%;"></div>
          </div>
      </div>
    </div>
  </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    function votar(id) {
      $.post('votar.php',{'id':id},function(data)
      {
        if(data.isSuccessful)
        {
          document.querySelector('#titulo').innerText = data.eleccion;
          document.querySelector('#titulo2').innerText = data.eleccion;
          $("#fotoEleccion").attr("src","elecciones/"+data.foto);
          $("#divCandi").html(data.candidato);
        }else
        {
          document.querySelector('#titulo').innerText = data.eleccion;
          document.querySelector('#titulo2').innerText = data.eleccion;
          $("#fotoEleccion").attr("src","elecciones/"+data.foto);
          $("#divCandi").html(data.candidato);
          Swal.fire({
            icon: 'info',
            title: 'Información!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Usted ya eligio un candidato en este proceso'
        })
        }
      }, 'json');
    }
    function cerrar(nomb,idP,idE) {
      Swal.fire({
        title: 'Votar por este candidato?',
        text: nomb,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#E67E22',
        confirmButtonText: 'Si procesar mi voto',
        cancelButtonText: 'Elegir otro candidato'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('voto.php',{'id':idP,'idEl':idE},function(data)
          {
            if(data.isSuccessful)
            {
              $('#votar').modal('hide')
              Swal.fire(
                'Excelente!',
                'Su voto ha sido procesado',
                'success'
              )
            }else
            {
              Swal.fire(
                'Error!',
                'Su voto no fue procesado',
                'error'
              )
            }
          }, 'json');
          
        }
      })
    }
  </script><?php 
  mysqli_free_result($eleccion_query);?>
</body>

</html>