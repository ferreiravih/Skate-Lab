<?php
session_start(); // <-- 1. ADICIONADO

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
}

// 2. CORRIGIDO O CAMINHO DE REDIRECIONAMENTO
header("Location: ../carrinho.php");
exit;