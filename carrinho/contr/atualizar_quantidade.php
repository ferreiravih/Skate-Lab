<?php
session_start();

if (!isset($_POST['id']) || !isset($_POST['acao'])) {
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


header('Location: ../carrinho.php');
exit;