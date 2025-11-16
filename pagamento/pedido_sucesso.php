<?php
// 1. Inicia a sessão e verifica o login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Proteção: Só pode ver esta página se estiver logado
if (!isset($_SESSION['id_usu'])) {
    header("Location: ../home/index.php?error=auth_required");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Efetuada! - SkateLab</title>
    
    <link rel="stylesheet" href="pedido_sucesso.css"> 
    
    <link rel="stylesheet" href="../global/global.css">
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>
<body>
    
    <header>
        <?php include '../componentes/navbar.php'; ?>
    </header>

    <main class="success-container">
        <div class="success-card">
            
            <i class="ri-checkbox-circle-line success-icon"></i>
            
            <h1>Compra efetuada com sucesso!</h1>
            <p>
                Seu pedido foi registrado e já está sendo preparado. 
                Você pode acompanhar o status do seu pedido a qualquer momento na sua página de perfil.
            </p>
            
            <div class="success-actions">
                <a href="../perfil/perfil.php" class="btn-primary">Ver Histórico de Pedidos</a>
                <a href="../skateshop/skateee.php" class="btn-secondary">Continuar Comprando</a>
            </div>
            
        </div>
    </main>

    <footer>
        <?php include '../componentes/footer.php'; ?>
    </footer>
    
</body>
</html>