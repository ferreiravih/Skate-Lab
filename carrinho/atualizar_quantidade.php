<?php
session_start();

if (!isset($_POST['id']) || !isset($_POST['acao'])) {
    header('Location: carrinho.php');
    exit;
}

$id = $_POST['id'];
$acao = $_POST['acao'];

// Verifica se o item com o ID existe no carrinho
if (isset($_SESSION['carrinho'][$id])) {
    if ($acao === 'mais') {
        $_SESSION['carrinho'][$id]['quantidade']++;
    } elseif ($acao === 'menos') {
        $_SESSION['carrinho'][$id]['quantidade']--;

        // Se a quantidade ficar menor que 1, remove o item do carrinho
        if ($_SESSION['carrinho'][$id]['quantidade'] < 1) {
            unset($_SESSION['carrinho'][$id]);
        }
    }
}

header('Location: carrinho.php');
exit;
    