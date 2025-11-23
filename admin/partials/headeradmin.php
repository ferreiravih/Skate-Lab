<?php
// Calcula a URL base do projeto (ex: /Skate-Lab)
// __DIR__ é .../Skate-Lab/admin/partials
// Subindo 2 níveis (/../..) chegamos na raiz do projeto
$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/../..'));
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';
$baseUrl = '';

if ($projectRoot !== false && $docRoot) {
    if (strpos($projectRoot, $docRoot) === 0) {
        $baseUrl = rtrim(substr($projectRoot, strlen($docRoot)), '/');
    }
}

// Garante que a barra inicial exista
if ($baseUrl === '' && isset($_SERVER['PHP_SELF'])) {
     $segments = explode('/', trim($_SERVER['PHP_SELF'], '/'));
     if (count($segments) > 0 && $segments[0] == 'Skate-Lab') {
         $baseUrl = '/Skate-Lab';
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
    <title>skatelab</title>
    
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/admin/partials/adminnav.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="icon" type="image/png" href="<?php echo $baseUrl; ?>/img/imgs-icon/icon.png">

</head>

<body>
    <header class="hea">
        <div class="logo">
            <h1><a href="<?php echo $baseUrl; ?>/admin/dashboard.php">SKATELAB</a></h1>
        </div>
        <nav class="vika">
            <a href="<?php echo $baseUrl; ?>/admin/produtos/produtos.php"><i class="ri-box-3-line"></i>Produtos</a>
            <a href="<?php echo $baseUrl; ?>/admin/categorias/categoria.php"><i class="ri-folder-line"></i>Categorias</a>
            <a href="<?php echo $baseUrl; ?>/admin/pedidos/pedidos.php"><i class="ri-shopping-cart-2-line"></i>Pedidos</a>
        </nav>
        
        <div class="user">
            <a href="<?php echo $baseUrl; ?>/auth/logout.php" title="Sair do Painel">
                <i class="ri-logout-box-r-line"></i>
            </a>
        </div>
    </header>
</body>
</html>