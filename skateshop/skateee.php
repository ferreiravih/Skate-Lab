<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkateShop</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../skateshop/skateee.css">
</head>

<body>
  <?php include '../componentes/navbar.php'; ?>

  <section class="imgebarra">
    <div class="texto">
      <h1>Sua próxima manobra começa aqui!</h1> <br>
      <p>Skates completos, shapes exclusivos e acessórios que fazem a diferença.</p> <br>
    </div>
  </section>

<nav class="categories">
  <button id="filtro-todos" class="active">Todos</button>
  <button id="filtro-completos">Completos</button>
  <button id="filtro-shapes">Shapes</button>
  <button id="filtro-rodas">Rodas</button>
  <button id="filtro-trucks">Trucks</button>
  <button id="filtro-acessorios">Acessórios</button>
</nav>



  <div class="hhh">
    <div class="letras">
      <h2 class="desta"> produtos em destaque </h2>
      <p class="parag"> descubra nossos produtos mais vendidos</p>
    </div>
    <!-- BARRA DE PESQUISA ADICIONADA -->
    <div class="search-container">
      <div class="search-bar">
        <input type="text" placeholder="Pesquisar produtos...">
        <button type="submit" class="search-button">
          <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        </button>
      </div>
    </div>
  </div>

  <main class="content">
    <section class="produtos">
      <div class="containershop">
        <a href="../../Skate-Lab/produto/produto.php" class="produto-card-link">  
        <div class="card" data-categoria="shapes">
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

    <section class="produtos" data-categoria="completos">
      <a href="../../Skate-Lab/produto/produto.php" class="produto-card-link">
        <div class="containershop">
          <div class="card">
            <div class="selos">
              <span class="novo">Novo</span>
              <span class="oferta">Oferta</span>
            </div>
            <img src="../img/imgs-skateshop/image.png" alt="Urban Purple Complete">
            <div class="info">
              <span class="categoria">COMPLETOS</span>
              <h3>Shape Street Art Pro</h3>
              <div class="rating">⭐⭐⭐⭐⭐ <span>(5.0)</span></div>
              <p class="preco">
                R$ 299.90 <span class="antigo">R$ 900.00</span>
              </p>
              <button class="botaocomprar">comprar</button> <button class="botaocarrinho"><i class="fa-solid fa-cart-shopping"></i></button>
            </div>
          </div>
      </a>

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
  </main>

  <?php include '../componentes/footer.php'; ?>
  <script src="../../Skate-Lab/skateshop/skateshop.js"></script>
</body>

</html>