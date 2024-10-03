<?php
session_start();
include("inicia.php");
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$preinsc=(isset($_GET['preinsc'])) ? $_GET['preinsc'] : '' ;
setcookie("usuarioCol","",time()-5, "/" ); 
setcookie("passwordCol","",time()-5, "/" ); 
setcookie("moroCol","",time()-5, "/" );
setcookie("pagoCol","",time()-5, "/" );
setcookie("totalPeriodo","",time()-5, "/" );
setcookie("periodoActivo","",time()-5, "/" );
session_destroy();
if($preinsc==1)
{ header("location:preinscripcion.php"); } else 
{ header("location:index.php"); }
?>