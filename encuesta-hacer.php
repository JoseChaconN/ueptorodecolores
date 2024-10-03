<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$fecha_hoy = date("Y-m-d H:i:s");
include_once("inicia.php");
include_once("conexion.php");
include_once "header.php";
$link = conectarse();
$idAlum=$_SESSION['idAlum'];
$nombrePeriodo=$_SESSION['nombre_periodo'];
$tablaPeriodo=$_SESSION['periodoAlum'];
$usuario = $_SESSION['usuario'];
$id_encuesta=desencriptar($_GET['idEnc']);
$encuesta_query=mysqli_query($link,"SELECT titulo_enc,descripcion FROM encuesta WHERE id_encuesta='$id_encuesta'  ");
$row=mysqli_fetch_array($encuesta_query);
$titulo_enc=$row['titulo_enc'];
$descripcion=$row['descripcion'];
mysqli_free_result($encuesta_query);
$result = mysqli_query($link,"SELECT A.nombre AS alumno ,B.nombreGrado AS nom_gra,  A.cedula, A.apellido, A.ruta as foto_alu, A.grado, A.Periodo, A.reinscribe, A.seccion, C.ruta as foto_rep FROM alumcer A,grado".$tablaPeriodo." B,represe C WHERE A.idAlum = '$idAlum' and B.grado = A.grado and C.cedula = A.ced_rep "); 
while ($row = mysqli_fetch_array($result))
{   
  $cedula = $row['cedula'];
  $nombre = $row['alumno'];  
  $apellido = $row['apellido'];
  $nom_gra = ($row['nom_gra']);
  $foto_alu = 'fotoalu/'.$row['foto_alu'];
  $foto_rep = 'fotorep/'.$row['foto_rep'];
  $grado = $row['grado'];
  $seccion = $row['seccion'];
  $periodo = $row['Periodo'];
  $reinscribe=$row['reinscribe'];
}
mysqli_free_result($result);
?>
<!DOCTYPE html>
<html lang="es">
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h3>Encuestas Escolares <?= $nombrePeriodo ?></h3>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col text-center">
            <img class='thumb from-group img-circle'  src="<?= $foto_alu; ?>" />    
            <img class='thumb from-group img-circle'  src="<?= $foto_rep; ?>" /> 
          </div>
        </div>
        <div class="row" style="margin-top:2%;">
          <div class="col-md-2 form-group">
            <label>Cedula</label>
            <label class="form-control"><?= $cedula;?></label>
          </div>
          <div class="col-md-4 form-group">
            <label>Nombres</label>
            <label class="form-control"><?= $nombre;?></label>
          </div>
          <div class="col-md-4 form-group">
            <label>Apellidos</label>
            <label class="form-control"><?= $apellido;?></label>
          </div>
          <div class="col-md-2 form-group">
            <label>Cursante</label>
            <label class="form-control"><?= $nom_gra;?></label>
          </div>
        </div>
        <style type="text/css">
          @media (max-width: 768px) {
            .text_movil {
              font-size: 12px !important;
              font-weight: normal !important;
              text-align: center !important;
            }
            .movil_p {
              font-size: 14px;
            }
          }
          @media (min-width: 769px) {
            .movil_p {
              font-weight: bold;
            }
          }
        </style>
        <div class="panel-heading text-center" style="margin-top: 1%;"><h3><?= $titulo_enc ?> </h3></div>
        <div class="row" style="margin-top:2%;">
          <form method="POST" action="encuesta-guarda.php" enctype="multipart/form-data">
            <div class="col-md-12" style="margin-top: 1%; ">
              <textarea rows="4" class="form-control text_movil" style="font-size: 20px; font-weight: bold; "><?= $descripcion ?></textarea>
            </div>
            <div class="col-md-12 text-center row" style="margin-top:1%;">
              <div class="col" style="margin-bottom: 1%; ">
                <button type="button" style="width: 100%; " onclick="atras()" id="antes" class="btn btn-info">Anterior</button> 
              </div>
              <div class="col" style="margin-bottom: 1%; ">
                <button type="button" style="width: 100%; " onclick="avanza()" id="sigue" class="btn btn-info">Siguiente</button>
              </div>
              <div class="col" style="margin-bottom: 1%; ">
                <button type="submit" style="width: 100%; " id="enviaEnc" class="btn btn-primary">Enviar</button>
              </div>
              <div class="col" style="margin-bottom: 1%; ">
                <a href="encuesta-lista.php"><button style="width: 100%; " type="button" class="btn btn-warning">Salir</button></a>
              </div>
              <div class="col-md-12" style="margin-bottom: 1%; "><hr></div>
            </div>

            <input type="hidden" name="idEnc" value="<?= encriptar($id_encuesta) ?>" ><?php 
            $pregunta_query=mysqli_query($link,"SELECT * FROM encuesta_pregunta WHERE id_encuesta='$id_encuesta' ");
            $va=0; $simple=0; $multi=0; $texto=0;
            while ($row = mysqli_fetch_array($pregunta_query))
            {
              $va++;
              $id_pregunta=$row['id_pregunta'];
              $pregunta=$row['pregunta'];
              $comentario=$row['comentario'];
              $tipo_pregunta=$row['tipo_pregunta'];?>
              <input type="hidden" id="<?= 'tipo_pregunta'.$va ?>" value="<?= $tipo_pregunta ?>">
              <div class="col-md-8 offset-md-2 row" id="<?= 'div_preg'.$va ?>" style="margin-top: 2%;  " >
                <input type="hidden" name="<?= 'id_pregunta'.$va ?>" value="<?= encriptar($id_pregunta) ?>" >
                <p class="movil_p" ><?= $va.'-) '.$pregunta ?></p><?php 
                $preguntas_query=mysqli_query($link,"SELECT * FROM encuesta_preguntas WHERE id_pregunta='$id_pregunta' ");
                $va2=0;
                while ($row2 = mysqli_fetch_array($preguntas_query))
                {
                  $va2++;
                  $id_preguntas=$row2['id_preguntas'];
                  $opcion=$row2['opcion'];
                  $respuestas_query=mysqli_query($link,"SELECT * FROM encuesta_respuesta WHERE id_pregunta='$id_pregunta' and id_encuesta='$id_encuesta' and id_alum='$idAlum' ");
                  if(mysqli_num_rows($respuestas_query) > 0)
                  {
                    while ($row3 = mysqli_fetch_array($respuestas_query))
                    {
                      $respuesta=$row3['respuesta'];
                      $comentaAlum=$row3['comentario'];
                      if($respuesta==$id_preguntas){?>
                        <div class="col-md-12 " >
                          <p><?= $opcion ?></p>
                        </div><?php
                      }
                    }
                  }else{
                    $respuesta=''; $comentaAlum='';
                    if($tipo_pregunta==1){?>
                      <div class="col-md-12" style="margin-bottom: 1%; ">
                        <label class="form-control"><input type="radio" style="transform: scale(1.8);" id="<?= 'preg'.$va.$va2 ?>" name="<?= 'simple'.$va ?>" value="<?= encriptar($id_preguntas) ?>" <?php if($respuesta==$id_preguntas){ echo "checked"; } ?> >&nbsp;&nbsp;&nbsp;<?= $opcion ?></label>
                      </div><?php
                      $simple++;
                    }
                    if($tipo_pregunta==2){?>
                      <div class="col-md-12" style="margin-bottom: 1%; ">
                        <label class="form-control"><input type="checkbox" style="transform: scale(1.8);" id="<?= 'preg'.$va.$va2 ?>" name="<?= 'multi'.$va.$va2 ?>" value="<?= encriptar($id_preguntas) ?>" <?php if($respuesta==$id_preguntas){ echo "checked"; } ?> >&nbsp;&nbsp;&nbsp;<?= $opcion ?></label>
                      </div><?php
                    }
                  }
                }
                if($tipo_pregunta==3){?>
                  <div class="col-md-12">
                    <textarea rows="3" required id="<?= 'texto'.$va ?>" name="<?= 'texto'.$va ?>" class="form-control" <?php if($respuesta!=''){ echo "readonly";} ?>><?= $respuesta ?></textarea>
                  </div><?php
                  $texto++;
                }
                if (!empty($comentario)) {?>
                  <div class="col-md-12">
                    <label><?= $comentario ?></label>
                    <textarea required id="<?= 'comenta'.$va ?>" name="<?= 'comenta'.$va ?>" rows="3" class="form-control" <?php if($comentaAlum!=''){ echo "readonly";} ?>><?= $comentaAlum ?></textarea>
                  </div><?php
                }?>
              </div>
              <input type="hidden" id="<?= 'respuesta'.$va ?>" value="<?= $respuesta ?>" >
              <input type="hidden" id="<?= 'pideCome'.$va ?>" value="<?= $comentario ?>">
              <input type="hidden" id="<?= 'preg'.$va ?>"  value="<?= $va2 ?>"><?php
            } ?>
            <input type="hidden" id="van" name="van" value="<?= $va ?>">
            <input type="hidden" id="mostrando" value="1">
          </form>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  
  <script type="text/javascript">
    $(document).ready( function () {
      hay=$('#van').val()
      $("#div_preg1"). css("display", "block")
      mos=$('#mostrando').val();
      if (mos==hay) {
        $("#enviaEnc").prop('disabled', false); //habilita
      }else{
        $("#enviaEnc").prop('disabled', true); //deshabilita
      }
      if(mos==1){
        $("#antes").prop('disabled', true);
      }else{
        $("#antes").prop('disabled', false);
      }
      if (mos==hay){
      $("#sigue"). css("display", "none")}else{$("#sigue"). css("display", "block")}
      for (var i = 2; i <=hay; i++) {
        $("#div_preg"+i). css("display", "none")  
      }
    } );
    function avanza() {
      mosVie=$('#mostrando').val();
      tip=$('#tipo_pregunta'+mosVie).val();
      nroPreg=$('#preg'+mosVie).val();
      come=$('#pideCome'+mosVie).val();
      resp=$('#respuesta'+mosVie).val();
      var opcionSeleccionada = false;
      if(tip==1){
        for (var ix = 1; ix <=nroPreg ; ix++) {
          if($("#preg"+mosVie+ix).prop('checked')) {
            opcionSeleccionada = true;
              break;
          }
        }
      }
      if(tip==2){
        for (var ix = 1; ix <=nroPreg ; ix++) {
          if($("#preg"+mosVie+ix).prop('checked')) {
            opcionSeleccionada = true;
              break;
          }
        }
      }
      if(tip==3){
        tex=$('#texto'+mosVie).val()
        if(tex!=''){
          opcionSeleccionada = true;
        }
      }
      if(come.length>0){
        comenta=$('#comenta'+mosVie).val()
        if(comenta.length==0){
          opcionSeleccionada = false; 
        }
      }
      // Validar si se seleccionó una opción
      if (!opcionSeleccionada && resp=='') {
        if(tip==1){
          Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: 'Debe seleccionar una opción, verifique!',
            showConfirmButton: false,
            timer: 2500
        })
        }
        if(tip==2){
          Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: 'Debe seleccionar al menos una opción, verifique!',
            showConfirmButton: false,
            timer: 2500
        })
        }
        if(tip==3){
          Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: 'Por favor responda la pregunta',
            showConfirmButton: false,
            timer: 2500
        })
        }
        return false;
      }else{
        mosNue=parseFloat($('#mostrando').val())+1;
        $("#div_preg"+mosVie). css("display", "none")
        $("#div_preg"+mosNue). css("display", "block")
          hay=$('#van').val();
          $('#mostrando').val(mosNue)
          $("#antes").prop('disabled', false);
          if (mosNue==hay) {
            $("#enviaEnc").prop('disabled', false);
            $("#sigue").prop('disabled', true);
        }else{
          $("#enviaEnc").prop('disabled', true);
        $("#sigue").prop('disabled', false);
      }
      }
    }
    function atras() {
      mosVie=$('#mostrando').val();
      mosNue=parseFloat($('#mostrando').val())-1;
      $("#div_preg"+mosVie). css("display", "none")
      $("#div_preg"+mosNue). css("display", "block")
        hay=$('#van').val();
        $('#mostrando').val(mosNue)
        $("#antes").prop('disabled', false);
        //$("#antes"). css("display", "block")
        if (mosNue==hay) {
          $("#enviaEnc").prop('disabled', false);
          //$("#enviaEnc"). css("display", "block")
          $("#sigue").prop('disabled', true);
      }else{
        $("#enviaEnc").prop('disabled', true);
      //$("#enviaEnc"). css("display", "none")
      $("#sigue").prop('disabled', false);
      }
      if(mosNue==1){
        $("#antes").prop('disabled', true);
      }else{
        $("#antes").prop('disabled', false);
      }
    }
    
  </script>

</body>

</html>