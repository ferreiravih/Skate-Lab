<?php
// Lista as customizaÃ§Ãµes salvas do usuÃ¡rio e permite aÃ§Ãµes (ver no carrinho / excluir)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    header('Location: ../../home/index.php?error=auth_required');
    exit;
}

require_once __DIR__ . '/../../config/db.php';

function obterBasePublicPath(): string {
    $projectRoot = realpath(__DIR__ . '/../../');
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

    $project = $projectRoot ? str_replace('\\', '/', $projectRoot) : '';
    $doc = $docRoot ? str_replace('\\', '/', $docRoot) : '';

    if ($project && $doc && strpos($project, $doc) === 0) {
        $relative = trim(substr($project, strlen($doc)), '/');
        return $relative === '' ? '' : '/' . $relative;
    }
    return '';
}

function resolverPreview(?string $path, string $basePublic, string $default): string {
    if (!is_string($path) || trim($path) === '') {
        return $default;
    }
    $normalizado = str_replace('\\', '/', trim($path));

    if (preg_match('#^https?://#i', $normalizado)) {
        return $normalizado;
    }
    if (strpos($normalizado, 'data:image') === 0) {
        return $default;
    }
    if ($normalizado !== '' && $normalizado[0] === '/') {
        return $normalizado;
    }

    while (strpos($normalizado, '../') === 0) {
        $normalizado = substr($normalizado, 3);
    }
    $normalizado = ltrim($normalizado, './');

    $prefixo = $basePublic ?: '';
    $caminho = ($prefixo ?: '') . '/' . ltrim($normalizado, '/');

    return $caminho ?: $default;
}

$basePublicPath = obterBasePublicPath();
$defaultPreview = ($basePublicPath ?: '') . '/img/imgs-skateshop/image.png';

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
    error_log('Erro ao carregar customizaÃ§Ãµes: ' . $e->getMessage());
    $erro_db = 'Não foi possivel carregar suas customização.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Customizações - SkateLab</title>

    <link rel="stylesheet" href="../../global/global.css?v=1.0.9">
    <link rel="stylesheet" href="../../componentes/nav.css?v=1.0.9">
    <link rel="stylesheet" href="../../componentes/footer.css?v=1.0.9">
    <link rel="stylesheet" href="../perfil.css?v=1.0.9">
    <link rel="stylesheet" href="customizacoes.css?v=1.1.1">
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <div class="page-container">
        <header>
            <nav>
                <?php include '../../componentes/navbar.php'; ?>
            </nav>
        </header>

        <div class="main-container">
            
            <aside class="profile-sidebar"> 
                <a href="../perfil.php" class="menu-item">
                    <i class="ri-user-line"></i> Perfil
                </a>
                <a href="../../favoritos/favoritos.php" class="menu-item">
                    <i class="ri-heart-line"></i> Favoritos
                </a>
                <a href="customizacoes.php" class="menu-item active">
                    <i class="ri-equalizer-line"></i> Customizações
                </a>
                <a href="../../carrinho/carrinho.php" class="menu-item">
                    <i class="ri-shopping-cart-line"></i> Carrinho
                </a>
                <a href="../../auth/logout.php" class="menu-item sair">
                    <i class="ri-logout-box-r-line"></i> Sair
                </a>
            </aside>

            <main class="profile-content">

                <div class="content-wrapper"> <div class="card favoritos-header-card">
                        <h3><i class="ri-equalizer-line"></i> Minhas Customizações</h3>
                    </div>

                    <?php if ($erro_db): ?>
                        <div class="vazio"><?= htmlspecialchars($erro_db) ?></div>
                    <?php elseif (empty($customizacoes)): ?>
                        <div class="vazio"> 
                            <i class="ri-equalizer-add-line"></i>
                            <p>Você ainda não salvou nenhuma customização.</p>
                            <a href="../../select/select.php" class="btn primary"><i class="ri-magic-line"></i> Criar uma agora</a>
                        </div>
                    <?php else: ?>
                        <div class="grid">
                        <?php foreach ($customizacoes as $c): ?>
                            <?php $previewSrc = resolverPreview($c['preview_img'] ?? null, $basePublicPath, $defaultPreview); ?>
                            <div class="card">
                                <img src="<?= htmlspecialchars($previewSrc) ?>" alt="Preview da customização">
                                <h3><?= htmlspecialchars($c['titulo']) ?></h3>
                                <div class="muted">Salvo em <?= htmlspecialchars(date('d/m/Y H:i', strtotime($c['criado_em']))) ?></div>
                                <div><strong>R$ <?= number_format((float)$c['preco_total'], 2, ',', '.') ?></strong></div>
                                
                                <div class="acoes">
                                    <a class="btn" href="../../customizacao/custom.php?custom_id=<?= (int)$c['id_customizacao'] ?>">
                                        <i class="ri-cube-3-line"></i> Ver
                                    </a>
                                    <form action="../../carrinho/contr/adicionar_carrinho.php" method="POST">
                                        <input type="hidden" name="id" value="custom-<?= (int)$c['id_customizacao'] ?>">
                                        <input type="hidden" name="nome" value="<?= htmlspecialchars($c['titulo']) ?>">
                                        <input type="hidden" name="preco" value="<?= htmlspecialchars((float)$c['preco_total']) ?>">
                                        <input type="hidden" name="quantidade" value="1">
                                        <input type="hidden" name="imagem" value="<?= htmlspecialchars($previewSrc) ?>">
                                        <input type="hidden" name="descricao" value="Skate customizado salvo">
                                        <input type="hidden" name="redirect_to" value="carrinho">
                                        <button type="submit" class="btn primary"><i class="ri-shopping-cart-2-line"></i> Carrinho</button>
                                    </form>
                                    <button type="button" class="btn btn-excluir" data-id="<?= (int)$c['id_customizacao'] ?>" data-titulo="<?= htmlspecialchars($c['titulo']) ?>">
                                        <i class="ri-delete-bin-line"></i> Excluir
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div> </main>
        </div>

        <div id="excluir-overlay" class="salvar-modal-overlay">
            <div class="salvar-modal-box">
                <h2>Excluir customização</h2>
                <p id="excluir-modal-descricao">Tem certeza que deseja excluir esta customização?</p>
                <div class="salvar-modal-botoes">
                    <button type="button" class="modal-btn-cancelar" id="excluir-btn-cancelar">Cancelar</button>
                    <button type="button" class="modal-btn-confirmar" id="excluir-btn-confirmar">Excluir</button>
                </div>
            </div>
        </div>

        <footer>
            <?php include '../../componentes/footer.php'; ?>
        </footer>
    </div> <script>
        document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.getElementById('excluir-overlay');
            if (!overlay) {
                return;
            }

            const btnCancelar = document.getElementById('excluir-btn-cancelar');
            const btnConfirmar = document.getElementById('excluir-btn-confirmar');
            const descricao = document.getElementById('excluir-modal-descricao');
            let alvoId = null;

            function abrirModal(id, titulo) {
                alvoId = id;
                if (descricao) {
                    descricao.textContent = titulo
                        ? `Tem certeza que deseja excluir "${titulo}"?`
                        : 'Tem certeza que deseja excluir esta customização?';
                }

                overlay.style.display = 'flex';
                requestAnimationFrame(() => overlay.classList.add('visivel'));
            }

            function fecharModal() {
                overlay.classList.remove('visivel');
                setTimeout(() => {
                    overlay.style.display = 'none';
                    alvoId = null;
                    if (btnConfirmar) {
                        btnConfirmar.disabled = false;
                        btnConfirmar.textContent = 'Excluir';
                    }
                }, 250);
            }

            document.querySelectorAll('.btn-excluir').forEach((btn) => {
                btn.addEventListener('click', () => {
                    abrirModal(btn.getAttribute('data-id'), btn.getAttribute('data-titulo'));
                });
            });

            btnCancelar?.addEventListener('click', fecharModal);
            overlay.addEventListener('click', (event) => {
                if (event.target === overlay) {
                    fecharModal();
                }
            });

            btnConfirmar?.addEventListener('click', () => {
                if (!alvoId) {
                    return;
                }
                btnConfirmar.disabled = true;
                btnConfirmar.textContent = 'Excluindo...';
                window.location.href = `excluir_customizacao.php?id=${alvoId}`;
            });
        });
    </script>
</body>
</html>

