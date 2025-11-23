<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// 1. PEGAR O ID DA URL E VALIDAR
$id_peca = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produto = null;
$avaliacoes = [];
$media_nota = 0;
$total_avaliacoes = 0;

if ($id_peca > 0) {
    try {
        $id_usuario_logado = $_SESSION['id_usu'] ?? null;

        if ($id_usuario_logado) {
            $sql = "SELECT p.*, c.nome AS categoria_nome, f.id_favorito
                    FROM pecas p
                    JOIN categorias c ON p.id_cat = c.id_cat
                    LEFT JOIN favoritos f ON p.id_pecas = f.id_pecas AND f.id_usu = :id_usu
                    WHERE p.id_pecas = :id_peca AND p.status = 'ATIVO'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_peca' => $id_peca, ':id_usu' => $id_usuario_logado]);
        } else {
            $sql = "SELECT p.*, c.nome AS categoria_nome, NULL as id_favorito
                    FROM pecas p
                    JOIN categorias c ON p.id_cat = c.id_cat
                    WHERE p.id_pecas = :id_peca AND p.status = 'ATIVO'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_peca' => $id_peca]);
        }
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        // --- MUDANÇA 1: Buscar url_perfil na consulta de avaliações ---
        if ($produto) {
            $sql_av = "SELECT a.*, u.nome as nome_usuario, u.apelido, u.url_perfil
                       FROM avaliacoes a
                       JOIN usuario u ON a.id_usu = u.id_usu
                       WHERE a.id_pecas = :id_peca
                       ORDER BY a.criado_em DESC";
            $stmt_av = $pdo->prepare($sql_av);
            $stmt_av->execute([':id_peca' => $id_peca]);
            $avaliacoes = $stmt_av->fetchAll(PDO::FETCH_ASSOC);


            $total_avaliacoes = count($avaliacoes);
            if ($total_avaliacoes > 0) {
                $soma_notas = array_sum(array_column($avaliacoes, 'nota'));
                $media_nota = round($soma_notas / $total_avaliacoes);
            }
        }
    } catch (PDOException $e) {
        echo "Erro ao buscar dados: " . $e->getMessage();
    }
}

$titulo_pagina = $produto ? htmlspecialchars($produto['nome']) : 'Produto não encontrado';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?> - Skate Lab</title>
    <link rel="stylesheet" href="produto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>

    <?php if ($produto): ?>
        <main class="container">

            <section class="galeria">
                <div class="imgprincipal">
                    <img src="<?php echo htmlspecialchars($produto['url_img']); ?>"
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                </div>
            </section>

            <section class="info">

                <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>

                <div class="estrelas">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $media_nota) {
                            echo '<i class="fa-solid fa-star" style="color: #ffd700;"></i> ';
                        } else {
                            echo '<i class="fa-regular fa-star" style="color: #ccc;"></i> ';
                        }
                    }
                    ?>
                    <span>(<?php echo $total_avaliacoes; ?> Avaliações)</span>
                </div>

                <p style="font-size: 1.1rem; color: #555; margin-top: 15px;">
                    <?php echo htmlspecialchars($produto['desc_curta']); ?>
                </p>
                <div class="precoproduto">
                    <div class="preco">
                        <h2>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h2>
                    </div>
                </div>
                <div class="estoque-info" style="margin: 15px 0;">
                    <?php if ($produto['estoque'] > 0): ?>
                        <span style="color: green; font-weight: 600;">
                            <i class="fa-solid fa-check-circle"></i> Em estoque (<?php echo $produto['estoque']; ?> unidades)
                        </span>
                    <?php else: ?>
                        <span style="color: red; font-weight: 600;">
                            <i class="fa-solid fa-times-circle"></i> Produto indisponível
                        </span>
                    <?php endif; ?>
                </div>

                <?php
                $activeClass = $produto['id_favorito'] ? 'active' : '';
                $btnText = $produto['id_favorito'] ? 'Salvo nos Favoritos' : 'Salvar nos Favoritos';
                ?>
                <button type="button" class="btn-favorito-bloco <?php echo $activeClass; ?> form-protegido"
                    data-id-peca="<?php echo $produto['id_pecas']; ?>">
                    <i class="ri-heart-fill icon-filled"></i>
                    <i class="ri-heart-line icon-outlined"></i>
                    <span class="btn-fav-text"><?php echo $btnText; ?></span>
                </button>

                <div class="product-actions">
                    <form action="../carrinho/contr/adicionar_carrinho.php" method="POST" class="form-carrinho">
                        <input type="hidden" name="id" value="<?php echo $produto['id_pecas']; ?>">
                        <input type="hidden" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>">
                        <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                        <input type="hidden" name="imagem" value="<?php echo htmlspecialchars($produto['url_img']); ?>">
                        <input type="hidden" name="descricao" value="<?php echo htmlspecialchars($produto['desc_curta']); ?>">
                        <input type="hidden" name="quantidade" value="1">
                        <input type="hidden" name="redirect_to" value="carrinho" class="redirect_to_input">

                        <button type="submit" class="botaocomprar1 form-protegido"
                            onclick="this.closest('form').querySelector('.redirect_to_input').value = 'checkout';">
                            Comprar Agora
                        </button>
                        <button type="submit" class="botaoadcarrinho form-protegido"
                            onclick="this.closest('form').querySelector('.redirect_to_input').value = 'carrinho';">
                            <i class="fa-solid fa-cart-shopping"></i> Adicionar ao Carrinho
                        </button>
                    </form>
                </div>
            </section>
        </main>

        <?php if (!empty($produto['dsc_longa'])): ?>
            <section class="descricaogeral">
                <h2>Descrição do Produto</h2>
                <p><?php echo nl2br(htmlspecialchars($produto['dsc_longa'])); ?></p>
            </section>
        <?php endif; ?>

        <section class="avaliacoes-container">
            <h2>Avaliações dos Clientes</h2>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            <?php if (isset($_SESSION['id_usu'])): ?>
                <div class="form-avaliacao">
                    <h3>Deixe sua opinião</h3>
                    <form action="adicionar_avaliacao.php" method="POST">
                        <input type="hidden" name="id_peca" value="<?php echo $produto['id_pecas']; ?>">

                        <div style="margin-bottom: 10px;">
                            <p>Sua nota:</p>
                            <div class="rating-input">
                                <input type="radio" name="nota" id="star5" value="5" required><label for="star5" title="5 estrelas"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="nota" id="star4" value="4"><label for="star4" title="4 estrelas"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="nota" id="star3" value="3"><label for="star3" title="3 estrelas"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="nota" id="star2" value="2"><label for="star2" title="2 estrelas"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="nota" id="star1" value="1"><label for="star1" title="1 estrela"><i class="fa-solid fa-star"></i></label>
                            </div>
                        </div>

                        <textarea name="comentario" rows="4" placeholder="Escreva o que você achou do produto..." required></textarea>
                        <button type="submit" class="btn-enviar-av">Enviar Avaliação</button>
                    </form>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 40px; background: #eee; padding: 20px; border-radius: 8px; text-align: center;">
                    <p>Você precisa estar logado para avaliar este produto.</p>
                    <a href="../auth/login.php" style="color: #333; font-weight: bold; text-decoration: underline;">Fazer Login</a>
                </div>
            <?php endif; ?>

            <div class="lista-avaliacoes">
                <?php if (count($avaliacoes) > 0): ?>
                    <?php foreach ($avaliacoes as $av): ?>
                        <div class="avaliacao-item">
                            <div class="user-info">
                                
                                <?php if (!empty($av['url_perfil'])): ?>
                                    <img src="<?php echo htmlspecialchars($av['url_perfil']); ?>" 
                                         alt="Foto de perfil" 
                                         style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #eee;">
                                <?php else: ?>
                                    <i class="fa-solid fa-user-circle fa-2x" style="color: #ccc;"></i>
                                <?php endif; ?>

                                <div>
                                    <span><?php echo htmlspecialchars($av['apelido'] ?? $av['nome_usuario']); ?></span>
                                    <span class="data-avaliacao">- <?php echo date('d/m/Y', strtotime($av['criado_em'])); ?></span>
                                </div>
                            </div>
                            <div class="estrelas-av">
                                <?php
                                for ($j = 1; $j <= 5; $j++) {
                                    echo ($j <= $av['nota'])
                                        ? '<i class="fa-solid fa-star"></i> '
                                        : '<i class="fa-regular fa-star" style="color: #ccc;"></i> ';
                                }
                                ?>
                            </div>
                            <p class="texto-comentario"><?php echo nl2br(htmlspecialchars($av['comentario'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #777; font-style: italic;">Este produto ainda não tem avaliações. Seja o primeiro a avaliar!</p>
                <?php endif; ?>
            </div>
        </section>

    <?php else: ?>
        <main class="container">
            <section class="info" style="text-align: center; width: 100%;">
                <h1><i class="fa-solid fa-circle-exclamation"></i> Produto não encontrado</h1>
                <p>O item que você está procurando não existe ou não está mais disponível.</p>
                <a href="../skateshop/skateee.php" class="botaoadcarrinho" style="max-width: 300px; margin-top: 20px;">Voltar para a Loja</a>
            </section>
        </main>
    <?php endif; ?>

    <?php include '../componentes/footer.php'; ?>
    <script src="produto.js"></script>
</body>

</html>