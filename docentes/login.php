<?php
session_start();
if(isset($_SESSION["usuario"]) && isset($_SESSION['passwordalum'])) 
{
  if ($_SESSION['admin']=='SA' || $_SESSION['admin']=='SE' || $_SESSION['admin']=='SC')
    {
        header("location:../admin/index.php");        
    }else
    {
        if($_SESSION['cargo']>1 )
        { header("location:index.php"); }else
        { header("location:../index.php"); }
    }
}
$profesor = $_GET["ced_prof"];  
$lapsoMod=$_GET['lapsoMod'];
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include("../inicia.php");
include("../conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  function verificar_login($useralum,$passwordalum,&$result)
  { 
    $link = Conectarse(); 
    $result = mysqli_query($link,"SELECT admin, nombre, apellido, cargo, editable FROM alumcer WHERE cedula = '$useralum' and clave = '$passwordalum' and statusAlum='1' ORDER BY cedula ASC"); 

    $count = 0; 
    while ($row = mysqli_fetch_array($result))
    { 
      $_SESSION['admin'] = $row['admin'];
      $_SESSION['cargo'] = $row['cargo'];
      $_SESSION['nomuser'] = $row['nombre'];
      $_SESSION['apelluser'] = $row['apellido'];
      $_SESSION['editable'] = $row['editable'];
      $count++; 
    } 
    $periodo_query=mysqli_query($link,"SELECT tablaPeriodo,nombre_periodo FROM  periodos where activoPeriodo='1'"); 
    while($row=mysqli_fetch_array($periodo_query))
    {
        $_SESSION['tablaPeriodo']=trim($row['tablaPeriodo']);
        $_SESSION['nombre_periodo'] = $row['nombre_periodo']; 
    } 
    if($count == 1) 
    { 
      $user2 = $useralum;
      return 1; 
    }else 
    { 
      return 0; 
    } 
  }
  if(!isset($_SESSION['userid'])) 
  {
    if(isset($_POST['login'])) 
    {
      if(verificar_login($_POST['useralum'],$_POST['passwordalum'],$result) == 1) 
      {
        $_SESSION['userid'] = $result->idusuario; 
        $_SESSION['usuario'] = $_POST['useralum'];
        $_SESSION['password'] = $_POST['passwordalum'];
        if($_SESSION['admin']=='SA' || $_SESSION['admin']=='SE')
        { ?>
          <script type="text/javascript">
            location.href="listmateprof.php?ced_prof=<?php echo $profesor ?>&lapsoMod=<?php echo $lapsoMod ?>";
          </script><?php
        }else
        {
          if($_SESSION['cargo']>1 )
          { ?>
            <script type="text/javascript">
              location.href="index.php?";
            </script><?php
            //header("location:index.php"); 
          }
        }
      }else 
      { ?> 
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2><div class="alert alert-danger text-center" role="alert">¡Usuario o Contraseña Incorrectos!</div></h2>
            </div>
          </div>
        </div><?php
      } 
    }
  }  ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Acceso al sistema docente</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          
          <form role="form" method="POST" enctype="multipart/form-data" action="" >
            <div class="rowx" style="margin-top: 2%;">
              <div class="col-md-4 form-group offset-4">
                <h3><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Usuario<input name="useralum" type="text" class="form-control" placeholder="Usuario"></h3>
              </div>
              <div class="col-md-4 form-group offset-4">
                <h3>Contraseña<input name="passwordalum" placeholder="********" type="password" class="form-control"></h3>
              </div>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
              <button type="submit" value="1" name="login" class="btn btn-success btn-lg"><i class="ri-upload-cloud-line"></i> Entrar</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>

</html>