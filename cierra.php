<?php 
session_start();
session_destroy();
setcookie("usuarioCol","",time()-5, "/" );
setcookie("passwordCol","",time()-5, "/" );
setcookie("moroCol","",time()-5, "/" );
setcookie("pagoCol","",time()-5, "/" );

header("location:index.php");
 ?>