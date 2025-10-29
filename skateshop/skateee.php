<?php
session_start();
// 1. Conexão do base
require_once __DIR__ . '/../config/db.php';

// 2. busca as categorias do filtro
try {
    $stmt_cattegorias = $pdo->query("SELECT nome FROM categorias ORDER BY nome");
    $categorias = $stmt_cattegorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar categorias: " . $e->getMessage();
    $categorias = []; // para garantir a varialvel
}

// 3. Busca produtos e junta com categorias
$filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : 'todos';

try {
    // Query base que junta peças e categorias
    $sql = "SELECT p.*, c.nome AS categoria_nome 
            FROM pecas p
            JOIN categorias c ON p.id_cat = c.id_cat
            WHERE p.status = 'ATIVO'"; // Garante que só produtos ativos apareçam

    // Se a categoria NÃO for "todos", adicionamos o filtro
    if ($filtro_categoria != 'todos') {
        $sql .= " AND c.nome = :categoria_nome";
        $stmt_produtos = $pdo->prepare($sql);
        $stmt_produtos->bindParam(':categoria_nome', $filtro_categoria);
    } else {
        // Se for "todos", apenas prepara a query sem filtro extra
        $stmt_produtos = $pdo->prepare($sql);
    }

    $stmt_produtos->execute();
    $produtos = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    $produtos = []; // Garante que a variável exista
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateShop</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- Corrigido para carregar o CSS da mesma pasta -->
    <link rel="stylesheet" href="skateee.css">
    <!-- Font Awesome (para ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>

    <section class="imgebarra">
        <div class="texto">
            <h1>Sua próxima manobra começa aqui!</h1> <br>
            <p>Skates completos, shapes exclusivos e acessórios que fazem a diferença.</p> <br>
        </div>
    </section>

    <!-- ================================== -->
    <!-- NOVA ORDEM DO LAYOUT (Sua sugestão) -->
    <!-- ================================== -->

    <!-- 1. BARRA DE PESQUISA (Veio para cima) -->
    <div class="search-container">
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar produtos...">
            <button type="submit" class="search-button">
                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            </button>
        </div>
    </div>

    <!-- 2. TÍTULOS H2/P (Continuam no meio) -->
    <div class="hhh">
        <div class="letras">
            <h2 class="desta"> produtos em destaque </h2>
            <p class="parag"> descubra nossos produtos mais vendidos</p>
        </div>
    </div>
    
    <!-- 3. FILTROS (Vieram para baixo, mais perto dos produtos) -->
    <nav class="categories">
        <a href="skateee.php?categoria=todos" class="<?php echo ($filtro_categoria == 'todos') ? 'active' : ''; ?>">
            Todos
        </a>

        <?php foreach ($categorias as $categoria) : ?>
            <?php
            // Prepara o nome da categoria para o link
            $nome_cat_url = htmlspecialchars($categoria['nome']);
            // Verifica se é o filtro ativo para aplicar a classe 'active'
            $classe_ativa = ($filtro_categoria == $nome_cat_url) ? 'active' : '';
            ?>
            <a href="skateee.php?categoria=<?php echo $nome_cat_url; ?>" class="<?php echo $classe_ativa; ?>">
                <?php echo $nome_cat_url; // Ex: "Shapes", "Rodas", etc. 
                ?>
            </a>
        <?php endforeach; ?>
    </nav>


    <!-- 4. GRADE DE PRODUTOS (Onde o AJAX/JS vai funcionar) -->
    <main class="content">
        <section class="produtos">
            <div class="containershop">

                <?php if (empty($produtos)) : ?>
                    <p>Nenhum produto encontrado...</p>
                <?php else : ?>
                    <?php foreach ($produtos as $produto) : ?>

                        <div class="card" data-categoria="<?php echo htmlspecialchars($produto['categoria_nome']); ?>">
                            
                            <a href="../produto/produto.php?id=<?php echo $produto['id_pecas']; ?>" class="produto-card-link">
                                <img src="<?php echo htmlspecialchars($produto['url_img']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            </a>

                            <div class="info">
                                <span class="categoria"><?php echo htmlspecialchars(strtoupper($produto['categoria_nome'])); ?></span>

                                <a href="../produto/produto.php?id=<?php echo $produto['id_pecas']; ?>" class="produto-card-link-titulo">
                                    <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                </a>

                                <p class="preco">
                                    R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                                </p>

                                <form action="../carrinho/contr/adicionar_carrinho.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $produto['id_pecas']; ?>">
                                    <input type="hidden" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>">
                                    <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                                    <input type="hidden" name="imagem" value="<?php echo htmlspecialchars($produto['url_img']); ?>">
                                    <input type="hidden" name="descricao" value="<?php echo htmlspecialchars($produto['desc_curta']); ?>">
                                    <input type="hidden" name="quantidade" value="1">
                                    <input type="hidden" name="redirect_to" value="checkout">
                                    
                                    <button type="submit" class="botaocomprar">comprar</button>
                                </form>

                                <form action="../carrinho/contr/adicionar_carrinho.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $produto['id_pecas']; ?>">
                                    <input type="hidden" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>">
                                    <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                                    <input type="hidden" name="imagem" value="<?php echo htmlspecialchars($produto['url_img']); ?>">
                                    <input type="hidden" name="descricao" value="<?php echo htmlspecialchars($produto['desc_curta']); ?>">
                                    <input type="hidden" name="quantidade" value="1">
                                    <input type="hidden" name="redirect_to" value="carrinho">
                                    
                                    <button type="submit" class="botaocarrinho"><i class="fa-solid fa-cart-shopping"></i></button>
                                </form>
                                
                                </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </section>
    </main>
    <?php include '../componentes/footer.php'; ?>
    
    <!-- 
    ==================================
    CORREÇÃO DO BUG DO "PULO"
    O caminho estava errado (../../Skate-Lab/...). 
    O correto é carregar da mesma pasta:
    ================================== 
    -->
    <script src="skateshop.js"></script>
</body>

</html>

