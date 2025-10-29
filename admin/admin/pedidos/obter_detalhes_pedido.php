<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

function enviarResposta($sucesso, $dados) {
    echo json_encode(['sucesso' => $sucesso, 'dados' => $dados]);
    exit;
}

$id_pedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_pedido) {
    enviarResposta(false, "ID inválido.");
}

try {
    // 1. Buscar os dados do pedido (incluindo o endereço snapshot)
    $stmt_pedido = $pdo->prepare("SELECT * FROM public.pedidos WHERE id_pedido = :id");
    $stmt_pedido->execute([':id' => $id_pedido]);
    $pedido = $stmt_pedido->fetch();

    if (!$pedido) {
        enviarResposta(false, "Pedido não encontrado.");
    }

    // 2. Buscar os itens do pedido (com JOIN em 'pecas')
    $sql_itens = "SELECT pi.*, p.nome AS peca_nome, p.url_img
                  FROM public.pedido_itens pi
                  JOIN public.pecas p ON pi.id_pecas = p.id_pecas
                  WHERE pi.id_pedido = :id";
    $stmt_itens = $pdo->prepare($sql_itens);
    $stmt_itens->execute([':id' => $id_pedido]);
    $itens = $stmt_itens->fetchAll();

    // 3. Montar a resposta
    $dados_completos = [
        'pedido' => $pedido,
        'itens' => $itens
    ];

    enviarResposta(true, $dados_completos);

} catch (PDOException $e) {
    error_log("Erro ao buscar detalhes do pedido: " . $e->getMessage());
    enviarResposta(false, "Erro de banco de dados.");
}
?>