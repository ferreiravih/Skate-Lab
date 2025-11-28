<?php
session_start();


if (!isset($_SESSION['id_usu'])) {
    // se não estiver logado vai p home.
    header('Location: ../../home/index.php?error=auth_required');
    exit;
}

if (
    !isset($_POST['id']) ||
    !isset($_POST['nome']) ||
    !isset($_POST['preco']) ||
    !isset($_POST['quantidade'])
) {
    header('Location: ../../produto/produto.php'); // redireciona de volta se faltar dados
    exit;
}

$id = $_POST['id'];
$nome = $_POST['nome'];
$preco = floatval($_POST['preco']);
$quantidade = max(1, intval($_POST['quantidade'])); // garante no mínimo 1
$descricao = $_POST['descricao'] ?? 'Sem descrição';
$imagem = $_POST['imagem'] ?? '../img/imgs-skateshop/image.png';
$idPecaRelacional = is_numeric($id) ? (int)$id : null;

// garante que o carrinho exista
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// verifica se o produto já existe no carrinho
if (isset($_SESSION['carrinho'][$id])) {
    // se for comprar "(redirect_to=checkout)" define a quantidade. senao soma
    if (isset($_POST['redirect_to']) && $_POST['redirect_to'] === 'checkout') {
         $_SESSION['carrinho'][$id]['quantidade'] = $quantidade;
    } else {
         $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
    }
} else {
    $_SESSION['carrinho'][$id] = [
        'id' => $id,
        'id_peca' => $idPecaRelacional,
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => $quantidade,
        'descricao' => $descricao,
        'imagem' => $imagem
    ];
}

// verifica para onde redirecionar
$redirect_to = $_POST['redirect_to'] ?? 'carrinho'; 

if ($redirect_to === 'checkout') {
    header("Location: ../../pagamento/checkout.php"); 
} else {
    header("Location: ../../carrinho/carrinho.php"); 
}
exit;
