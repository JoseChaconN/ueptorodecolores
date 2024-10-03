<?php
session_start(); 
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechaYa = date( "d-m-Y H:i:s");
$fechahoy = date( "Y-m-d");
$fecHoy = date( "Y-m-d H:i:s");
$usuario=$_POST['usuario'];
$passwordalum=$_POST['passwordalum'];
$dispo=$_POST['dispo'];
include_once "conexion.php";
include_once "includes/funciones.php";
$link = Conectarse();
$usuario_query = mysqli_query($link, "SELECT idAlum,cedula,admin,nombre,apellido,cargo,grado, seccion,consulVoto,Periodo,reinscribe,ruta,correo,ced_papa,ced_mama,nom_emerg_1 FROM alumcer WHERE (cedula='$usuario' || miUsuario='$usuario') and clave = '$passwordalum' and statusAlum='1' "); 
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
    $_SESSION['fotoAlum'] = $row['ruta'];
    $_SESSION['consulVoto'] = $row['consulVoto'];
    $_SESSION['reinscribe'] = $row['reinscribe'];
    $_SESSION['usuario'] = $row['cedula'];
    $_SESSION['correo'] = $row['correo'];
    $_SESSION['password'] = $_POST['passwordalum'];
    $periodo = $row['Periodo'];
    $grado = $row['grado'];
    $seccion = $row['seccion'];
    $idAlum=$row['idAlum'];
    $_SESSION['ced_papa']=$row['ced_papa'];
    $_SESSION['ced_mama']=$row['ced_mama'];
    $_SESSION['nom_emerg_1']=$row['nom_emerg_1'];
    $count++; 
}
mysqli_free_result($usuario_query);
if($count>0)
{
    setcookie("usuarioCol",encriptar($usuario),time()+(60*60*8), "/" ); 
    setcookie("passwordCol",encriptar($passwordalum),time()+(60*60*8), "/" );  
    $periodoAlum_query=mysqli_query($link,"SELECT tablaPeriodo, nombre_periodo FROM  periodos where nombre_periodo='$periodo'"); 
    while($row=mysqli_fetch_array($periodoAlum_query))
    {
        $_SESSION['periodoAlum'] = $row['tablaPeriodo'];    
        $_SESSION['nombre_periodo'] = $row['nombre_periodo']; 
        $periodoAlum=$row['tablaPeriodo'];
    }
    mysqli_free_result($periodoAlum_query);
    $periodo_query=mysqli_query($link,"SELECT tablaPeriodo, nombre_periodo FROM  periodos where activoPeriodo='1'"); 
    while($row=mysqli_fetch_array($periodo_query))
    {
        $_SESSION['tablaPeriodo']=trim($row['tablaPeriodo']);
        $_SESSION['periodoActivo']=trim($row['nombre_periodo']);
        $periodoActivo=trim($row['nombre_periodo']);
    }
    mysqli_free_result($periodo_query);
    $lapso_query=mysqli_query($link,"SELECT lapso FROM preinscripcion where id='2'"); 
    while($row=mysqli_fetch_array($lapso_query)){
        $_SESSION['lapsoActivo']=$row['lapso'];
        $lapsoActivo=$row['lapso'];
    }
//////////// OBTENER LA MOROSIDAD DEL ALUMNO /////////////////////
    $matri_query = mysqli_query($link,"SELECT suma_a_pagado,desc1,desc2,desc3,desc4,desc5,desc6,desc7,desc8,desc9,desc10,desc11,desc12,desc13,pagado,exoneraMorosidad FROM matri".$periodoAlum." WHERE idAlumno='$idAlum' "); 
    
    while ($row = mysqli_fetch_array($matri_query))
    {
        $exoneraMorosidad=$row['exoneraMorosidad'];
        //$pagado=$row['pagado']+$row['suma_a_pagado']; quitado para hacer query de pagos
        $pagado=$row['suma_a_pagado'];
        for ($i=1; $i <14 ; $i++) { 
            ${'desc'.$i} = $row['desc'.$i];
        }
    }
    $montos_query = mysqli_query($link,"SELECT monto,fecha_vence FROM montos".$periodoAlum." WHERE id_grado ='$grado' "); 
        $meses=0; $morosida=0;
    while ($row = mysqli_fetch_array($montos_query))
    {
        $meses++;
        if($row['fecha_vence']<$fechahoy)
        {
            $morosida=$morosida+($row['monto']-${'desc'.$meses});
        }
    }
    $pagos_query = mysqli_query($link,"SELECT A.montoDolar,A.statusPago,D.afecta FROM pagos".$periodoAlum." A, formas_pago B, bancos C, conceptos D WHERE A.idAlum = '$idAlum' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.id ");
    //$pagado=0; 
    while ($row = mysqli_fetch_array($pagos_query))
    {
        if($row['statusPago']=='1' and $row['afecta']=='S' )
        {
            $pagado=$pagado+$row['montoDolar'];
        }
    }
    $morosida=$morosida-$pagado;
///////////////////////////////////////////////////////////////////    
    /*$periodoTodos_query=mysqli_query($link,"SELECT tablaPeriodo FROM periodos where pagos='S' ORDER BY orden "); 
    while($row=mysqli_fetch_array($periodoTodos_query))
    {
        $tabla=$row['tablaPeriodo'];
        if ($grado<61) {
            $deuda_query=mysqli_query($link,"SELECT morosida,exoneraMorosidad,pagado,totalPeriodo, suma_a_pagado FROM notaprimaria".$tabla." where idAlumno='$idAlum'"); 
            while($row=mysqli_fetch_array($deuda_query))
            {
                $morosida=$morosida+$row['morosida'];
                $exoneraMorosidad=$row['exoneraMorosidad'];
                $pagado=$pagado+$row['pagado']+$row['suma_a_pagado'];
                $totalPeriodo=$row['totalPeriodo'];
            }
        }else
        {
            $deuda_query=mysqli_query($link,"SELECT morosida,exoneraMorosidad,pagado,totalPeriodo, suma_a_pagado FROM matri".$tabla." where idAlumno='$idAlum'"); 
            while($row=mysqli_fetch_array($deuda_query))
            {
                $morosida=$morosida+$row['morosida'];
                $exoneraMorosidad=$row['exoneraMorosidad'];
                $pagado=$pagado+$row['pagado']+$row['suma_a_pagado'];
                $totalPeriodo=$row['totalPeriodo'];
            }
        }
        mysqli_free_result($deuda_query);
    }
    mysqli_free_result($periodoTodos_query);*/
    $tareaPen=0;
    $tarea_query=mysqli_query($link,"SELECT idTarea,todos FROM tareas".$tabla." WHERE codGrado='$grado' and codSecci='$seccion' and lapsoTarea='$lapsoActivo'  ");
    while($row=mysqli_fetch_array($tarea_query)){
        $idTarea=$row['idTarea'];
        $todos=$row['todos'];
        if ($todos=='S') {
            $viotarea_query=mysqli_query($link,"SELECT id_tabla FROM vio_tarea WHERE id_tarea='$idTarea' and id_alum='$idAlum' ");
            if(mysqli_num_rows($viotarea_query)==0)
            {
                $tareaPen=2;
                break;
            }
        }else{
            $tareaInd_query=mysqli_query($link,"SELECT id_tabla FROM tarea_ind_".$tabla." WHERE idAlum='$idAlum' and idTarea='$idTarea'  ");
            if(mysqli_num_rows($tareaInd_query)>0)
            {
                $viotarea_query=mysqli_query($link,"SELECT id_tabla FROM vio_tarea WHERE id_tarea='$idTarea' and id_alum='$idAlum' ");
                if(mysqli_num_rows($viotarea_query)==0)
                {
                    $tareaPen=2;
                    break;
                }
            }
        }
    }
    $_SESSION['tareaPend']=$tareaPen;
    $encuesta_query=mysqli_query($link,"SELECT id_encuesta FROM encuesta WHERE periodo='$periodo' and fecha_ini<='$fechahoy' and fecha_fin>='$fechahoy' and IF(todos='S',periodo='$periodo','$grado'>=grado_des and '$grado'<=grado_has and '$seccion'>=sec_des and '$seccion'<=sec_has ) ");
    $sinrespuesta=0;
    while($row2=mysqli_fetch_array($encuesta_query)){
        $id_encuesta=$row2['id_encuesta'];
        $respuestas_query=mysqli_query($link,"SELECT id_respuesta FROM encuesta_respuesta WHERE id_encuesta='$id_encuesta' and id_alum='$idAlum' ");
        if(mysqli_num_rows($respuestas_query) == 0){$sinrespuesta=10; break; }
    }
    mysqli_query($link,"INSERT INTO acceso_alum (idAlum,fecha) VALUE ('$idAlum','$fecHoy' ) ") or die ("NO SE CREO ".mysqli_error());  
    if ($usuario=='19809918713') {
        $morosida=0;
        $pagado=100;
    }
    $_SESSION['exoneraMorosidad'] = $exoneraMorosidad;
    $_SESSION['pagado'] = $pagado; //activar al usar sistema pagos
    $_SESSION['totalPeriodo'] = $totalPeriodo;
    if($exoneraMorosidad>=$fechahoy){$_SESSION['morosida'] = 0;}else{$_SESSION['morosida'] = $morosida;}
    $mensajes_query=mysqli_query($link,"SELECT count(id_chat) as msjHay FROM chat WHERE idAlum='$idAlum' and visto='2' and envia='1' ");
    $row2=mysqli_fetch_array($mensajes_query);
    $_SESSION['msjHay'] = $row2['msjHay'];
    setcookie("lapAct",encriptar($lapsoActivo),time()+(60*60*8), "/" );
    setcookie("moroCol",encriptar($morosida),time()+(60*60*8), "/" );
    setcookie("pagoCol",encriptar($pagado),time()+(60*60*8), "/" );
    setcookie("totalPeriodo",encriptar($totalPeriodo),time()+(60*60*8), "/" );
    setcookie("periodoActivo",encriptar($periodoActivo),time()+(60*60*8), "/" );
    if (!empty($_SESSION['admin']))
    {
        setcookie("usuarioCol","",time()-5, "/" ); 
        setcookie("passwordCol","",time()-5, "/" ); 
        setcookie("moroCol","",time()-5, "/" );
        setcookie("pagoCol","",time()-5, "/" );
        setcookie("totalPeriodo","",time()-5, "/" );
        setcookie("periodoActivo","",time()-5, "/" );
        session_destroy();
        echo "<script type='text/javascript'>                                
                  window.location='https://pagina.jesistemas.com.ve/login.php';
              </script>";                   
    }
    else if ($_SESSION['cargo']>1)
    {
        echo "<script type='text/javascript'>                                
                  window.location='docentes/index.php?ingreso=1';
              </script>";  
        echo "";
    }
    else
    {
        if (empty($_SESSION['fotoAlum']) || empty($_SESSION['correo'])  )
        {
            echo "<script type='text/javascript'>                                
                window.location='consulta.php?complet';
              </script>";
        }
        if($sinrespuesta>0)
        {
            echo "<script type='text/javascript'>                                
                    window.location='encuesta-lista.php?sinresp';
                  </script>";
        }else{
            if($dispo=='movil' )
            {
                echo "<script type='text/javascript'>                                
                        window.location='indexm.php?ingreso=2';
                      </script>";
            }else
            {
                echo "<script type='text/javascript'>                                
                        window.location='index.php?ingreso=2';
                      </script>";
            }
        }
    }
} else
{
     echo "<script type='text/javascript'>                                
                window.location='index.php?ingreso=fail';
            </script>";
}
?>
 