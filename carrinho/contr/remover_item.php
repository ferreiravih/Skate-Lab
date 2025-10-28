<?php
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Remove diretamente pelo ID, pois o índice no carrinho é o ID
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
}

// NÃO reindexe o array com array_values() aqui — isso quebra a lógica de IDs
header("Location: carrinho.php");
exit;
