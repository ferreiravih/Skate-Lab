<?php
// 1. Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Inclui a conexão com o banco
require_once __DIR__. '/../config/db.php';

// 3. Verifica se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../home/home.php"); // Expulsa se não for POST
    exit;
}

// 4. Coleta dados do formulário
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// 5. Validação básica
if (empty($nome) || empty($email) || empty($senha)) {
    header("Location: ../home/home.php?error=register_empty");
    exit;
}

// 6. Criptografa a senha (IMPORTANTE!)
$hash_senha = password_hash($senha, PASSWORD_DEFAULT);

try {
    // 7. Verifica se o e-mail já existe
    $sql_check = "SELECT id_usu FROM public.usuario WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':email' => $email]);
    
    if ($stmt_check->fetch()) {
        // Usuário já existe
        header("Location: ../home/home.php?error=email_exists");
        exit;
    }

    // 8. Insere o novo usuário no banco
    $sql_insert = "INSERT INTO public.usuario (nome, email, senha, tipo) VALUES (:nome, :email, :senha, 'comum')";
    $stmt_insert = $pdo->prepare($sql_insert);
    
    $stmt_insert->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $hash_senha
    ]);

    // 9. Pega o ID do usuário que acabamos de criar
    $id_novo_usuario = $pdo->lastInsertId('public.usuario_id_usu_seq'); // Sintaxe do PostgreSQL

    // 10. FAZ O LOGIN AUTOMÁTICO: Cria a sessão para o usuário
    session_regenerate_id(true);
    $_SESSION['id_usu'] = $id_novo_usuario;
    $_SESSION['nome_usu'] = $nome;
    $_SESSION['email_usu'] = $email; // <-- AQUI TAMBÉM!
    $_SESSION['tipo_usu'] = 'comum'; 

    // 11. Redireciona para a página de perfil
    header("Location: ../perfil/perfil.php?status=registered");
    exit;

} catch (PDOException $e) {
    error_log("Erro de registro: " . $e->getMessage());
    header("Location: ../home/home.php?error=db_register");
    exit;
}
?>