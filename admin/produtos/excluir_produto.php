<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

// 1. Pegar o ID da URL e validar
$id_pecas = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_pecas) {
    header("Location: produtos.php?error=invalid_id");
    exit;
}

// 2. SQL de EXCLUSÃO
// ATENÇÃO: Verifique se há 'itens_p' ligados a esta peça.
// Se houver, você pode precisar deletar eles primeiro ou apenas desativar a peça.
// Por simplicidade, este script DELETA a peça.
$sql = "DELETE FROM public.pecas WHERE id_pecas = :id_pecas";

try {
    // 3. Prepared Statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_pecas' => $id_pecas]);

    // 4. Redirecionar com sucesso
    header("Location: produtos.php?status=deleted");
    exit;

} catch (PDOException $e) {
    // Erro (ex: a peça está em um pedido e não pode ser deletada)
    error_log("Erro ao excluir produto: " . $e->getMessage());
    header("Location: produtos.php?error=delete_failed");
    exit;
}
?>