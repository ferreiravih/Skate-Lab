<?php
session_start();

// --- ADICIONADO ---
// VERIFICAÇÃO DE LOGIN
if (!isset($_SESSION['id_usu'])) {
    // Se não estiver logado, redireciona para a home.
    // O caminho (../../) sobe duas pastas (de /carrinho/contr/ para /)
    header('Location: ../../home/home.php?error=auth_required');
    exit;
}
// --- FIM DA ADIÇÃO ---
if (
    !isset($_POST['id']) ||
    !isset($_POST['nome']) ||
    !isset($_POST['preco']) ||
    !isset($_POST['quantidade'])
) {
    header('Location: ../../produto/produto.php'); // Redireciona de volta se faltar dados
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
    // Se for "Comprar Agora" (redirect_to=checkout), define a quantidade. Senão, soma.
    if (isset($_POST['redirect_to']) && $_POST['redirect_to'] === 'checkout') {
         $_SESSION['carrinho'][$id]['quantidade'] = $quantidade;
    } else {
         $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
    }
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

// *** AQUI ESTÁ A CORREÇÃO ***
// Verifica para onde redirecionar
$redirect_to = $_POST['redirect_to'] ?? 'carrinho'; // Padrão é ir para o carrinho

if ($redirect_to === 'checkout') {
    header("Location: ../../pagamento/checkout.php"); // Vai para a nova página de checkout
} else {
    header("Location: ../../carrinho/carrinho.php"); // Vai para o carrinho
}
exit;