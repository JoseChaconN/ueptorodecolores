<?php
session_start();
/*if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}*/
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  if(isset($_POST['usuario']))
  {
    $usuario=$_POST['usuario'];
    $passwordalum=$_POST['passwordalum'];
    $usuario_query = mysqli_query($link, "SELECT idAlum,cedula,admin,nombre,apellido,cargo,grado, seccion,consulVoto,Periodo,morosida,pagado,reinscribe,ruta FROM alumcer WHERE cedula = '$usuario' and clave = '$passwordalum' and statusAlum='1' "); 
    $count = 0; 
    while ($row = mysqli_fetch_array($usuario_query))
    { 
        $_SESSION['idAlum']=$row['idAlum'];
        $_SESSION['admin'] = $row['admin'];
        $_SESSION['nomuser'] = $row['nombre'];
        $_SESSION['apelluser'] = $row['apellido'];
        $_SESSION['cargo'] = $row['cargo'];
        $_SESSION['grado'] = $row['grado'];
        $_SESSION['seccion'] = $row['seccion'];
        $_SESSION['morosida'] = $row['morosida'];
        $_SESSION['pagado'] = $row['pagado'];
        $_SESSION['fotoAlum'] = $row['ruta'];
        $_SESSION['consulVoto'] = $row['consulVoto'];
        $_SESSION['reinscribe'] = $row['reinscribe'];
        $_SESSION['usuario'] = $_POST['usuario'];
        $_SESSION['password'] = $_POST['passwordalum'];
        $periodo = $row['Periodo'];
        $count++; 
    }
    if($count>0)
    {
      $periodoAlum_query=mysqli_query($link,"SELECT tablaPeriodo, nombre_periodo FROM  periodos where nombre_periodo='$periodo'"); 
      while($row=mysqli_fetch_array($periodoAlum_query))
      {
          $_SESSION['periodoAlum'] = $row['tablaPeriodo'];    
          $_SESSION['nombre_periodo'] = $row['nombre_periodo']; 
          $periodoAlum=$row['tablaPeriodo'];
      }
      
      $periodo_query=mysqli_query($link,"SELECT tablaPeriodo, nombre_periodo FROM  periodos where activoPeriodo='1'"); 
      while($row=mysqli_fetch_array($periodo_query))
      {
          $_SESSION['tablaPeriodo']=trim($row['tablaPeriodo']);
          $_SESSION['periodoActivo']=trim($row['nombre_periodo']);
      }
    }
  }
  include_once "header.php"; ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Verificación de sesión activa</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row"><?php 
          if(!isset($_SESSION["usuario"])) 
          { ?>
            <form role="form" method="POST" enctype="multipart/form-data" action="" >
              <div style="margin-top: 2%;">
                <div class="col-md-4 offset-4 form-group text-center">
                  <h4>Su sesión a expirado!</h4>
                </div>
                <div class="col-md-4 offset-4 form-group">
                  <label for="recipient-name" class="col-form-label"><i class="ri-user-follow-line"></i> Cedula del Estudiante:</label>
                  <input type="text" required class="form-control" placeholder="Ingrese solo numeros" id="usuario" name="usuario">
                </div>
                <div class="col-md-4 offset-4 form-group">
                  <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Contraseña:</label>
                  <input type="password" required class="form-control" id="passwordalum" name="passwordalum">
                </div>
              </div>
              <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
                <button type="submit" value="1" name="enviar" class="btn btn-info btn-lg"><i class="ri-key-2-fill"></i> Ingresar nuevamente</button>
              </div>
            </form><?php 
          }else 
          {?>
            <div class="col-md-6 offset-3 form-group text-center">
              <h3>Excelente su sesión continua activa,<br>cierre esta ventana y envíe sus archivos.</h3>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
              <button type="button" onclick="javascript:window.close();opener.window.focus();" class="btn btn-warning btn-lg"><i class="ri-key-2-fill"></i> Cerrar Ventana</button>
            </div><?php
          }?>
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>

</html>