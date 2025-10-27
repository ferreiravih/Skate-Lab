<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos Skate Lab</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
  <link rel="stylesheet" href="../skateshop/skateee.css">
  <script>
    // Filtro dinâmico por categoria
    document.addEventListener('DOMContentLoaded', () => {
      const buttons = document.querySelectorAll('.categories button');
      const cards = document.querySelectorAll('.card');

      buttons.forEach(button => {
        button.addEventListener('click', () => {
          buttons.forEach(btn => btn.classList.remove('active'));
          button.classList.add('active');

          const filtro = button.textContent.toUpperCase();

          cards.forEach(card => {
            const categoria = card.querySelector('.categoria')?.textContent.toUpperCase();
            if (filtro === 'TODOS' || categoria === filtro) {
              card.style.display = 'block';
            } else {
              card.style.display = 'none';
            }
          });
        });
      });
    });
  </script>
</head>

<body>
  <?php include '../componentes/navbar.php'; ?>

  <section class="imgebarra">
    <div class="texto">
      <h1>Sua próxima manobra começa aqui!</h1>
      <p>Skates completos, shapes exclusivos e acessórios que fazem a diferença.</p>
    </div>
  </section>

  <nav class="categories">
    <button class="active">Todos</button>
    <button>Completos</button>
    <button>Shapes</button>
    <button>Rodas</button>
    <button>Trucks</button>
  </nav>

  <h2 class="desta">produtos em destaque</h2>
  <p class="parag">descubra nossos produtos mais vendidos</p>

  <section class="produtos">
    <div class="container">
      <!-- Repita o card abaixo para cada produto com a class="card" -->


      <a href="../produto/produto.php?id=1" class="card-link">  
        <div class="card">
          <div class="selos">
            <span class="novo">Novo</span>
          </div>
          <img src="../img/imgs-skateshop/image.png" alt="Street Art Complete">
          <div class="info">
            <span class="categoria">COMPLETOS</span>
            <h3>Street Art Complete</h3>
            <div class="rating">⭐⭐⭐⭐ <span>(4.0)</span></div>
            <p class="preco">R$ 279.90</p>
            <button class="botaocomprar">comprar</button>
            <button class="botaocarrinho">🛒</button>
          </div>
        </div>
      </a>

    <a href="../produto/produto.php?id=2" class="card-link">
      <div class="card">
        <img src="../img/imgs-skateshop/image.png" alt="Graffiti Deck Pro">
        <div class="info">
          <span class="categoria">SHAPES</span>
          <h3>Graffiti Deck Pro</h3>
          <div class="rating">⭐⭐⭐⭐⭐ <span>(5.0)</span></div>
          <p class="preco">R$ 159.90</p>
          <button class="botaocomprar">comprar</button>
          <button class="botaocarrinho">🛒</button>
        </div>
      </div>
    </a>

      <!-- Adicione outros produtos com categorias diferentes -->
    </div>
  </section>

  <?php include '../componentes/footer.php'; ?>
</body>

</html>
