<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

$id_cat = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_cat) {
    header("Location: categoria.php?error=invalid_id");
    exit;
}


$sql = "DELETE FROM public.categorias WHERE id_cat = :id_cat";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_cat' => $id_cat]);

    header("Location: categoria.php?status=deleted");
    exit;

} catch (PDOException $e) {

    if ($e->getCode() == '23503') {
        error_log("Falha ao excluir categoria: " . $e->getMessage());
        header("Location: categoria.php?status=delete_failed");
        exit;
    } else {

        error_log("Erro ao excluir categoria: " . $e->getMessage());
        die("Erro ao excluir dados.");
    }
}
?>
