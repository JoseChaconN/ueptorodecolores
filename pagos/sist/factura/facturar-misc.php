<?php
include_once "../include/header.php";
$link = Conectarse();
if(isset($_GET['id']))
{
    $idAlum=desencriptar($_GET['id']);
    $tablaPeriodo=$_GET['peri'];
    $grado=$_GET['gra'];
    $tasa_query = mysqli_query($link,"SELECT monto FROM tasa_dia WHERE idTasa='1' "); 
    $row2=mysqli_fetch_array($tasa_query);
    $tasa=$row2['monto'];
    
    $datos_query = mysqli_query($link,"SELECT A.nombre AS alumno, A.cedula, A.apellido, A.ruta as foto_alu, C.ruta as foto_rep,B.nom_reci, B.ced_reci, B.dir_reci FROM alumcer A, emite_pago B, represe C WHERE A.idAlum='$idAlum' and C.cedula=A.ced_rep and A.id_quienPaga=B.id "); 
    while ($row = mysqli_fetch_array($datos_query))
    {   
        $cedula = $row['cedula'];
        $nombre = $row['alumno'].' '.$row['apellido'];
        $foto_alu = $row['foto_alu'];
        $foto_rep = $row['foto_rep'];
        $nom_reci=$row['nom_reci'];
        $ced_reci=$row['ced_reci'];
        $dir_reci=$row['dir_reci'];
    }
    if($grado<61)
    {
        $matri_query = mysqli_query($link,"SELECT A.*,B.nombreGrado AS nomgra, C.nombre AS nomsec FROM notaprimaria".$tablaPeriodo." A,grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
        $idGrado=''; 
        while ($row = mysqli_fetch_array($matri_query))
        {
            $idGrado=$row['grado'];
            $nomGrado = ($row['nomgra'].' "'.$row['nomsec'].'"');
        }
    }else
    {
        $matri_query = mysqli_query($link,"SELECT A.*,B.nombreGrado AS nomgra, C.nombre AS nomsec FROM matri".$tablaPeriodo." A,grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
        $idGrado=''; 
        while ($row = mysqli_fetch_array($matri_query))
        {
            $idGrado=$row['grado'];
            $nomGrado = ($row['nomgra'].' "'.$row['nomsec'].'"');
        }    
    }
    
    $nomGrado = ($idGrado>0) ? $nomGrado : 'No cursa periodo '.substr($tablaPeriodo,0,2).'-'.substr($tablaPeriodo,2,2) ;
    
    $pagos_query = mysqli_query($link,"SELECT A.*,B.nombrePago,C.nom_banco,D.afecta FROM miscelaneos A, formas_pago B, bancos C, conceptos D WHERE A.idAlum = '$idAlum' and A.recibo <> '' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.id ");
    ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="form-row">
            <div class="col-md-8">
                <h1 class="h3 mb-2 text-gray-800">Emisión de Factura Miscelaneos </h1>
            </div>
            <div class="col-md-4">
                <button type="button" style="width: 100%; color: black;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
            </div>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
           <div class="card-body">
                <div class="table-responsive">
                    <div class="form-row"><!--FOTO-->
                        <div class="col-md-12" style="height: 40px;">
                            <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;    background: #5499C7; color: white;" onclick="fnShowSecciones('#fotos_1','#btn_1');"><i class="fas fa-chevron-right f26em" id="btn_1"> </i>
                            <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Foto del Estudiante y Representante</strong></button>
                        </div>
                        <div class="col-md-12" style="display: none;" id="fotos_1">
                            <div class="form-row">
                                <div class="col-md-4  text-center"><?php  
                                    if (file_exists('../../../fotoalu/'.$foto_alu)) 
                                    {
                                        $foto_alu='../../../fotoalu/'.$foto_alu.'?'.time().mt_rand(0, 99999);
                                    }else
                                    {
                                        $foto_alu='../../../fotoalu/'.$foto_alu.'?'.time().mt_rand(0, 99999);
                                    }
                                    if (file_exists('../../../fotorep/'.$foto_rep)) 
                                    {
                                        $foto_rep='../../../fotorep/'.$foto_rep.'?'.time().mt_rand(0, 99999);
                                    }else
                                    {
                                        $foto_rep='../../../fotorep/'.$foto_rep.'?'.time().mt_rand(0, 99999);
                                    }?>
                                    <img class='img-circle from-group' id="fotox" src="<?= $foto_alu ?>" /><br>
                                    <label >Estudiante</label>
                                </div>
                                <div class="col-md-4  text-center">
                                    <img class='img-circle from-group' src="<?= $foto_rep ?>" /><br>
                                    <label >Representante</label>
                                </div>
                            </div>
                            <div class="col-md-12 " style="background-color: #CCD1D1; margin-top: 2%; margin-bottom: 2%; height: .5px;" ></div>
                        </div>
                    </div>
                    <div class="form-row"><!--CED NOMB GRADO-->
                        <div class="col-md-2 col-xs-12 col-sm-2">
                            <label>Cedula</label>
                            <input type="text" class="form-control" readonly value="<?= $cedula ?>">
                        </div>
                        <input type="hidden" name="ced_alu" value="<?= $cedula ?>">
                        <div class="col-md-4 col-xs-12 col-sm-5">
                            <label>Estudiante</label>
                            <input type="text" class="form-control" readonly value="<?= $nombre ?>">
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-5">
                            <label>Año/Grado</label>
                            <input type="text" class="form-control" readonly value="<?= $nomGrado ?>">
                            <input type="hidden" id="idGrado" value="<?= $idGrado ?>">
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-4"><!--PERIODO-->
                            <label for="peri_alu" >Año Escolar</label>
                            <select style="background-color: red; color: white; font-size: 18px; font-weight: bold; text-align: center; " name='peri_alu' id="peri_alu" disabled class="form-control"  ><?php
                                $peri2 = mysqli_query($link,"SELECT * FROM periodos  ORDER BY tablaPeriodo ASC");     
                                while($row = mysqli_fetch_array($peri2))
                                {   
                                    $nom_peri2=$row['nombre_periodo'];
                                    $tabla=$row['tablaPeriodo'];
                                    $selected='';
                                    if($tablaPeriodo == $tabla){
                                        $selected='selected';
                                    }
                                    echo '<option readonly value="'.$tabla.'"'.$selected.'>'.'>>> '.utf8_encode($nom_peri2)." <<<</option>";
                                }
                                mysqli_free_result($peri2);?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row" style="margin-top: 1%; "><!--TABLA DE HISTORIA-->
                        <div class="col-md-12" style="height: 40px;">
                            <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;    background: #5499C7; color: white;" onclick="fnShowSecciones('#historia_1','#btn_2');"><i class="fas fa-chevron-right f26em" id="btn_2"> </i>
                            <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Historia de Pagos</strong></button>
                        </div><?php 
                        if ($idGrado>0) {?>
                            <div class="col-md-12" style="display: none;" id="historia_1">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Recibo</th>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Monto $</th>
                                            <th>Monto Bs.</th>
                                            <th>Status</th>
                                            <th>Boton</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Recibo</th>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Monto $</th>
                                            <th>Monto Bs.</th>
                                            <th>Status</th>
                                            <th>Boton</th>
                                        </tr>
                                    </tfoot>
                                    <tbody><?php 
                                        $son=0;
                                        mysqli_data_seek($pagos_query, 0);
                                        while($row=mysqli_fetch_array($pagos_query)) 
                                        {
                                            $id=$row['id'];
                                            $nrodeposito=$row['nrodeposito'];
                                            $fechadepo=date("d-m-Y", strtotime($row['fechadepo']));
                                            $fecha=date("d-m-Y", strtotime($row['fecha']));
                                            $recibo=$row['recibo'];
                                            $monto=$row['monto'];
                                            $id_concepto=$row['id_concepto'];
                                            $concepto=$row['concepto'].' ('.$row['cantidad'].' Unid.)';
                                            $comentario=$row['comentario'];
                                            $nombrePago=$row['nombrePago'];
                                            $montoDolar=$row['montoDolar'];
                                            $montoTasa=$row['montoTasa'];
                                            $emitidoPor=$row['emitidoPor'];
                                            $statusPago=$row['statusPago'];
                                            $status = ($row['statusPago']=='1') ? 'Activo' : 'Anulado' ;
                                            $nom_banco=$row['nom_banco'];
                                            $opera='Operación: '.$nombrePago.' Banco: '.$nom_banco.', Ref.: '.$nrodeposito;?>
                                            <tr <?php if($statusPago=='2'){ echo 'style="background-color:#FFCDD2; "';} ?> class="<?= 'trLin'.$recibo ?>">
                                                <td><?= $son+=1; ?></td>
                                                <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                                <td><?= $fecha ?></td>
                                                <td><span data-toggle="tooltip" data-placement="top" title="<?= $opera ?>"><?= $concepto ?></span></td>
                                                <td align="right"><?= $montoDolar ?></td>
                                                <td align="right"><?= number_format($monto,2,',','.') ?></td>
                                                <td class="<?= 'sta'.$recibo ?>"><?= $status ?></td>
                                                <td>
                                                    <button onclick="verPago('<?= $recibo ?>','<?= $son ?>')" type="button" title='Ver datos del Pago' data-toggle="modal" data-target="#verPago" class="btn btn-info btn-circle" ><i class="fas fa-eye fa-lg" ></i></button>
                                                </td>
                                            </tr><?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div><?php 
                        }?>
                    </div>
                    
                    <form id="facturaForm" method="POST" target="_blank" action="factura-misc-guarda.php" autocomplete="off" onsubmit="return validacion()">
                        <div class="form-row">
                            <div class="col-md-3"><!--TASA DEL DIA-->
                                <h4>Tasa del dia:<br>
                                <input type="text" onClick="this.select()" onchange="MASK(this,this.value,'-##,###,##0.00',1); totalRecibo(); actualTasa()"  style="text-align: right; width: 100px; " name="tasaDolar" id="tasaDolar" value="<?= $tasa ?>">Bs.</h4>
                            </div>
                            <div class="col-md-3"><!--TASA DEL DIA-->
                                <h4>F. Pago:<br> 
                                    <select id="formaGen" onchange="todasForma();" class="form-control"><?php 
                                        $fpag_query = mysqli_query($link,"SELECT id,nombrePago FROM formas_pago WHERE status='1' ");
                                        while($row = mysqli_fetch_array($fpag_query))
                                        {
                                            $id=$row['id'];
                                            $nombrePago=$row['nombrePago'];
                                            echo '<option readonly value="'.$id.'" >'.utf8_encode($nombrePago)."</option>";
                                        }?>
                                    </select></h4>
                            </div>
                        </div>
                        <div class="form-row text-center" style="background-color: #C5CAE9; margin-bottom: 1%;"><!--TITULO-->
                            <div class="col-md-2">
                                <h5>Cod.</h5>
                            </div>
                            <div class="col-md-3">
                                <h5>Detalle</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>F.Pago</h5>
                            </div>
                            <div class="col-md-1">
                                <h5>Cant.</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Monto $</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Monto Bs.</h5>
                            </div>
                            
                        </div>
                        <div class="form-row" id="div_factura" ><?php  
                            for ($i=1; $i < 11; $i++) { ?>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none;"><!--CONCEPTOS-->
                                    <select name="<?= 'conce'.$i ?>" id="<?= 'conce'.$i ?>" onchange="datos('<?= $i ?>'); totalRecibo(); nuevaLin('<?= $i ?>',1); " class="form-control">
                                        <option value="">Seleccione </option><?php
                                        $select1 = mysqli_query($link,"SELECT * FROM miscelaneos_conceptos WHERE status='1' ");
                                        while($row = mysqli_fetch_array($select1))
                                        {
                                            $id=$row['id'];
                                            $concepto=$row['concepto'];
                                            $monto=$row['monto'];
                                            $editar=$row['editar'];
                                            $articulo=$row['articulo'];
                                            $dispo=0;
                                            if($articulo==1)
                                            {
                                                $stock_query = mysqli_query($link,"SELECT SUM(IF(proceso=1 and statusPago=1,cantidad,0)) as suma, SUM(IF(proceso=2 and statusPago=1,cantidad,0)) as resta FROM miscelaneos WHERE id_concepto='$id' ");
                                                $sto=mysqli_fetch_array($stock_query);
                                                $dispo=$sto['suma']-$sto['resta'];
                                                $dispo = ($dispo<0) ? 0 : $dispo ;
                                            }
                                            if ($articulo==1 && $dispo>0) {
                                                echo '<option readonly value="'.$concepto.'" data-id='.$id.' data-monto='.$monto.' data-escribe='.$editar.' data-arti='.$articulo.' data-disp='.$dispo.' >'.$id.'-'.$concepto.' ('.$dispo."Disp.)</option>";
                                            }
                                            if ($articulo==1 && $dispo<=0) {
                                                echo '<option disabled readonly value="'.$concepto.'" data-id='.$id.' data-monto='.$monto.' data-escribe='.$editar.' data-arti='.$articulo.' data-disp='.$dispo.' >'.$id.'-'.$concepto.' ('.$dispo."Disp.)</option>";
                                            }
                                            if ($articulo==2 ) {
                                                echo '<option readonly value="'.$concepto.'" data-id='.$id.' data-monto='.$monto.' data-escribe='.$editar.' data-arti='.$articulo.' data-disp='.$dispo.' >'.$id.'-'.($concepto)."</option>";
                                            }
                                        }?>
                                    </select>
                                    <input type="hidden" name="<?= 'id_concepto'.$i ?>" id="<?= 'id_concepto'.$i ?>">
                                    <input type="hidden" name="<?= 'afecta'.$i ?>" id="<?= 'afecta'.$i ?>">
                                    <input type="hidden" name="<?= 'abrev'.$i ?>" id="<?= 'abrev'.$i ?>">
                                    <input type="hidden" name="<?= 'agosto'.$i ?>" id="<?= 'agosto'.$i ?>">
                                </div>
                                <div class="col-md-3 linea<?= $i ?>" style="display: none;">
                                    <textarea rows="1" name="<?= 'detalle'.$i ?>" id="<?= 'detalle'.$i ?>" class="form-control" readonly ></textarea>
                                </div>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none;"><!--FORMA DE PAGO-->
                                    <select name="<?= 'fpag'.$i ?>" id="<?= 'fpag'.$i ?>" onchange="muestraForma('<?= $i ?>'); totalRecibo() " class="form-control"><?php 
                                        $fpag_query = mysqli_query($link,"SELECT id,nombrePago FROM formas_pago WHERE status='1' ");
                                        while($row = mysqli_fetch_array($fpag_query))
                                        {
                                            $id=$row['id'];
                                            $nombrePago=$row['nombrePago'];
                                            echo '<option readonly value="'.$id.'" >'.utf8_encode($nombrePago)."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-1 linea<?= $i ?>" style="display: none;">
                                    <select onchange="sumaCant('<?= $i ?>')" name="<?= 'cant'.$i ?>" id="<?= 'cant'.$i ?>" class="form-control"><?php 
                                        for ($ic=0; $ic < 6; $ic++) { ?>
                                            <option value="<?= $ic ?>"><?= $ic ?></option> <?php    
                                        }?>
                                        
                                    </select>
                                    <input type="hidden" id="<?= 'disponible'.$i ?>" name="<?= 'disponible'.$i ?>">
                                </div>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none; " ><!--MONTO DOLAR-->
                                    <input type="text" name="<?= 'montoDolar'.$i ?>" onClick="this.select();" onchange="MASK(this,this.value,'-##,###,##0.00',1); totalRecibo(); nuevaLin('<?= $i ?>',2)" onkeypress="return ValMon(event)" style="text-align: right; " id="<?= 'montoDolar'.$i ?>" readonly class="form-control">
                                </div>
                                <div class="col-md-2 linea<?= $i ?>" style="display: none;"><!--MONTO BS-->
                                    <input type="text" class="form-control" readonly name="<?= 'montoBs'.$i ?>" id="<?= 'montoBs'.$i ?>" style="text-align: right; ">
                                </div>
                                <div class="col-md-12" style="margin-top: 1%;"></div> <?php 
                            }?>
                        </div>
                        <div class="form-row"><!--TOTALES-->
                            <div class="col-md-8 text-right">
                                <h4>Totales-></h4>
                            </div>
                            <div class="col-md-2">
                                <input type="text" readonly style="text-align: right; font-size: 18px; " name="totalReciboDolar" id="totalReciboDolar" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <input type="text" readonly style="text-align: right; font-size: 18px; " name="totalReciboBs" id="totalReciboBs" class="form-control">
                            </div>
                        </div>
                        <div class="form-row" style="margin-top:1%;">
                            <div class="col-md-12">
                                <textarea class="form-control" rows="2" name="comentario" placeholder="Si desea colocar un comentario interno del recibo puede hacerlo en esta area." id="comentario"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" style="background-color: #5499C7; color: black; margin-top: 1%; "><h4>Datos del Pago</h4></div>
                        <div class="form-row" style="margin-bottom: 1%; ">
                            <div class="col-md-4 offset-md-2 bg-gradient-success" id="divDolar" style="padding: 5px; color: black; ">
                                <label>Total Efectivo en Dolares ($)</label>
                                <input type="text" name="totalDolar" style="text-align: center; font-size: 18px; " id="totalDolar" class="form-control" value="0.00" readonly>
                            </div>
                            <div class="col-md-4 bg-gradient-danger" id="divBolivar" style="color:black; padding: 5px; ">
                                <label>Total Efectivo en Bolivares (Bs.)</label>
                                <input type="text" name="totalBolivar" style="text-align: center; font-size: 18px; " id="totalBolivar" class="form-control" value="0.00" readonly>
                            </div>    
                        </div>
                        <div class="bg-gradient-light" id="divTransf" style="display: none; padding: 5px; color: black; "><!--TRANSF-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos de la Transferencia</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoTransf" id="bancoTransf" class="form-control" onchange="transRefe()">
                                        <option value="">Seleccione</option><?php 
                                        $transf_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($transf_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoTransf" id="montoTransf" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" readonly onblur="buscaTran()" name="nroTransf" id="nroTransf" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Transferencia</label>
                                    <input type="date" name="fechaTransf" id="fechaTransf" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-primary" id="divDebito" style="display: none; padding: 5px; color: black; "><!--DEBITO-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos Tarjeta Debito</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoDebito" id="bancoDebito" class="form-control">
                                        <option value="">Seleccione</option><?php 
                                        $debito_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($debito_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoDebito" id="montoDebito" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" name="nroDebito" id="nroDebito" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Pago Movil</label>
                                    <input type="date" value="<?= $fechaHoy ?>" name="fechaDebito" id="fechaDebito" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-info" id="divPagMovil" style="display: none; color: black; padding: 5px;"><!--P.MOV-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos del Pago Movil</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoPagMov" onchange="movilRefe()" id="bancoPagMov" class="form-control">
                                        <option value="">Seleccione</option><?php 
                                        $p_movil_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($p_movil_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoPagMovil" id="montoPagMovil" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" readonly onblur="buscaMovil()" name="nroPagMovil" id="nroPagMovil" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Pago Movil</label>
                                    <input type="date" name="fechaPagMovil" id="fechaPagMovil" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" style="background-color: #5499C7; color: black; margin-top: 1%; " id="tituloEmite"><h4>Emitir Recibo a Nombre de:</h4></div>
                        <div class="form-row" style="margin-bottom: 1%; ">
                            <div class="col-md-5">
                                <label>Nombre o Razón Social</label>
                                <input type="text" class="form-control" name="reciboNombre" id="reciboNombre" value="<?= $nom_reci ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Cedula o RIF</label>
                                <input type="text" class="form-control" name="reciboRif" id="reciboRif" value="<?= $ced_reci ?>">
                            </div>
                            <div class="col-md-5">
                                <label>Dirección</label>
                                <input type="text" class="form-control" name="reciboDire" id="reciboDire" value="<?= $dir_reci ?>">
                            </div>
                        </div>
                        <input type="hidden" id="idAlum" name="idAlum" value="<?= encriptar($idAlum) ?>">
                        <input type="hidden" name="pagaTransf" id="pagaTransf">
                        <input type="hidden" name="pagaDebito" id="pagaDebito">
                        <input type="hidden" name="pagaPagMov" id="pagaPagMov">
                        <input type="hidden" name="cedula" value="<?= $cedula ?>">
                        <input type="hidden" name="alumno" value="<?= $nombre ?>">
                        <input type="hidden" name="nombreGrado" value="<?= $nomGrado ?>">
                        <input type="hidden" name="linea" id="linea">
                        <input type="hidden" id="grado" name="grado" value="<?= $grado ?>">
                        <input type="hidden" name="salida" value="1" >
                        <input type="hidden" name="tablaPeriodo" value="<?= $tablaPeriodo ?>">
                        <div class="form-row" style="margin-bottom:2%;"><!--BOTONES IMPRIMIR Y CERRAR-->
                            <div class="col-md-4 offset-md-2">
                                <button class="btn btn-success" id="btnPrint" type="submit" style="width:100%;" disabled ><i class="fas fa-print fa-sm"></i> Procesar Recibo</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" style="width: 100%; color: black; " class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><?php 
}?>
<div class="modal fade" id="verPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Recibo Nro.</label>
                            <input type="text" id="recibo" class="form-control">
                            <input type="hidden" id="reciboPrint">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha</label>
                            <input type="text" id="fecha" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tasa Bs.</label>
                            <input type="text" id="tasa" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Monto $</label>
                            <input type="text" id="dolar" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Monto Bs.</label>
                            <input type="text" id="bolivar" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Procesado por:</label>
                            <input type="text" id="usuario" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <h4 id="msjNulo" style="margin-top: 1%; background-color: #FFCDD2; display:none; text-align: center;">*** RECIBO ANULADO ***</h4>
                            <label>Comentario: </label>
                            <textarea id="comenta" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12" style="margin-top: 2%;">
                            <table class="table table-striped table-bordered " cellspacing="0" width="100%" >
                                <thead>
                                    <th >Concepto</th>
                                    <th style="text-align: center;">Monto $</th>
                                    <th style="text-align: center;">Monto Bs.</th>
                                </thead>
                                <tbody id="cuerpo">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Cerrar Ventana</button><?php
                if($_SESSION['cargo']=='1')
                {?>
                    <button type="button" data-toggle="modal" data-target="#anulaRecibo"  id="btnAnula" class="btn btn-danger">Anular recibo</button>
                    <button type="button" data-toggle="modal" data-target="#recuperaRecibo" id="btnRecupera" style="display:none;" class="btn btn-info">Recuperar recibo</button>
                    <?php 
                }?>
                <button type="button" onclick="reimprimeRecibo()" id="btnPrint2" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button>
            </div>
            <!--input type="hidden" id="recibo"-->
            <input type="hidden" id="tablaPeriodo" value="<?= $tablaPeriodo ?>">
            <input type="hidden" id="linea">
        </div>
    </div>
</div>
<div class="modal fade" id="anulaRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-color: #FFCCBC; ">
            <div class="modal-header">
                <h5 class="modal-title">Anulación del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Motivo de Anulación</label>
                            <textarea class="form-control" placeholder="Por favor indique el motivo por el cual anula este recibo, para poder ejecutar la anulación" id="motivo" onkeyup="activaBoton()" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Rechazar Anulación</button>
                <button type="button" onclick="anulaRecibo()" disabled id="btnAnula2" class="btn btn-danger">Ejecutar Anulación</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="recuperaRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-color: #FFCCBC; ">
            <div class="modal-header">
                <h5 class="modal-title">Recuperación del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Motivo de Recuperación</label>
                            <textarea class="form-control" placeholder="Por favor indique el motivo por el cual recupera este recibo, despues de haber sido anulado" id="motivoRecupera" onkeyup="activaBotonRecuperar()" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Rechazar Recuperación</button>
                <button type="button" onclick="recuperarRecibo()" disabled id="btnRecupera2" class="btn btn-danger">Ejecutar Recuperación</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        $('#page-top').removeClass("sidebar-toggled");
        $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        $('#emisionFactura').addClass("active");
        $(".linea1"). css("display", "block")
        //$("#btnTrash1"). css("display", "none")
        //$("#montoDolar1").prop('disabled', false);
    });
    function sumaCant(lin) {
        can=$('#cant'+lin).val()
        con=$("#conce"+lin).val()
        mon=$("#conce"+lin+" option:selected").attr('data-monto')
        art=$("#conce"+lin+" option:selected").attr('data-arti')
        dis=$('#disponible'+lin).val()
        can2=0;
        if(art==1)
        {
            for (var i = 1; i < 11; i++) {
                con2=$("#conce"+i).val()
                if(i!=lin && con==con2)
                {
                    can2=can2+$('#cant'+i).val()
                }
            }
            can3=parseFloat(can)+parseFloat(can2)
            if(can3>dis)
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Stock revasado!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'El stock actual es de '+dis+' Unid.'
                })
                $('#cant'+lin).val(0)
                tot=0
            }else 
            {
                tot=mon*can    
            }
        }else
        {
            tot=mon*can       
        }
        $('#montoDolar'+lin).val(tot.toFixed(2))
        totalRecibo()
    }
    function transRefe() {
        ban=$('#bancoTransf').val()
        if(ban>0){$("#nroTransf").removeAttr("readonly");}else 
        {$("#nroTransf").attr("readonly","readonly");}
    }
    function movilRefe() {
        ban=$('#bancoPagMov').val()
        if(ban>0){$("#nroPagMovil").removeAttr("readonly");}else 
        {$("#nroPagMovil").attr("readonly","readonly");}
    }
    function buscaTran() {
        ban=$('#bancoTransf').val()
        nro=$('#nroTransf').val()
        $.post('buscaTrans.php',{'banco':ban,'oper':nro},function(data)
        {
            if(data.isSuccessful){
                fec=data.fecha;
                alu=data.alumno;
                usu=data.usuario;
                rec=data.recibo;
                Swal.fire({
                    icon: 'error',
                    title: 'INFORMACION IMPORTANTE!',
                    html: 'El numero de referencia ya fue utilizado en otro recibo!<br>En fecha: '+fec+'<br>Alumno: '+alu+'<br>Procesado por: '+usu+'<br>Recibo nro. '+rec,
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else
            {
                
            }
        }, 'json');
    }
    function buscaMovil() {
        ban=$('#bancoPagMov').val()
        nro=$('#nroPagMovil').val()
        $.post('buscaMovil.php',{'banco':ban,'oper':nro},function(data)
        {
            if(data.isSuccessful){
                fec=data.fecha;
                alu=data.alumno;
                usu=data.usuario;
                rec=data.recibo;
                Swal.fire({
                    icon: 'error',
                    title: 'INFORMACION IMPORTANTE!',
                    html: 'El numero de referencia ya fue utilizado en otro recibo!<br>En fecha: '+fec+'<br>Alumno: '+alu+'<br>Procesado por: '+usu+'<br>Recibo nro. '+rec,
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else
            {
                
            }
        }, 'json');
    }
    function todasForma() {
        son=$('#formaGen').val()
        $('#fpag1').val(son)
        $('#fpag2').val(son)
        $('#fpag3').val(son)
        $('#fpag4').val(son)
        $('#fpag5').val(son)
        $('#fpag6').val(son)
        $('#fpag7').val(son)
        $('#fpag8').val(son)
        $('#fpag9').val(son)
        $('#fpag10').val(son)
        for (var i = 1; i < 11; i++) {
            if ($('#fpag'+i).val()==3) {
                $("#divTransf"). css("display", "block");
                document.querySelector('#nroTransf').required = true;
                document.querySelector('#fechaTransf').required = true;
                $('#pagaTransf').val(1)
            }else
            {
                $("#divTransf"). css("display", "none");
                document.querySelector('#nroTransf').required = false;
                document.querySelector('#fechaTransf').required = false;
                $('#pagaTransf').val('')
            }
            if ($('#fpag'+i).val()==4) {
                $("#divDebito"). css("display", "block");
                document.querySelector('#nroDebito').required = true;
                document.querySelector('#fechaDebito').required = true;
                $('#pagaDebito').val(1)
            }else
            {
                $("#divDebito"). css("display", "none");
                document.querySelector('#nroDebito').required = false;
                document.querySelector('#fechaDebito').required = false;
                $('#pagaDebito').val('')
            }
            if ($('#fpag'+i).val()==5) {
                $("#divPagMovil"). css("display", "block");
                document.querySelector('#nroPagMovil').required = true;
                document.querySelector('#fechaPagMovil').required = true;
                $('#pagaPagMov').val(1)
            }else
            {
                $("#divPagMovil"). css("display", "none");
                document.querySelector('#nroPagMovil').required = false;
                document.querySelector('#fechaPagMovil').required = false;
                $('#pagaPagMov').val('')
            }
        }
        totalRecibo()
    }
    function reimprimeRecibo() {
        reci=$('#reciboPrint').val()
        window.open("factura-reimprime-misc-pdf.php?recibo="+reci)
    }
    function limpiar() {
        setTimeout(function(){
            window.parent.location.reload();
        }, 2000);
    }
    function validacion() 
    {
        var bancoT = document.getElementById("bancoTransf");
        var bancoB = document.getElementById("bancoDebito");
        var bancoP = document.getElementById("bancoPagMov");
        if (bancoT.value.length == 0 && $('#pagaTransf').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco de la transferencia',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        if (bancoB.value.length == 0 && $('#pagaDebito').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco del pago con Tarjeta de Debito',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        if (bancoP.value.length == 0 && $('#pagaPagMov').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco del pago movil',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        limpiar()
    }
    function actualTasa() {
        tasa=$('#tasaDolar').val()
        monMes=0;
        $.post('tasa-actual-ajax.php',{'tasa':tasa,'mes':monMes},function(data)
        {
            if(data.isSuccessful){
                Swal.fire({
                    icon: 'success',
                    title: 'Tasa del día',
                    text: 'El monto de la tasa ha sido actualizado!',
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else
            {
                
            }
        }, 'json');
    }
    function muestraForma(lin) {
        fpag='fpag'
        for (var i = 1; i < 11; i++) {
            window[fpag+i] = $('#fpag'+i).val();
        }
        if(fpag1=='3'||fpag2=='3'||fpag3=='3'||fpag4=='3'||fpag5=='3'||fpag6=='3'||fpag7=='3'||fpag8=='3'||fpag9=='3'||fpag10=='3')
        {
            $("#divTransf"). css("display", "block");
            document.querySelector('#nroTransf').required = true;
            document.querySelector('#fechaTransf').required = true;
            $('#pagaTransf').val(1)
        }else
        {
            $("#divTransf"). css("display", "none");
            document.querySelector('#nroTransf').required = false;
            document.querySelector('#fechaTransf').required = false;
            $('#pagaTransf').val('')
        }
        if(fpag1=='4'||fpag2=='4'||fpag3=='4'||fpag4=='4'||fpag5=='4'||fpag6=='4'||fpag7=='4'||fpag8=='4'||fpag9=='4'||fpag10=='4')
        {
            $("#divDebito"). css("display", "block");
            document.querySelector('#nroDebito').required = true;
            document.querySelector('#fechaDebito').required = true;
            $('#pagaDebito').val(1)
        }else
        {
            $("#divDebito"). css("display", "none");
            document.querySelector('#nroDebito').required = false;
            document.querySelector('#fechaDebito').required = false;
            $('#pagaDebito').val('')
        }
        if(fpag1=='5'||fpag2=='5'||fpag3=='5'||fpag4=='5'||fpag5=='5'||fpag6=='5'||fpag7=='5'||fpag8=='5'||fpag9=='5'||fpag10=='5')
        {
            $("#divPagMovil"). css("display", "block");
            document.querySelector('#nroPagMovil').required = true;
            document.querySelector('#fechaPagMovil').required = true;
            $('#pagaPagMov').val(1)
        }else
        {
            $("#divPagMovil"). css("display", "none");
            document.querySelector('#nroPagMovil').required = false;
            document.querySelector('#fechaPagMovil').required = false;
            $('#pagaPagMov').val('')
        }
    }
    function datos(lin) {
        conce=$('#conce'+lin).val()
        gra=$('#grado').val()
        id=$("#conce"+lin+" option:selected").attr('data-id')
        mon=$("#conce"+lin+" option:selected").attr('data-monto')
        escribe=$("#conce"+lin+" option:selected").attr('data-escribe')
        art=$("#conce"+lin+" option:selected").attr('data-arti')
        dis=$("#conce"+lin+" option:selected").attr('data-disp')
        $('#disponible'+lin).val(dis)
        //$('#montoDolar'+lin).val(mon)    
        $('#id_concepto'+lin).val(id)
        if(escribe=='S')
        { $("#detalle"+lin).removeAttr("readonly"); }else{ $("#detalle"+lin).attr("readonly","readonly"); }
        lin2=parseFloat(lin)+1
        if(conce!=''){ $(".linea"+lin2). css("display", "block") }
        boliv=parseFloat(mon)*parseFloat($('#tasaDolar').val())
        //if(boliv>0){$('#montoBs'+lin).val(boliv.toFixed(2))}else{$('#montoBs'+lin).val('0.00')}
        //if(id>0){$("#montoDolar"+lin).prop('disabled', false);}
    }
    function nuevaLin(lin,donde) {
        van=0;
        idAlu=$('#idAlum').val()
        monLin=$('#montoDolar'+lin).val()
        tabla=$('#tablaFactura').val()
        $('#linea').val(lin)
        lin2=parseFloat(lin)+1
        $(".linea"+lin2). css("display", "block") 
        
        idCon=$("#conce"+lin+" option:selected").attr('data-id')
        conce=$('#conce'+lin).val()
        tabla=$('#tablaPeriodo').val()
        $.post('restaMontoMisc-ajax.php',{'idA':idAlu,'idC':idCon,'tab':tabla,'mont':monLin},function(data)
        {
            if(data.isSuccessful)
            {
                $('#detalle'+lin).val(data.conceNue);
                //$('#montoDolar'+lin).val(data.pagar)
            }
        }, 'json');
        if(monLin==0 && donde==2)
        {
            $('#fpag'+lin).val(1)
            $('#conce'+lin).val('')
            $('#detalle'+lin).val('')
        }
    }
    function totalRecibo() {
        var suma=0; totTra=0; totMov=0; totDeb=0; totDiv=0; totBol=0;
        for (var i = 1; i < 11; i++) {
            monto=$('#montoDolar'+i).val()
            if(monto>0){suma=suma+parseFloat(monto)}
            bsLin=monto*parseFloat($('#tasaDolar').val())
            if(bsLin>0){$('#montoBs'+i).val(bsLin.toFixed(2))}else{$('#montoBs'+i).val('0.00')}
            if($('#fpag'+i).val()=='3'){totTra=totTra+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='4'){totDeb=totDeb+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='5'){totMov=totMov+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='1'){if (monto>0) {totDiv=totDiv+parseFloat(monto)} }
            if($('#fpag'+i).val()=='2'){totBol=totBol+parseFloat(bsLin)}
        }
        $('#montoTransf').val(totTra.toFixed(2))
        $('#montoDebito').val(totDeb.toFixed(2))
        $('#montoPagMovil').val(totMov.toFixed(2))
        $('#totalDolar').val(totDiv.toFixed(2))
        $('#totalBolivar').val(totBol.toFixed(2))
        totBs=suma*parseFloat($('#tasaDolar').val())
        $('#totalReciboDolar').val(suma.toFixed(2));
        if(suma>0)
        {
            $('#totalReciboBs').val(totBs.toFixed(2))
            $("#btnPrint").prop('disabled', false);
        }else
        {
            $('#totalReciboBs').val('0.00')
            $("#btnPrint").prop('disabled', true);
        }
    }
    function fnShowSecciones(div,btn) 
    {
        $(div).slideToggle();
        $(btn).toggleClass("fas fa-chevron-right");
        $(btn).toggleClass("fas fa-chevron-down");
    }
    function verPago(reci,lin)
    {
        peri=$('#tablaPeriodo').val()
        $("#cuerpo").html("");
        $('#recibo').val(reci);
        $('#linea').val(lin);
        $.post('../procesos/historia-misc-buscar.php',{'recib':reci,'tabla':peri},function(data)
        {
            if(data.isSuccessful)
            {
                $('#fecha').val(data.fecha);
                $('#forma').val(data.formaPag);
                $('#dolar').val(data.totalDolar);
                $('#bolivar').val(data.totalBs);
                $('#tasa').val(data.tasa);
                $('#referencia').val(data.nrodeposito);
                $('#banco').val(data.banco);
                $('#usuario').val(data.emitidoPor);  
                $('#comenta').val(data.comenta); 
                $('#reciboPrint').val(data.recPrint);
                if(data.status=='2')
                {
                    $('#btnAnula').hide();
                    $('#btnRecupera').show();
                    $('#btnPrint2').hide();
                    document.getElementById('msjNulo').style.display = 'block';
                } else 
                {
                    $('#btnAnula').show();
                    $('#btnRecupera').hide();
                    $('#btnPrint2').show();
                    document.getElementById('msjNulo').style.display = 'none';
                }
                for(var i=0; i<data.options.length; i++)
                {
                    if(data.options[i].codigo<10){tipo='0'+data.options[i].codigo} else{tipo=data.options[i].codigo}
                    var tr = "<tr>"+
                      "<td>"+data.options[i].conce+"</td>"+
                      "<td align='right'>"+data.options[i].dolar+" $</td>"+
                      "<td align='right'>"+data.options[i].bolivar+" Bs.</td>"+
                    "</tr>";
                    $("#cuerpo").append(tr)
                }
            } else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Alerta!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos no encontrados!'
                })
            }
        }, 'json');
    }
    function anulaRecibo() {
        reci=$('#recibo').val()
        mot=$('#comenta').val()+' (Motivo de la Anulacion: '+$('#motivo').val()+')'
        lin=$('#linea').val()
        $.post('../procesos/historia-misc-anula.php',{'recib':reci,'motivo':mot },function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
            } else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Alerta!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos no encontrados!'
                })
            }
        }, 'json');
    }
    function activaBoton() {
        mot=$('#motivo').val().length
        if(mot<16){document.getElementById("btnAnula2").disabled = true;}else{document.getElementById("btnAnula2").disabled = false;}
    }
    function activaBotonRecuperar() {
        mot=$('#motivoRecupera').val().length
        if(mot<16){document.getElementById("btnRecupera2").disabled = true;}else{document.getElementById("btnRecupera2").disabled = false;}
    }
    function recuperarRecibo() {
        reci=$('#recibo').val()
        mot=$('#comenta').val()+' (Motivo de la Recuperacion: '+$('#motivoRecupera').val()+')'
        lin=$('#linea').val()
        $.post('../procesos/historia-misc-recupera.php',{'recib':reci,'motivo':mot },function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
            } else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Alerta!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos no encontrados!'
                })
            }
        }, 'json');
    }
    
    
</script>
<?php
include_once "../include/footer.php";
mysqli_free_result($tasa_query);
mysqli_free_result($datos_query);
mysqli_free_result($matri_query);
mysqli_free_result($pagos_query);
mysqli_free_result($peri2);
mysqli_free_result($fpag_query);
mysqli_free_result($select1);
mysqli_free_result($stock_query);
mysqli_free_result($fpag_query);
mysqli_free_result($transf_query);
mysqli_free_result($debito_query);
mysqli_free_result($p_movil_query);
?>
           