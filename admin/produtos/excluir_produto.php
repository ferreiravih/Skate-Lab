<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';


$id_pecas = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_pecas) {
    header("Location: produtos.php?error=invalid_id");
    exit;
}


$sql = "DELETE FROM public.pecas WHERE id_pecas = :id_pecas";

try {

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_pecas' => $id_pecas]);

    header("Location: produtos.php?status=deleted");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao excluir produto: " . $e->getMessage());
    header("Location: produtos.php?error=delete_failed");
    exit;
}
?>