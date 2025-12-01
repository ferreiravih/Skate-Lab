<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['id_usu']) || $_SESSION['tipo_usu'] !== 'admin') {
    
    session_unset();
    session_destroy();
    

    header("Location: /Skate-Lab/home/index.php?error=auth");
    exit; 
}

?>