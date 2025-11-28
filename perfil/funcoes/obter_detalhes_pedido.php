<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_usu'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}


require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$id_usuario_logado = $_SESSION['id_usu'];
$id_pedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_pedido) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID do pedido inválido.']);
    exit;
}

try {

    $stmt_pedido = $pdo->prepare(
        "SELECT * FROM public.pedidos 
         WHERE id_pedido = :id_pedido AND id_usu = :id_usu"
    );
    $stmt_pedido->execute([
        ':id_pedido' => $id_pedido,
        ':id_usu' => $id_usuario_logado
    ]);
    $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Pedido não encontrado ou não pertence a este usuário.']);
        exit;
    }


    $stmt_itens = $pdo->prepare(
        "SELECT pi.quantidade, pi.preco_unitario, p.nome, p.url_img
         FROM public.pedido_itens pi
         JOIN public.pecas p ON pi.id_pecas = p.id_pecas
         WHERE pi.id_pedido = :id_pedido"
    );
    $stmt_itens->execute([':id_pedido' => $id_pedido]);
    $itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);


    $dados_completos = [
        'pedido' => $pedido,
        'itens' => $itens
    ];

    echo json_encode(['sucesso' => true, 'dados' => $dados_completos]);
    exit;

} catch (PDOException $e) {
    error_log("Erro ao buscar detalhes do pedido: " . $e->getMessage());
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados.']);
    exit;
}
?>
