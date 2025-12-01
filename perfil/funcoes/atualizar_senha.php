<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_usu'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit;
}


require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');


$id_usu = $_SESSION['id_usu'];
$senha_atual = $_POST['senha_atual'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';


if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Por favor, preencha todos os campos de senha.']);
    exit;
}
if ($nova_senha !== $confirmar_senha) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'A nova senha e a confirmação não são iguais.']);
    exit;
}
if (strlen($nova_senha) < 6) { 
    echo json_encode(['sucesso' => false, 'mensagem' => 'A nova senha deve ter pelo menos 6 caracteres.']);
    exit;
}
if ($nova_senha === $senha_atual) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'A nova senha não pode ser igual à senha atual.']);
    exit;
}

try {

    $stmt_check = $pdo->prepare("SELECT senha FROM public.usuario WHERE id_usu = :id");
    $stmt_check->execute([':id' => $id_usu]);
    $usuario = $stmt_check->fetch();

    if (!$usuario || !password_verify($senha_atual, $usuario['senha'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'A senha atual está incorreta.']);
        exit;
    }


    $novo_hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);


    $stmt_update = $pdo->prepare("UPDATE public.usuario SET senha = :novo_hash WHERE id_usu = :id");
    $stmt_update->execute([
        ':novo_hash' => $novo_hash_senha,
        ':id' => $id_usu
    ]);


    echo json_encode(['sucesso' => true, 'mensagem' => 'Senha alterada com sucesso!']);
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar senha: " . $e->getMessage());
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados. Tente novamente.']);
    exit;
}
?>
