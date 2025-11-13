<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$response = ['status' => 'error', 'message' => 'Ocorreu um erro.'];


if (!isset($_SESSION['id_usu'])) {
    $response['message'] = 'Login necessário.';
    http_response_code(403); 
    echo json_encode($response);
    exit;
}


$data = json_decode(file_get_contents('php://input'), true);
$id_peca = $data['id_pecas'] ?? null;

if (!$id_peca) {
    $response['message'] = 'ID da peça não informado.';
    http_response_code(400); 
    echo json_encode($response);
    exit;
}

$id_usuario = $_SESSION['id_usu'];

try {
    require_once __DIR__ . '/../config/db.php'; 
    $sql_check = "SELECT id_favorito FROM public.favoritos WHERE id_usu = :id_usu AND id_pecas = :id_pecas";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id_usu' => $id_usuario, ':id_pecas' => $id_peca]);
    $favorito_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($favorito_existente) {
        $sql_delete = "DELETE FROM public.favoritos WHERE id_favorito = :id_favorito";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([':id_favorito' => $favorito_existente['id_favorito']]);
        
        $response['status'] = 'removed';
        $response['message'] = 'Item removido dos favoritos.';
        
    } else {
        $sql_insert = "INSERT INTO public.favoritos (id_usu, id_pecas) VALUES (:id_usu, :id_pecas)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([':id_usu' => $id_usuario, ':id_pecas' => $id_peca]);
        
        $response['status'] = 'added';
        $response['message'] = 'Item adicionado aos favoritos!';
    }

} catch (PDOException $e) {
    error_log("Erro no toggle_favorito: " . $e->getMessage());
    $response['message'] = 'Erro de banco de dados: ' . $e->getMessage();
    http_response_code(500); 
}

header('Content-Type: application/json');
echo json_encode($response);
exit;