<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>produto</title>
  <link rel="stylesheet" href="produto.css">
  <script src="../produto/produto.js"></script>
</head>
<body>

    <?php include '../componentes/navbar.php'; ?>
  <main class="container">
    
    <!-- imagens -->
    <section class="galeria">
      <div class="miniimg">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 1" onclick="mudarImagem('img1.jpg')">
        <img src="../img/imgs-skateshop/skt.webp" alt="Skate ângulo 2" onclick="mudarImagem('img2.jpg')">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 3" onclick="mudarImagem('img3.jpg')">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 4" onclick="mudarImagem('img4.jpg')">
      </div>

      <div class="imgprincipal">
        <img id="imagemPrincipal" src="../img/imgs-skateshop/image.png" alt="Skate completo colorido">
      </div>
    </section>

    <!-- informações do produto -->
    <section class="info">
      <div class="estrelas">
        ⭐⭐⭐⭐⭐ <span>(2 avaliações)</span>
      </div>
      <h1>Shape Street Art Pro</h1>
      <div class="precoproduto">
        <h2>R$ 900,00</h2>
        <p>ou 12x de R$ 75,00 sem juros</p>
      </div>

      <div class="quantidadecard">
        <p>Quantidade:</p>
        <div class="quantidade">
          <button class="botaomenos">−</button>
          <span class="num">1</span>
          <button class="botaomais">+</button>
        </div>
      </div>
      <a href="../pagamento/pagamento.php" class="botaocomprar1">comprar</a> <br>
      <a href="pagamento.html" class="botaoadcarrinho">adicionar ao carrinho</a>

      <div class="frete">
        <p>Calcular Frete</p>
        <input type="text" placeholder="00000-000">
        <button class="ok">OK</button>
      </div>
    </section>
  </main>

  <!-- descrição e avaliações -->
  <section class="descricaogeral">
    <h2>DESCRIÇÃO GERAL</h2>
    <p>
      O Skate Completo Street Pro é perfeito para skatistas que buscam performance e estilo nas ruas.
      Desenvolvido com materiais de alta qualidade, oferece máxima durabilidade e controle em manobras
      técnicas. Ideal tanto para iniciantes quanto para praticantes avançados.
    </p>
    <p>
      Com design moderno e gráficos vibrantes, este skate não é apenas um equipamento esportivo,
      mas uma verdadeira obra de arte urbana que reflete a cultura do skate contemporâneo.
    </p>

    <div class="skateespecificacoes">
      <h3>Especificações Técnicas</h3>
      <table>
        <tr>
          <td>Shape</td>
          <td>Maple 7 Lâminas</td>
        </tr>
        <tr>
          <td>Tamanho</td>
          <td>8.0" x 31.5"</td>
        </tr>
        <tr>
          <td>Trucks</td>
          <td>Liga de Alumínio 5.25"</td>
        </tr>
        <tr>
          <td>Rodas</td>
          <td>52mm 99A Dureza</td>
        </tr>
        <tr>
          <td>Rolamentos</td>
          <td>ABEC-9 Alta Velocidade</td>
        </tr>
        <tr>
          <td>Peso</td>
          <td>2.8kg</td>
        </tr>
      </table>
    </div>
  </section>

  <section class="avaliacoes">
    <h2>AVALIAÇÕES</h2>
    <div class="avaliacoescard">
      <i class="fa-regular fa-comment"></i>
      <p><strong>Nenhuma avaliação disponível</strong></p>
      <span>Seja o primeiro a avaliar este produto!</span>
    </div>
  </section>




  <h2 class="desta"> outros produtos em destaque </h2>
    <section class="produtos">
      <div class="containershop">
        <a href="../../Skate-Lab/produto/produto.php" class="produto-card-link">  
        <div class="card" data-categoria="completos">
          <div class="selos">
            <span class="novo">Novo</span>
            <span class="oferta">Oferta</span>
          </div>
          <img src="../img/imgs-skateshop/image.png" alt="">
          <div class="info">
            <span class="categoria">SHAPES</span>
            <h3>Shape Street Art Pro</h3>
            <div class="rating">⭐⭐⭐⭐⭐<span>(5.0)</span></div>
            <p class="preco">R$ 900.00 <span class="antigo">R$ 780.00</span></p>
          </a>
            <button class="botaocomprar" onclick="window.location.href='../pagamento/pagamento.php'"> comprar </button>
            <button class="botaocarrinho"><i class="fa-solid fa-cart-shopping"></i></button>
          </div>
        </div>

        <!-- Produto 2 -->
        <div class="card" data-categoria="completos">
          <div class="selos">
            <span class="novo">Novo</span>
          </div>
          <img src="../img/imgs-skateshop/image.png" alt="Street Art Complete">
          <div class="info">
            <span class="categoria">COMPLETOS</span>
            <h3>Street Art Complete</h3>
            <div class="rating">⭐⭐⭐⭐ <span>(4.0)</span></div>
            <p class="preco">R$ 279.90</p>
            <button class="botaocomprar">comprar</button> <button class="botaocarrinho"><i class="fa-solid fa-cart-shopping"></i></button>
          </div>
        </div>

        <!-- Produto 3 -->
        <div class="card" data-categoria="shapes">
          <img src="../img/imgs-skateshop/image.png" alt="Graffiti Deck Pro">
          <div class="info">
            <span class="categoria">SHAPES</span>
            <h3>Graffiti Deck Pro</h3>
            <div class="rating">⭐⭐⭐⭐⭐ <span>(5.0)</span></div>
            <p class="preco">R$ 159.90</p>
            <button class="botaocomprar">comprar</button> <button class="botaocarrinho"><i class="fa-solid fa-cart-shopping"></i></button>
          </div>
        </div>

        <!-- Produto 4 -->
        <div class="card" data-categoria="rodas">
          <div class="selos">
            <span class="oferta">Oferta</span>
          </div>
          <img src="../img/imgs-skateshop/image.png" alt="Pro Wheels Orange">
          <div class="info">
            <span class="categoria">RODAS</span>
            <h3>Pro Wheels Orange</h3>
            <div class="rating">⭐⭐⭐⭐ <span>(4.0)</span></div>
            <p class="preco">
              R$ 89.90 <span class="antigo">R$ 109.90</span>
            </p>
            <button class="botaocomprar">comprar</button> <button class="botaocarrinho"><i class="fa-solid fa-cart-shopping"></i></button>
          </div>
        </div>
      </div>
    </section>

    <?php include '../componentes/footer.php'; ?>
</body>
</html>