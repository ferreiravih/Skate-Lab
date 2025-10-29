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

// Coleta dados. Atenção ao 'descricao' do formulário
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? ''); // O 'name' do textarea

if (empty($nome)) {
    enviarResposta(false, "Erro: O campo 'Nome' é obrigatório.");
}
if (empty($descricao)) {
    enviarResposta(false, "Erro: O campo 'Descrição' é obrigatório.");
}

// INSERIR NO BANCO
// CUIDADO: O schema usa 'descrição' com acento!
$sql = "INSERT INTO public.categorias (nome, descrição) VALUES (:nome, :descricao)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao // O valor do POST
    ]);

    enviarResposta(true, "Categoria criada com sucesso!");

} catch (PDOException $e) {
    error_log("Erro ao criar categoria: " . $e->getMessage());
    enviarResposta(false, "Erro de banco de dados. Verifique os logs.");
}
?>