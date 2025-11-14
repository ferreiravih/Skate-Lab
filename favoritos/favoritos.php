<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../home/index.php?error=auth_required");
    exit;
}

require_once __DIR__ . '/../config/db.php';

$id_usuario_logado = $_SESSION['id_usu'];
$favoritos = [];
$erro_db = null;

try {
    $sql = "SELECT p.id_pecas, p.nome, p.url_img, p.preco, p.desc_curta
            FROM public.favoritos f
            JOIN public.pecas p ON f.id_pecas = p.id_pecas
            WHERE f.id_usu = :id
            ORDER BY f.criado_em DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_usuario_logado]);
    $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao buscar favoritos: " . $e->getMessage());
    $erro_db = "Não foi possível carregar seus favoritos.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../perfil/perfil.css">
    <link rel="stylesheet" href="favoritos.css">
    <link rel="stylesheet" href="../../global/global.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <title>Meus Favoritos - SkateLab</title>
</head>
<body>
    <div class="page-container">
        <header>
            <nav>
                <?php include '../componentes/navbar.php'; ?>
            </nav>
        </header>
        <div class="main-container">

            <aside class="profile-sidebar">
                <a href="../perfil/perfil.php" class="menu-item">
                    <i class="ri-user-line"></i> Perfil
                </a>
                <a href="favoritos.php" class="menu-item active"> <i class="ri-heart-line"></i> Favoritos
                </a>
                <a href="../perfil/funcoes/customizacoes.php" class="menu-item">
                    <i class="ri-equalizer-line"></i> Customizações
                </a>
                <a href="../carrinho/carrinho.php" class="menu-item">
                    <i class="ri-shopping-cart-line"></i> Carrinho
                </a>
                <a href="../../auth/logout.php" class="menu-item sair">
                    <i class="ri-logout-box-r-line"></i> Sair
                </a>
            </aside>
            <main class="profile-content">
                <?php if ($erro_db): ?>
                    <div class="feedback error" style="display: block;"><?php echo htmlspecialchars($erro_db); ?></div>

                <?php else: ?>
                    <div class="card favoritos-header-card">
                        <h3><i class="ri-heart-line"></i> Meus Itens Favoritos</h3>
                    </div>
                    <div class="favoritos-grid" id="favoritos-grid">
                        <?php if (empty($favoritos)): ?>
                            <div class="empty-state-favoritos">
                                <i class="ri-heart-add-line"></i>
                                <p>Você ainda não favoritou nenhum item.</p>
                                <a href="../skateshop/skateee.php" class="btn-add-new">Ver produtos</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($favoritos as $item): ?>
                                <div class="fav-card" id="fav-card-<?php echo $item['id_pecas']; ?>">
                                    <a href="../../produto/produto.php?id=<?php echo $item['id_pecas']; ?>" class="fav-card-img-link">
                                        <img src="<?php echo htmlspecialchars($item['url_img']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                                    </a>
                                    <div class="fav-card-body">
                                        <h4 class="fav-card-title"><?php echo htmlspecialchars($item['nome']); ?></h4>
                                        <p class="fav-card-desc"><?php echo htmlspecialchars($item['desc_curta']); ?></p>
                                        <p class="fav-card-price">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                                    </div>
                                    <div class="fav-card-actions">
                                        <button type="button" class="btn-remover-fav" data-id-peca="<?php echo $item['id_pecas']; ?>">
                                            <p><i class="ri-delete-bin-line"></i> Remover</p>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
        <footer>
            <?php include '../componentes/footer.php'; ?>
        </footer>
    </div>
    <div id="confirm-remove-modal" class="modal-overlay">
        <div class="modal-content">
            <button type="button" class="modal-close-btn" id="confirm-modal-close-btn">&times;</button>
            <h3><i class="ri-delete-bin-line"></i> Confirmar Remoção</h3>
            <p>Tem certeza que deseja remover este item dos seus favoritos?</p>
            
            <div class="form-actions-modal">
                <button type="button" class="btn-cancel" id="confirm-modal-cancel-btn">Cancelar</button>
                <button type="button" class="btn-save btn-danger" id="confirm-modal-remove-btn">Remover</button>
            </div>
        </div>
    </div>
    <script src="favoritos.js"></script>
</body>

</html>