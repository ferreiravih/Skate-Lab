<?php
// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- CORREÇÃO AUTOMÁTICA DE SESSÃO ---
if (isset($_SESSION['id_usu']) && !isset($_SESSION['url_perfil']) && isset($pdo)) {
    try {
        $stmtFoto = $pdo->prepare("SELECT url_perfil FROM public.usuario WHERE id_usu = :id");
        $stmtFoto->execute([':id' => $_SESSION['id_usu']]);
        $dadosUsuario = $stmtFoto->fetch(PDO::FETCH_ASSOC);
        $_SESSION['url_perfil'] = $dadosUsuario['url_perfil'] ?? null;
    } catch (Exception $e) {}
}

$qtdFavoritos = 0;
if (isset($_SESSION['id_usu']) && isset($pdo)) {
    try {
        $stmtFav = $pdo->prepare("SELECT COUNT(*) FROM public.favoritos WHERE id_usu = :id_usu");
        $stmtFav->execute([':id_usu' => $_SESSION['id_usu']]);
        $qtdFavoritos = $stmtFav->fetchColumn();
    } catch (Exception $e) {}
}

$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';
$baseUrl = '';
if ($projectRoot !== false) {
    if ($docRoot && strpos($projectRoot, $docRoot) === 0) {
        $baseUrl = rtrim(substr($projectRoot, strlen($docRoot)), '/');
    }
}
if ($baseUrl === '' && isset($_SERVER['PHP_SELF'])) {
    $segments = explode('/', trim($_SERVER['PHP_SELF'], '/'));
    if (count($segments) > 1) {
        $baseUrl = '/' . $segments[0];
    }
}
if ($baseUrl !== '' && $baseUrl[0] !== '/') {
    $baseUrl = '/' . $baseUrl;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skatelab</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/componentes/nav.css?v=1.3">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="<?php echo $baseUrl; ?>/home/index.php">
                <h1>SKATELAB</h1>
            </a>
        </div>
        <nav class="menu">
            <a href="<?php echo $baseUrl; ?>/skateshop/skateee.php">SkateShop</a>
            <a href="<?php echo $baseUrl; ?>/select/select.php">Customizar</a>
        </nav>
        <div class="icones1">
            
            <a><i class="fa-regular fa-user user-icon" id="userIcon"></i></a>

            <?php if (!isset($_SESSION['id_usu'])): ?>

                <div id="sidebarLogin" class="sidebar">
                    <h2>Login</h2>
                    <div id="login-error-message" class="auth-error-message"></div>
                    <form action="<?php echo $baseUrl; ?>/auth/login.php" method="POST">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Digite seu email" required />

                        <label>Senha</label>
                        <div class="password-wrapper">
                            <input type="password" name="senha" placeholder="Digite sua senha" required id="loginSenhaInput" />
                            <i class="fa-solid fa-eye-slash toggle-password" id="toggleLoginPassword"></i>
                        </div>

                        <div class="forgot-password-container">
                            <a href="<?php echo $baseUrl; ?>/auth/recuperar_senha.php" class="forgot-password-link">Esqueceu a senha?</a>
                        </div>

                        <button type="submit">Entrar</button>
                    </form>
                    <button type="button" id="abrirCadastro">Faça seu cadastro</button>
                </div>

                <div id="sidebarCadastro" class="sidebar">
                    <h2>Cadastro</h2>
                    <div id="register-error-message" class="auth-error-message"></div>
                    <form action="<?php echo $baseUrl; ?>/auth/registrar.php" method="POST" id="formCadastro">
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
                    <div class="sidebar-profile-container">
                        <?php if (!empty($_SESSION['url_perfil'])): ?>
                            <img src="<?php echo $baseUrl . '/' . str_replace('../', '', $_SESSION['url_perfil']); ?>" alt="Foto de Perfil" class="sidebar-profile-img">
                        <?php else: ?>
                            <div class="sidebar-default-icon-bg">
                                <i class="fa-regular fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3 class="sidebar-greeting"><?php echo htmlspecialchars($_SESSION['nome_usu']); ?></h3>
                    <p class="sidebar-email"><?php echo htmlspecialchars($_SESSION['email_usu']); ?></p>

                    <div class="sidebar-actions">
                        <a href="<?php echo $baseUrl; ?>/perfil/perfil.php" id="editarPerfil">Editar Perfil</a>
                        <a href="<?php echo $baseUrl; ?>/auth/logout.php" id="sair">Sair</a>
                    </div>
                </div>

            <?php endif; ?>
            
            <div id="auth-modal-overlay" class="auth-modal-overlay">
                <div class="auth-modal-content">
                    <button id="auth-modal-close" class="auth-modal-close">&times;</button>
                    <i class="fa-solid fa-lock auth-modal-icon"></i>
                    <h2>Login Necessário</h2>
                    <p>Você precisa fazer login para adicionar itens ao carrinho.</p>
                    <button id="auth-modal-login-btn" class="auth-modal-button">Fazer Login</button>
                </div>
            </div>

            <a href="<?php echo $baseUrl; ?>/favoritos/favoritos.php" class="form-protegido"><i class="fa-regular fa-heart"></i></a>

            <div class="carrinho1">
                <a href="<?php echo $baseUrl; ?>/carrinho/carrinho.php" class="form-protegido"><i class="fa-solid fa-cart-shopping"></i></a>
                <?php
                $qtdCarrinho = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0;
                $ocultar = ($qtdCarrinho === 0) ? 'style="display: none;"' : '';
                ?>
                <span class="itenscarrinho1" <?php echo $ocultar; ?>>
                    <?= $qtdCarrinho ?>
                </span>
            </div>
        </div>
    </header>
    <script>
        const isUserLoggedIn = <?php echo isset($_SESSION['id_usu']) ? 'true' : 'false'; ?>;
    </script>
    <script src="<?php echo $baseUrl; ?>/componentes/nav.js"></script>
    <script src="<?php echo $baseUrl; ?>/componentes/auth_popup.js"></script>
</body>
</html>