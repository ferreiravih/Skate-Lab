<?php
session_start();

if (!isset($_POST['id']) || !isset($_POST['acao'])) {
    // CORRIGIDO O CAMINHO DE REDIRECIONAMENTO DE ERRO
    header('Location: ../carrinho.php'); 
    exit;
}

$id = $_POST['id'];
$acao = $_POST['acao'];

if (isset($_SESSION['carrinho'][$id])) {
    if ($acao === 'mais') {
        $_SESSION['carrinho'][$id]['quantidade']++;
    } elseif ($acao === 'menos') {
        $_SESSION['carrinho'][$id]['quantidade']--;

        if ($_SESSION['carrinho'][$id]['quantidade'] < 1) {
            unset($_SESSION['carrinho'][$id]);
        }
    }
}

// CORRIGIDO O CAMINHO DE REDIRECIONAMENTO DE SUCESSO
header('Location: ../carrinho.php');
exit;