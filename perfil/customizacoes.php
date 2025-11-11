<?php
// Lista as customizações salvas do usuário e permite ações (ver no carrinho / excluir)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    header('Location: ../home/index.php?error=auth_required');
    exit;
}

require_once __DIR__ . '/../config/db.php';

$id_usuario = (int)$_SESSION['id_usu'];
$customizacoes = [];
$erro_db = null;

try {
    $stmt = $pdo->prepare(
        "SELECT id_customizacao, titulo, preco_total, criado_em, preview_img
         FROM public.customizacoes
         WHERE id_usu = :id
         ORDER BY criado_em DESC"
    );
    $stmt->execute([':id' => $id_usuario]);
    $customizacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Erro ao carregar customizações: ' . $e->getMessage());
    $erro_db = 'Não foi possível carregar suas customizações.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Customizações - SkateLab</title>
    <link rel="stylesheet" href="../global/global.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .page { max-width: 1100px; margin: 0 auto; padding: 24px; }
        .titulo { display:flex; align-items:center; gap:12px; margin-bottom: 16px; }
        .grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(260px,1fr)); gap:16px; }
        .card { border:1px solid #eee; border-radius:10px; padding:14px; background:#fff; display:flex; flex-direction:column; gap:10px; }
        .card img { width:100%; height:160px; object-fit:cover; border-radius:8px; background:#f7f7f7; }
        .card h3 { margin:6px 0 0; font-size:1.05rem; }
        .muted { color:#666; font-size:0.9rem; }
        .acoes { display:flex; gap:8px; margin-top:auto; }
        .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 10px; border-radius:8px; border:1px solid #ddd; background:#fafafa; cursor:pointer; text-decoration:none; color:#222; }
        .btn.primary { background:#111; color:#fff; border-color:#111; }
        .vazio { padding:24px; border:1px dashed #ccc; border-radius:10px; background:#fafafa; }
        header nav { margin-bottom: 16px; }
    </style>
    <link rel="stylesheet" href="../perfil/perfil.css">
    <link rel="stylesheet" href="../componentes/nav.css">
    <link rel="stylesheet" href="../componentes/footer.css">
</head>
<body>
    <header>
        <nav>
            <?php include '../componentes/navbar.php'; ?>
        </nav>
    </header>

    <main class="page">
        <div class="titulo">
            <i class="ri-equalizer-line"></i>
            <h1>Minhas Customizações</h1>
        </div>

        <?php if ($erro_db): ?>
            <div class="vazio"><?= htmlspecialchars($erro_db) ?></div>
        <?php elseif (empty($customizacoes)): ?>
            <div class="vazio">
                Você ainda não salvou nenhuma customização.
                <div style="margin-top:10px;">
                    <a href="../select/select.php" class="btn primary"><i class="ri-magic-line"></i> Criar uma customização</a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid">
            <?php foreach ($customizacoes as $c): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($c['preview_img'] ?: '../img/imgs-skateshop/image.png') ?>" alt="Preview da customização">
                    <h3><?= htmlspecialchars($c['titulo']) ?></h3>
                    <div class="muted">Salvo em <?= htmlspecialchars(date('d/m/Y H:i', strtotime($c['criado_em']))) ?></div>
                    <div><strong>R$ <?= number_format((float)$c['preco_total'], 2, ',', '.') ?></strong></div>
                    <div class="acoes">
                        <form action="../carrinho/contr/adicionar_carrinho.php" method="POST">
                            <input type="hidden" name="id" value="custom-<?= (int)$c['id_customizacao'] ?>">
                            <input type="hidden" name="nome" value="<?= htmlspecialchars($c['titulo']) ?>">
                            <input type="hidden" name="preco" value="<?= htmlspecialchars((float)$c['preco_total']) ?>">
                            <input type="hidden" name="quantidade" value="1">
                            <input type="hidden" name="imagem" value="<?= htmlspecialchars($c['preview_img'] ?: '../img/imgs-skateshop/image.png') ?>">
                            <input type="hidden" name="descricao" value="Skate customizado salvo">
                            <input type="hidden" name="redirect_to" value="carrinho">
                            <button type="submit" class="btn primary"><i class="ri-shopping-cart-2-line"></i> Adicionar ao carrinho</button>
                        </form>
                        <a class="btn" href="excluir_customizacao.php?id=<?= (int)$c['id_customizacao'] ?>" onclick="return confirm('Remover esta customização?');"><i class="ri-delete-bin-line"></i> Excluir</a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <?php include '../componentes/footer.php'; ?>
    </footer>
</body>
</html>
