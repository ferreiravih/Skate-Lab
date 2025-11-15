<?php
// Salva a customização do usuário logado
// Espera JSON: { titulo, config, preco_total, preview_img? }

session_start();
header('Content-Type: application/json');

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

function normalizarPreviewPath(?string $path): ?string {
    if (!is_string($path)) {
        return null;
    }
    $path = trim($path);
    if ($path === '') {
        return null;
    }
    $path = str_replace('\\', '/', $path);

    if (preg_match('#^https?://#i', $path) || strpos($path, 'data:image') === 0) {
        return $path;
    }
    if ($path[0] === '/') {
        return $path;
    }

    while (strpos($path, '../') === 0) {
        $path = substr($path, 3);
    }
    $path = ltrim($path, './');
    $base = obterBasePublicPath();
    return ($base ?: '') . '/' . ltrim($path, '/');
}

function salvarPreviewBase64(string $dataUrl, int $idUsuario): ?string {
    if (!preg_match('#^data:image/(png|jpe?g);base64,(.+)$#i', $dataUrl, $matches)) {
        return null;
    }

    $ext = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
    $binario = base64_decode($matches[2], true);
    if ($binario === false) {
        return null;
    }

    $destino = __DIR__ . '/../image/previews';
    if (!is_dir($destino) && !mkdir($destino, 0775, true) && !is_dir($destino)) {
        return null;
    }
    $destino = realpath($destino) ?: $destino;

    try {
        $hash = bin2hex(random_bytes(6));
    } catch (Exception $e) {
        $hash = bin2hex(substr(str_shuffle('abcdef0123456789'), 0, 6));
    }

    $arquivo = sprintf('preview-%d-%s.%s', $idUsuario, $hash, $ext);
    $caminhoCompleto = rtrim($destino, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $arquivo;

    if (file_put_contents($caminhoCompleto, $binario) === false) {
        return null;
    }

    $basePublica = obterBasePublicPath();
    return ($basePublica ?: '') . '/customizacao/image/previews/' . $arquivo;
}

function processarPreviewEntrada($entrada, int $idUsuario): ?string {
    if (!is_string($entrada)) {
        return null;
    }
    $entrada = trim($entrada);
    if ($entrada === '') {
        return null;
    }
    if (strpos($entrada, 'data:image') === 0) {
        return salvarPreviewBase64($entrada, $idUsuario);
    }
    return normalizarPreviewPath($entrada);
}

function resposta($ok, $msg, $extra = []) {
    http_response_code($ok ? 200 : (isset($extra['code']) ? (int)$extra['code'] : 400));
    unset($extra['code']);
    echo json_encode(array_merge(['sucesso' => $ok, 'mensagem' => $msg], $extra));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    resposta(false, 'Método inválido.', ['code' => 405]);
}

if (!isset($_SESSION['id_usu'])) {
    resposta(false, 'Não autenticado.', ['code' => 401]);
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    resposta(false, 'JSON inválido.');
}

$id_usu = (int)$_SESSION['id_usu'];
$titulo = trim($data['titulo'] ?? '');
$config = $data['config'] ?? null;
$preco_total = isset($data['preco_total']) ? (float)$data['preco_total'] : 0.0;
$preview_img = processarPreviewEntrada($data['preview_img'] ?? '', $id_usu);
if ($preview_img === null) {
    $base = obterBasePublicPath();
    $preview_img = ($base ?: '') . '/img/imgs-skateshop/image.png';
}

if ($titulo === '' || !is_array($config)) {
    resposta(false, "Campos obrigatórios ausentes: 'titulo' ou 'config'.");
}

// Pequenas validações para evitar valores absurdos vindos do client
if ($preco_total < 0) $preco_total = 0;
if ($preco_total > 100000) $preco_total = 100000; // limite de segurança

try {
    $sql = "INSERT INTO public.customizacoes (id_usu, titulo, config, preco_total, preview_img)
            VALUES (:id_usu, :titulo, CAST(:config AS jsonb), :preco_total, :preview_img)
            RETURNING id_customizacao";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_usu' => $id_usu,
        ':titulo' => $titulo,
        ':config' => json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ':preco_total' => $preco_total,
        ':preview_img' => $preview_img ?: null,
    ]);

    $id = $stmt->fetchColumn();
    resposta(true, 'Customização salva com sucesso.', ['id' => (int)$id]);

} catch (PDOException $e) {
    error_log('Erro ao salvar customização: ' . $e->getMessage());
    resposta(false, 'Erro de banco de dados.');
}

?>

