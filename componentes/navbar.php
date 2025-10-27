<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Gera token CSRF para formularios POST (logout, etc.)
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>skatelab</title>
    <link rel="stylesheet" href="../componentes/nav.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
<header class="header">
    <div class="logo">
        <h1>SKATELAB</h1>
    </div>

    <nav class="menu">
        <a href="../skateshop/skateee.php">SkateShop</a>
        <a href="../customização/custom.php">Customizar</a>
    </nav>

    <div class="icones1">
        <!-- Ícone de perfil -->
        <i class="fa-regular fa-user user-icon" id="userIcon"></i>

        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <!-- Usuário não logado -->
                <h2>Login</h2>
                <form action="../auth/login.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required minlength="7" maxlength="100" placeholder="Digite seu email:" class="inputcss"><br>
                    
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

                    <button type="submit">Entrar</button>
                </form>
                <button id="continuarEmail">Continuar com E-mail</button>
            <?php else: ?>
                <!-- Usuário logado -->
                <h2>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
                <a href="../perfil/perfil.php" class="btn-perfil">Meu Perfil</a>
                <form action="../auth/logout.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <button type="submit" id="sair">Sair</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Ícone de favoritos -->
        <a><i class="fa-regular fa-heart"></i></a>

        <!-- Ícone de carrinho -->
        <div class="carrinho1">
            <a href="../carrinho/carrinho.php"><i class="fa-solid fa-cart-shopping"></i></a>
            <span class="itenscarrinho1"><?= isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0 ?></span>
        </div>
    </div>
</header>

<script src="../componentes/nav.js"></script>
</body>
</html>
