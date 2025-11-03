<?php
// 1. INCLUI O BANCO DE DADOS
require_once __DIR__ . '/../config/db.php';

$categoria_kits = 'Completos';

$kits = []; // Array padrão
try {

    $stmt = $pdo->prepare(
        "SELECT p.id_pecas, p.nome, p.preco, p.url_img
         FROM public.pecas p
         JOIN public.categorias c ON p.id_cat = c.id_cat
         WHERE c.nome = :categoria_nome AND p.status = 'ATIVO'
         ORDER BY p.criado_em DESC -- (Ou use RANDOM() se preferir aleatório)
         LIMIT 3"
    );
    $stmt->execute([':categoria_nome' => $categoria_kits]);
    $kits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

    error_log("Erro ao buscar kits da home: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="../global/global.css">
    <title>Home_SkateLab</title>
</head>

<body>

    <header>
        <nav>
            <?php include '../componentes/navbar.php'; ?>
        </nav>
    </header>

    <main>
        <!-- HOME - CAPA -->
        <section id="capa">
            <div class="capa-conteudo">
                <h1> MONTE SEU <br> SKATE DOS <br> SONHOS </h1>
                <p>Peça por peça, visualizando tudo em tempo real</p>
                <a href="#beneficios" class="btn-saiba-mais">SAIBA MAIS</a>
            </div>
        </section>


        <!-- HOME - BENEFICIOS -->
        <section id="beneficios">
            <div class="beneficio-item">
                <img src="../img/imgs-home/home-entrega.png" alt="Entrega Expressa">
                <p>ENTREGA EXPRESSA<br><span>para todo o Brasil</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/home-segura.png" alt="Compra Segura">
                <p>COMPRA SEGURA<br><span>ambiente protegido</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/home-desconto.png" alt="5% Desconto">
                <p>5% DE DESCONTO<br><span>no PIX ou boleto</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/home-troca.png" alt="Troca grátis">
                <p>PRIMEIRA TROCA GRÁTIS<br><span>em até 7 dias</span></p>
            </div>
        </section>


        <!-- HOME - CUSTOMIZAÇAO / MONTAGEM -->
        <section id="customizacao">
            <div class="customizacao-conteudo">
                <div class="texto">
                    <h2>CUSTOMIZAÇÃO EM TEMPO REAL</h2>
                    <p>VISUALIZE SEU SKATE ENQUANTO ESCOLHE<br>AS MELHORES PEÇAS EM 3 PASSOS SIMPLES</p>
                    <a href="../select/select.php" class="btn-customizacao">TESTAR CUSTOMIZAÇÃO</a>
                </div>

                <div class="img-skate">
                    <img src="../img/imgs-home/home-montagem.png" alt="Montagem Skate">
                </div>
            </div>
        </section>


        <!-- HOME - PASSOS -->
        <section class="passos">
            <div class="passo passo1">
                <div class="numero">1</div>
                <img src="../img/imgs-home/home-passo1.png" alt="Passo 1: Escolha o Shape">
                <p>ESCOLHA O SHAPE</p>
            </div>
            <div class="passo passo2">
                <div class="numero">2</div>
                <img src="../img/imgs-home/home-passo2.png" alt="Passo 2: Adicione as Peças">
                <p>ADICIONE AS PEÇAS</p>
            </div>
            <div class="passo passo3">
                <div class="numero">3</div>
                <img src="../img/imgs-home/home-passo3.png" alt="Passo 3: Finalize sua Compra">
                <p>FINALIZE SUA COMPRA</p>
            </div>
        </section>


        <!-- HOME - KITS -->
        <section class="kits">
            <h3>KITS TEMÁTICOS PARA CADA SKATISTA</h3>
            <div class="galeria-kits">

                <?php if (empty($kits)): ?>

                    <p style="color: #333; font-size: 1.1rem; padding: 20px;">
                        Nenhum kit encontrado.
                        <br><small>(Verifique se a categoria "<?php echo htmlspecialchars($categoria_kits); ?>" existe e tem produtos)</small>
                    </p>

               <?php else: ?>
                    
                    <?php foreach ($kits as $kit): ?>
                        <div class="kit">
                            <a href="../produto/produto.php?id=<?php echo $kit['id_pecas']; ?>">
                                <img src="<?php echo htmlspecialchars($kit['url_img']); ?>" alt="<?php echo htmlspecialchars($kit['nome']); ?>">
                            </a>
                            
                            <p class="nome-kit"><?php echo htmlspecialchars($kit['nome']); ?></p>
                            <p class="preco-kit">R$ <?php echo number_format($kit['preco'], 2, ',', '.'); ?></p>
                            <p class="parcelamento-kit">
                                Em até 12x de R$ <?php echo number_format($kit['preco'] / 12, 2, ',', '.'); ?>
                            </p>
                            
                            <a href="../produto/produto.php?id=<?php echo $kit['id_pecas']; ?>" class="botao-ver-mais">Ver Mais</a>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <div class="nov">
                <a href="../skateshop/skateee.php" class="botao-nov">Descubra Mais</a>
            </div>

        </section>


        <!-- HOME - NOVIDADES -->
        <section class="novidades">
            <div class="conteudo-novidades">
                <h2>NOVIDADES E PROMOÇÕES</h2>
                <p>Cadastre-se agora e receba novidades</p>
                <div class="formulario-novidades">
                    <input type="email" placeholder="Digite seu e-mail para receber novidades e promoções">
                    <a href="#" class="botao-cadastrar">CADASTRAR</a>
                </div>
            </div>
        </section>


        <!-- HOME - AVALIAÇÕES -->
        <section class="avaliacoes">
            <h2>AVALIAÇÕES</h2>
            <div class="carrossel-avaliacoes">
                <button class="seta esquerda">&lt;</button>
                <div class="card-avaliacao">
                    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p class="nome-avaliador">Pomni</p>
                </div>
                <div class="card-avaliacao">
                    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p class="nome-avaliador">Neon</p>
                </div>
                <div class="card-avaliacao">
                    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p class="nome-avaliador">Jax</p>
                </div>
                <button class="seta direita">&gt;</button>
            </div>
        </section>

        <script src="home.js"></script>
    </main>

    <footer>
        <?php include '../componentes/footer.php'; ?>
    </footer>
</body>