<?php
include_once "../include/header.php";
$link = Conectarse();
$proxAnio=PROXANOE;
$periodo_query = mysqli_query($link,"SELECT * FROM periodos WHERE nombre_periodo ='$proxAnio' and adultos='N' "); 
while ($row = mysqli_fetch_array($periodo_query))
{
    $tablaPeriodo=$row['tablaPeriodo'];
    $periodo=$row['nombre_periodo'];
}
$guarda = 'nuevo'; //(isset($_GET['guarda'])) ? $_GET['guarda'] : 'actual' ;
$grado = 61; //(isset($_GET['gra'])) ? $_GET['gra'] : '' ;
$seccion = 7; //(isset($_GET['sec'])) ? $_GET['sec'] : '' ;

if ($guarda=='nuevo') 
{
    $nacion=''; $cedula=''; $miUsuario=''; $clave=''; $nombre=''; $apellido='';
    $sexo=''; $fechanac=''; $locali=''; $municip=''; $estado='';
    $idEstado=''; $pais='VENEZUELA'; $direccion=''; $telefono=''; $ced_rep=''; $correo='';
    $foto_alu=''; $nomarch=''; $editar=''; $agosto=0;
    $parenRep=''; $representante=''; $correo_rep=''; $tlf_rep=''; $dir_rep='';
    $foto_rep=''; $fnac_repre=''; $tlf_celu='';
    $ced_reci=''; $nom_reci=''; $dir_reci='';
    $montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$grado' "); 
    $totalPeriodo=0; $meses=0; $morosida=0; $exonera=0;$pagado=0;
    while ($row = mysqli_fetch_array($montos_query))
    {
        $totalPeriodo=$totalPeriodo+$row['monto'];
        $meses++;
        ${'insc'.$meses} = $row['insc'];
        ${'mes'.$meses} = $row['mes'];
        ${'f_vence'.$meses} = $row['fecha_vence'];
        ${'monto'.$meses} = $row['monto'];
        ${'desc'.$meses}=0;
        if($row['fecha_vence']<$fechaHoy)
        {
            $morosida=$morosida+($row['monto']);
        }
    }
    $pendiente=$totalPeriodo;
    $pagos=0;
}?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Ficha del Estudiante Bachillerato</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos Principales</h6>
        </div>
        <div class="card-body">
            <form role="form" method="POST" onsubmit="return validacion();" enctype="multipart/form-data" action="nuevo-ingreso-guarda.php" >
                <!--Fotos-->
                <div class="form-row">
                    <div class="col-md-12" style="height: 40px;" >
                        <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;  margin-top: -10px;  background: #5499C7; color: white; " onclick="fnShowSecciones('#fotos_1','#btn_1');"><i class="fas fa-chevron-right f26em" id="btn_1"> </i>
                        <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Foto del Estudiante y Representante</strong></button>
                    </div>
                    <div class="col-md-12" style="display: none;" id="fotos_1">
                        <div class="form-row">
                            <div class="col-md-4  text-center">
                                <output id="list"><?php 
                                    if(empty($foto_alu)) 
                                    { ?>
                                        <img class='img-circle from-group' id="foto_alu" src="../img/usuario.png" /><?php 
                                    } else 
                                    { 
                                        if (file_exists('../../../fotoalu/'.$foto_alu)) 
                                        {
                                            $foto='../../../fotoalu/'.$foto_alu.'?'.time().mt_rand(0, 99999);
                                        }else
                                        {
                                            $foto='../../../fotoalu/'.$foto_alu.'?'.time().mt_rand(0, 99999);
                                        }
                                        ?>
                                        <img class='img-circle from-group' id="fotox" src="<?= $foto ?>" /><?php 
                                    } ?>
                                        
                                </output><br>
                                <label class="btn btn-primary">Estudiante<input type="file" name="foto_alu" id="files" accept=".jpg, .jpeg, .png" style="display: none;"></label>
                            </div>
                            <div class="col-md-4  text-center">
                                <output id="list1"><?php 
                                    if(empty($foto_rep)) 
                                    { ?>
                                        <img class='img-circle from-group' id="foto_nue" src="../img/usuario.png" /><?php 
                                    } else 
                                    { ?>
                                        <img class='img-circle from-group' src="<?= '../../../fotorep/'.$foto_rep.'?'.time().mt_rand(0, 99999); ?>" /><?php 
                                    } ?>
                                </output><br>
                                <label class="btn btn-primary">Representante<input type="file" name="foto_rep" id="files1" accept=".jpg, .jpeg, .png" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="col-md-12 " style="background-color: #CCD1D1; margin-top: 2%; margin-bottom: 2%; height: .5px;" ></div>
                    </div>
                    <div class="col-md-12" style="height: 40px;" >
                        <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;  margin-top: -10px;  background: #5499C7; color: white; " onclick="fnShowSecciones('#admin_1','#btn_2');"><i class="fas fa-chevron-right f26em" id="btn_2"> </i>
                        <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Datos de Administración</strong></button>
                    </div>
                    <style type="text/css">
                        .titulo{
                            background-color:#81C784;
                            color: white;
                         }
                         .titInsc{
                            background-color: #43A047;
                            color: white;
                         }
                         .tieneDesc{
                            background-color: #FFCDD2;
                         }
                         .montosDer{
                            text-align: right;
                         }
                    </style>
                    <div class="col-md-12" style="display: none; background-color: #C8E6C9; margin-bottom: 1%;" id="admin_1">
                        <div class="form-row">
                            <div class="col-md-12 text-center"><h4>Montos hacen referencia unicamente al periodo <?= $periodo ?></h4></div>
                            <div class="form-row col-md-4 text-center">
                                <div class="col-md-6">
                                    <label>Periodo</label>
                                    <label class="form-control"><?= number_format($totalPeriodo,2,',','.').' $' ?></label>
                                </div>
                                <div class="col-md-6">
                                    <label>Pagado</label>
                                    <label class="form-control" data-toggle="tooltip" data-placement="top" title="<?= $pagos ?> pagos realizados "><?= number_format($pagado,2,',','.').' $' ?></label>
                                    <input type="hidden" name="pagado" value="<?= $pagado ?>">
                                </div>    
                            </div>
                            <div class="form-row col-md-4 text-center">
                                <div class="col-md-6">
                                    <label>Abono Agost.</label>    
                                    <label class="form-control" ><?= number_format($agosto,2,',','.').' $' ?></label>
                                </div>
                                <div class="col-md-6">
                                    <label>Pendiente $</label>
                                    <label class="form-control"><?= number_format($pendiente,2,',','.').' $' ?></label>
                                </div>
                            </div>
                            <div class="form-row col-md-4 text-center">
                                <div class="col-md-6">
                                    <label>Morosidad $</label>
                                    <label class="form-control"><?= number_format($morosida,2,',','.').' $' ?></label>
                                </div>
                                <div class="col-md-6">
                                    <label>Descuento:</label>
                                    <input type="text" name="descGen" onkeyup="cambiaColorTodos();descTodos()" onkeypress="return ValMon(event)" onClick="this.select()" id="descGen" class="form-control">
                                </div>
                            </div>
                            <div class="row col-md-10 offset-md-1">
                                <div class="col-md-6 titInsc">
                                    <label>Concepto:</label>
                                    <p><?= $mes1.' Periodo '.$periodo ?></p>
                                </div>
                                <div class="col-md-3 titInsc">
                                    <label>Monto:</label>
                                    <p><?= $monto1.' $' ?></p>
                                </div>
                                <div class="col-md-3 titInsc">
                                    <label>Descuen.Inscrip.:</label><?php 
                                    if($cargoAct>=1)
                                    {?>
                                        <input type="text" onkeyup="cambiaColor('1')" name="desc1" id="desc1" value="<?= $desc1 ?>"  onkeypress="return ValMon(event)" onClick="this.select()" <?php if($desc1>0){echo 'style="color:red;"'; } ?> class="form-control montosDer"><?php 
                                    }else
                                    {?>
                                        <input type="text" readonly value="<?= $desc1 ?>" <?php if($desc1>0){echo 'style="color:red;"'; } ?> class="form-control montosDer">
                                        <!--label class="form-control montosDer" <?php if($desc1>0){echo 'style="color:red; background-color:#FFCDD2; "'; } ?>><?= $desc1 ?></label-->
                                        <input type="hidden" name="desc1" id="desc1" value="<?= $desc1 ?>" <?php if($desc1>0){echo 'style="color:red;"'; } ?> class="form-control montosDer"><?php 
                                    }?>
                                </div>
                            </div>
                            <div class="form-row col-md-12"></div><?php
                            for ($i=1; $i <=2 ; $i++) { ?>
                                <div class="form-row col-md-6">
                                    <div class="col-md-3 titulo">
                                        <label>Mes</label>
                                    </div>
                                    <div class="col-md-4 titulo">
                                        <label>Fecha Vence</label>
                                    </div>
                                    <div class="col-md-2 titulo">
                                        <label>Monto</label>
                                    </div>
                                    <div class="col-md-3 titulo">
                                        <label>Descuen.</label>
                                    </div>
                                </div><?php 
                            }
                            for ($i=1; $i <=$meses ; $i++) { 
                                if(${'insc'.$i} == NULL)
                                { ?>
                                    <div class="form-row col-md-6" >
                                        <div class="col-md-3 <?php if(${'desc'.$i}>0){echo 'tieneDesc'; } ?>" id="<?= 'divMes'.$i ?>">
                                            <label ><?= substr(${'mes'.$i},0,3).'/'.substr(${'f_vence'.$i}, 0,4) ?></label>
                                        </div>
                                        <div class="col-md-4 <?php if(${'desc'.$i}>0){echo 'tieneDesc'; } ?>" id="<?= 'divFec'.$i ?>">
                                            <label><?= date("d-m-Y", strtotime(${'f_vence'.$i})) ?></label>
                                        </div>
                                        <div class="col-md-2 <?php if(${'desc'.$i}>0){echo 'tieneDesc'; } ?>" id="<?= 'divMon'.$i ?>">
                                            <label><?= ${'monto'.$i}.'$' ?></label>
                                        </div>
                                        <div class="col-md-3"><?php
                                            if($cargoAct>=1)
                                            {?>
                                                <input type="text" onkeyup="cambiaColor('<?= $i ?>')" <?php if(${'desc'.$i}>0){echo 'style="color:red;"'; } ?> onkeypress="return ValMon(event)" onClick="this.select()" name="<?= 'desc'.$i ?>" id="<?= 'desc'.$i ?>" value="<?= ${'desc'.$i} ?>" class="form-control montosDer"><?php 
                                            }else
                                            {?>
                                                <input type="text" readonly class="form-control montosDer" <?php if(${'desc'.$i}>0){echo 'style="color:red; background-color:#FFCDD2; "'; } ?>  value="<?= ${'desc'.$i} ?>" >
                                                <input type="hidden" <?php if(${'desc'.$i}>0){echo 'style="color:red;"'; } ?> name="<?= 'desc'.$i ?>" id="<?= 'desc'.$i ?>" value="<?= ${'desc'.$i} ?>" ><?php 
                                            }?>

                                        </div>
                                    </div><?php
                                }
                            }?>
                            <input type="hidden" id="meses" name="meses" value="<?= $meses ?>">
                        </div>
                    </div>
                </div>
                <!--Periodo Grado Seccion-->
                <div class="form-row" style="background-color: #FFF9C4; padding: 5px;">
                    <div class="col-md-3 col-xs-12 col-sm-12">
                        <label for="peri_alu" >Año Escolar</label>
                        <label class="form-control"><?= $periodo ?></label>
                        <input type="hidden" name="tablaPeriodo" value="<?= $tablaPeriodo ?>">
                        <input type="hidden" name="nomPeriodo" value="<?= $periodo ?>">
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-3" id="divGrado">
                        <label for ="gra_alu">Grado o Año</label><br>
                        <select name='gra_alu' id='gra_alum' class="form-control">
                            <option value="">Seleccione....</option><?php
                            $select1 = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo );
                            while($row = mysqli_fetch_array($select1))
                            {
                                $id_gra=$row['grado'];
                                $nom_gra=$row['nombreGrado'];
                                $selected ='';
                                //if($grado == $id_gra){$selected='selected';}
                                echo '<option readonly value="'.$id_gra.'"'.$selected.'>'.($nom_gra)."</option>";
                            }
                            mysqli_free_result($select1);?>                        
                        </select>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-3" id="divSec">
                        <label for ="sec_alu">Sección</label><br>
                        <select name='sec_alu' id="sec_alumno" class="form-control" >
                            <option value="">Seleccione....</option><?php 
                            $select3 = mysqli_query($link,"SELECT * FROM secciones");
                            while($row = mysqli_fetch_array($select3))
                            {
                                $id_sec=$row['id'];
                                $nom_sec=$row['nombre'];
                                $selected ='';
                                //if($seccion == $id_sec){$selected='selected';}
                                echo '<option readonly value="'.$id_sec.'"'.$selected.'>'.utf8_encode($nom_sec)."</option>";
                            }
                            mysqli_free_result($select3); ?>                 
                        </select>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-3">
                        <label>Retirado <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="Indique fecha solo para el caso de retiro antes de culminar periodo escolar, de esta manera congela la morosidad del estudiante hasta la fecha indicada"></i></label>
                        <input type="date" class="form-control" name="retiraPagos" value="<?= $retiraPagos ?>">
                    </div>
                </div>
                <!--Estudiante-->
                <div class="card-header py-3" >
                    <h6 class="m-0 font-weight-bold text-primary">Estudiante</h6>
                </div>
                <div class="form-row" style="background-color: #E0E0E0; padding: 5px;">
                    <div class="col-md-3">
                        <label for="cedula">Nacion&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cedula</label>
                        <div class="form-row">
                            <div class="col-md-4">
                                <select name="nacion" id="nacion" class="form-control" onchange="paisCambia()" >
                                    <option value="V" <?php if($nacion=='V'){echo "selected"; } ?>>V</option>
                                    <option value="E" <?php if($nacion=='E'){echo "selected"; } ?>>E</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" onchange="cedClave()" required onkeypress="return ValCed(event)" name="cedula" id="cedula" <?php if($guarda!='nuevo'){echo "readonly";} ?> placeholder="Solo numeros" value="<?= $cedula ?>" onkeyup="fnBuscarAlum()" >    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="miUsuario">Usuario</label>
                        <input type="text" class="form-control" name="miUsuario" id="miUsuario" value="<?= $miUsuario ?>" >
                    </div>
                    <div class="col-md-3">
                        <label for="clave">Contraseña</label>
                        <input type="text" class="form-control" required name="clave" id="clave" value="<?= $clave ?>" >    
                    </div>
                    <div class="col-md-3">
                        <label for="fna_alu">Fecha Nac.</label>
                        <input type="date" class="form-control" required name="fna_alu" id="fna_alu" value="<?= $fechanac ?>" >    
                    </div>
                    <div class="col-md-6">
                        <label for="nombre">Nombres</label>
                        <input type="text" class="form-control" required name="nombre" id="nombre" value="<?= $nombre ?>" >    
                    </div>
                    <div class="col-md-6">
                        <label for="apellido">Apellidos</label>
                        <input type="text" class="form-control" required name="apellido" id="apellido" value="<?= $apellido ?>" >    
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-3" id="divEdo">
                        <label>Estado</label><br>
                        <select class="form-control" data-live-search="true" name="edo_alu" id='edo_alu' onchange="municipio();" >
                        <option value="">Seleccione....</option><?php
                        $query2 = mysqli_query($link,"SELECT id_edo,estado AS nomedo FROM estado");  
                            while($row = mysqli_fetch_array($query2))
                            {   
                                $nom_edoA=$row['nomedo'];
                                $id_edoA=$row['id_edo'];
                            }
                            $select2 = mysqli_query($link,"SELECT * FROM estado");
                            while($row = mysqli_fetch_array($select2))
                            {
                                $id_edo=$row['id_edo'];
                                $nom_edo=$row['estado'];
                                $selected ='';
                                if($idEstado == $id_edo){$selected='selected';}
                                echo '<option readonly value="'.$id_edo.'"'.$selected.'>'.($nom_edo)."</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-3" id="divLoc">
                        <label for="loc_alu" >Ciudad</label><br>
                        <select class=" form-control" data-live-search="true" name="loc_alu" id='loc_alu'>
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
                    <div class="col-md-3 col-xs-6 col-sm-3" id="divMun">
                        <label for="loc_alu" >Municipio</label><br>
                        <select class=" form-control" data-live-search="true" name="muni_alu" id='muni_alu' tabindex="4" >
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
                    <div class="col-md-3 col-xs-12 col-sm-3">
                        <label for="pai_alu">Pais de Nacimiento</label>
                        <input type="text" name="pai_alu" id="pai_alu" required maxlength="30" class="form-control" value="<?= $pais ?>" >
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-3">
                        <label>Sexo: </label><br>
                        <div class="form-check form-check-inline">
                          <label class="form-check-label"><input class="form-check-input" type="radio" name="sex_alu" id="sexM" value="M" <?php if($sexo == 'M'){echo "checked=true";} ?>>Masculino</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <label class="form-check-label"><input class="form-check-input" type="radio" name="sex_alu" id="sexF" value="F" <?php if($sexo == 'F'){echo "checked=true";} ?>>Femenino</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-3">
                        <label for="tlf_alu" >Telefono</label>
                        <input type="text" name="tlf_alu" maxlength="30" id="telefono" onkeypress="return valida(event)" class="form-control" value="<?= $telefono ?>">
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <label for="mai_alu">Email</label>
                        <input type="email" title="Ingrese un correo valido ya que con el podra recuperar su contraseña" name="mai_alu" id="email" class="form-control" required maxlength="50" value="<?= $correo ?>">
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-3">
                        <label for="dir_alu" >Direccion</label>
                        <input type="text" name="dir_alu" id="direccion" maxlength="100" class="form-control" value="<?= $direccion ?>">
                    </div>
                </div>
                <!--Representante-->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Representante</h6>
                </div>
                <div class="form-row" style="background-color: #C5CAE9; padding: 5px;">
                    <div class="col-md-3">
                        <label for="cedula">Cedula</label>
                        <input type="text" class="form-control" onkeypress="return ValCed(event)" required name="ced_rep" id="ced_rep" placeholder="ingrese solo numeros" <?php if($guarda=='nuevo'){echo 'onkeyup="fnBuscarRepr()"';} ?> value="<?= $ced_rep ?>" >    
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <label for="nom_rep" >Nombre y Apellido</label>
                        <input type="text" name="nom_rep" id="nom_rep" required maxlength="50" class="form-control" value="<?php echo $representante ?>" >
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6" id="divPar">
                        <label for ="par_rep">Parentesco</label><br>
                        <select  name='par_rep' id='par_rep' class="form-control">
                            <option value="">Seleccione....</option><?php
                            $select3 = mysqli_query($link,"SELECT * FROM parentescos");
                            while($row = mysqli_fetch_array($select3))
                            {
                                $parentesco=$row['idparen'];
                                $paren=$row['nomparen'];
                                $selected ='';
                                if($parentesco==1){$selected='selected';}
                                echo '<option value="'.$parentesco.'"'.$selected.'>'.$paren."</option>";
                            }
                            mysqli_free_result($select3);?>                             
                       </select>
                    </div>
                    <div class="form-group col-md-4 col-xs-12 col-sm-6">
                        <label for="dir_rep" >Direccion</label>
                        <input type="text" name="dir_rep" id="dir_rep" maxlength="100" class="form-control" value="<?= $dir_rep ?>" >
                    </div>
                    <div class="form-group col-md-4 col-xs-12 col-sm-6">
                        <label for="mai_rep">Email</label>
                        <input type="email" name="mai_rep" id="mai_rep" required maxlength="50" class="form-control" value="<?= $correo_rep ?>">
                    </div>
                    <div class="form-group col-md-2 col-xs-6 col-sm-6">
                        <label for="tlf_hab_rep" >Telefono Habitacion</label>
                        <input type="text" name="tlf_hab_rep" id="tlf_hab_rep" class="form-control" value="<?= $tlf_rep ?>">
                    </div>
                    <div class="form-group col-md-2 col-xs-6 col-sm-6">
                        <label for="tlf_cel_rep" >Celular Personal</label>
                        <input type="text"  name="tlf_cel_rep" id="tlf_cel_rep" required class="form-control" value="<?= $tlf_celu ?>">
                    </div>
                </div>
                <!--Emitir recibos a-->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Emitir recibos de pago a: (representante&nbsp;&nbsp;&nbsp;<input type="checkbox" onclick="copiaRepre()" name="emiteCheck" id="emiteCheck" style="cursor: pointer; transform: scale(2); " >&nbsp;&nbsp;)</h6>
                </div>
                <div class="form-row" style="background-color: #C5CAE9; padding: 5px;">
                    <div class="col-md-2">
                        <label for="ced_reci">Cedula o RIF</label>
                        <input type="text" class="form-control" required name="ced_reci" id="ced_reci"  value="<?= $ced_reci ?>" >    
                    </div>
                    <div class="col-md-5">
                        <label for="nom_reci" >Nombre o Razón Social</label>
                        <input type="text" name="nom_reci" id="nom_reci" required maxlength="50" class="form-control" value="<?= $nom_reci ?>" >
                    </div>
                    <div class="col-md-5">
                        <label for="dir_reci" >Direccion</label>
                        <input type="text" name="dir_reci" id="dir_reci" maxlength="100" class="form-control" value="<?= $dir_reci ?>" >
                    </div>
                </div>
                <div class="form-row" style="margin-top:1%;">
                    <div class="col-md-12">
                        <label>Enviar datos de acceso por correo al guardar?&nbsp;&nbsp;
                            <input type="checkbox" <?php if($url_actual=='localhost'){ echo "disabled";} ?> style="cursor: pointer; transform: scale(2);" name="enviar" value="1" ></label>
                    </div>
                    <div class="col-md-3" style="margin-top: 2%;">
                        <button type="button" style="width: 100%;" disabled class="btn btn-info"><i class="fas fa-print fa-sm"></i> Imprimir Planilla</button>
                    </div>
                    <div class="col-md-3" style="margin-top: 2%;">
                        <button type="button" style="width: 100%;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
                    </div>
                    <div class="col-md-3" style="margin-top: 2%;">
                        <button type="submit"  style="width: 100%;" id="btn-guarda" class="btn btn-primary"><i class="fas fa-save fa-sm"></i> Guardar</button>
                    </div>  
                    <div class="col-md-3" style="margin-top: 2%;">
                        <button type="button" disabled style="width: 100%;" id="btn-factura" class="btn btn-success"><i class="fas fa-dollar-sign fa-sm"></i> Facturar</button>
                    </div>  
                </div>
                <input type="hidden" name="id_quienPaga" id="id_quienPaga" value="<?= $id_quienPaga ?>">
                <input type="hidden" id="idAlum" name="idAlum" value="<?= encriptar($idAlum) ?>">
                <input type="hidden" name="ced_rep_vie" value="<?= $ced_rep ?>" > 
                <input type="hidden" name="debe" id="debe" value="0">
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        $('#page-top').removeClass("sidebar-toggled");
        $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        municipio();   
    });
    function paisCambia() {
        if($('#nacion').val()=='V'){
            $('#pai_alu').val('VENEZUELA')    
        }else{
            $('#pai_alu').val('')
        }
    }
    function copiaRepre() {
        if($("#emiteCheck").prop('checked')) {
            $('#ced_reci').val($('#ced_rep').val())
            $('#nom_reci').val($('#nom_rep').val())
            $('#dir_reci').val($('#dir_rep').val())
        }else
        {
            $('#ced_reci').val('')
            $('#nom_reci').val('')
            $('#dir_reci').val('')
        }
    }
    function cambiaColor(linea) {
        cifra=$('#desc'+linea).val()
        if(cifra>0)
        {
            document.getElementById('desc'+linea).style.color = "red";
            document.getElementById('divMes'+linea).style.backgroundColor = "#FFCDD2";
            document.getElementById('divFec'+linea).style.backgroundColor = "#FFCDD2";
            document.getElementById('divMon'+linea).style.backgroundColor = "#FFCDD2";
        }else 
        {
            document.getElementById('desc'+linea).style.color = "black";
            document.getElementById('divMes'+linea).style.backgroundColor = "#C8E6C9";
            document.getElementById('divFec'+linea).style.backgroundColor = "#C8E6C9";
            document.getElementById('divMon'+linea).style.backgroundColor = "#C8E6C9";
        }
    }
    function cambiaColorTodos() {
        cifra=$('#descGen').val()
        for (var i = 2; i <= $('#meses').val(); i++) {
            if(cifra>0)
            {
                document.getElementById('desc'+i).style.color = "red";
                document.getElementById('divMes'+i).style.backgroundColor = "#FFCDD2";
                document.getElementById('divFec'+i).style.backgroundColor = "#FFCDD2";
                document.getElementById('divMon'+i).style.backgroundColor = "#FFCDD2";
            }else 
            {
                document.getElementById('desc'+i).style.color = "black";
                document.getElementById('divMes'+i).style.backgroundColor = "#C8E6C9";
                document.getElementById('divFec'+i).style.backgroundColor = "#C8E6C9";
                document.getElementById('divMon'+i).style.backgroundColor = "#C8E6C9";
            }    
        }
    }
    function descTodos() {
        des=$('#descGen').val()
        mes=$('#meses').val()
        $('#desc2').val(des)
        for (var i = 2; i <= mes; i++) {
            $('#desc'+i).val(des)
        }
    }
    function validacion() 
    {
        var graAlu = document.getElementById("gra_alum");
        var secAlu = document.getElementById("sec_alumno");
        var edoNac = document.getElementById("edo_alu");
        var lugNac = document.getElementById("loc_alu");
        var munNac = document.getElementById("muni_alu");
        var parRep = document.getElementById("par_rep");
        if (graAlu.value.length==0 || secAlu.value.length==0 || edoNac.value.length==0 || lugNac.value.length==0 || munNac.value.length==0 || parRep.value.length==0  )
        {
            Swal.fire({
                  icon: 'info',
                  title: 'Verifique!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Faltan datos en el formulario!'
            })
            if (graAlu.value.length == 0 )
            { document.getElementById("divGrado").style.border="thin solid #FE0101";} else { document.getElementById("divGrado").style.border=""; }
            if (secAlu.value.length == 0 )
            { document.getElementById("divSec").style.border="thin solid #FE0101";} else { document.getElementById("divSec").style.border=""; }
            if (edoNac.value.length == 0 )
            { document.getElementById("divEdo").style.border="thin solid #FE0101";} else { document.getElementById("divEdo").style.border=""; }
            if (lugNac.value.length == 0 )
            {document.getElementById("divLoc").style.border="thin solid #FE0101";} else { document.getElementById("divLoc").style.border=""; }
            if (munNac.value.length == 0 )
            { document.getElementById("divMun").style.border="thin solid #FE0101";} else { document.getElementById("divMun").style.border=""; }
            if (parRep.value.length == 0 )
            { document.getElementById("divPar").style.border="thin solid #FE0101";} else { document.getElementById("divPar").style.border=""; }
            return false;
        }
    }
    function cedClave() {
        ced=$('#cedula').val()
        $('#clave').val(ced)
        $('#miUsuario').val(ced)
    }
    function municipio() 
    {
        $('.region_opt').hide();
        $('.region_'+$('#edo_alu').val()).show();
    }
    function archivo(evt) 
    {
        var files = evt.target.files; // FileList object
        // Obtenemos la imagen del campo "file".
        for (var i = 0, f; f = files[i]; i++) 
        {
            //Solo admitimos imágenes.
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
                    document.getElementById("list").innerHTML = ['<img class="img-circle" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
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
            {   alert("FORMATO DE IMAGEN INCORRECTO");
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
                    document.getElementById("list1").innerHTML = ['<img class="img-circle" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
                };
            })(f);
            reader.readAsDataURL(f);
        }
    }
    document.getElementById('files1').addEventListener('change', archivo1, false); 
    function fnShowSecciones(div,btn) 
    {
        $(div).slideToggle();
        $(btn).toggleClass("fas fa-chevron-right");
        $(btn).toggleClass("fas fa-chevron-down");
    } 
    function  fnBuscarAlum()
    {
        ced_buscar = $('#cedula').val();
        if(ced_buscar.length > 7 ){
            $.post('alumno-nuevo-buscar.php',{'ced':ced_buscar},function(data){
                if(data.isSuccessful){
                    Swal.fire({
                        icon: 'info',
                        title: 'Atencion!',
                        confirmButtonText:
                        '<i class="fa fa-thumbs-up"></i> Entendido',
                        html: 'Numero de cedula ya registrada en el sistema por favor usar el boton buscar y luego Reinscribir en la pestaña (Buscar Estudiante por Cedula, Nombre o Apellido)'  
                    })
                    $('#cedula').val('')
                    $('#clave').val('')
                }else
                {
                    $('#idAlum').val('')
                    $('#clave').val('')
                    $('#nombre').val('')
                    $('#apellido').val('')
                    document.querySelector('#sexM').checked = false;
                    document.querySelector('#sexF').checked = false;
                    $('#fna_alu').val('')
                    $('#loc_alu').val('')
                    $('#edo_alu').val('')
                    $('#muni_alu').val('')
                    $('#miUsuario').val('')
                    $('#direccion').val('')
                    $('#telefono').val('')
                    $('#ced_rep').val('')
                    $('#par_rep').val('')
                    $('#email').val('')
                    $("#foto_alu").attr("src",'../img/usuario.png');
                    $('#nom_rep').val('')
                    $('#mai_rep').val('')
                    $('#dir_rep').val('')
                    $('#tlf_hab_rep').val('')
                    $('#tlf_cel_rep').val('')
                    $("#foto_nue").attr("src",'../img/usuario.png');
                    document.getElementById("btn-guarda").disabled = false;
                }
          }, 'json');
        }
    }
    function  fnBuscarRepr()
    {
        ced_buscar = $('#ced_rep').val();
        if(ced_buscar.length > 5 ){
          $.post('repre-buscar.php',{'ced':ced_buscar},function(data){
            if(data.isSuccessful){
              $('#nom_rep').val(data.repr)
              $('#dir_rep').val(data.dire)
              $('#mai_rep').val(data.corr)
              $('#tlf_hab_rep').val(data.telf)
              $('#tlf_cel_rep').val(data.celu)
              $("#foto_nue").attr("src",data.foto);
            }else{
              $('#nom_rep').val('')
              $('#dir_rep').val('')
              $('#mai_rep').val('')
              $('#tlf_hab_rep').val('')
              $('#tlf_cel_rep').val('')
              $("#foto_nue").attr("src",'../img/usuario.png');
            }
          }, 'json');
        }
    }
</script>
<?php
include_once "../include/footer.php";  

if(isset($_GET['guar']) && $_GET['guar']=='1')
{ ?>
    <script type="text/javascript">
        //opener.document.location.reload();
        Swal.fire({
          icon: 'success',
          title: 'Excelente!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Datos actualizados correctamente!'
        })
    </script><?php
}
mysqli_free_result($montos_query);
mysqli_free_result($periodo_query);
mysqli_free_result($repre_query);
mysqli_free_result($quien_paga_query);
mysqli_free_result($matri_query);
mysqli_free_result($agosto_query);
mysqli_free_result($pagos_query);
mysqli_free_result($select1);
mysqli_free_result($select3);
mysqli_free_result($query2);
mysqli_free_result($select2);
mysqli_free_result($locali_query);
mysqli_free_result($munici_query);
?>
           