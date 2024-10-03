<?php
include_once "../include/header.php";
$link = Conectarse();
if(isset($_POST['enviar']))
{
    $periodoVer=$_POST['periodoVer'];
    $tipo = $_FILES['ingreso']['type'];
    $tamanio = $_FILES['ingreso']['size'];
    $archivotmp = $_FILES['ingreso']['tmp_name'];
    if(!empty($archivotmp))  // RECIBOS
    {
        $productos = fopen ($archivotmp , "r" );//leo el archivo que contiene los datos del producto
        while (($datos =fgetcsv($productos,3600,";")) !== FALSE )
        {
            $linea[]=array('cedula'=>$datos[0],'recibo'=>$datos[1],'fecha'=>$datos[2],'id_concepto'=>$datos[3],'concepto'=>$datos[4],'monto'=>$datos[5],'montoTasa'=>$datos[6],'montoDolar'=>$datos[7],'banco'=>$datos[8], 'nrodeposito'=>$datos[9],'fechadepo'=>$datos[10],'operacion'=>$datos[11],'emitidoPor'=>$datos[12]);//Arreglo Bidimensional para guardar los datos de cada linea leida del archivo
        }
        fclose ($productos);//Cierra el archivo
        $ingresado=0;//Variable que almacenara los insert exitosos
        $error=0;//Variable que almacenara los errores en almacenamiento
        $duplicado=0;//Variable que almacenara los registros duplicados
        $errores='';
        $van=1;
        foreach($linea as $indice=>$value) //Iteracion el array para extraer cada uno de los valores almacenados en cada items
        {
            $cedula=$value["cedula"];
            $recibo=$value["recibo"];
            $fecha=$value["fecha"];
            $id_concepto=$value['id_concepto'];
            $concepto=$value["concepto"];
            $monto = $value['monto'];
            $montoTasa=$value['montoTasa'];
            $montoDolar=$value['montoDolar'];
            $banco=$value['banco'];
            $nrodeposito=$value['nrodeposito'];
            $fechadepo=$value["fechadepo"];
            $operacion=$value['operacion'];
            $emitidoPor=$value['emitidoPor'];
            if($montoDolar>0)
            {
                //echo $cedula.' '.$recibo.' '.$concepto.'<br>';
                $sql=mysqli_query($link,"SELECT cedula, recibo, fechadepo, concepto FROM pagos".$periodoVer." WHERE cedula='$cedula' and recibo='$recibo' and fecha='$fecha' and concepto = '$concepto' ");//Consulta a la tabla productos
                $num=mysqli_num_rows($sql);//Cuenta el numero de registros devueltos por la consulta
                if ($num==0)//Si es == 0 inserto
                {
                    $existe=mysqli_query($link,"SELECT cedula,idAlum FROM alumcer WHERE cedula='$cedula'");//Consulta a la tabla alumcer para ver si existe el alumno
                    $hay1=mysqli_num_rows($existe);//Cuenta el numero de registros devueltos por la consulta
                    if($hay1>0)
                    {   
                        $row2=mysqli_fetch_array($existe);
                        $idAlum=$row2['idAlum'];
                        
                        if ($insert=mysqli_query($link,"INSERT INTO pagos".$periodoVer." (idAlum, banco, nrodeposito, fechadepo, fecha, recibo, monto, id_concepto, concepto, operacion, montoDolar, montoTasa, emitidoPor,statusPago) VALUES('$idAlum', '$banco', '$nrodeposito', '$fechadepo', '$fecha', '$recibo', '$monto', '$id_concepto', '$concepto', '$operacion', '$montoDolar', '$montoTasa','$emitidoPor','1' )"))
                        {
                            $ingresado+=1;
                            $ingresos=mysqli_query($link,"SELECT id FROM ingresos WHERE id='$recibo'");//
                            $hay2=mysqli_num_rows($ingresos);//Cuenta el numero de registros devueltos por la consulta
                            if($hay2==0)
                            {
                                if ($operacion==3 || $operacion==4 || $operacion==5) {
                                    if ($operacion==3) {
                                        mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha,fechaTransf) VALUE ('$recibo','$periodoVer','$fecha','$fechadepo' ) ") or die ("NO SE CREO ".mysqli_error());
                                    }
                                    if ($operacion==4) {
                                        mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha,fechaDebito) VALUE ('$recibo','$periodoVer','$fecha','$fechadepo' ) ") or die ("NO SE CREO ".mysqli_error());
                                    }
                                    if ($operacion==5) {
                                        mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha,fechaPagMovil) VALUE ('$recibo','$periodoVer','$fecha','$fechadepo' ) ") or die ("NO SE CREO ".mysqli_error());
                                    }
                                }else
                                {
                                    mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha) VALUE ('$recibo','$periodoVer','$fecha' ) ") or die ("NO SE CREO ".mysqli_error());
                                }
                                
                            }

                        }//fin del if que comprueba que se guarden los datos

                        else//sino ingresa el producto
                        {
                            $error+=1;
                        }
                    } else { $error+=1; }
                }//fin de if que comprueba que no haya en registro duplicado
                else
                {
                    $duplicado+=1;
                    $ingresos=mysqli_query($link,"SELECT id FROM ingresos WHERE id='$recibo'");
                    $hay2=mysqli_num_rows($ingresos);//Cuenta el numero de registros devueltos por la consulta
                    if($hay2==0)
                    {
                        //mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha) VALUE ('$recibo','$periodoVer','$fecha' ) ") or die ("NO SE CREO ".mysqli_error());
                        if ($operacion==3 || $operacion==4 || $operacion==5) {
                            if ($operacion==3) {
                                mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha,fechaTransf) VALUE ('$recibo','$periodoVer','$fecha','$fechadepo' ) ") or die ("NO SE CREO ".mysqli_error());
                            }
                            if ($operacion==4) {
                                mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha,fechaDebito) VALUE ('$recibo','$periodoVer','$fecha','$fechadepo' ) ") or die ("NO SE CREO ".mysqli_error());
                            }
                            if ($operacion==5) {
                                mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha,fechaPagMovil) VALUE ('$recibo','$periodoVer','$fecha','$fechadepo' ) ") or die ("NO SE CREO ".mysqli_error());
                            }
                        }else
                        {
                            mysqli_query($link,"INSERT INTO ingresos (id,tabla,fecha) VALUE ('$recibo','$periodoVer','$fecha' ) ") or die ("NO SE CREO ".mysqli_error());
                        }
                    }
                }
            }
        }
    }
}
if(!isset($_POST['periodoVer']) )
{
    $periodo_query = mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos WHERE activoPeriodo='1' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $nombre_periodo=$row['nombre_periodo'];
        $periodoVer=$row['tablaPeriodo'];
    }
} else
{
    $periodoVer=$_POST['periodoVer'];
    $periodo_query = mysqli_query($link,"SELECT nombre_periodo FROM periodos WHERE tablaPeriodo='$periodoVer' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $nombre_periodo=$row['nombre_periodo'];
    }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Subir data de pagos a Pagina</h1>    
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="form-row">
                        <div class="form-group col-md-12" style="margin-bottom:2%;">
                            <label class="subtituloficha">Seleccione el DATOS1.CSV </label>
                            <input type="file" accept=".csv" name="ingreso" class="archivo" >
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-12" style="margin-bottom:2%;">
                            <select name="periodoVer" id="periodoVer" class="form-control"><?php
                                $periodo_query = mysqli_query($link,"SELECT tablaPeriodo,nombre_periodo FROM periodos WHERE pagos='S' ORDER BY nombre_periodo ");
                                while($row = mysqli_fetch_array($periodo_query))
                                {
                                    $nombre_periodo1=$row['nombre_periodo'];
                                    $tablaPeriodo1=$row['tablaPeriodo'];
                                    $selected = ($tablaPeriodo1==$periodoVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$tablaPeriodo1.'">'.$nombre_periodo1."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-12" style="margin-bottom:2%;">
                            <button type="submit" name="enviar" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Enviar</button><br><br>
                        </div>
                    </div>
                    <input type="hidden" id="tabla" value="<?= $tablaPeriodo ?>">
                </form>
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
            $('#configura').addClass("show");
        }
        $('#subeDatos').addClass("active");
    });
    $('.archivo').filestyle({
      buttonName : 'btn-info',
      buttonText : ' Buscar Archivo'
    });
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           