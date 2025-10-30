<?php
// 1. Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Inclui a conexão com o banco
require_once __DIR__. '/../config/db.php';

// 3. Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../home/index.php"); // Expulsa se não for POST
    exit;
}

// 4. Coleta dados do formulário
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($email) || empty($senha)) {
    // Erro: campos vazios (pode ser melhorado com mensagens de erro)
    header("Location: ../home/index.php?error=empty");
    exit;
}

try {
    // 5. Busca o usuário pelo email (usando o schema 'public.usuario')
    $sql = "SELECT id_usu, nome, email, senha, tipo FROM public.usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    // 6. Verifica o usuário e a senha
    // ATENÇÃO: Este exemplo usa password_verify(). 
    // Suas senhas no banco DEVEM estar hasheadas com password_hash() para isso funcionar.
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        
        // 7. Autenticação bem-sucedida!
        // Regenera o ID da sessão por segurança
        session_regenerate_id(true);

        // 8. Armazena os dados da sessão (conforme o schema)
        $_SESSION['id_usu'] = $usuario['id_usu'];
        $_SESSION['nome_usu'] = $usuario['nome'];
        $_SESSION['tipo_usu'] = $usuario['tipo']; // ex: 'admin' ou 'comum'
        $_SESSION['email_usu'] = $usuario['email'];
        
        // 9. Redireciona para o dashboard se for admin
        if ($usuario['tipo'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            // Redireciona para o perfil se for usuário comum
            header("Location: ../perfil/perfil.php");
        }
        exit;

    } else {
        // Falha no login (usuário ou senha incorretos)
        header("Location: ../home/index.php?error=invalid");
        exit;
    }

} catch (PDOException $e) {
    error_log("Erro de login: " . $e->getMessage());
    header("Location: ../home/index.php?error=db");
    exit;
}
?>