<?php
// Este é uma proteção simples mais segura para garantir que apenas administradores autenticados possam acessar este arquivo.

// Inicia a sessão se ela ainda não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verfica se user esta logado e é admin
if (!isset($_SESSION['id_usu']) || $_SESSION['tipo_usu'] !== 'admin') {
    
    session_unset();
    session_destroy();
    
    // Vamos redirecionar para a home, onde está o sidebar de login.
    header("Location: /Skate-Lab/home/home.php?error=auth");
    exit; 
}

?>