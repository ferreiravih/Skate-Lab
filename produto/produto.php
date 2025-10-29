<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// 1. PEGAR O ID DA URL E VALIDAR
$id_peca = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produto = null;

if ($id_peca > 0) {
    // 2. BUSCAR O PRODUTO ESPECÍFICO NO BANCO
    try {
        $sql = "SELECT p.*, c.nome AS categoria_nome 
                FROM pecas p
                JOIN categorias c ON p.id_cat = c.id_cat
                WHERE p.id_pecas = :id_peca AND p.status = 'ATIVO'";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_peca', $id_peca, PDO::PARAM_INT);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao buscar produto: " . $e->getMessage();
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
                    <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>
                    <i class="fa-regular fa-star"></i> <span>(Avaliações)</span>
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

                <form action="../carrinho/contr/adicionar_carrinho.php" method="POST" style="width: 100%;">
                    <input type="hidden" name="id" value="<?php echo $produto['id_pecas']; ?>">
                    <input type="hidden" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>">
                    <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                    <input type="hidden" name="imagem" value="<?php echo htmlspecialchars($produto['url_img']); ?>">
                    <input type="hidden" name="descricao" value="<?php echo htmlspecialchars($produto['desc_curta']); ?>">
                    
                    <input type="hidden" name="redirect_to" value="carrinho" class="redirect_to_input">

                    <div class="quantidadecard">
                        <span>Quantidade:</span>
                        <div class="quantidade">
                            <button type="button" class="qtd-btn" data-action="decrease">-</button>
                            
                            <input type="text" class="num" name="quantidade" value="1" readonly 
                                   style="width: 30px; text-align: center; border: none; font-size: 16px; font-weight: 500; color: #333;">
                                   
                            <button type="button" class="qtd-btn" data-action="increase">+</button>
                        </div>
                    </div>
                    
                    <button type="submit" class="botaocomprar1"
                        onclick="this.closest('form').querySelector('.redirect_to_input').value = 'checkout';">
                        Comprar Agora
                    </button>
                    <button type="submit" class="botaoadcarrinho"
                        onclick="this.closest('form').querySelector('.redirect_to_input').value = 'carrinho';">
                        <i class="fa-solid fa-cart-shopping"></i> Adicionar ao Carrinho
                    </button>
                </form>

                </section>
        </main>

        <?php if (!empty($produto['dsc_longa'])): ?>
            <section class="descricaogeral">
                <h2>Descrição do Produto</h2>
                <p><?php echo nl2br(htmlspecialchars($produto['dsc_longa'])); ?></p>
            </section>
        <?php endif; ?>

    <?php else: ?>
        <?php endif; ?>

    <?php include '../componentes/footer.php'; ?>

    <script src="produto.js"></script>
</body>

</html>