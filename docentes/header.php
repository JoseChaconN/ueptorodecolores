<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Docentes <?= $_SESSION['nomuser'] ?></title>
  <meta content="Docentes" name="description">
  <meta content="colegio, Inscripcion, liceo, escuela, bachillerato, primaria, <?= CIUDADM ?>, educacion, ciencias, deportes,<?= NKXS.' '.EKKS ?>,jesistemas.com" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/logo.png?3" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css?7" rel="stylesheet">
  <script type="text/javascript">
  var _smartsupp = _smartsupp || {};
  _smartsupp.key = '204a52fe22cf0bc6a2946217a1dfa17ad91c8629';
  window.smartsupp||(function(d) {
    var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
    s=d.getElementsByTagName('script')[0];c=d.createElement('script');
    c.type='text/javascript';c.charset='utf-8';c.async=true;
    c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
  })(document);
  </script>
</head><?php 
include_once("../includes/funciones.php");
$periodoActivo=$_SESSION['periodoActivo'];
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$lapsoActivo=$_SESSION['lapsoActivo'];
$cedula=$_SESSION["usuario"];
$nombre= $_SESSION['nomuser'];
$apelli=$_SESSION['apelluser'];
$idDoc=$_SESSION['idAlum']; ?>
<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
      <h4 class="logox me-auto"><a href="index.php"><img id="logoColegio" src="../assets/img/logo.png?3" style="width:10%; height: auto;"><span id="colegio" style="color: #FFF;">UEP Colegio <?= EKKS ?></span></a></h4>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="perfil.php">Perfil</a></li>
          <li><a href="carnet.php" target="_blank">Carnet</a></li>
          <li><a href="tutoYou.php">Tutorial</a></li>
          <li class="dropdown"><a href="#"><span>Alumnos</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li class="dropdown"><a href="#"><span>Primaria</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosTar">Material Clases</a></li>
                  <li><a data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosVid">Videos</a></li>
                  <li><a data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosNot">Notas</a></li>
                  <li><a data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosCom">Mensajes</a></li>
                </ul>
              </li>
              <li class="dropdown"><a href="#"><span>Bachillerato</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="list-materias.php">Material Clases</a></li>
                  <li><a href="list-video-materias.php" >Videos</a></li>
                  <li><a href="list-mate-prof.php">Notas</a></li>
                  <li><a data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosBach">Mensajes</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li><a href="../cierra.php">Salir</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header><!-- End Header -->
  <div class="modal fade" id="gradosTar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Seleccione</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="list-tareas-pri.php" >
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Grado:</label>
              <select name="grado" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required="" >  
                <option value="">Seleccione....</option><?php
                $result = mysqli_query($link,"SELECT A.*, B.nombreGrado, B.grado FROM trgsp".$tablaPeriodo." A, grado".$tablaPeriodo." B WHERE A.ced_prof = '$cedula' and ( A.id_grado1=B.grado or A.id_grado2=B.grado) GROUP BY B.grado");
                while($row = mysqli_fetch_array($result))
                {
                  $nom_gra=utf8_encode($row['nombreGrado']);
                  $id_gra=$row['grado'];
                  echo '<option value="'.$id_gra.'">'.$nom_gra.'</option>';
                } ?>                                 
              </select>
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Sección:</label>
              <select name="seccion" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required>  
                <option value="">Seleccione....</option><?php
                 $result1 = mysqli_query($link,"SELECT A.*, B.nombre, B.id FROM trgsp".$tablaPeriodo." A, secciones B WHERE A.ced_prof = '$cedula' and ( A.id_seccion1=B.id or A.id_seccion2=B.id) GROUP BY nombre");
                while($row1 = mysqli_fetch_array($result1))
                {
                  $nom_secdsd=utf8_encode($row1['nombre']);
                  $id_secdsd=$row1['id'];
                  echo '<option value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                } ?>                                 
              </select>
            </div>
            <div class="col-md-12">
              <button type="submit" style="width:100%;" class="btn btn-primary"><i class="ri-key-2-fill"></i> Buscar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="gradosVid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Seleccione</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="list-videos-pri.php" >
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Grado:</label>
              <select name="grado" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required="" >  
                <option value="">Seleccione....</option><?php
                $result = mysqli_query($link,"SELECT A.*, B.nombreGrado, B.grado FROM trgsp".$tablaPeriodo." A, grado".$tablaPeriodo." B WHERE A.ced_prof = '$cedula' and ( A.id_grado1=B.grado or A.id_grado2=B.grado) GROUP BY B.grado");
                while($row = mysqli_fetch_array($result))
                {
                  $nom_gra=utf8_encode($row['nombreGrado']);
                  $id_gra=$row['grado'];
                  echo '<option value="'.$id_gra.'">'.$nom_gra.'</option>';
                } ?>                                 
              </select>
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Sección:</label>
              <select name="seccion" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required>  
                <option value="">Seleccione....</option><?php
                 $result1 = mysqli_query($link,"SELECT A.*, B.nombre, B.id FROM trgsp".$tablaPeriodo." A, secciones B WHERE A.ced_prof = '$cedula' and ( A.id_seccion1=B.id or A.id_seccion2=B.id) GROUP BY nombre");
                while($row1 = mysqli_fetch_array($result1))
                {
                  $nom_secdsd=utf8_encode($row1['nombre']);
                  $id_secdsd=$row1['id'];
                  echo '<option value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                } ?>                                 
              </select>
            </div>
            <div class="col-md-12">
              <button type="submit" style="width:100%;" class="btn btn-primary"><i class="ri-key-2-fill"></i> Buscar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="gradosNot" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Seleccione</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="list-alumnos-pri.php" >
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Grado:</label>
              <select name="grado" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required="" >  
                <option value="">Seleccione....</option><?php
                $result = mysqli_query($link,"SELECT A.*, B.nombreGrado, B.grado FROM trgsp".$tablaPeriodo." A, grado".$tablaPeriodo." B WHERE A.ced_prof = '$cedula' and ( A.id_grado1=B.grado or A.id_grado2=B.grado) GROUP BY B.grado");
                while($row = mysqli_fetch_array($result))
                {
                  $nom_gra=utf8_encode($row['nombreGrado']);
                  $id_gra=$row['grado'];
                  echo '<option value="'.$id_gra.'">'.$nom_gra.'</option>';
                } ?>                                 
              </select>
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Sección:</label>
              <select name="seccion" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required>  
                <option value="">Seleccione....</option><?php
                 $result1 = mysqli_query($link,"SELECT A.*, B.nombre, B.id FROM trgsp".$tablaPeriodo." A, secciones B WHERE A.ced_prof = '$cedula' and ( A.id_seccion1=B.id or A.id_seccion2=B.id) GROUP BY nombre");
                while($row1 = mysqli_fetch_array($result1))
                {
                  $nom_secdsd=utf8_encode($row1['nombre']);
                  $id_secdsd=$row1['id'];
                  echo '<option value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                } ?>                                 
              </select>
            </div>
            <div class="col-md-12">
              <button type="submit" style="width:100%;" class="btn btn-primary"><i class="ri-key-2-fill"></i> Buscar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="gradosCom" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Seleccione</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="listaAlumPri.php" >
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Grado:</label>
              <select name="grado" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required="" >  
                <option value="">Seleccione....</option><?php
                $result = mysqli_query($link,"SELECT A.*, B.nombreGrado, B.grado FROM trgsp".$tablaPeriodo." A, grado".$tablaPeriodo." B WHERE A.ced_prof = '$cedula' and ( A.id_grado1=B.grado or A.id_grado2=B.grado) GROUP BY B.grado");
                while($row = mysqli_fetch_array($result))
                {
                  $nom_gra=utf8_encode($row['nombreGrado']);
                  $id_gra=$row['grado'];
                  echo '<option value="'.$id_gra.'">'.$nom_gra.'</option>';
                } ?>                                 
              </select>
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Sección:</label>
              <select name="seccion" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required>  
                <option value="">Seleccione....</option><?php
                 $result1 = mysqli_query($link,"SELECT A.*, B.nombre, B.id FROM trgsp".$tablaPeriodo." A, secciones B WHERE A.ced_prof = '$cedula' and ( A.id_seccion1=B.id or A.id_seccion2=B.id) GROUP BY nombre");
                while($row1 = mysqli_fetch_array($result1))
                {
                  $nom_secdsd=utf8_encode($row1['nombre']);
                  $id_secdsd=$row1['id'];
                  echo '<option value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                } ?>                                 
              </select>
            </div>
            <div class="col-md-12">
              <button type="submit" style="width:100%;" class="btn btn-primary"><i class="ri-key-2-fill"></i> Buscar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="gradosBach" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Seleccione</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="listaAlumBach.php" >
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Grado:</label>
              <select name="grado" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required="" >  
                <option value="">Seleccione....</option><?php
                $result = mysqli_query($link,"SELECT A.*, B.nombreGrado, B.grado FROM trgsmp".$tablaPeriodo." A, grado".$tablaPeriodo." B WHERE A.cod_grado>60 and A.ced_prof = '$cedula' and A.cod_grado=B.grado GROUP BY A.cod_grado");
                while($row = mysqli_fetch_array($result))
                {
                  $nom_gra=($row['nombreGrado']);
                  $id_gra=$row['grado'];
                  echo '<option value="'.$id_gra.'">'.$nom_gra.'</option>';
                } ?>                                 
              </select>
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Sección:</label>
              <select name="seccion" class="form-control" data-style="btn-info" data-live-search="true" data-width="fit" required>  
                <option value="">Seleccione....</option><?php
                 $result1 = mysqli_query($link,"SELECT A.*, B.nombre, B.id FROM trgsmp".$tablaPeriodo." A, secciones B WHERE A.cod_grado>60 and A.ced_prof = '$cedula' and A.cod_seccion=B.id GROUP BY B.nombre");
                while($row1 = mysqli_fetch_array($result1))
                {
                  $nom_secdsd=utf8_encode($row1['nombre']);
                  $id_secdsd=$row1['id'];
                  echo '<option value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                } ?>                                 
              </select>
            </div>
            <div class="col-md-12">
              <button type="submit" style="width:100%;" class="btn btn-primary"><i class="ri-key-2-fill"></i> Buscar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
    </div>
  </div>
  