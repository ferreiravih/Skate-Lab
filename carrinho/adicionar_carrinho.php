<?php
session_start();

if (
    !isset($_POST['id']) ||
    !isset($_POST['nome']) ||
    !isset($_POST['preco']) ||
    !isset($_POST['quantidade'])
) {
    header('Location: ../produto/produto.php');
    exit;
}

$id = $_POST['id'];
$nome = $_POST['nome'];
$preco = floatval($_POST['preco']);
$quantidade = max(1, intval($_POST['quantidade'])); // garante no mínimo 1
$descricao = $_POST['descricao'] ?? 'Sem descrição';
$imagem = $_POST['imagem'] ?? '../img/imgs-skateshop/image.png';

// Garante que o carrinho exista
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Verifica se o produto já existe no carrinho
if (isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
} else {
    $_SESSION['carrinho'][$id] = [
        'id' => $id,
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => $quantidade,
        'descricao' => $descricao,
        'imagem' => $imagem
    ];
}

header("Location: ../carrinho/carrinho.php");
exit;
