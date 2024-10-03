<?php
$usuario=desencriptar($_COOKIE['usuarioCol']);
$passwordalum=desencriptar($_COOKIE['passwordCol']);
$morosida=desencriptar($_COOKIE['moroCol']);
$pagado=desencriptar($_COOKIE['pagoCol']);
$totalPeriodo=desencriptar($_COOKIE['totalPeriodo']);
$periodoActivo=desencriptar($_COOKIE['periodoActivo']);
$lapsoActivo=desencriptar($_COOKIE['lapAct']);
$usuario_query = mysqli_query($link, "SELECT idAlum,cedula,admin,nombre,apellido,cargo,grado, seccion,consulVoto,Periodo,reinscribe,correo,ced_papa,ced_mama,nom_emerg_1,ruta FROM alumcer WHERE (cedula='$usuario' || miUsuario='$usuario') and clave = '$passwordalum' and statusAlum='1' "); 
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
    $_SESSION['usuario'] = $usuario;
    $_SESSION['password'] = $passwordalum;
    $_SESSION['nombre_periodo'] = $row['Periodo'];
    $_SESSION['periodoAlum']=substr($row['Periodo'], 2,2).substr($row['Periodo'], 7,2);
    $_SESSION['tablaPeriodo']=substr($row['Periodo'], 2,2).substr($row['Periodo'], 7,2);
    $_SESSION['pagado']=$pagado;
    $_SESSION['morosida']=$morosida;
    $_SESSION['totalPeriodo'] = $totalPeriodo;
    $_SESSION['periodoActivo']=$periodoActivo;
    $_SESSION['lapsoActivo']=$lapsoactivo;
    
    $_SESSION['correo']=$row['correo'];
    $_SESSION['ced_papa']=$row['ced_papa'];
    $_SESSION['ced_mama']=$row['ced_mama'];
    $_SESSION['nom_emerg_1']=$row['nom_emerg_1'];
    $idAlum=$row['idAlum'];
    $periodoAlum=substr($row['Periodo'], 2,2).substr($row['Periodo'], 7,2);
    $grado=$row['grado'];
    $seccion=$row['seccion'];
}
?>