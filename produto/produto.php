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

// O título da página agora é dinâmico
$titulo_pagina = $produto ? htmlspecialchars($produto['nome']) : 'Produto não encontrado';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?> - Skate Lab</title>
    <!-- Link para o SEU CSS -->
    <link rel="stylesheet" href="produto.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>

    <?php if ($produto): ?>
        <!-- ============================================= -->
        <!-- SE O PRODUTO FOI ENCONTRADO, MOSTRAR ISSO:    -->
        <!-- (Agora usando as classes do SEU CSS)         -->
        <!-- ============================================= -->
        
        <!-- Seu CSS usa ".container" como o main wrapper -->
        <main class="container">
            
            <!-- Seu CSS usa ".galeria" e ".imgprincipal" -->
            <section class="galeria">
                <!-- <div class="miniimg"> ... </div> --> 
                <!-- (Pulando as mini-imagens por enquanto, focando na principal) -->
                <div class="imgprincipal">
                    <img src="<?php echo htmlspecialchars($produto['url_img']); ?>" 
                         alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                </div>
            </section>

            <!-- Seu CSS usa ".info" para os detalhes -->
            <section class="info">
                
                <!-- Título -->
                <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
                
                <!-- Estrelas (fixo por enquanto, pois não tem no banco) -->
                <div class="estrelas">
                    <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>
                    <i class="fa-regular fa-star"></i> <span>(Avaliações)</span>
                </div>

                <!-- Descrição Curta (do banco) -->
                <!-- (Adicionei um estilo inline simples, já que não tinha classe pra isso) -->
                <p style="font-size: 1.1rem; color: #555; margin-top: 15px;">
                    <?php echo htmlspecialchars($produto['desc_curta']); ?>
                </p>

                <!-- Seu CSS usa ".precoproduto" > ".preco" > "h2" -->
                <div class="precoproduto">
                    <div class="preco">
                        <h2>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h2>
                    </div>
                </div>
                
                <!-- Informação de Estoque (do banco) -->
                <!-- (Adicionei estilo inline simples) -->
                <div class="estoque-info" style="margin: 15px 0;">
                    <?php if ($produto['estoque'] > 0): ?>
                        <span style="color: green; font-weight: 600;">
                            <i class="fa-solid fa-check-circle"></i> Em estoque (<?php echo $produto['estoque']; ?> unidades) <!-- <--- MUDANÇA AQUI -->
                        </span>
                    <?php else: ?>
                        <span style="color: red; font-weight: 600;">
                            <i class="fa-solid fa-times-circle"></i> Produto indisponível
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Seu CSS usa ".quantidadecard" e ".quantidade" -->
                <div class="quantidadecard">
                    <span>Quantidade:</span>
                    <div class="quantidade">
                        <button class="qtd-btn" data-action="decrease">-</button>
                        <span class="num">1</span>
                        <button class="qtd-btn" data-action="increase">+</button>
                    </div>
                </div>

                <!-- Botões (usando <a> como no seu CSS) -->
                <a href="../pagamento/pagamento.php" class="botaocomprar1">Comprar Agora</a>
                
                <!-- O botão do carrinho agora tem o ID do produto -->
                <a href="#" class="botaoadcarrinho" data-id="<?php echo $produto['id_pecas']; ?>">
                    <i class="fa-solid fa-cart-shopping"></i> Adicionar ao Carrinho
                </a>
                <!-- Área para mensagem de feedback do JS -->
                <div id="feedback-carrinho" style="margin-top: 10px; font-weight: 600;"></div>

            </section>
        </main>

        <!-- Descrição Longa (do banco) -->
        <!-- Seu CSS usa ".descricaogeral" -->
        <?php if (!empty($produto['dsc_longa'])): ?>
            <section class="descricaogeral">
                <h2>Descrição do Produto</h2>
                <p><?php echo nl2br(htmlspecialchars($produto['dsc_longa'])); ?></p>
            </section>
        <?php endif; ?>

    <?php else: ?>
        <!-- =================================================== -->
        <!-- SE O PRODUTO NÃO FOI ENCONTRADO, MOSTRAR ISSO:    -->
        <!-- =================================================== -->
        <div class="produto-nao-encontrado" style="text-align: center; padding: 50px;">
            <h1>Oops!</h1>
            <p>Não conseguimos encontrar o produto (ID: <?php echo $id_peca; ?>).</p>
            <a href="../skateshop/skateee.php" style="color: blue; font-weight: 600;">Voltar para a loja</a>
        </div>
    <?php endif; ?>

    <?php include '../componentes/footer.php'; ?>
    
    <!-- Link para o NOVO produto.js -->
    <script src="produto.js"></script>
</body>
</html>

