<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: categoria.php");
    exit;
}

$id_cat = filter_input(INPUT_POST, 'id_cat', FILTER_VALIDATE_INT);
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? ''); 

if (!$id_cat || empty($nome) || empty($descricao)) {
    die("Erro: Todos os campos são obrigatórios.");
}


$sql = "UPDATE public.categorias SET 
            nome = :nome, 
            descricao = :descricao 
        WHERE id_cat = :id_cat";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao, 
        ':id_cat' => $id_cat
    ]);

    header("Location: categoria.php?status=updated");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar categoria: " . $e->getMessage());
    die("Erro ao atualizar dados.");
}
?>