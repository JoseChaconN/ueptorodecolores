<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  $cedula=$_SESSION['usuario'];
  if(isset($_POST['enviar']))
  {
    $nacion = $_POST['nacion'];  
    $clave = $_POST['clave'];
    $nombre = $_POST['nombre'];  
    $apellido = $_POST['apellido'];
    $fechanac = $_POST['fechanac'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    
    $foto = (empty($_FILES['foto']['tmp_name'])) ? '' : addslashes(file_get_contents($_FILES['foto']['tmp_name'])); 
    $nombrearchivo = $_FILES["foto"]["name"];
    $nombreruta = $_FILES["foto"]["tmp_name"];
    $ext = substr($nombrearchivo, strrpos($nombrearchivo, '.'));
    $formatos = array('.jpg','.jpeg','.png' );
    $ruta = "$cedula$ext";
    $guardaRutaAlu='fotodoc/'.$ruta;  
    if(!empty($foto) && in_array($ext, $formatos))
    {
      move_uploaded_file($nombreruta, $guardaRutaAlu);
    } 

    mysqli_query($link,"UPDATE alumcer SET nacion='$nacion', apellido='$apellido', nombre='$nombre', FechaNac='$fechanac', direccion='$direccion', telefono='$telefono', correo='$correo', clave='$clave'  WHERE cedula = '$cedula' ") or die ("NO ACTUALIZO DOCENTE ".mysqli_error($link));
    if (!empty($foto) && in_array($ext, $formatos))
    {
      mysqli_query($link,"UPDATE alumcer SET ruta='$ruta' WHERE cedula='$cedula'");
    }

  }
  $result = mysqli_query($link,"SELECT nacion, cedula, clave, nombre, apellido, FechaNac, direccion, telefono, correo, ruta FROM alumcer WHERE cedula='$cedula' and cargo>0");
  while ($row = mysqli_fetch_array($result))
  {   
    $nacion = $row['nacion'];  
    $clave = $row['clave'];
    $nombre = ($row['nombre']);  
    $apellido = ($row['apellido']);
    $fechanac = $row['FechaNac'];
    $direccion = ($row['direccion']);
    $telefono = $row['telefono'];
    $correo = $row['correo'];
    $periodo = $row['Periodo'];
    $nomarch = $row['ruta'];
  } ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Datos Personales del Docente</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <form role="form" method="POST" enctype="multipart/form-data" action="" >
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-12 form-group text-center">
                <output id="list"><?php 
                  if(empty($nomarch)) 
                  { ?>
                    <img class='thumb from-group img-circle' src="../imagenes/fotocarnet.jpg" /> <?php 
                  } else 
                  { ?>
                    <img class='thumb from-group img-circle' src="<?= 'fotodoc/'.$nomarch.'?'.time().mt_rand(0, 99999) ?>" /><?php 
                  } ?>
                </output><br><br>
                <label class="btn btn-info">Foto Docente<input type="file" name="foto" id="files" style="display: none;"></label>
              </div>
              <div class="col-md-1 form-group">
                <label for="nac_alu" >Nac.</label>
                <input type="text" name="nacion" maxlength="2" required size="1" value="<?= $nacion; ?>" class="form-control">
              </div>
              <div class="col-md-2 form-group">
                <label>Cedula</label>
                <input type="text"  onkeypress="return ValCed(event)" name="cedula" readonly value="<?= $cedula; ?>" class="form-control" >
              </div>
              <div class="col-md-3 form-group">
                <label for="cla_alu">Contraseña</label>
                <input type="text" name="clave" required class="form-control" value="<?= $clave ?>">
              </div>
              <div class="col-md-2 form-group">
                <label for="fechanac" >Fecha de Nacimiento</label>
                <input type="date" name="fechanac" max="<?php $fechahoy; ?>" value = "<?= $fechanac ?>" class="form-control">
              </div>
              <div class="col-md-4 form-group">
                <label for="correo">Email</label>
                <input type="email" required="" title="Ingrese un correo valido ya que con el podra recuperar su contraseña" name="correo" class="form-control" size="20" value="<?= $correo ?>" >
              </div>
            </div>
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-6 form-group">
                <label for="nombre" >Nombres</label>
                <input type="text" required="" name="nombre" size="50" value="<?= $nombre; ?>" class="form-control" >
              </div>
              <div class="col-md-6 form-group">
                <label for="apellido" >Apellidos</label>
                <input type="text" required="" name="apellido" size="50" class="form-control" value="<?= $apellido ?>">
              </div>
            </div>
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-3 form-group">
                <label for="telefono" >Celular</label>
                <input type="text" name="telefono" id="telefono" onClick="this.select()" value="<?= $telefono ?>" class="form-control" >
              </div>
              <div class="col-md-9 form-group">
                <label for="direccion" >Direccion</label>
                <input type="text" name="direccion" size="50" class="form-control" value="<?= $direccion ?>">
              </div>
            </div>

            <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
              <button type="submit" value="1" name="enviar" class="btn btn-success btn-lg"><i class="ri-upload-cloud-line"></i> Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script src="../includes/jquery.maskedinput/src/jquery.mask.js" type="text/javascript"></script>
  <script type="text/javascript">
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
            text: 'El tamaño de la imagen supera el permitido por el Sistema verifique. (Maximo permitido 200kb)'
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

    $("#telefono").mask("????-???.??.??");
  </script>

</body>

</html>