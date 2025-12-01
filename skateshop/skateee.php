<?php
session_start();

require_once __DIR__ . '/../config/db.php';


try {
    $stmt_cattegorias = $pdo->query("SELECT nome FROM categorias WHERE nome <> 'Arquivados' ORDER BY nome");
    $categorias = $stmt_cattegorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar categorias: " . $e->getMessage();
    $categorias = []; 
}


$filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : 'todos';

try {

    $sql = "SELECT p.*, c.nome AS categoria_nome 
            FROM pecas p
            JOIN categorias c ON p.id_cat = c.id_cat
            WHERE p.status = 'ATIVO'";

    $stmt_produtos = $pdo->query($sql);
    $produtos = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    $produtos = []; 
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateShop</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="skateee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>

    <section class="imgebarra">
        <div class="texto">
            <h1>Sua próxima manobra começa aqui!</h1> <br>
            <p>Skates completos, shapes exclusivos e acessórios que fazem a diferença.</p> <br>
        </div>
    </section>


    <div class="search-container">
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar produtos...">
            <button type="submit" class="search-button">
                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            </button>
        </div>
    </div>


    <div class="hhh">
        <div class="letras">
            <h2 class="desta"> produtos em destaque </h2>
            <p class="parag"> descubra nossos produtos mais vendidos</p>
        </div>
    </div>
    

    <nav class="categories" data-default="<?php echo htmlspecialchars($filtro_categoria, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="button" data-categoria="todos" class="<?php echo ($filtro_categoria == 'todos') ? 'active' : ''; ?>">
            Todos
        </button>

        <?php foreach ($categorias as $categoria) : ?>
            <?php

            $nome_categoria = htmlspecialchars($categoria['nome'], ENT_QUOTES, 'UTF-8');
            $classe_ativa = ($filtro_categoria == $nome_categoria) ? 'active' : '';
            ?>
            <button type="button" data-categoria="<?php echo $nome_categoria; ?>" class="<?php echo $classe_ativa; ?>">
                <?php echo $nome_categoria; 
                ?>
            </button>
        <?php endforeach; ?>
    </nav>



    <main class="content">
        <section class="produtos">
            <div class="containershop">

                <?php if (empty($produtos)) : ?>
                    <p>Nenhum produto encontrado...</p>
                <?php else : ?>
                    <?php foreach ($produtos as $produto) : ?>

                        <div class="card" data-categoria="<?php echo htmlspecialchars($produto['categoria_nome'], ENT_QUOTES, 'UTF-8'); ?>">
                            
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
                                    
                                    <button type="submit" class="botaocomprar form-protegido">comprar</button>
                                </form>

                                <form action="../carrinho/contr/adicionar_carrinho.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $produto['id_pecas']; ?>">
                                    <input type="hidden" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>">
                                    <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                                    <input type="hidden" name="imagem" value="<?php echo htmlspecialchars($produto['url_img']); ?>">
                                    <input type="hidden" name="descricao" value="<?php echo htmlspecialchars($produto['desc_curta']); ?>">
                                    <input type="hidden" name="quantidade" value="1">
                                    <input type="hidden" name="redirect_to" value="carrinho">
                                    
                                    <button type="submit" class="botaocarrinho form-protegido"><i class="fa-solid fa-cart-shopping"></i></button>
                                </form>
                                
                                </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </section>
    </main>
    <?php include '../componentes/footer.php'; ?>

    <script src="skateshop.js"></script>

</body>

</html>