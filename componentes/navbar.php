<?php
// Inicia a sessão em todas as páginas que incluem a navbar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skatelab</title>

    <link rel="stylesheet" href="../componentes/nav.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="../../Skate-Lab/home/index.php">
                <h1>SKATELAB</h1>
            </a>
        </div>
        <nav class="menu">
            <a href="../skateshop/skateee.php">SkateShop</a>
            <a href="../select/select.php">Customizar</a>
        </nav>
        <div class="icones1">
            <a><i class="fa-regular fa-user user-icon" id="userIcon"></i></a>

            <?php // AQUI COMEÇA A MÁGICA: Verifica se o ID do usuário NÃO existe na sessão ?>
            <?php if (!isset($_SESSION['id_usu'])): ?>

                <div id="sidebarLogin" class="sidebar">
                    <h2>Login</h2>
                    <form action="../auth/login.php" method="POST">
                        <label>Email</label> 
                        <input type="email" name="email" placeholder="Digite seu email" required />
                        
                        <label>Senha</label>
                        <div class="password-wrapper">
                            <input type="password" name="senha" placeholder="Digite sua senha" required id="loginSenhaInput" />
                            <i class="fa-solid fa-eye-slash toggle-password" id="toggleLoginPassword"></i>
                        </div>
                        
                        <button type="submit">Entrar</button>
                    </form>
                    <button type="button" id="abrirCadastro">Faça seu cadastro</button>
                </div>

                <div id="sidebarCadastro" class="sidebar">
                    <h2>Cadastro</h2>
                    <form action="../auth/registrar.php" method="POST" id="formCadastro">
                        <label>Nome completo</label>
                        <input type="text" name="nome" placeholder="Digite seu nome" required />
                        
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Digite seu email" required />
                        
                        <label>Senha</label>
                        <div class="password-wrapper">
                            <input type="password" name="senha" placeholder="Crie sua senha" required id="registerSenhaInput" />
                            <i class="fa-solid fa-eye-slash toggle-password" id="toggleRegisterPassword"></i>
                        </div>

                        <label>Confirmar Senha</label>
                        <div class="password-wrapper">
                            <input type="password" name="confirmar_senha" placeholder="Confirme sua senha" required id="confirmSenhaInput" />
                            <i class="fa-solid fa-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                        </div>
                        
                        <button type="submit">Cadastrar</button>
                        <button type="button" id="voltarLogin">Voltar ao login</button>
                    </form>
                </div>

            <?php else: ?>

                <div id="sidebarUsuario" class="sidebar">
                    <?php // Puxa os dados da Sessão que o login.php e registrar.php criaram ?>
                    <h3 class="sidebar-greeting"><?php echo htmlspecialchars($_SESSION['nome_usu']); ?></h3>
                    <p class="sidebar-email"><?php echo htmlspecialchars($_SESSION['email_usu']); ?></p>
                    
                    <div class="sidebar-actions">
                        <a href="../perfil/perfil.php" id="editarPerfil">Editar Perfil</a>
                        <a href="../auth/logout.php" id="sair">Sair</a>
                    </div>
                </div>

            <?php endif; // Fim da verificação de login ?>


            <a><i class="fa-regular fa-heart"></i></a>
            <div class="carrinho1">
                <a href="../carrinho/carrinho.php"><i class="fa-solid fa-cart-shopping"></i></a>
                <span class="itenscarrinho1">
                    <?= isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0 ?>
                </span>
            </div>
        </div>
    </header>
    

    <script src="../componentes/nav.js"></script>
</body>

</html>
