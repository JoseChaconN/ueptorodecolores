<?php
session_start(); 
$usuario=$_GET['ced'];
$passwordalum=$_GET['accs'];
$dispo=$_GET['ingre'];
include_once "conexion.php";
$link = Conectarse();
$usuario_query = mysqli_query($link, "SELECT idAlum,cedula,admin,nombre,apellido,cargo,grado, seccion,consulVoto,Periodo,morosida,pagado,reinscribe,ruta,clave FROM alumcer WHERE cedula = '$usuario' and clave = '$passwordalum' and statusAlum='1' "); 
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
    $_SESSION['usuario'] = $usuario;
    $_SESSION['password'] = $passwordalum;
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
    
    if($dispo=='movil' )
    {
        echo "<script type='text/javascript'>                                
                window.location='indexm.php?ingreso=2&alNew=1';
              </script>";
    }else
    {
        echo "<script type='text/javascript'>                                
                window.location='index.php?ingreso=2&alNew=1';
              </script>";
    }
    
} else
{
     echo "<script type='text/javascript'>                                
                window.location='index.php?ingreso=fail';
            </script>";
}
?>
 