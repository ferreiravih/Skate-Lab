<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

function enviarResposta($sucesso, $mensagem, $novoStatus = null, $novaClasse = null) {
    echo json_encode([
        'sucesso' => $sucesso,
        'mensagem' => $mensagem,
        'novoStatus' => $novoStatus,
        'novaClasse' => $novaClasse
    ]);
    exit;
}


$input = json_decode(file_get_contents('php://input'), true);
$id_pedido = $input['id_pedido'] ?? null;
$novo_status = $input['novo_status'] ?? null;

if (!$id_pedido || !$novo_status) {
    enviarResposta(false, "ID do pedido ou novo status não fornecido.");
}


$status_validos = ['PENDENTE', 'EM PREPARO', 'ENVIADO', 'ENTREGUE', 'CANCELADO'];
if (!in_array($novo_status, $status_validos)) {
    enviarResposta(false, "Status inválido.");
}


$sql = "UPDATE public.pedidos SET status = :status WHERE id_pedido = :id_pedido";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':status' => $novo_status,
        ':id_pedido' => $id_pedido
    ]);


    $novaClasse = strtolower(str_replace(' ', '', $novo_status));
    enviarResposta(true, "Status atualizado!", $novo_status, $novaClasse);

} catch (PDOException $e) {
    error_log("Erro ao atualizar status do pedido: " . $e->getMessage());
    enviarResposta(false, "Erro de banco de dados.");
}
?>