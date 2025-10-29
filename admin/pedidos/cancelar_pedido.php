<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

$id_pedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_pedido) {
    header("Location: pedidos.php?error=invalid_id");
    exit;
}

$sql = "UPDATE public.pedidos SET status = 'CANCELADO' WHERE id_pedido = :id_pedido";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_pedido' => $id_pedido]);

    header("Location: pedidos.php?status=cancelled");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao cancelar pedido: " . $e->getMessage());
    header("Location: pedidos.php?error=cancel_failed");
    exit;
}
?>