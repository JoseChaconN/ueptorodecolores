<?php   
include("encabezado1.php");
$profesor = $_GET["ced_prof"];  
$lapsoMod=$_GET['lapsoMod'];
session_start(); 
if(!isset($_SESSION['usuario']) && !isset($_SESSION['passwordalum']))
{
  include_once "../conexion.php";
  function verificar_login($useralum,$passwordalum,&$result)
  { 
    $link = Conectarse(); 
    $result = mysqli_query($link,"SELECT admin, nombre, apellido, cargo, editable FROM alumcer WHERE cedula = '$useralum' and clave = '$passwordalum' and statusAlum='1' ORDER BY cedula ASC"); 

    $count = 0; 
    while ($row = mysqli_fetch_array($result))
    { 
      $_SESSION['admin'] = $row['admin'];
      $_SESSION['cargo'] = $row['cargo'];
      $_SESSION['nomuser'] = $row['nombre'];
      $_SESSION['apelluser'] = $row['apellido'];
      $_SESSION['editable'] = $row['editable'];
      $count++; 
    } 
    $periodo_query=mysqli_query($link,"SELECT tablaPeriodo FROM  periodos where activoPeriodo='1'"); 
    while($row=mysqli_fetch_array($periodo_query))
    {
        $_SESSION['tablaPeriodo']=trim($row['tablaPeriodo']);
    } 
    if($count == 1) 
    { 
      $user2 = $useralum;
      return 1; 
    }else 
    { 
      return 0; 
    } 
  }
  if(!isset($_SESSION['userid'])) 
  { 
    if(isset($_POST['login'])) 
    { 
      if(verificar_login($_POST['useralum'],$_POST['passwordalum'],$result) == 1) 
      { 
        $_SESSION['userid'] = $result->idusuario; 
        $_SESSION['usuario'] = $_POST['useralum'];
        $_SESSION['password'] = $_POST['passwordalum'];
        if($_SESSION['admin']=='SA' || $_SESSION['admin']=='SE')
        { header("location:listmateprof.php?ced_prof=$profesor&lapsoMod=$lapsoMod"); }else
        {
          if($_SESSION['cargo']>1 )
          { header("location:index.php"); }
        }
        /**/
      }else 
      { ?> 
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2><div class="alert alert-danger text-center" role="alert">¡Usuario o Contraseña Incorrectos!</div></h2>
            </div>
          </div>
        </div><?php
      } 
    }?>
    <div class="container">
      <div class="row text-center">
        <div class="col-md-12 alert alert-info">
          <center><h1>ACCESO AL SISTEMA</h1></center>          
        </div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading"><p class="titulo-login">Ingreso</p></div>
              <div class="panel-body">
                <form role="form" method="post" action="">
                  <div class="form-group" >
                    <h3><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Usuario<input name="useralum" type="text" class="form-control" placeholder="Usuario"></h3>
                    <h3>Contraseña<input name="passwordalum" placeholder="********" type="password" class="form-control"></h3>
                  </div>
                  <div class="btn-toolbar" role="toolbar">
                    <button class="btn-group btn btn-primary " role="group" aria-label="" type="submit" name="login">Ingresar</button>
                    <a href="recuperaclave.php" class="input-group"><button type="button" class="btn-group btn btn-warning" role="group" aria-label="">Olvide mi contraseña</button></a>
                    <a class="input-group" href="index.php"><button class="btn-group  btn" type="button" role="group" aria-label="">Inicio</button></a>
                  </div>
                </form>
              </div>                     
            </div>  
          </div>
          <div class="col-md-4"></div>
        </div>            
      </div><?php
  }
} else 
{ 
  if ($_SESSION['admin']=='SA' || $_SESSION['admin']=='SE' || $_SESSION['admin']=='SC')
    {
        header("location:../admin/index.php");        
    }else
    {
        if($_SESSION['cargo']>1 )
        { header("location:index.php"); }else
        { header("location:../index.php"); }
    }
}
include("../footer1.html"); 
?>
 