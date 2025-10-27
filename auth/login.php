<?php
session_start();
require_once '../conexao.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } else {
        // Busca usuário pelo email
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id_usu'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redireciona (pode ser para a home ou dashboard)
            header('Location: ../home/home.php');
            exit;
        } else {
            $erro = "Email ou senha inválidos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../auth/login.css">
     <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   


    <title>login</title>
</head>

<body>
    
    <?php include '../componentes/navbar.php'; ?>
    <?php  ?>
    <main>
        <div class="containerlogin">
            <h3>BEM-VINDO DE VOLTA</h3>
            <p>Acesse sua conta</p>

            <?php if (!empty($erro)): ?>
                <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>

            <form method="POST" class="form">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required minlength="7" maxlength="100" placeholder="Digite seu email:" class="inputcss"><br>

                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required minlength="6" maxlength="100" placeholder="Digite sua senha:" class="inputcss"><br>

                <input type="submit" value="Entrar" class="formbotao">
            </form>

            <div class="linhaou">
                <span>OU</span>
            </div>

            <h5>Não tem uma conta? <a href="../auth/criarconta.php">Cadastre-se</a></h5>
        </div>
    </main>
</body>

</html>