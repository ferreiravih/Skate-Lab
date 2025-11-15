<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    header('Location: ../../home/index.php?error=auth_required');
    exit;
}

require_once __DIR__ . '/../../config/db.php';

function obterBasePublicPath(): string {
    $projectRoot = realpath(__DIR__ . '/../../');
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

    $project = $projectRoot ? str_replace('\\', '/', $projectRoot) : '';
    $doc = $docRoot ? str_replace('\\', '/', $docRoot) : '';

    if ($project && $doc && strpos($project, $doc) === 0) {
        $relative = trim(substr($project, strlen($doc)), '/');
        return $relative === '' ? '' : '/' . $relative;
    }
    return '';
}

function removerPreviewArquivo(?string $publicPath): void {
    if (!is_string($publicPath) || $publicPath === '') {
        return;
    }
    $normalizado = str_replace('\\', '/', trim($publicPath));
    if ($normalizado === '') {
        return;
    }

    $projectRoot = realpath(__DIR__ . '/../../');
    if (!$projectRoot) {
        return;
    }
    $projectRoot = str_replace('\\', '/', $projectRoot);
    $basePublica = obterBasePublicPath();

    $relativo = $normalizado;
    if ($basePublica && strpos($relativo, $basePublica . '/') === 0) {
        $relativo = substr($relativo, strlen($basePublica));
    }
    $relativo = ltrim($relativo, '/');

    if (strpos($relativo, 'customizacao/image/') !== 0) {
        return;
    }

    $arquivo = $projectRoot . '/' . $relativo;
    $arquivo = str_replace('/', DIRECTORY_SEPARATOR, $arquivo);

    if (is_file($arquivo)) {
        @unlink($arquivo);
    }
}

$id_usuario = (int)$_SESSION['id_usu'];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: customizacoes.php?error=invalid_id');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT preview_img FROM public.customizacoes WHERE id_customizacao = :id AND id_usu = :uid');
    $stmt->execute([':id' => $id, ':uid' => $id_usuario]);
    $customizacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customizacao) {
        header('Location: customizacoes.php?error=not_found');
        exit;
    }

    $previewPath = $customizacao['preview_img'] ?? null;

    $stmtDel = $pdo->prepare('DELETE FROM public.customizacoes WHERE id_customizacao = :id AND id_usu = :uid');
    $stmtDel->execute([':id' => $id, ':uid' => $id_usuario]);

    removerPreviewArquivo($previewPath);

    header('Location: customizacoes.php?status=deleted');
    exit;
} catch (PDOException $e) {
    error_log('Erro ao excluir customizacao: ' . $e->getMessage());
    header('Location: customizacoes.php?error=db');
    exit;
}
?>
