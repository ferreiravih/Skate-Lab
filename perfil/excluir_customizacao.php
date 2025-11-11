<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    header('Location: ../home/index.php?error=auth_required');
    exit;
}

require_once __DIR__ . '/../config/db.php';

$id_usuario = (int)$_SESSION['id_usu'];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: customizacoes.php?error=invalid_id');
    exit;
}

try {
    $stmt = $pdo->prepare('DELETE FROM public.customizacoes WHERE id_customizacao = :id AND id_usu = :uid');
    $stmt->execute([':id' => $id, ':uid' => $id_usuario]);
    header('Location: customizacoes.php?status=deleted');
    exit;
} catch (PDOException $e) {
    error_log('Erro ao excluir customização: ' . $e->getMessage());
    header('Location: customizacoes.php?error=db');
    exit;
}
?>

