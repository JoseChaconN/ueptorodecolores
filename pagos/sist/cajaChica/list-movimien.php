<?php
include_once "../include/header.php";
$link = Conectarse();

if(!isset($_GET['cajaChica']))
{
    $caja_query = mysqli_query($link,"SELECT id,nombre_caja_chica,moneda FROM cajas_chicas WHERE status='1' and (estado is NULL or estado='1') LIMIT 1 ");
    while($row = mysqli_fetch_array($caja_query))
    {
        $idCaja1=$row['id'];
        $nombreCaja1=$row['nombre_caja_chica'];
        $moneda1=$row['moneda'];
        $simbMoneda = ($moneda1==1) ? 'Bs.' : '$' ;
        $nomMoneda = ($moneda1==1) ? 'Bolivares' : 'Dolares' ;
    }
} else
{
    $idCaja1=$_GET['cajaChica'];
    $caja_query = mysqli_query($link,"SELECT nombre_caja_chica,moneda FROM cajas_chicas WHERE id='$idCaja1' ");
    while($row = mysqli_fetch_array($caja_query))
    {
        $nombreCaja1=$row['nombre_caja_chica'];
        $moneda1=$row['moneda'];
        $simbMoneda = ($moneda1==1) ? 'Bs.' : '$' ;
        $nomMoneda = ($moneda1==1) ? 'Bolivares' : 'Dolares' ;
    }
}
$sumar_query = mysqli_query($link,"SELECT SUM(monto) as monto_mas FROM caja_chica_movi WHERE id_caja_chica='$idCaja1' and tipo_operacion=1 ");
$row=mysqli_fetch_array($sumar_query);
$monto_mas=$row['monto_mas'];
$resta_query = mysqli_query($link,"SELECT SUM(monto) as monto_menos FROM caja_chica_movi WHERE id_caja_chica='$idCaja1' and tipo_operacion=2 ");
$row=mysqli_fetch_array($resta_query);
$monto_menos=$row['monto_menos'];

$disponible=$monto_mas-$monto_menos;

$movimiento_query = mysqli_query($link,"SELECT A.*,B.nombreUser,B.apellidoUser FROM caja_chica_movi A, user B WHERE A.id_caja_chica='$idCaja1' and A.usuario=B.idUser ORDER BY A.id DESC ");

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-12">
            <h1 class="h3 mb-2 text-gray-800">Movimiento de Caja Chica</h1>    
        </div>    
        
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <div class="form-row" style="margin-bottom: 1%; ">
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <label>Nombre Caja Chica:</label>
                        <select name="cajaChica" onchange="buscarCajaChica()" id="cajaChica" class="form-control"><?php 
                            $cajaChica_query = mysqli_query($link,"SELECT * FROM cajas_chicas WHERE status='1' and  (estado is NULL or estado='1')");
                            while($row = mysqli_fetch_array($cajaChica_query))
                            {
                                $id=$row['id'];
                                $nombreCaja=$row['nombre_caja_chica'];
                                $selected ='';
                                if($idCaja1 == $id){$selected='selected';}
                                echo '<option readonly value="'.$id.'"'.$selected.' >'.utf8_encode($nombreCaja)."</option>";
                            }?>
                        </select> 
                        <input type="hidden" id="nombreCaja" value="<?= $nombreCaja1 ?>" >
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <label>Monto Disponible (<?= $nomMoneda ?>)</label>
                        <input type="text" readonly="" class="form-control" style="text-align:right; " value="<?= $simbMoneda.' '.number_format($disponible,2,',','.') ?>" >
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <label>&nbsp;</label>
                        <button type="button" onclick="nuevoMovi('<?= $nombreCaja1 ?>')" data-toggle="modal" data-target="#nuevoConcepto" class="btn btn-primary" style="width: 100%;"><span class="fas fa-plus fa-sm" ></span> Nuevo Movimiento</button>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger" onclick="btnPdf()" style="width: 100%"><i class="fas fa-file-pdf"></i> Exporta a PDF</button>
                    </div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Concepto</th>
                            <th>Fecha/Hora</th>
                            <th>Procesado</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Recibo</th>
                            <th>Concepto</th>
                            <th>Fecha/Hora</th>
                            <th>Procesado</th>
                            <th>Monto</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($movimiento_query)) 
                        {
                            $recibo=$row['id'];
                            $simbOpera = ($row['tipo_operacion']==1) ? '+' : '-' ;
                            $fecha_mov=date("d-m-Y / H:i:s", strtotime($row['fecha_mov']));
                            $concepto=$row['concepto'];
                            $monto=$row['monto'];
                            $moneda=$row['moneda'];
                            $usuario=$row['nombreUser'].' '.$row['apellidoUser'];
                            //$=$row['']; ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                <td><?= $concepto ?> </td> 
                                <td><?= $fecha_mov ?></td>
                                <td><?= $usuario ?></td>
                                <td align="right" style="font-weight: bold; color: black; background-color: <?php if($simbOpera=='+'){ echo '#A9DFBF'; }else{ echo '#F5B7B1';} ?>" ><?= $simbMoneda.' '.number_format($monto,2,',','.').' '.$simbOpera ?></td>

                            </tr><?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="nuevoConcepto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Movimiento de Caja Chica:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre de Caja Chica: (<?= $nomMoneda ?>)</label>
                            <input type="text" readonly class="form-control" id="nombreNuevo">
                        </div>
                        <div class="col-md-12">
                            <label>Concepto:</label>
                            <textarea class="form-control" id="conceptoNuevo" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Operación:</label>
                            <select name="tipoNuevo" onchange="tipoOpera()" id="tipoNuevo" class="form-control">
                                <option value="">Seleccione</option> <?php 
                                if($disponible<=0)
                                {
                                    $tipo_query = mysqli_query($link,"SELECT * FROM caja_chica_tipo WHERE status='1' and operacion=1 ");    
                                }else
                                {
                                    $tipo_query = mysqli_query($link,"SELECT * FROM caja_chica_tipo WHERE status='1' ");    
                                }                                
                                while($row = mysqli_fetch_array($tipo_query))
                                {
                                    $id=$row['id'];
                                    $nombre_tipo=$row['nombre_tipo'];
                                    $operacion=$row['operacion'];
                                    $selected ='';
                                    echo '<option readonly data-opera="'.$operacion.'" value="'.$id.'"'.$selected.' >'.utf8_encode($nombre_tipo)."</option>";
                                }?>
                            </select> 
                        </div>
                        <div class="col-md-6">
                            <label>Monto: (<?= $simbMoneda.' Disponible' ?>)</label>
                            <input type="text" id="montoNuevo" onClick="this.select()" value="<?= number_format($disponible,2,'.',',') ?>" class="form-control">
                        </div>
                        <input type="hidden" id="operacionNuevo">
                        <input type="hidden" id="monedaNuevo" value="<?= $moneda1 ?>">
                        <input type="hidden" id="usuarioNuevo" value="<?= $_SESSION['idUser'] ?>">
                        <input type="hidden" id="disponible" value="<?= $disponible ?>">
                        <input type="hidden" id="disponible2" value="<?= encriptar($disponible) ?>">
                        <input type="hidden" id="simbMoneda" value="<?= $simbMoneda ?>" >
                        <input type="hidden" id="nomMoneda" value="<?= $nomMoneda ?>" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                <button type="button" id="btn-movi" onclick="guardaMovimi()" class="btn btn-primary">Procesar Movimiento</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        if (screen.width<1025) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        if (screen.width>1024) {
            $('#cajaChicaMenu').addClass("show");
        }
        $('#cajaChicaMov').addClass("active");

    });
    function guardaMovimi() {
        id=$('#cajaChica').val() 
        tip=$('#tipoNuevo').val(); // caja_chica_tipo
        ope=$('#operacionNuevo').val(); // suma o resta
        con=$('#conceptoNuevo').val()
        mon=$('#montoNuevo').val()
        usu=$('#usuarioNuevo').val()
        mone=$('#monedaNuevo').val()
        disp=$('#disponible').val()
        if (parseFloat(mon)>parseFloat(disp) && ope==2 ) 
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Monto disponible para esta operación '+parseFloat(disp).toFixed(2)+' '+$('#simbMoneda').val(),
                showConfirmButton: false,
                timer: 2500
            })
            return false;   
        }
        if (tip.length == 0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione una operación valida',
                showConfirmButton: false,
                timer: 1500
            })
            return false;
        }
        if(con.length<6)
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Por favor ingresar un concepto valido!',
                showConfirmButton: false,
                timer: 1500
            })
            return false;
        }
        $.post('movimi-procesa.php',{'id':id,'tipo':tip,'conce':con,'monto':mon,'usua':usu,'moned':mone,'opera':ope,'disp':disp },function(data)
            {
                if(data.isSuccessful)
                {
                    window.parent.location.reload();
                }else
                {
                    Swal.fire({
                      icon: 'error',
                      title: 'Alerta!',
                      confirmButtonText:
                      '<i class="fa fa-thumbs-up"></i> Entendido',
                      text: 'Movimiento NO Procesado!'
                    })
                }
            }, 'json');
    }
    function nuevoMovi(nom) {
        if(nom==''){
            document.getElementById("btn-movi").disabled = true;
        }else{
            document.getElementById("btn-movi").disabled = false;
        }
        $('#nombreNuevo').val(nom)
    }
    function tipoOpera() {
        ope=$("#tipoNuevo option:selected").attr('data-opera')
        $('#operacionNuevo').val(ope)
    }
    function buscarCajaChica() {
        id=$('#cajaChica').val()
        location.href="list-movimien.php?cajaChica="+id;
    }
    function btnPdf()
    {
        disp=$('#disponible2').val()
        nomb=$('#nombreCaja').val()
        simb=$('#simbMoneda').val()
        mone=$('#nomMoneda').val()
        window.open('movimi-pdf.php?caja=<?= $idCaja1 ?>&fondo='+disp+'&simbo='+simb+'&moned='+mone+'&nombre='+nomb+'&filtro='+$('#example_filter').find('input').val())
    }
    
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";  
mysqli_free_result($caja_query);
mysqli_free_result($sumar_query);
mysqli_free_result($resta_query);
mysqli_free_result($movimiento_query);
mysqli_free_result($cajaChica_query);
mysqli_free_result($tipo_query);
              
?>
           