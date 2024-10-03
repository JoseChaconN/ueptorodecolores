<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
if($_SESSION['morosida']>0)
{ 
  header("location:index.php?moro"); 
}
if($_SESSION['pagado']==0)
{ 
  header("location:index.php?sinpago"); 
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse();
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$usuario = $_SESSION['usuario'];
$lapsom = (isset($_GET['lapsom'])) ? $_GET['lapsom'] : ""; 

$result = mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.grado, A.pagado, A.ruta, A.Periodo, B.nombreGrado, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C WHERE cedula ='$usuario' and A.grado=B.grado and A.seccion=C.id"); 
while ($row = mysqli_fetch_array($result))
{ 
  $cedula = $row['cedula'];
  $gra_alu = $row['grado'];
  $susb=substr($gra_alu,0,1);
  $foto_alu = 'fotoalu/'.$row['ruta'];
  $alumno=$row['nombre'].' '.$row['apellido'];
  $nomGra=($row['nombreGrado']);
  $nomSec=$row['nomsec'];
  $periodo=$row['Periodo'];
  $ruta = $row['ruta'];
} ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Visualizar Calificaciones<br>Cursando el Periodo escolar <?= $periodo.' '.$_SESSION['morosida']; ?></h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="row">
            <div class="col-md-12 form-group text-center">
              <img src="<?= $foto_alu ?>" class="thumb" />
            </div>
          </div>
          <div class="row" style="margin-top: 2%;">
            <div class="col-md-2 form-group">
              <label>Cedula</label>
              <input type="text" class="form-control" readonly="" value="<?= $cedula; ?>" >
            </div>
            <div class="col-md-5 form-group">
              <label>Estudiante</label>
              <input type="text" readonly="" class="form-control" value="<?= $alumno; ?>">
            </div>
            <div class="col-md-3 form-group">
              <label>Grado o Año</label>
              <input type="text" readonly="" class="form-control" value="<?= $nomGra.' sección '.$nomSec; ?>">
            </div>
            <div class="col-md-2 form-group">
              <label>Periodo</label>
                <select class="form-control" id="tabla">
                  <option value="2324" selected>2023-2024</option>
                </select>
            </div>
          </div>
          <div class="row" style="margin-top: 2%;">
            <div class="col-md-4 offset-2 form-group">
              <label>Consultar el:</label>
              <select class="form-control" id="lapso">
                <option value="1">1er. Momento</option>
                <option value="2">2do. Momento</option>
                <option value="3">3er. Momento</option>
              </select>
            </div>
            <div class="col-md-4 form-group"><?php 
              if(empty($ruta) || $ruta==NULL || $ruta==''){?>
                <br><button style="width:100%;" title="Para poder imprimir su boletin de calificaciones debe agregar una foto del estudiante" type="button" onclick="subeFoto()" class="btn btn-success">Cargar Foto</button> <?php 
              }else{?>
                <br><button style="width:100%;" type="button" onclick="imprime()" class="btn btn-success">Imprimir</button> <?php 
              }?>
            </div>
          </div>
          <input type="hidden" id="grado" value="<?= $gra_alu ?>">
          <input type="hidden" id="cedula" value="<?= encriptar($cedula); ?>" >
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <div class="modal fade" id="subeFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Foto del Estudiante</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form role="form" method="POST" onsubmit="return validacion()" enctype="multipart/form-data" action="subefoto.php">
          <div class="modal-body">
            <div class="col-md-12 text-center">
              <output id="list">
                <img class='thumb from-group img-circle' src="imagenes/fotocarnet.jpg" />
              </output><br><br>
              <label class="btn btn-info">Buscar Foto<input type="file" name="foto_alu" id="files" style="display: none;"></label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success"> Guardar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    function imprime() 
    {
      ced=$('#cedula').val()
      per=$('#tabla').val()
      lap=$('#lapso').val()
      gra=$('#grado').val()
      if(gra>60)
      {
        window.open('bole-liceo.php?lapsom='+lap+'&peri='+per)
      }
      if(gra>50 && gra<60)
      {
        window.open('bole-primaria.php?lapsom='+lap+'&peri='+per)
      }
      if(gra<50)
      {
        window.open('bole-inicial.php?lapsom='+lap+'&peri='+per)
      }
    }
    function subeFoto() {
      $('#subeFoto').modal('show')
    }
    function archivo(evt) 
    {
      var files = evt.target.files; // FileList object
      for (var i = 0, f; f = files[i]; i++) 
      {
        if (!f.type.match('image.*')) 
        {
            alert("FORMATO DE IMAGEN INCORRECTO");
            continue;
        }
        var sizeByte = this.files[0].size;
        var siezekiloByte = parseInt(sizeByte / 1024);
        if(siezekiloByte > 200)
        {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            html: 'La imagen excede el peso permitido por el sistema<br>(Máximo permitido 200kb)<br>nota: vea como capturar foto'
          })
          continue;
        }
        //HASTA AQUI
        var reader = new FileReader();
        reader.onload = (function(theFile) 
        {
          return function(e) 
          {
            document.getElementById("list").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
          };
        })(f);
        reader.readAsDataURL(f);
      }         
    }
    document.getElementById('files').addEventListener('change', archivo, false); 
    function validacion() {
      fot=$('#files').val()
      if (fot=='') {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Por favor debe agregar una foto para poder ver su boletin de calificaciones'
        })
        return false;
      }
    }
  </script>

</body>

</html>