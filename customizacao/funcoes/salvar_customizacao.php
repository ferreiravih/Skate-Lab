<?php
// Salva a customização do usuário logado
// Espera JSON: { titulo, config, preco_total, preview_img? }

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';

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
$preview_img = trim($data['preview_img'] ?? '../img/imgs-skateshop/image.png');

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
        ':preview_img' => $preview_img !== '' ? $preview_img : null,
    ]);

    $id = $stmt->fetchColumn();
    resposta(true, 'Customização salva com sucesso.', ['id' => (int)$id]);

} catch (PDOException $e) {
    error_log('Erro ao salvar customização: ' . $e->getMessage());
    resposta(false, 'Erro de banco de dados.');
}

?>

