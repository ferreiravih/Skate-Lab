<?php
// Finaliza a sessão do usuário e redireciona
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpa variáveis da sessão
$_SESSION = [];

// Remove cookie de sessão, se existir
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Destroi a sessão
session_destroy();

// Redireciona para a home
header('Location: ../home/home.php');
exit;
?>

