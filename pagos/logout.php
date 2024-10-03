<?php
session_start();
session_destroy();
#setcookie("usuario",".",time()-5, "/" );
#setcookie("password",".",time()-5, "/" );
setcookie("usuario","",time()+(60) ); 
setcookie("password","",time()+(60));  
header("location:login.php");
?>