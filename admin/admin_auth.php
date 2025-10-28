<?php
// Este é uma proteção simples mais segura para garantir que apenas administradores autenticados possam acessar este arquivo.<?php

// Inicia a sessão se ela ainda não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. O user está logado? O user é admin? (Verifica se o ID exite e se o user é admin na sessão)

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') { // admin = verificar na tabela de perfil
    
    // Se não for um admin logado, nós o expulsamos e limpamos os dados da sessão.
 
    session_unset();
    session_destroy();
    
    // Redireciona para a página de login '/Skate-Lab/auth/login.php' para garantir o caminho correto
    header("Location: /Skate-Lab/auth/login.php");
    exit; 
}

// Se o script chegou até aqui, o usuário é um admin autenticado.
?>