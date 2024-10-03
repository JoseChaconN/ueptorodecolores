<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse();
$idAlum=$_SESSION['idAlum'];
$tablaPeriodo=$_SESSION['periodoAlum'];
$result = mysqli_query($link,"SELECT A.*,B.nombreGrado, C.cedula as cedRep, C.representante, C.correo as correoRep, C.direccion as direccionRep, C.telefono as tlfRep, C.ruta as rutaRep, C.fnac_repre, C.lug_trabaj, C.tlf_celu, C.ocupacion, D.nombre as nomSec FROM alumcer A,grado".$tablaPeriodo." B,represe C, secciones D WHERE A.idAlum='$idAlum' and B.grado=A.grado and C.cedula=A.ced_rep and A.seccion=D.id"); 
while ($row = mysqli_fetch_array($result))
{   
  $nacion = $row['nacion'];  
  $cedula = $row['cedula'];
  $miUsuario = $row['miUsuario'];
  $clave = $row['clave'];
  $nombre = ($row['nombre']);  
  $apellido = ($row['apellido']);
  $sexo = $row['sexo'];
  $fechanac = $row['FechaNac'];
  $locali = $row['locali'];
  $municip=$row['municip'];
  $idEstado=$row['estado'];
  $pais = $row['pais'];
  $direccion = ($row['direccion']);
  $telefono = $row['telefono'];
  $ced_rep = $row['ced_rep'];
  $ced_mama = $row['ced_mama'];
  $ced_papa = $row['ced_papa'];
  $parenRep=$row['parentesco'];
  $correo = $row['correo'];
  $periodo = $row['Periodo'];
  $foto_alu = $row['ruta'];
  $nomarch = $row['ruta'];
  $gra_alu = $row['grado'];
  $secci_alu = $row['seccion'];
  $editar = $row['editable'];
  $nombreGrado=($row['nombreGrado']);
  $nomSec=$row['nomSec'];
  //Representante
  $cedrep = $row['cedRep'];
  $nomrep = ($row['representante']);
  $mairep = $row['correoRep'];
  $dirrep = ($row['direccionRep']);  
  $tlfrep = $row['tlfRep'];
  $arch_rep = $row['rutaRep'];
  $foto_rep=$row['rutaRep'];
  $fnac_repre = $row['fnac_repre'];
  $lug_trab_rep = $row["lug_trabaj"];
  $tlf_cel_rep=$row["tlf_celu"];
  $ocup_rep=$row["ocupacion"];
    // Datos de Emergencia
  $nom_emerg_1=$row["nom_emerg_1"];
  $pare_emerg_1=$row["pare_emerg_1"];
  $tlf_emerg_hab_1=$row["tlf_emerg_hab_1"];
  $tlf_emerg_ofi_1=$row["tlf_emerg_ofi_1"];
  $tlf_emerg_cel_1=$row["tlf_emerg_cel_1"];
  $nom_emerg_2=$row["nom_emerg_2"];
  $pare_emerg_2=$row["pare_emerg_2"];
  $tlf_emerg_hab_2=$row["tlf_emerg_hab_2"];
  $tlf_emerg_ofi_2=$row["tlf_emerg_ofi_2"];
  $tlf_emerg_cel_2=$row["tlf_emerg_cel_2"];
  $peso=$row["peso"];
  $talla=$row["talla"];
  //$=$row[""];
}
$madre_query = mysqli_query($link,"SELECT * FROM madres WHERE ced_mama = '$ced_mama'");
  while ($row = mysqli_fetch_array($madre_query))
{
  $nom_ape_mama=$row['nom_ape_mama'];
  $fnac_mama=$row['fnac_mama'];
  $dire_mama=$row['dire_mama'];
  $lug_trab_mama=$row['lug_trab_mama'];
  $ocupa_mama=$row['ocupa_mama'];
  $tlf_cel_mama=$row['tlf_cel_mama'];
  $tlf_hab_mama=$row['tlf_hab_mama'];
  $lugar_nac_mama=$row['lugar_nac_mama'];
  $tlf_ofi_mama=$row['tlf_ofi_mama'];
  $estudio_mama=$row['estudio_mama'];
}
$padre_query = mysqli_query($link,"SELECT * FROM padres WHERE ced_papa = '$ced_papa'");
while ($row = mysqli_fetch_array($padre_query))
{
  $nom_ape_papa=$row['nom_ape_papa'];
  $fnac_papa=$row['fnac_papa'];
  $dire_papa=$row['dire_papa'];
  $lug_trab_papa=$row['lug_trab_papa'];
  $ocupa_papa=$row['ocupa_papa'];
  $tlf_cel_papa=$row['tlf_cel_papa'];
  $tlf_hab_papa=$row['tlf_hab_papa'];
  $lugar_nac_papa=$row['lugar_nac_papa'];
  $tlf_ofi_papa=$row['tlf_ofi_papa'];
  $estudio_papa=$row['estudio_papa'];
}
$ficha_query = mysqli_query($link,"SELECT * FROM ficha_medica WHERE idAlum = '$idAlum'");
while ($row = mysqli_fetch_array($ficha_query)){
  $edo_civil=$row['edo_civil_padres'];
  $nro_herma=$row['nro_herma'];
  $edad_efinteres=$row['edad_efinteres'];
  $ducha_solo=$row['ducha_solo'];
  $moja_cama=$row['moja_cama'];
  $alergico=$row['alergico'];
  $defic_motora=$row['defic_motora'];
  $examen_motora=$row['examen_motora'];
  $accidentes=$row['accidentes'];
  $bronquiti=$row['bronquiti'];
  $hepatitis=$row['hepatitis'];
  $paperas=$row['paperas'];
  $asma=$row['asma'];
  $varicela=$row['varicela'];
  $resfrio=$row['resfrio'];
  $lateridad=$row['lateridad'];
  $es_medicado=$row['es_medicado'];
  $cardiologica=$row['cardiologica'];
  $respiratoria=$row['respiratoria'];
  $ve_bien=$row['ve_bien'];
  $lentes=$row['lentes'];
  $oye_bien=$row['oye_bien'];
  $audifono=$row['audifono'];
  $pediatra=$row['pediatra'];
  $clinica=$row['clinica'];
  $tlfClinica=$row['tlfClinica'];
  $sangre=$row['sangre'];
  $bsc=$row['bsc'];
  $polio=$row['polio'];
  $penta=$row['penta'];
  $anti_hepati=$row['anti_hepati'];
  $bacteriana=$row['bacteriana'];
  $triple_viral=$row['triple_viral'];
  $amarilla=$row['amarilla'];
  $doble_viral=$row['doble_viral'];
  $tetanico=$row['tetanico'];
  $difterico=$row['difterico'];
  $influenza=$row['influenza'];
  $otras=$row['otras'];
  //$=$row[''];
}?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Perfil del estudiante periodo <?= $_SESSION['nombre_periodo'] ?></h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <form role="form" method="POST" enctype="multipart/form-data" action="actualizar.php" onsubmit="return validacion()" name="consulta">
          <div class="row">
            <div class="col-md-4 form-group text-center">
              <output id="list"><?php 
                if(empty($nomarch)) 
                { ?>
                  <img class='thumb from-group img-circle' src="imagenes/fotocarnet.jpg" /> <?php 
                } else 
                { ?>
                  <img class='thumb from-group img-circle' src="<?php echo 'fotoalu/'.$nomarch.'?'.time().mt_rand(0, 99999) ?>" /><?php 
                } ?>
              </output><br><br>
              <label class="btn btn-info">Foto Alumno<input type="file" name="foto_alu" id="files" style="display: none;"></label>
            </div>
            <div class="col-md-4 form-group text-center">
              <output id="list1"><?php 
                if(empty($arch_rep)) 
                { ?>
                  <img class='thumb from-group img-circle' src="imagenes/fotocarnet.jpg" /> <?php 
                } else 
                { ?>
                  <img class='thumb from-group img-circle' src="<?php echo 'fotorep/'.$arch_rep.'?'.time().mt_rand(0, 99999); ?>"/> <?php 
                } ?>
              </output><br><br>
              <label class="btn btn-info">Foto representante<input type="file" name="foto_rep" id="files1" style="display: none;"></label>
            </div>
            <div class="col-md-4 form-group text-center">
              <label>INDICACIONES!!!</label>
              <textarea rows="3" readonly style="width: 100%; text-align: justify;">Si la fotografía a subir es muy pesada por favor vea el siguiente video de como capturar foto</textarea><br>
              <a href="lighshot.php" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Pulse aqui para ver tutorial de como capturar una foto" ><button type="button" class="btn-group btn btn-danger" role="group" aria-label="">¿Como Capturar Foto?</button></a>
            </div>
            <!-------------- DATOS DEL ESTUDIANTE ------------->
            <div class="row" style="background-color:#FFF8E1; padding: 10px;">
              <div class="col-md-12 from-group" style="margin-top: 2%;">
                <h3>Datos del Estudiante</h3>
              </div>
              
              <div class="row" style="margin-top:2%;">
                <div class="col-md-1 form-group">
                  <label for="nac_alu" >Nac.</label>
                  <input type="text" name="nac_alu" maxlength="1" required="" class="form-control" value="<?= $nacion; ?>" <?php if($editar=='N') { echo "readonly"; }?>>
                </div>
                <div class="col-md-3 form-group">
                  <label for="ced_alu" >Cédula</label>
                  <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="En caso de requerir cambio de cedula, por favor notifique el cambio al dpto. de administración y asi pueda mantener todas sus notas e información del estudiante." onkeypress="return ValCed(event)" readonly name="ced_alu" id="ced_alu" class="form-control" value="<?= $cedula; ?>" readonly>
                  <input type="hidden" name="id" value="<?= encriptar($idAlum) ?>">
                </div>
                <div class="col-md-3 form-group">
                  <label for="loginUser" >Usuario</label>
                  <input type="text" name="loginUser" id="loginUser" onchange="buscaUsu()" required maxlength="50" data-bs-toggle="tooltip" data-bs-placement="top" title="Usuario con el cual podrá entrar y usar todas las opciones de la página web" class="form-control" value="<?= $miUsuario; ?>">
                </div>
                <div class="col-md-2 form-group">
                  <label for="cla_alu" >Contraseña</label>
                  <input type="text" name="cla_alu" minlength="5" data-bs-toggle="tooltip" data-bs-placement="top" title="Contraseña de acceso a todas las funciones de la página web" required class="form-control" value="<?= $clave ?>">
                </div>
                <div class="col-md-3 form-group">
                  <label for ="gra_alu" >Cursando</label><br>
                  <input type="text" name="gra_alu"  readonly class="form-control" value="<?= $nombreGrado.' '.$nomSec ?>">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-4 form-group">
                  <label for="nom_alu" >Nombres</label>
                      <input type="text" name="nom_alu" maxlength="50" required="" class="form-control" value="<?= $nombre; ?>" <?php if($editar=='N') { echo "readonly"; }?> >
                </div>
                <div class="col-md-4 form-group">
                  <label for="ape_alu" >Apellidos</label>
                  <input type="text" name="ape_alu" required maxlength="50" class="form-control" <?php if($editar=='N') { echo "readonly"; }?> value="<?= $apellido ?>" >
                </div>
                <div class="col-md-2 form-group">
                  <label for="peso_alu" >Peso</label>
                  <input type="text" id="peso_alu" name="peso_alu" required maxlength="10" class="form-control" value="<?= $peso ?>" >
                </div>
                <div class="col-md-2 form-group">
                  <label for="talla_alu" >Talla</label>
                  <input type="text" id="talla_alu" name="talla_alu" required maxlength="10" class="form-control" value="<?= $talla ?>" >
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-2 form-group">
                  <label for="fna_alu" >Fecha de Nac.</label>
                  <input type="date" required="" name="fna_alu" max="<?= $fechahoy; ?>" class="form-control" value = "<?= $fechanac ?>" <?php if($editar=='N') { echo "readonly"; }?>>
                </div>
                <div class="col-md-2 form-group">
                  <label>Estado</label><br>
                  <select class="form-control" data-live-search="true" name="edo_alu" id='edo_alu' onchange="municipio();" <?php if($editar=='N') { echo "disabled"; }?>>
                    <option value="">Seleccione....</option><?php
                    $select2 = mysqli_query($link,"SELECT * FROM estado");
                    while($row = mysqli_fetch_array($select2))
                    {
                      $id_edo=$row['id_edo'];
                      $nom_edo=$row['estado'];
                      $selected ='';
                      if($idEstado == $id_edo){$selected='selected';}
                      echo '<option readonly value="'.$id_edo.'"'.$selected.'>'.utf8_encode($nom_edo)."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-3 form-group">
                  <label for="loc_alu" >Lugar de Nacimiento</label><br>
                  <select class=" form-control" onchange="validaMun()" data-live-search="true" name="loc_alu" id='loc_alu' <?php if($editar=='N') { echo "disabled"; }?>>
                    <option value="">Seleccione....</option><?php 
                    $locali_query=mysqli_query($link,"SELECT id_ciudad, ciudad,id_estado FROM ciudades ORDER BY ciudad ASC");
                    while($row = mysqli_fetch_array($locali_query))
                    { 
                      $id_ciudad=$row['id_ciudad'];
                      $ciudad=($row['ciudad']);
                      $id_estado=$row['id_estado'];
                      $selected ='';
                      if($locali == $id_ciudad){$selected='selected';}
                      echo '<option class="region_opt region_'.$id_estado.'" value="'.$id_ciudad.'"'.$selected.'>'.($ciudad)."</option>";
                    } ?>  
                  </select>
                </div>
                <div class="col-md-3 form-group">
                  <label for="loc_alu" >Municipio</label><br>
                  <select class=" form-control" onchange="validaMun()" data-live-search="true" name="muni_alu" id='muni_alu' tabindex="4" <?php if($editar=='N') { echo "disabled"; }?>>
                    <option value="">Seleccione....</option><?php 
                    $munici_query=mysqli_query($link,"SELECT id_municipio, municipio,id_estado FROM municipios ORDER BY municipio ASC");
                    while($row = mysqli_fetch_array($munici_query))
                    { 
                      $id_municipio=$row['id_municipio'];
                      $id_estado=$row['id_estado'];
                      $municipio=($row['municipio']);
                      $selected ='';
                      if($municip == $id_municipio){$selected='selected';}
                      echo '<option class="region_opt region_'.$id_estado.'" value="'.$id_municipio.'"'.$selected.'>'.($municipio)."</option>";
                    } ?>  
                  </select>
                </div>
                <div class="col-md-2 form-group">
                  <label for="pai_alu">Pais de Nac.</label>
                  <input type="text" name="pai_alu" maxlength="30" required class="form-control" <?php if($editar=='N') { echo "readonly"; }?> value="<?= $pais ?>" >
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-2 form-group">
                  <label>Género: </label><br>               
                  <label class="radio-inline"><input type="radio" name="sex_alu" required value="M" <?php if( $sexo=="M") {echo "checked=true" ;}?>>Mas.</label>
                  <label class="radio-inline"><input type="radio" name="sex_alu" required value="F" <?php if( $sexo=="F") {echo "checked=true"; }?>>Fem.</label>
                </div>
                <div class="col-md-2 form-group">
                  <label for="tlf_alu">Celular</label>
                  <input type="text" name="tlf_alu" id="tlf_alu" onClick="this.select()" maxlength="30" required onkeypress="return valida(event)" class="form-control" value="<?php echo $telefono ?>">
                </div>
                <div class="col-md-4 form-group">
                  <label for="dir_alu">Direccion</label>
                  <input type="text" name="dir_alu" required maxlength="100" class="form-control" value="<?php echo $direccion ?>">
                </div>
                <div class="col-md-4 form-group">
                  <label for="mai_alu">Email</label>
                  <input type="email" required title="Ingrese un correo valido ya que con el podra recuperar su contraseña" name="mai_alu" data-bs-toggle="tooltip" data-bs-placement="top" title="Correo para la recuperación de clave" class="form-control" maxlength="50" value="<?php echo $correo ?>">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-4">
                  <label>Estado Civil de los Padres</label>
                  <select name="edoCivPadr" class="form-control">
                    <option value="1" <?php if ($edo_civil==1){echo "selected";} ?>>Casados</option>
                    <option value="2" <?php if ($edo_civil==2){echo "selected";} ?>>Separados</option>
                    <option value="3" <?php if ($edo_civil==3){echo "selected";} ?>>Unión de Hecho</option>
                    <option value="4" <?php if ($edo_civil==4){echo "selected";} ?>>Madre sola</option>
                    <option value="5" <?php if ($edo_civil==4){echo "selected";} ?>>Viudo/a</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Hermanos</label>
                  <input type="text" name="hermanos" maxlength="2" class="form-control" value="<?= $nro_herma ?>">
                </div>
                <div class="col-md-3">
                  <label>Lateridad</label>
                  <select name="mano" class="form-control">
                    <option value="1" <?php if ($lateridad==1){echo "selected";} ?>>Derecho</option>
                    <option value="2" <?php if ($lateridad==2){echo "selected";} ?>>Izquierdo</option>
                    <option value="3" <?php if ($lateridad==3){echo "selected";} ?>>Ambidiestro</option>
                  </select>
                </div>
              </div>
            </div>
            <!------------DATOS DEL REPRESENTANTE ------------------>
            <div class="row" style="background-color:#E0F2F1; padding: 10px;">
              <div class="col-md-12 from-group" style="margin-top: 2%;">
                <h3>Datos del Representante</h3>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-2 form-group">
                  <label for="ced_rep">Cedula</label>
                  <input type="text" required="" onkeypress="return ValCed(event)" name="ced_rep" maxlength="11"  class="form-control" value="<?php echo $cedrep ?>" <?php if($editar=='N' && empty($admin)) { ?> <?php echo "readonly"; }?> >
                </div>
                <div class="col-md-6 form-group">
                  <label for="nom_rep" >Nombre y Apellido</label>
                  <input type="text" required name="nom_rep" maxlength="50" class="form-control" value="<?php echo $nomrep ?>" 
                        <?php if($editar=='N' && empty($admin)) { ?> <?php echo "readonly"; }?>>
                </div>
                <div class="col-md-2 form-group">
                  <label for ="par_rep">Parentesco</label><br>
                  <select  name='par_rep' id='pare_rep' class="form-control">
                    <option value="">Seleccione....</option><?php
                    $select3 = mysqli_query($link,"SELECT * FROM parentescos");
                    while($row = mysqli_fetch_array($select3))
                    {
                      $parentesco=$row['idparen'];
                      $paren=$row['nomparen'];
                      $selected ='';
                      if($parenRep == $parentesco){$selected='selected';}
                      echo '<option value="'.$parentesco.'"'.$selected.'>'.$paren."</option>";
                    }?>
                  </select>
                </div>
                <div class="col-md-2 form-group">
                  <label for="fna_rep" >Fecha de Nac.</label>
                  <input type="date" required="" name="fna_rep" class="form-control" value="<?= $fnac_repre ?>" >
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-4 form-group">
                  <label for="dir_rep" >Direccion</label>
                  <input type="text" required name="dir_rep" maxlength="100" class="form-control" value="<?= $dirrep ?>" >
                </div>
                <div class="col-md-4 form-group">
                  <label for="mai_rep">Email</label>
                  <input type="email" name="mai_rep" required maxlength="50" class="form-control" value="<?= $mairep ?>">
                </div>
                <div class="col-md-2 form-group">
                  <label for="tlf_hab_rep" >Telefono Hab.</label>
                  <input type="text" required name="tlf_hab_rep" id="tlf_hab_rep" onClick="this.select()" onkeypress="return valida(event)" class="form-control" value="<?= $tlfrep ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
                <div class="col-md-2 form-group">
                  <label for="tlf_cel_rep" >Celular Personal</label>
                  <input type="text"  name="tlf_cel_rep" id="tlf_cel_rep" data-bs-toggle="tooltip" data-bs-placement="top" title="Preferiblemente whatsapp, para que pueda recibir información emitida por la institución" onClick="this.select()" onkeypress="return valida(event)"  class="form-control" value="<?php echo $tlf_cel_rep ?>">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-6 form-group">
                  <label for="lug_trab_rep" >Lugar de trabajo</label>
                  <input type="text" required name="lug_trab_rep" maxlength="100" class="form-control" value="<?php echo $lug_trab_rep ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label for="ocup_rep" >Profesión</label>
                  <input type="text" required name="ocup_rep" id="ocup_rep" maxlength="50" class="form-control" value="<?php echo $ocup_rep ?>">
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
                  <input type="text" required  onkeypress="return ValCed(event)" name="ced_mama" maxlength="11" class="form-control" id="ced_mama" value="<?= $ced_mama ?>" >
                </div>
                <div class="form-group col-md-4">
                  <label for="nom_mama">Nombre y Apellido</label>
                    <input type="text" required name="nom_mama" maxlength="50" class="form-control" id="nom_mama" value="<?= $nom_ape_mama ?>" >
                </div>
                <div class="form-group col-md-2">
                  <label for="fecNac_mama" >Fecha de Nac.</label>
                  <input type="date" name="fecNac_mama" onkeypress="return valida(event)"  class="form-control" id="fecNac_mama" onClick="this.select()" value="<?= $fnac_mama ?>">
                </div>
                <div class="form-group col-md-2">
                  <label for="tlf_cel_mama" >Celular Personal</label>
                  <input type="text" name="tlf_cel_mama" maxlength="15" onkeypress="return valida(event)"  class="form-control" id="tlf_cel_mama" onClick="this.select()" value="<?= $tlf_cel_mama ?>">
                </div>
                <div class="form-group col-md-2 col-xs-6 col-sm-6">
                  <label for="tlf_hab_mama" >Teléfono Hab.</label>
                  <input type="text" name="tlf_hab_mama" maxlength="15" onkeypress="return valida(event)" class="form-control" id="tlf_hab_mama" onClick="this.select()" value="<?= $tlf_hab_mama ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="form-group col-md-5">
                  <label for="nacio_mama" >Lugar de nacimiento</label>
                  <input type="text" name="nacio_mama" class="form-control" value="<?= $lugar_nac_mama ?>" id="nacio_mama">
                </div>
                <div class="form-group col-md-5">
                  <label for="ocup_mama" >Profesión</label>
                  <input type="text" name="ocup_mama" maxlength="30" class="form-control" id="ocup_mama" value="<?= $ocupa_mama ?>">
                </div>
                <div class="form-group col-md-2 col-xs-6 col-sm-6">
                  <label for="tlf_ofi_mama" >Teléfono Oficina</label>
                  <input type="text" name="tlf_ofi_mama" maxlength="30" onkeypress="return valida(event)" class="form-control" id="tlf_ofi_mama" onClick="this.select()" value="<?= $tlf_ofi_mama ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="form-group col-md-5">
                  <label for="dir_mama" >Dirección Habitación</label>
                  <input type="text" name="dir_mama" maxlength="50" class="form-control" id="dir_mama" value="<?= $dire_mama ?>">
                </div>
                <div class="form-group col-md-5">
                  <label for="lug_trab_mama" >Donde trabaja? Dirección:</label>
                  <input type="text" name="lug_trab_mama" maxlength="50" class="form-control" id="lug_trab_mama" value="<?= $lug_trab_mama ?>">
                </div>
                <div class="col-md-2">
                  <label>Estudios</label>
                  <select class="form-control" name="est_mama" id="est_mama">
                    <option value="1" <?php if ($estudio_mama==1) { echo "selected";} ?>>Primarios</option>
                    <option value="2" <?php if ($estudio_mama==2) { echo "selected";} ?>>Secundarios</option>
                    <option value="3" <?php if ($estudio_mama==3) { echo "selected";} ?>>Tercearios o Universitarios</option>
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
                  <input type="text" required  onkeypress="return ValCed(event)" name="ced_papa" maxlength="11" class="form-control" id="ced_papa" value="<?= $ced_papa ?>" >
                </div>
                <div class="form-group col-md-4">
                  <label for="nom_papa">Nombre y Apellido</label>
                    <input type="text" required name="nom_papa" maxlength="50" class="form-control" id="nom_papa" value="<?= $nom_ape_papa ?>" >
                </div>
                <div class="form-group col-md-2">
                  <label for="fecNac_papa" >Fecha de Nac.</label>
                  <input type="date" name="fecNac_papa" onkeypress="return valida(event)"  class="form-control" id="fecNac_papa" onClick="this.select()" value="<?= $fnac_papa ?>">
                </div>
                <div class="form-group col-md-2">
                  <label for="tlf_cel_papa" >Celular Personal</label>
                  <input type="text" name="tlf_cel_papa" maxlength="30" onkeypress="return valida(event)"  class="form-control" id="tlf_cel_papa" onClick="this.select()" value="<?= $tlf_cel_papa ?>">
                </div>
                <div class="form-group col-md-2 col-xs-6 col-sm-6">
                  <label for="tlf_hab_papa" >Teléfono Hab.</label>
                  <input type="text" name="tlf_hab_papa" maxlength="14" onkeypress="return valida(event)" class="form-control" id="tlf_hab_papa" onClick="this.select()" value="<?= $tlf_hab_papa ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="form-group col-md-5">
                  <label for="nacio_papa" >Lugar de nacimiento</label>
                  <input type="text" name="nacio_papa" class="form-control" value="<?= $lugar_nac_papa ?>" id="nacio_papa">
                </div>
                <div class="form-group col-md-5">
                  <label for="ocup_papa" >Profesión</label>
                  <input type="text" name="ocup_papa" maxlength="30" class="form-control" id="ocup_papa" value="<?= $ocupa_papa ?>">
                </div>
                <div class="form-group col-md-2 col-xs-6 col-sm-6">
                  <label for="tlf_ofi_papa" >Teléfono Oficina</label>
                  <input type="text" name="tlf_ofi_papa" maxlength="30" onkeypress="return valida(event)" class="form-control" id="tlf_ofi_papa" onClick="this.select()" value="<?= $tlf_ofi_papa ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="form-group col-md-5">
                  <label for="dir_papa" >Dirección Habitación</label>
                  <input type="text" name="dir_papa" maxlength="50" class="form-control" id="dir_papa" value="<?= $dire_papa ?>">
                </div>
                <div class="form-group col-md-5">
                  <label for="lug_trab_papa" >Donde trabaja? Dirección:</label>
                  <input type="text" name="lug_trab_papa" maxlength="50" class="form-control" id="lug_trab_papa" value="<?= $lug_trab_papa ?>">
                </div>
                <div class="col-md-2">
                  <label>Estudios</label>
                  <select class="form-control" name="est_papa" id="est_papa">
                    <option value="1" <?php if ($estudio_papa==1) { echo "selected";} ?>>Primarios</option>
                    <option value="2" <?php if ($estudio_papa==2) { echo "selected";} ?>>Secundarios</option>
                    <option value="3" <?php if ($estudio_papa==3) { echo "selected";} ?>>Tercearios o Universitarios</option>
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
                  <input type="text" name="nom_emerg_1" maxlength="50" class="form-control" value="<?= $nom_emerg_1 ?>">
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
                        if($pare_emerg_1 == $parentesco){$selected='selected';}
                      echo '<option value="'.$parentesco.'"'.$selected.'>'.$paren."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-2 form-group">
                  <label for="telf_emerg_hab_1" >Teléfono Hab.</label>
                  <input type="text" name="tlf_emerg_hab_1" id="tlf_emerg_hab_1"  onClick="this.select()" maxlength="20" class="form-control" value="<?= $tlf_emerg_hab_1 ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
                <div class="col-md-2 form-group">
                  <label for="telf_emerg_ofi_1" >Teléfono Ofi.</label>
                  <input type="text" name="tlf_emerg_ofi_1" id="tlf_emerg_ofi_1"  onClick="this.select()" maxlength="20" class="form-control" value="<?= $tlf_emerg_ofi_1 ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
                <div class="col-md-2 form-group">
                  <label for="telf_emerg_cel_1" >Teléfono Cel.</label>
                  <input type="text" name="tlf_emerg_cel_1" id="tlf_emerg_cel_1"  onClick="this.select()" maxlength="20" class="form-control" value="<?= $tlf_emerg_cel_1 ?>">
                </div>
              </div>

              <div class="row" style="margin-top:2%;">
                <div class="col-md-4 form-group">
                  <label for="nom_emerg_2">Nombre y Apellido</label>
                  <input type="text" name="nom_emerg_2" maxlength="50" class="form-control" value="<?= $nom_emerg_2 ?>">
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
                        if($pare_emerg_2 == $parentesco){$selected='selected';}
                      echo '<option value="'.$parentesco.'"'.$selected.'>'.$paren."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-2 form-group">
                  <label for="telf_emerg_hab_2" >Teléfono Hab.</label>
                  <input type="text" name="tlf_emerg_hab_2" id="tlf_emerg_hab_2" maxlength="20" onClick="this.select()" class="form-control" value="<?= $tlf_emerg_hab_2 ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
                <div class="col-md-2 form-group">
                  <label for="telf_emerg_ofi_2" >Teléfono Ofi.</label>
                  <input type="text" name="tlf_emerg_ofi_2" id="tlf_emerg_ofi_2" maxlength="20" onClick="this.select()" class="form-control" value="<?= $tlf_emerg_ofi_2 ?>" title="agregue codigo de area ej. 0243-000.00.00">
                </div>
                <div class="col-md-2 form-group">
                  <label for="telf_emerg_cel_2" >Teléfono Cel.</label>
                  <input type="text" name="tlf_emerg_cel_2" id="tlf_emerg_cel_2" maxlength="20" onClick="this.select()" class="form-control" value="<?= $tlf_emerg_cel_2 ?>">
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
                  <input type="text" name="controlPopo" maxlength="10" class="form-control" value="<?= $edad_efinteres ?>">
                </div>
                <div class="col-md-4">
                  <label>Actualmente va al baño solo?</label>
                  <select class="form-control" name="vaSolo">
                    <option value="1" <?php if ($ducha_solo==1){echo "selected";} ?>>SI</option>
                    <option value="2" <?php if ($ducha_solo==2){echo "selected";} ?>>NO</option>
                    <option value="3" <?php if ($ducha_solo==3){echo "selected";} ?>>Con Ayuda</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Por las noches moja la cama?</label>
                  <select class="form-control" name="mojaCama">
                    <option value="2" <?php if ($moja_cama==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($moja_cama==1){echo "selected";} ?>>SI</option>
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
                  <input type="text" name="alergias" maxlength="100" class="form-control" value="<?= $alergico ?>">
                </div>
                <div class="col-md-6">
                  <label>Tiene alguna dificultad motora? (Indique cual)</label>
                  <input type="text" name="difiMotora" maxlength="100" class="form-control" value="<?= $defic_motora ?>">
                </div>
                <div class="col-md-3">
                  <label>Le realizaron examenes?</label>
                  <select class="form-control" name="examMotor">
                    <option value="2" <?php if ($examen_motora==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($examen_motora==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-9">
                  <label>Sufrio algun accidente, convulsiones, enfermedades? (Explique)</label>
                  <input type="text" name="sufrioAcci" maxlength="100" class="form-control" value="<?= $accidentes ?>">
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
                    <option value="2" <?php if ($bronquiti==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($bronquiti==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Hepatitis</label>
                  <select class="form-control" name="padeHepa">
                    <option value="2" <?php if ($hepatitis==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($hepatitis==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Paperas</label>
                  <select class="form-control" name="padePape">
                    <option value="2" <?php if ($paperas==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($paperas==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Asma</label>
                  <select class="form-control" name="padeAsma">
                    <option value="2" <?php if ($asma==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($asma==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Varicela</label>
                  <select class="form-control" name="padeVaric">
                    <option value="2" <?php if ($varicela==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($varicela==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Resfrios</label>
                  <select class="form-control" name="padeResfri">
                    <option value="2" <?php if ($resfrio==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($resfrio==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-2">
                  <label>Está medicado?</label>
                  <select class="form-control" name="medicado">
                    <option value="2" <?php if ($es_medicado==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($es_medicado==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Ve bien?</label>
                  <select class="form-control" name="veBien">
                    <option value="2" <?php if ($ve_bien==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($ve_bien==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Usa lentes?</label>
                  <select class="form-control" name="anteojos">
                    <option value="2" <?php if ($lentes==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($lentes==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Oye bien?</label>
                  <select class="form-control" name="audio">
                    <option value="2" <?php if ($oye_bien==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($oye_bien==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Usa audifonos?</label>
                  <select class="form-control" name="aparatos">
                    <option value="2" <?php if ($audifono==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($audifono==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Grupo sanguineo:</label>
                  <select name="tipoSangre" class="form-control">
                    <option value="0">Seleccione</option>
                    <option value="O-" <?php if ($sangre=='O-'){echo "selected";} ?>>O Negativo</option>
                    <option value="O+" <?php if ($sangre=='O+'){echo "selected";} ?>>O Positivo</option>
                    <option value="A-" <?php if ($sangre=='A-'){echo "selected";} ?>>A Negativo</option>
                    <option value="A+" <?php if ($sangre=='A+'){echo "selected";} ?>>A Positivo</option>
                    <option value="B-" <?php if ($sangre=='B-'){echo "selected";} ?>>B Negativo</option>
                    <option value="B+" <?php if ($sangre=='B+'){echo "selected";} ?>>B Positivo</option>
                    <option value="AB-" <?php if ($sangre=='AB-'){echo "selected";} ?>>AB Negativo</option>
                    <option value="AB+" <?php if ($sangre=='AB+'){echo "selected";} ?>>AB Positivo</option>
                  </select>
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-6">
                  <label>Tiene alguna dificultad cardiológica?</label>
                  <input type="text" name="enfeCardio" maxlength="100" class="form-control" value="<?= $cardiologica ?>">
                </div>
                <div class="col-md-6">
                  <label>Tiene alguna dificultad respiratoria?</label>
                  <input type="text" name="enfeRespi" maxlength="100" class="form-control" value="<?= $respiratoria ?>">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-6">
                  <label>Pediatra que lo atiende:</label>
                  <input type="text" name="atenPedia" maxlength="50" class="form-control" value="<?= $pediatra ?>">
                </div>
                <div class="col-md-4">
                  <label>Clínica - Hospital:</label>
                  <input type="text" name="atenClini" maxlength="50" class="form-control" value="<?= $clinica ?>">
                </div>
                <div class="col-md-2">
                  <label>Telefono:</label>
                  <input type="text" name="atenTlf" maxlength="15" class="form-control" value="<?= $tlfClinica ?>">
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
                    <option value="2" <?php if ($bsc==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($bsc==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Anti-Poliomielitica</label>
                  <select class="form-control" name="vacu_polio">
                    <option value="2" <?php if ($polio==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($polio==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Pentavelente</label>
                  <select class="form-control" name="vacu_penta">
                    <option value="2" <?php if ($penta==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($penta==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Anti-Hepatitis B</label>
                  <select class="form-control" name="vacu_hepat">
                    <option value="2" <?php if ($anti_hepati==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($anti_hepati==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Triple Bacteriana</label>
                  <select class="form-control" name="vacu_bacte">
                    <option value="2" <?php if ($bacteriana==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($bacteriana==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Trivalente Viral</label>
                  <select class="form-control" name="vacu_trival">
                    <option value="2" <?php if ($triple_viral==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($triple_viral==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Anti-Amarilica</label>
                  <select class="form-control" name="vacu_amari">
                    <option value="2" <?php if ($amarilla==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($amarilla==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Doble-Viral</label>
                  <select class="form-control" name="vacu_doble">
                    <option value="2" <?php if ($doble_viral==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($doble_viral==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Toxoide Tetanico</label>
                  <select class="form-control" name="vacu_teta">
                    <option value="2" <?php if ($tetanico==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($tetanico==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Toxoide Difterico</label>
                  <select class="form-control" name="vacu_difte">
                    <option value="2" <?php if ($difterico==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($difterico==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Anti-Influenza</label>
                  <select class="form-control" name="vacu_influ">
                    <option value="2" <?php if ($influenza==2){echo "selected";} ?>>NO</option>
                    <option value="1" <?php if ($influenza==1){echo "selected";} ?>>SI</option>
                  </select>
                </div>
                <div class="col-md-10">
                  <label>Otras vacunas aplicadas:</label>
                  <input type="text" name="vacu_otras" maxlength="100" class="form-control" value="<?= $otras ?>">
                </div>
              </div>
            </div>


            <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
              <button type="submit" value="1" name="enviar" class="btn btn-success btn-lg"><i class="ri-upload-cloud-line"></i> Guardar Cambios</button>
            </div>
            <?php if ($habi==1) {?>
            <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
              <a href="planilla.php" target="_blank"><button type="button" style="width: 100%;" class="btn btn-primary btn-lg"><i class="ri-printer-line"></i> Imprimir Planilla</button></a>
            </div><?php 
            }?>
          </div>
        </form>
      </div>
    </section>
     

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script src="includes/jquery.maskedinput/src/jquery.mask.js" type="text/javascript"></script>
  <script type="text/javascript">
    jQuery(document).ready(function($) 
    {
      municipio();  
    });
    function municipio() 
    {
      $('.region_opt').hide();
      $('.region_'+$('#edo_alu').val()).show();
    }
    function buscaUsu() {
      usu=$('#loginUser').val()
      $.post('usuario-busca.php',{'usua':usu},function(data)
      {
        if(data.isSuccessful)
        {
          Swal.fire({
            icon: 'info',
            title: 'Información!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Usuario ya existe, por favor ingrese otro...'
          })
          $('#loginUser').val('')
        }
      }, 'json');
    }
    $("#tlf_alu").mask("????-???.??.??");
    $("#tlf_cel_rep").mask("????-???.??.??");
    $("#tlf_cel_mama").mask("????-???.??.??");
    $("#tlf_cel_papa").mask("????-???.??.??");
    $("#tlf_hab_rep").mask(" ????-???.??.??");
    $("#tlf_hab_mama").mask("????-???.??.??");
    
        
    $("#tlf_emerg_hab_1").mask("????-???.??.??");
    $("#tlf_emerg_ofi_1").mask("????-???.??.??");
    $("#tlf_emerg_cel_1").mask("????-???.??.??");

    $("#tlf_emerg_hab_2").mask("????-???.??.??");
    $("#tlf_emerg_ofi_2").mask("????-???.??.??");
    $("#tlf_emerg_cel_2").mask("????-???.??.??");
    
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
    function archivo1(evt) 
    {
      var files1 = evt.target.files; // FileList object
      // Obtenemos la imagen del campo "file".
      for (var i = 0, f; f = files1[i]; i++) 
      {
        if (!f.type.match('image.*')) 
        { alert("FORMATO DE IMAGEN INCORRECTO");
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
            document.getElementById("list1").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
          };
        })(f);
        reader.readAsDataURL(f);
      }
    }
    document.getElementById('files1').addEventListener('change', archivo1, false);
    function validacion() 
    {
      var edoNac = $('#edo_alu').val(); 
      var lugNac = $('#loc_alu').val();
      var munNac = $('#muni_alu').val();
      var pare_rep = $('#pare_rep').val();
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
    }
    function ValCed(e)
    {
      tecla = (document.all) ? e.keyCode : e.which;
      if (tecla==8)
      {
        return true;
      }
      patron =/[0-9]/;
      tecla_final = String.fromCharCode(tecla);
      return patron.test(tecla_final);
    }
  </script><?php 
  if(isset($_GET['actual']))
  { ?>
    <script type="text/javascript">
      Swal.fire({
        icon: 'success',
        title: 'Excelente!',
        confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Entendido',
        text: 'Sus datos fueron almacenados exitosamente'
    })
    </script><?php
  }
  if(isset($_GET['complet']))
  { ?>
    <script type="text/javascript">
      Swal.fire({
        icon: 'info',
        title: 'Información!',
        confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Entendido',
        text: 'Para poder ver todas las opciones de nuestra página web, por favor, complete todos los datos del formulario incluyendo la foto del estudiante y el representante luego haga click en Guardar Cambios'
    })
    </script><?php
  } ?>

</body>

</html>