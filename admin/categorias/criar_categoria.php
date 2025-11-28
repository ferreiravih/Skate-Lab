<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

function enviarResposta($sucesso, $mensagem) {
    echo json_encode(['sucesso' => $sucesso, 'mensagem' => $mensagem]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    enviarResposta(false, "Método inválido.");
}


$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? ''); 

if (empty($nome)) {
    enviarResposta(false, "Erro: O campo 'Nome' é obrigatório.");
}
if (empty($descricao)) {
    enviarResposta(false, "Erro: O campo 'Descrição' é obrigatório.");
}


$sql = "INSERT INTO public.categorias (nome, descricao) VALUES (:nome, :descricao)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao 
    ]);

    enviarResposta(true, "Categoria criada com sucesso!");

} catch (PDOException $e) {
    error_log("Erro ao criar categoria: " . $e->getMessage());
    enviarResposta(false, "Erro de banco de dados. Verifique os logs.");
}
?>
