<?php
session_start();
session_destroy();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse(); 
$fecpreins=mysqli_query($link,"SELECT * FROM preinscripcion WHERE id = '1'");
while($row=mysqli_fetch_array($fecpreins))
{
  $fecinicio=$row['fecinicio'];
  $fecfinal=$row['fecfinal'];
} ?>

<!DOCTYPE html>
<html lang="es">
  <head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= EKKS ?></title>
  <meta content="<?=NKXS.' '.EKKS ?> ubicado en <?= DIRECCM.' '.CIUDADM.' tlf: '.TELEMPM ?>" name="description">
  <meta content="colegio, Inscripcion, liceo, escuela, bachillerato, primaria, <?= CIUDADM ?>, educacion, ciencias, deportes,<?=NKXS.' '.EKKS ?>,jesistemas.com,control de estudios" name="keywords">
  <!-- Favicons -->
  <link href="assets/img/logo.png?3" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css?0.4" rel="stylesheet">
</head>
<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
      <h4 class="logox me-auto"><a href="index.php"><img id="logoColegio" src="assets/img/logo.png?3" style="width:10%; height: auto; padding: 2px;  "><span id="colegio" style="color: #FFF;"> <?= 'U.E.P. '.EKKS ?></span></a></h4>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="index.php#why-us">Procesos</a></li>
          <li><a href="index.php#features">Reportes</a></li>
          <li><a href="index.php#bancos">Bancos</a></li>
          <li><a href="contacto.php">Contacto</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header><!-- End Header -->
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in" style="margin-top: 7%;">
      <div class="container">
        <h3>Preinscripción Nuevo Ingreso<br><?= PROXANOE ?></h3>
      </div>
    </div><!-- End Breadcrumbs --><?php 
    if($fecinicio>$fechahoy || $fechahoy > $fecfinal)
    { ?>
      <section id="about" class="about" style="background-color:#E0E0E0;">
        <div class="container" data-aos="fade-up">
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-info " role="alert">
                <h2>El Proceso de Inscripción se encuentra cerrado <?php 
                $fechaView = date("d-m-Y", strtotime($fecfinal)); ?>
                 </h2>
                <H3>Si es estudiante activo seleccione la opción INGRESAR</H3>
                <h3>Si es primera vez que ingresa a la página</h3>
                <H4>1- En USUARIO coloque el número de cedula del estudiante </H4>
                <h4>2- En contraseña los 2 primeros y los 3 ultimos de la cedula</h4>
                <hr>
                <h4>Ejemplo de contraseña: Cedula: 34123456 (Contraseña: 34456)</h4>
                <h4>Si el estudiante no tiene cedula de identidad ubíquelo con su cedula escolar</h4>
              </div>    
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center"><a href="index.php"><button type="btn" class="btn btn-warning btn-lg">Regresar</button></a>
            </div>
          </div>      
        </div>
      </section><?php 
    }else
    {
      $periodo_query=mysqli_query($link,"SELECT tablaPeriodo FROM  periodos where activoPeriodo='1'"); 
      while($row=mysqli_fetch_array($periodo_query))
      {
          $tablaPeriodo = trim($row['tablaPeriodo']);
      } ?>
      <style>
        .thumb {
        width: 150px;
        height: 150px;
        border: 1px ;
        margin: 10px 5px 0 0;
        border-radius: 30%;
        }
      </style>
      <section id="about" class="about" style="background-color:#E0E0E0;">
        <div class="container" data-aos="fade-up">
          <div class="row">
            <form role="form" method="POST" enctype="multipart/form-data" action="guardanuevos.php" onsubmit="return validacion()" autocomplete="off" name="preinscripcion" id="preinscripcion">
              <div class="row">
                <div class="col-md-3 form-group text-center" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="En caso de no poder cargar su foto, vea como hacerlo en el boton identificado<br>¿Como Capturar Foto?">
                  <output id="list"><img class='thumb from-group img-circle' src="imagenes/fotocarnet.jpg" /> </output><br><center><label class="btn btn-primary">Foto Alumno<input type="file" name="foto_alu" id="files" style="display: none;"></label></center>
                </div>
                <div class="col-md-3 form-group text-center" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="En caso de no poder cargar su foto, vea como hacerlo en el boton identificado<br>¿Como Capturar Foto?">
                    <output id="list1"><img class='thumb from-group img-circle' src="imagenes/fotocarnet2.jpg" /></output><br><center><label class="btn btn-primary">Foto representante<input type="file"  name="foto_rep" id="files1" style="display: none;"></label></center>
                </div>
                <div class="col-md-6 form-group text-center">
                  <label>INDICACIONES!!!</label>
                  <textarea rows="6" style="width: 100%; text-align: justify;">Si el alumno no posee cedula de Identidad debe registrarse con cedula escolar Ej. 11012266340 donde 1 = parto de único hijo (no morochos, gemelos etc.), 10 = es el año de nacimiento y 12266340 es la cedula de la madre, si la cedula de la madre es inferior a 10000000 debe colocar 0 hasta completar los 8 dígitos.</textarea><br><br>
                  <a href="lighshot.php" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Pulse aqui para ver tutorial de como capturar una foto" ><button type="button" class="btn-group btn btn-danger" role="group" aria-label="">¿Como Capturar Foto?</button></a>
                </div>
              </div>
              <!---------------- DATOS DEL ESTUDIANTE -------------->
              <div class="row" style="background-color:#FFF8E1; padding: 10px;">
                <div class="col-md-12 from-group " style="margin-top: 2%;">
                  <h3>Datos del Estudiante</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-1 form-group">
                    <label for="nac_alu">Nac.</label><input type="text" name="nac_alu" required maxlength="1" value="V" class="form-control">
                  </div>
                  <div class="col-md-3 form-group">
                    <label for="ced_alu" >Cédula</label>
                    <input type="text" onkeypress="return ValCed(event)" onkeyup="fnBuscarClient()" required name="ced_alu" data-bs-toggle="tooltip" data-bs-placement="top" title="Si el estudiante no posee cedula de identidad, por favor use cedula escolar siguiendo las indicaciones arriba mencionadas" class="form-control" maxlength="11" placeholder="Solo numeros" id="ced_alu" >
                  </div>
                  <div class="col-md-3 form-group">
                    <label for="loginUser" >Usuario</label><input type="text" data-bs-toggle="tooltip" data-bs-placement="top" required onchange="miUser()" title="Indique usuario con el cual podrá entrar y usar todas las opciones de la página web" id="loginUser" name="loginUser" maxlength="50" class="form-control"  >
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="cla_alu" >Contraseña</label><input type="text" name="cla_alu" required minlength="5" data-bs-toggle="tooltip" data-bs-placement="top" title="memorice su contraseña para luego entrar"  class="form-control" >
                  </div>
                  <div class="col-md-3 form-group">
                    <label for ="gra_alu" >Grado o Año</label><br>
                    <select  name='gra_alu' id='gra_alum' class="form-control" data-style="btn-default" data-live-search="true" data-width="fit" >
                      <option value="">Seleccione....</option><?php                   
                      $select2 = mysqli_query($link,"SELECT grado,nombreGrado FROM grado".$tablaPeriodo." ORDER BY grado" );
                      while($row1 = mysqli_fetch_array($select2))
                      {
                        $id_gra=$row1['grado'];
                        $nom_gra=$row1['nombreGrado'];
                        echo '<option value="'.$id_gra.'">'.($nom_gra)."</option>";
                      } ?>
                    </select>
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-4 form-group">
                    <label for="nom_alu" >Nombres</label><input type="text" name="nom_alu" required maxlength="50"   class="form-control" >
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="ape_alu" >Apellidos</label><input type="text" name="ape_alu" required  maxlength="50" class="form-control">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="peso_alu" >Peso</label>
                    <input type="text" id="peso_alu" name="peso_alu" required maxlength="10" class="form-control" >
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="talla_alu" >Talla</label>
                    <input type="text" id="talla_alu" name="talla_alu" required maxlength="10" class="form-control" >
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-2 form-group">
                    <label for="fna_alu" >Fecha de Nac.</label><input type="date"  name="fna_alu" required class="form-control" id="fna_alu">
                  </div>
                  <div class="col-md-2 form-group">
                    <label>Estado</label><br>
                    <select class="form-control" data-live-search="true" name="edo_alu" id='edo_alu' onchange="municipio(); validaMun();" >
                      <option value="">Seleccione....</option><?php
                      $select2 = mysqli_query($link,"SELECT * FROM estado");
                      while($row = mysqli_fetch_array($select2))
                      {
                          $id_edo=$row['id_edo'];
                          $nom_edo=$row['estado'];
                          echo '<option readonly value="'.$id_edo.'">'.($nom_edo)."</option>";
                      } ?>
                    </select>
                  </div>
                  <div class="col-md-3 form-group">
                    <label for="loc_alu" >Lugar de Nacimiento</label>
                    <select class=" form-control" onchange="validaMun()" data-live-search="true" name="loc_alu" id='loc_alu'>
                      <option value="">Seleccione....</option><?php 
                      $locali_query=mysqli_query($link,"SELECT id_ciudad, ciudad,id_estado FROM ciudades ORDER BY ciudad ASC");
                      while($row = mysqli_fetch_array($locali_query))
                      { 
                          $id_ciudad=$row['id_ciudad'];
                          $ciudad=($row['ciudad']);
                          $id_estado=$row['id_estado'];
                          echo '<option class="region_opt region_'.$id_estado.'" value="'.$id_ciudad.'">'.($ciudad)."</option>";
                      } ?>  
                    </select>
                  </div>
                  <div class="col-md-3 form-group">
                    <label for="muni_alu" >Municipio</label>
                    <select class=" form-control" onchange="validaMun()" data-live-search="true" name="muni_alu" id='muni_alu' tabindex="4" >
                      <option value="">Seleccione....</option><?php 
                      $munici_query=mysqli_query($link,"SELECT id_municipio, municipio,id_estado FROM municipios ORDER BY municipio ASC");
                      while($row = mysqli_fetch_array($munici_query))
                      { 
                        $id_municipio=$row['id_municipio'];
                        $id_estado=$row['id_estado'];
                        $municipio=($row['municipio']);
                        echo '<option class="region_opt region_'.$id_estado.'" value="'.$id_municipio.'">'.($municipio)."</option>";
                      } ?>  
                    </select>
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="pai_alu">País de Nac.</label><input type="text" name="pai_alu" required maxlength="30"  class="form-control" >
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-2 form-group">
                    <label>Género: </label><br>               
                    <label class="radio-inline"><input type="radio" name="sex_alu" required value="M" >&nbsp;Mas.&nbsp;&nbsp;</label>
                    <label class="radio-inline"><input type="radio" name="sex_alu" required value="F" >&nbsp;Fem.&nbsp;&nbsp;</label>
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="tlf_alu" >Celular</label><input type="text" name="tlf_alu" id="tlf_alu" onClick="this.select()" required maxlength="30"  onkeypress="return valida(event)" class="form-control" >
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="dir_alu" >Dirección</label><input type="text" name="dir_alu" required maxlength="100" class="form-control">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="mai_alu">Email</label><input type="email" data-bs-toggle="tooltip" data-bs-placement="top" title="Ingrese un correo valido ya que con el podrá recuperar su contraseña" name="mai_alu" required class="form-control" maxlength="50">
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-4">
                    <label>Estado Civil de los Padres</label>
                    <select name="edoCivPadr" class="form-control">
                      <option value="1">Casados</option>
                      <option value="2">Separados</option>
                      <option value="3">Unión de Hecho</option>
                      <option value="4">Madre sola</option>
                      <option value="5">Viudo/a</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Hermanos</label>
                    <input type="text" name="hermanos" maxlength="2" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label>Lateridad</label>
                    <select name="mano" class="form-control">
                      <option value="1">Derecho</option>
                      <option value="2">Izquierdo</option>
                      <option value="3">Ambidiestro</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-------------- DATOS DEL REPRESENTANTE  ------------------>
              <div class="row" style="background-color:#E0F2F1; padding: 10px;">
                <div class="col-md-12 from-group " style="margin-top: 2%;">
                  <h3>Datos del Representante</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-2 form-group">
                    <label for="ced_rep">Cedula</label>
                    <input type="text"   onkeypress="return ValCed(event)" onkeyup="fnBuscarRepre(); autoFillDadMom(this.id)" name="ced_rep" required maxlength="8" id="ced_rep" class="form-control" >
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="nom_rep" >Nombre y Apellido</label>
                    <input type="text" onkeyup="autoFillDadMom(this.id)"  name="nom_rep" required id="nom_rep" maxlength="50" class="form-control" >
                  </div>
                  <div class="col-md-2 form-group">
                    <label for ="par_rep">Parentesco</label><br>
                    <select  name='par_rep' id='pare_rep' class="form-control" data-style="btn-default" data-live-search="true" data-width="fit">
                      <option value="">Seleccione....</option><?php
                      $select3 = mysqli_query($link,"SELECT * FROM parentescos");
                      while($row = mysqli_fetch_array($select3))
                      {
                        $parentesco=$row['idparen'];
                        $paren=$row['nomparen'];
                        $selected ='';
                        
                        echo '<option value="'.$parentesco.'"'.$selected.'>'.$paren."</option>";
                      }?>
                    </select>
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="fna_rep" >Fecha de Nac.</label>
                    <input type="date" name="fna_rep" required onchange="autoFillDadMom(this.id)" id="fna_rep" class="form-control" >
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-4 form-group">
                    <label for="dir_rep" >Dirección</label>
                    <input type="text" onkeyup="autoFillDadMom(this.id)"  name="dir_rep" required id="dir_rep" maxlength="100" class="form-control" >
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="mai_rep">Email</label>
                    <input type="email" onkeyup="autoFillDadMom(this.id)" name="mai_rep" required id="mai_rep" data-bs-toggle="tooltip" data-bs-placement="top"  title="Ingrese un correo valido ya que a el enviaremos Información Importante" maxlength="50" class="form-control">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="tlf_hab_rep" >Teléfono Hab.</label>
                    <input type="text" onkeyup="autoFillDadMom(this.id)" data-bs-toggle="tooltip" data-bs-placement="top" title="agregue codigo de area ej. 0243-000.00.00" name="tlf_hab_rep" required id="tlf_hab_rep" onClick="this.select()" onkeypress="return valida(event)" class="form-control">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="tlf_cel_rep" >Celular Personal</label><input type="text" onkeyup="autoFillDadMom(this.id)"  name="tlf_cel_rep" data-bs-toggle="tooltip" data-bs-placement="top" title="Preferiblemente whatsapp, para poder recibir mensajes de la institución" required id="tlf_cel_rep" onkeypress="return valida(event)" onClick="this.select()"  class="form-control" value="">
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-6 form-group">
                    <label for="lug_trab_rep" >Lugar de trabajo</label>
                    <input type="text" onkeyup="autoFillDadMom(this.id)" required name="lug_trab_rep" id="lug_trab_rep" maxlength="100" class="form-control" >
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="ocup_rep" >Profesión</label>
                    <input type="text" required onkeyup="autoFillDadMom(this.id)" name="ocup_rep" id="ocup_rep" maxlength="50" class="form-control" value="">
                  </div>
                </div>
              </div>
              <!-------------- DATOS DE LA MADRE  ------------------>
              <div class="row" style="background-color:#FCE4EC; padding: 10px;">
                <div class="col-md-12 from-group" style="margin-top: 2%;">
                  <h3>Datos de la Madre</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="form-group col-md-2">
                    <label for="ced_mama">Cedula</label>
                    <input type="text" required onkeyup="fnBuscarMama();" onkeypress="return ValCed(event)" name="ced_mama" maxlength="11" class="form-control" id="ced_mama"  >
                  </div>
                  <div class="form-group col-md-4">
                    <label for="nom_mama">Nombre y Apellido</label>
                      <input type="text" required name="nom_mama" maxlength="50" class="form-control" id="nom_mama" >
                  </div>
                  <div class="form-group col-md-2">
                    <label for="fecNac_mama" >Fecha de Nac.</label>
                    <input type="date" name="fecNac_mama" class="form-control" id="fecNac_mama" onClick="this.select()" >
                  </div>
                  <div class="form-group col-md-2">
                    <label for="tlf_cel_mama" >Celular Personal</label>
                    <input type="text" required name="tlf_cel_mama" maxlength="30" onkeypress="return valida(event)"  class="form-control" value="" id="tlf_cel_mama" onClick="this.select()">
                  </div>
                  <div class="form-group col-md-2 col-xs-6 col-sm-6">
                    <label for="tlf_hab_mama" >Teléfono Hab.</label>
                    <input type="text" required name="tlf_hab_mama" maxlength="30" onkeypress="return valida(event)" class="form-control" id="tlf_hab_mama" data-bs-toggle="tooltip" data-bs-placement="top" title="agregue codigo de area ej. 0243-000.00.00" onClick="this.select()">
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="form-group col-md-5">
                    <label for="nacio_mama" >Lugar de nacimiento</label>
                    <input type="text" name="nacio_mama" class="form-control" id="nacio_mama">
                  </div>
                  <div class="form-group col-md-5">
                    <label for="ocup_mama" >Profesión</label>
                    <input type="text" name="ocup_mama" maxlength="30" class="form-control" value="" id="ocup_mama">
                  </div>
                  <div class="form-group col-md-2 col-xs-6 col-sm-6">
                    <label for="tlf_ofi_mama" >Teléfono Oficina</label>
                    <input type="text" name="tlf_ofi_mama" maxlength="30" onkeypress="return valida(event)" class="form-control" id="tlf_ofi_mama" onClick="this.select()" title="agregue codigo de area ej. 0243-000.00.00">
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="form-group col-md-5">
                    <label for="dir_mama" >Dirección Habitación</label>
                    <input type="text" required name="dir_mama" maxlength="50" class="form-control" id="dir_mama">
                  </div>
                  <div class="form-group col-md-5">
                    <label for="lug_trab_mama" >Donde trabaja? Dirección:</label>
                    <input type="text" required name="lug_trab_mama" maxlength="50" class="form-control" value="" id="lug_trab_mama">
                  </div>
                  <div class="col-md-2">
                    <label>Estudios</label>
                    <select class="form-control" name="est_mama" id="est_mama">
                      <option value="1">Primarios</option>
                      <option value="2">Secundarios</option>
                      <option value="3">Tercearios o Universitarios</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-------------- DATOS DEL PADRE  ------------------>
              <div class="row" style="background-color:#E3F2FD; padding: 10px;">
                <div class="col-md-12 from-group" style="margin-top: 2%;">
                  <h3>Datos del Padre</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="form-group col-md-2">
                    <label for="ced_papa">Cedula</label>
                    <input type="text" required onkeyup="fnBuscarPapa();" onkeypress="return ValCed(event)" name="ced_papa" maxlength="11" class="form-control" id="ced_papa"  >
                  </div>
                  <div class="form-group col-md-4">
                    <label for="nom_papa">Nombre y Apellido</label>
                      <input type="text" required name="nom_papa" maxlength="50" class="form-control" id="nom_papa" >
                  </div>
                  <div class="form-group col-md-2">
                    <label for="fecNac_papa" >Fecha de Nac.</label>
                    <input type="date" name="fecNac_papa" onkeypress="return valida(event)"  class="form-control" id="fecNac_papa" onClick="this.select()" >
                  </div>
                  <div class="form-group col-md-2">
                    <label for="tlf_cel_papa" >Celular Personal</label>
                    <input type="text" required name="tlf_cel_papa" maxlength="30" onkeypress="return valida(event)"  class="form-control" value="" id="tlf_cel_papa" onClick="this.select()">
                  </div>
                  <div class="form-group col-md-2 col-xs-6 col-sm-6">
                    <label for="tlf_hab_papa" >Teléfono Hab.</label>
                    <input type="text" required name="tlf_hab_papa" maxlength="30" onkeypress="return valida(event)" class="form-control" id="tlf_hab_papa" data-bs-toggle="tooltip" data-bs-placement="top" title="agregue codigo de area ej. 0243-000.00.00" onClick="this.select()">
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="form-group col-md-5">
                    <label for="nacio_papa" >Lugar de nacimiento</label>
                    <input type="text" name="nacio_papa" class="form-control" id="nacio_papa">
                  </div>
                  <div class="form-group col-md-5">
                    <label for="ocup_papa" >Profesión</label>
                    <input type="text" name="ocup_papa" maxlength="30" class="form-control" value="" id="ocup_papa">
                  </div>
                  <div class="form-group col-md-2 col-xs-6 col-sm-6">
                    <label for="tlf_ofi_papa" >Teléfono Oficina</label>
                    <input type="text" name="tlf_ofi_papa" maxlength="30" class="form-control" id="tlf_ofi_papa" onClick="this.select()" title="agregue codigo de area ej. 0243-000.00.00">
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="form-group col-md-5">
                    <label for="dir_papa" >Dirección Habitación</label>
                    <input type="text" required name="dir_papa" maxlength="50" class="form-control" id="dir_papa">
                  </div>
                  <div class="form-group col-md-5">
                    <label for="lug_trab_papa" >Donde trabaja? Dirección:</label>
                    <input type="text" required name="lug_trab_papa" maxlength="50" class="form-control" value="" id="lug_trab_papa">
                  </div>
                  <div class="col-md-2">
                    <label>Estudios</label>
                    <select class="form-control" name="est_papa" id="est_papa">
                      <option value="1">Primarios</option>
                      <option value="2">Secundarios</option>
                      <option value="3">Tercearios o Universitarios</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-------------- DATOS DE CONTACTO  ------------------>
              <div class="row" style="background-color:#EF5350; padding: 10px;">
                <div class="col-md-12 from-group" style="margin-top: 2%;">
                  <h3>Otras Personas de Contacto (Casos de emergencia)</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-4 form-group">
                    <label for="nom_emerg_1">Nombre y Apellido</label>
                    <input type="text" required name="nom_emerg_1" maxlength="50" class="form-control">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for ="pare_emerg_1">Parentesco</label><br>
                    <select  name='pare_emerg_1' id="pare_emerg_1" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit" >
                      <option value="">Seleccione....</option><?php
                      $select3 = mysqli_query($link,"SELECT * FROM parentescos");
                      while($row = mysqli_fetch_array($select3))
                      {
                        $parentesco=$row['idparen'];
                        $paren=$row['nomparen'];
                        $selected ='';
                          if($idparen == $parentesco){$selected='selected';}
                        echo '<option value="'.$parentesco.'"'.$selected.'>'.$paren."</option>";
                      } ?>
                    </select>
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="telf_emerg_hab_1" >Teléfono Hab.</label>
                    <input type="text" required name="tlf_emerg_hab_1" id="tlf_emerg_hab_1" maxlength="20" class="form-control" onClick="this.select()" data-bs-toggle="tooltip" data-bs-placement="top" title="agregue codigo de area ej. 0243-000.00.00">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="telf_emerg_ofi_1" >Teléfono Ofi.</label>
                    <input type="text" required name="tlf_emerg_ofi_1" id="tlf_emerg_ofi_1" maxlength="20" class="form-control" onClick="this.select()" data-bs-toggle="tooltip" data-bs-placement="top" title="agregue codigo de area ej. 0243-000.00.00">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="telf_emerg_cel_1" >Teléfono Cel.</label>
                    <input type="text" required name="tlf_emerg_cel_1" id="tlf_emerg_cel_1" maxlength="20" class="form-control" onClick="this.select()">
                  </div>
                </div>

                <div class="row" style="margin-top:2%;">
                  <div class="col-md-4 form-group">
                    <label for="nom_emerg_2">Nombre y Apellido</label>
                    <input type="text" name="nom_emerg_2" maxlength="50" class="form-control">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for ="pare_emerg_2">Parentesco</label><br>
                    <select  name='pare_emerg_2' id="pare_emerg_2" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit" >
                      <option value="">Seleccione....</option><?php
                      $select3 = mysqli_query($link,"SELECT * FROM parentescos");
                      while($row = mysqli_fetch_array($select3))
                      {
                        $parentesco=$row['idparen'];
                        $paren=$row['nomparen'];
                        $selected ='';
                          if($idparen == $parentesco){$selected='selected';}
                        echo '<option value="'.$parentesco.'"'.$selected.'>'.$paren."</option>";
                      } ?>
                    </select>
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="telf_emerg_hab_2" >Teléfono Hab.</label>
                    <input type="text" name="tlf_emerg_hab_2" id="tlf_emerg_hab_2" maxlength="20" class="form-control" onClick="this.select()" data-bs-toggle="tooltip" data-bs-placement="top" title="agregue codigo de area ej. 0243-000.00.00">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="telf_emerg_ofi_2" >Teléfono Ofi.</label>
                    <input type="text" name="tlf_emerg_ofi_2" id="tlf_emerg_ofi_2" maxlength="20" class="form-control" onClick="this.select()" data-bs-toggle="tooltip" data-bs-placement="top" title="agregue codigo de area ej. 0243-000.00.00">
                  </div>
                  <div class="col-md-2 form-group">
                    <label for="telf_emerg_cel_2" >Teléfono Cel.</label>
                    <input type="text" name="tlf_emerg_cel_2" id="tlf_emerg_cel_2" maxlength="20" class="form-control" onClick="this.select()">
                  </div>
                </div>
              </div>
              <!---------------EFINTERES----------------------------->
              <div class="row" style="background-color:#A1887F; padding: 10px;">
                <div class="col-md-12 from-group" style="margin-top: 2%;">
                  <h3>Control de Esfinteres</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-4">
                    <label>Edad en que controlo?</label>
                    <input type="text" name="controlPopo" maxlength="10" class="form-control" >
                  </div>
                  <div class="col-md-4">
                    <label>Actualmente va al baño solo?</label>
                    <select class="form-control" name="vaSolo">
                      <option value="1">SI</option>
                      <option value="2">NO</option>
                      <option value="3">Con Ayuda</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>Por las noches moja la cama?</label>
                    <select class="form-control" name="mojaCama">
                      <option value="2">NO</option>
                      <option value="1">SI</option>
                    </select>
                  </div>
                </div>
              </div>
              <!---------------SALUD----------------------------->
              <div class="row" style="background-color:#2196F3; padding: 10px;">
                <div class="col-md-12 from-group" style="margin-top: 2%;">
                  <h3>Salud</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-6">
                    <label>Es alergico? (indique alergias)</label>
                    <input type="text" name="alergias" maxlength="100" class="form-control" >
                  </div>
                  <div class="col-md-6">
                    <label>Tiene alguna dificultad motora? (Indique cual)</label>
                    <input type="text" name="difiMotora" maxlength="100" class="form-control" >
                  </div>
                  <div class="col-md-3">
                    <label>Le realizaron examenes?</label>
                    <select class="form-control" name="examMotor">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-9">
                    <label>Sufrio algun accidente, convulsiones, enfermedades? (Explique)</label>
                    <input type="text" name="sufrioAcci" maxlength="100" class="form-control" >
                  </div>
                </div>
              </div>
              <!---------------ENFERMEDADES----------------------------->
              <div class="row" style="background-color:#B39DDB; padding: 10px;">
                <div class="col-md-12 from-group" style="margin-top: 2%;">
                  <h3>Enfermedades que padeció</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-2">
                    <label>Bronquitis</label>
                    <select class="form-control" name="padeBroqui">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Hepatitis</label>
                    <select class="form-control" name="padeHepa">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Paperas</label>
                    <select class="form-control" name="padePape">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Asma</label>
                    <select class="form-control" name="padeAsma">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Varicela</label>
                    <select class="form-control" name="padeVaric">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Resfrios</label>
                    <select class="form-control" name="padeResfri">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-2">
                    <label>Está medicado?</label>
                    <select class="form-control" name="medicado">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Ve bien?</label>
                    <select class="form-control" name="veBien">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Usa lentes?</label>
                    <select class="form-control" name="anteojos">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Oye bien?</label>
                    <select class="form-control" name="audio">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Usa audifonos?</label>
                    <select class="form-control" name="aparatos">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Grupo sanguineo:</label>
                    <select name="tipoSangre" class="form-control">
                      <option value="0">Seleccione</option>
                      <option value="O-" >O Negativo</option>
                      <option value="O+" >O Positivo</option>
                      <option value="A-" >A Negativo</option>
                      <option value="A+" >A Positivo</option>
                      <option value="B-" >B Negativo</option>
                      <option value="B+" >B Positivo</option>
                      <option value="AB-" >AB Negativo</option>
                      <option value="AB+" >AB Positivo</option>
                    </select>
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-6">
                    <label>Tiene alguna dificultad cardiológica?</label>
                    <input type="text" name="enfeCardio" maxlength="100" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Tiene alguna dificultad respiratoria?</label>
                    <input type="text" name="enfeRespi" maxlength="100" class="form-control" >
                  </div>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-6">
                    <label>Pediatra que lo atiende:</label>
                    <input type="text" name="atenPedia" maxlength="50" class="form-control" >
                  </div>
                  <div class="col-md-4">
                    <label>Clínica - Hospital:</label>
                    <input type="text" name="atenClini" maxlength="50" class="form-control" >
                  </div>
                  <div class="col-md-2">
                    <label>Telefono:</label>
                    <input type="text" name="atenTlf" maxlength="15" class="form-control" >
                  </div>
                </div>
              </div>
              <!------------VACUNAS------------>
              <div class="row" style="background-color:#FFAB91; padding: 10px;">
                <div class="col-md-12 from-group" style="margin-top: 2%;">
                  <h3>Vacunas</h3>
                </div>
                <div class="row" style="margin-top:2%;">
                  <div class="col-md-2">
                    <label>BSC</label>
                    <select class="form-control" name="vacu_bsc">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Anti-Poliomielitica</label>
                    <select class="form-control" name="vacu_polio">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Pentavelente</label>
                    <select class="form-control" name="vacu_penta">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Anti-Hepatitis B</label>
                    <select class="form-control" name="vacu_hepat">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Triple Bacteriana</label>
                    <select class="form-control" name="vacu_bacte">
                      <option value="2">NO</option>
                      <option value="1">SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Trivalente Viral</label>
                    <select class="form-control" name="vacu_trival">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Anti-Amarilica</label>
                    <select class="form-control" name="vacu_amari">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Doble-Viral</label>
                    <select class="form-control" name="vacu_doble">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Toxoide Tetanico</label>
                    <select class="form-control" name="vacu_teta">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Toxoide Difterico</label>
                    <select class="form-control" name="vacu_difte">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label>Anti-Influenza</label>
                    <select class="form-control" name="vacu_influ">
                      <option value="2" >NO</option>
                      <option value="1" >SI</option>
                    </select>
                  </div>
                  <div class="col-md-10">
                    <label>Otras vacunas aplicadas:</label>
                    <input type="text" name="vacu_otras" maxlength="100" class="form-control" >
                  </div>
                </div>
              </div>

              <div class="d-grid gap-2 col-md-6 col-xs-12 mx-auto" style="margin-top: 2%;">
                <button type="submit" value="1" name="enviar" class="btn btn-success btn-lg"><i class="ri-upload-cloud-line"></i> Guardar Planilla</button>
              </div>
            </form>
          </div>
        </div>
      </section><?php 
    } ?>
    <div class="modal fade" id="guardar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" >
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLongTitle">Buen día:</h3>
          </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 text-center">
                  <p>Por favor resuelva la operación</p>
                </div>
                <div class="col-md-12 text-center" style="margin-top: 2%;">
                  <h3 id="suma"></h3>
                  <input type="text" placeholder="Resultado" id="resultado" class="form-control">  
                </div> 
              </div>
            </div>
            <div class="modal-footer">
              <div class="col-md-4 col-md-offset-8">
                <button type="button" onclick="enviar()" class="btn btn-rectangular btn-success" style="color: #000; width: 100%;">Continuar</button>
              </div>
            </div>
            <input type="hidden" id="n1" >
            <input type="hidden" id="n2" >
        </div>
      </div>
    </div>
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script src="includes/jquery.maskedinput/src/jquery.mask.js" type="text/javascript"></script>
  <script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>
  <script type="text/javascript">
    jQuery(document).ready(function($) 
    {
      $('#guardar').modal('show')
      calcula()
      municipio(); 
    });
    $("#tlf_alu").mask("????-???.??.??");
    $("#tlf_cel_rep").mask("????-???.??.??");
    $("#tlf_cel_mama").mask("????-???.??.??");
    $("#tlf_cel_papa").mask("????-???.??.??");
    $("#tlf_hab_rep").mask(" ????-???.??.??");
    $("#tlf_hab_mama").mask("????-???.??.??");
    $("#tlf_hab_papa").mask("????-???.??.??");
    
    $("#tlf_emerg_hab_1").mask("????-???.??.??");
    $("#tlf_emerg_ofi_1").mask("????-???.??.??");
    $("#tlf_emerg_cel_1").mask("????-???.??.??");

    $("#tlf_emerg_hab_2").mask("????-???.??.??");
    $("#tlf_emerg_ofi_2").mask("????-???.??.??");
    $("#tlf_emerg_cel_2").mask("????-???.??.??");
    function miUser() {
      usu=$('#loginUser').val()
      $.post('user-buscar.php',{'usua':usu},function(data){
        if(data.isSuccessful)
        {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Usuario YA existe, por favor indique otro'
          })
          $('#loginUser').val('')
        }
      }, 'json');
    }
    function calcula() 
    {
      hoy=new Date();
      n1=hoy.getMinutes();
      if(n1>10)
      {
        n1=n1/10;
        n1=Math.round(n1);
      }
      n2=hoy.getSeconds();
      if(n2>10)
      {
        n2=n2/10;
        n2=Math.round(n2);
      }
      if(n1>n2)
      {calcu=n1+' - '+n2+' = ?';}else{calcu=n1+' + '+n2+' = ?';}
      
      document.getElementById("suma").innerHTML = calcu;
      $('#n1').val(n1)
      $('#n2').val(n2)
    }
    function enviar()
    {
      res=$('#resultado').val()
      if(n1>n2)
      {tot=parseFloat($('#n1').val())-parseFloat($('#n2').val())}else{tot=parseFloat($('#n1').val())+parseFloat($('#n2').val())}
      if(res==tot)
      {
        $('#guardar').modal('hide')
      }else
      {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Resultado incorrecto!'
        })
        //location.href="index.php"
      }
    }
    function municipio() 
    {
      $('.region_opt').hide();
      $('.region_'+$('#edo_alu').val()).show();
    }
    function validaMun() 
    {
      var edoNac = $('#edo_alu').val(); 
      var lugNac = $('#loc_alu').val();
      var munNac = $('#muni_alu').val(); 

      if (edoNac == '' )
      { 
        document.getElementById("edo_alu").style.border="solid #FE0101";
        return false;
      } else { document.getElementById("edo_alu").style.border=""; }
      if (lugNac == '' )
      { 
        document.getElementById("loc_alu").style.border="solid #FE0101";
        return false;
      } else { document.getElementById("loc_alu").style.border=""; }
      if (munNac == '' )
      { 
        document.getElementById("muni_alu").style.border="solid #FE0101";
        return false;
      } else { document.getElementById("muni_alu").style.border=""; }
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
        //ESTE ES EL CODIGO 
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
           // Insertamos la imagen
           document.getElementById("list").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
          };
        })(f);
        reader.readAsDataURL(f);
      }         
    }
    document.getElementById('files').addEventListener('change', archivo, false);
    function archivo1(evt) 
    {
      var files1 = evt.target.files; // FileList object
      // Obtenemos la imagen del campo "file".
      for (var i = 0, f; f = files1[i]; i++) 
      {
        //Solo admitimos imágenes.
        if (!f.type.match('image.*')) 
        { alert("FORMATO DE IMAGEN INCORRECTO");
            continue;
        }
        //ESTE ES EL CODIGO 
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
        var reader = new FileReader();
        reader.onload = (function(theFile) 
        {
          return function(e) 
          {
            // Insertamos la imagen
            document.getElementById("list1").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
          };
        })(f);
        reader.readAsDataURL(f);
      }
    }
    document.getElementById('files1').addEventListener('change', archivo1, false);
    function  fnBuscarClient()
    {
      ced_buscar = $('#ced_alu').val();
      if(ced_buscar.length > 7 ){
        $.post('buscar-alum.php',{'ced':ced_buscar},function(data){
          if(data.isSuccessful)
          {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              confirmButtonText:
              '<i class="fa fa-thumbs-up"></i> Entendido',
              text: 'Cedula YA existe, regrese a inicio e ingrese con su cedula y contraseña, si olvido su contraseña pulse olvide contraseña en iniciar sesion.'
            })
            $('#ced_alu').val('');
          }
        }, 'json');
      }
    }
    function  fnBuscarRepre()
    {
      ced_buscar = $('#ced_rep').val();
      if(ced_buscar.length > 5 ){
        $.post('buscarRepre.php',{'ced':ced_buscar},function(data){
          if(data.isSuccessful){
            $('#nom_rep').val(data.nombre)
            $('#fna_rep').val(data.fnac)
            $('#tlf_hab_rep').val(data.tlf)
            $('#tlf_cel_rep').val(data.celu)
            $('#mai_rep').val(data.email)
            $('#dir_rep').val(data.direcc)
            $('#ocup_rep').val(data.ocup)
            $('#lug_trab_rep').val(data.lugTra)
            
          }else{
            $('#nom_rep').val('')
            $('#fna_rep').val('')
            $('#tlf_hab_rep').val('')
            $('#tlf_cel_rep').val('')
            $('#mai_rep').val('')
            $('#dir_rep').val('')
            $('#ocup_rep').val('')
            $('#lug_trab_rep').val('')
          }
        }, 'json');
      }
    }
    function fnBuscarPapa() {
      ced_buscar = $('#ced_papa').val();
      if(ced_buscar.length > 5 ){
        $.post('buscarPapa.php',{'ced':ced_buscar},function(data){
          if(data.isSuccessful){
            $('#nom_papa').val(data.nombre)
            $('#dedica_papa').val(data.dedica)
            $('#tlf_hab_papa').val(data.tlf)
            $('#tlf_cel_papa').val(data.celu)
            $('#dir_papa').val(data.direcc)
            $('#ocup_papa').val(data.ocup)
            $('#lug_trab_papa').val(data.lugTra)
            
          }else{
            $('#nom_papa').val('')
            $('#dedica_papa').val('')
            $('#tlf_hab_papa').val('')
            $('#tlf_cel_papa').val('')
            $('#dir_papa').val('')
            $('#ocup_papa').val('')
            $('#lug_trab_papa').val('')
          }
        }, 'json');
      }
    }
    function fnBuscarMama() {
      ced_buscar = $('#ced_mama').val();
      if(ced_buscar.length > 5 ){
        $.post('buscarMama.php',{'ced':ced_buscar},function(data){
          if(data.isSuccessful){
            $('#nom_mama').val(data.nombre)
            $('#dedica_mama').val(data.dedica)
            $('#tlf_hab_mama').val(data.tlf)
            $('#tlf_cel_mama').val(data.celu)
            $('#dir_mama').val(data.direcc)
            $('#ocup_mama').val(data.ocup)
            $('#lug_trab_mama').val(data.lugTra)
            
          }else{
            $('#nom_mama').val('')
            $('#dedica_mama').val('')
            $('#tlf_hab_mama').val('')
            $('#tlf_cel_mama').val('')
            $('#dir_mama').val('')
            $('#ocup_mama').val('')
            $('#lug_trab_mama').val('')
          }
        }, 'json');
      }
    }
    function autoFillDadMom(input_rep)
    {
      if($('#pare_rep').val() == 1)
      {
        input_mama = input_rep.replace("rep","mama");
        $('#'+input_mama).val($('#'+input_rep).val());
      }else if($('#pare_rep').val() == 2)
      {
        input_papa = input_rep.replace("rep","papa");
        $('#'+input_papa).val($('#'+input_rep).val());
      }
    }
    $('#pare_rep').change(function()
    {   
      if($('#pare_rep').val() == 1){
        ($('#ced_mama').val() == '') ? $('#ced_mama').val($('#ced_rep').val()) : '';
        ($('#nom_mama').val() == '') ? $('#nom_mama').val($('#nom_rep').val()) : '';
        ($('#dir_mama').val() == '') ? $('#dir_mama').val($('#dir_rep').val()) : '';
        ($('#tlf_hab_mama').val() == '') ? $('#tlf_hab_mama').val($('#tlf_hab_rep').val()) : '';
        ($('#tlf_cel_mama').val() == '') ? $('#tlf_cel_mama').val($('#tlf_cel_rep').val()) : '';
        ($('#dir_trab_mama').val() == '') ? $('#dir_trab_mama').val($('#dir_trab_rep').val()) : '';
        ($('#lug_trab_mama').val() == '') ? $('#lug_trab_mama').val($('#lug_trab_rep').val()) : '';
        ($('#ocup_mama').val() == '') ? $('#ocup_mama').val($('#ocup_rep').val()) : '';
      }else if($('#pare_rep').val() == 2){
        ($('#ced_papa').val() == '') ? $('#ced_papa').val($('#ced_rep').val()) : '';
        ($('#nom_papa').val() == '') ? $('#nom_papa').val($('#nom_rep').val()) : '';
        ($('#dir_papa').val() == '') ? $('#dir_papa').val($('#dir_rep').val()) : '';
        ($('#tlf_hab_papa').val() == '') ? $('#tlf_hab_papa').val($('#tlf_hab_rep').val()) : '';
        ($('#tlf_cel_papa').val() == '') ? $('#tlf_cel_papa').val($('#tlf_cel_rep').val()) : '';
        ($('#dir_trab_papa').val() == '') ? $('#dir_trab_papa').val($('#dir_trab_rep').val()) : '';
        ($('#lug_trab_papa').val() == '') ? $('#lug_trab_papa').val($('#lug_trab_rep').val()) : '';
        ($('#ocup_papa').val() == '') ? $('#ocup_papa').val($('#ocup_rep').val()) : '';
      }
    });
    function validacion() 
    {
      var foto_alu = $('#files').val();
      var foto_rep = $('#files1').val();
      var edoNac = $('#edo_alu').val(); 
      var lugNac = $('#loc_alu').val();
      var munNac = $('#muni_alu').val();
      var gra_alum = $('#gra_alum').val();
      var pare_rep = $('#pare_rep').val();
      var pare_eme1 = $('#pare_emerg_1').val();
      if (foto_alu == '' )
      {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Agregue una foto del estudiante'
        })
        return false;
      } 
      if (foto_rep == '' )
      {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Agregue una foto del representante'
        })
        return false;
      }
      if (gra_alum == '' )
      {
        document.getElementById("gra_alum").style.border="solid #FE0101";
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Grado a cursar'
        })
        return false;
      }
      if (edoNac == '' )
      {
        document.getElementById("edo_alu").style.border="solid #FE0101";
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Estado de nacimiento'
        })
        return false;
      }
      if (lugNac == '' )
      {
        document.getElementById("loc_alu").style.border="solid #FE0101";
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Lugar de nacimiento'
        })
        return false;
      }
      if (munNac == '' )
      {
        document.getElementById("muni_alu").style.border="solid #FE0101";
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Municipio de nacimiento'
        })
        return false;
      }
      if (pare_rep == '' )
      {
        document.getElementById("pare_rep").style.border="solid #FE0101";
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Parentesco con el representante'
        })
        return false;
      }
      if (pare_eme1 == '' )
      {
        document.getElementById("pare_emerg_1").style.border="solid #FE0101";
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Parentesco en Emergencias'
        })
        return false;
      }
    }
  </script>
</body>
</html>