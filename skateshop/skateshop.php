<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="../skateshop/skateshop.css">
</head>
<body>
     <?php include '../componentes/navbar.php'; ?>
     <section class="imgebarra">
  <div class="container">
    <form class="barradepesquisa">
      <input type="text" placeholder="Buscar..." /> 
      <button type="submit">🔍</button>
    </form>
  </div>
</section>
<nav class="categoria" aria-label="Categorias">
    <a href="#" class="cate is-active">Todos</a>
    <a href="#" class="cate">Completos</a>
    <a href="#" class="cate">Shapes</a>
    <a href="#" class="cate">Rodas</a>
    <a href="#" class="cate">Trucks</a>
    <a href="#" class="cate">Acessórios</a>
</nav>
<section class="container2">
  <article class="cardproduto">
    <div class="simbolooferta">Oferta</div>
    <div class="skateimagem">
      <img src="placeholder.jpg" alt="Shape Street Pro">
    </div>
    <div class="skateinfo">
      <span class="tag">Shape</span>
      <h3>Shape Street Pro</h3>
      <div class="nota">⭐ 4.8</div>
      <div class="preco">
        <span>R$ 189,90</span>
      </div>
    </div>
  </article>

  <article class="cardproduto">
    <div class="simbolooferta">Novo</div>
    <div class="skateimagem">
      <img src="placeholder.jpg" alt="Skate Completo Classic">
    </div>
    <div class="skateinfo">
      <span class="tag">Completo</span>
      <h3>Skate Completo Classic</h3>
      <div class="nota">⭐ 4.9</div>
      <div class="preco">
        <span class="atual">R$ 450,00</span>
      </div>
    </div>
  </article>

    <article class="cardproduto">
    <div class="simbolooferta">Novo</div>
    <div class="skateimagem">
      <img src="placeholder.jpg" alt="Skate Completo Classic">
    </div>
    <div class="skateinfo">
      <span class="tag">Completo</span>
      <h3>Skate Completo Classic</h3>
      <div class="nota">⭐ 4.9</div>
      <div class="preco">
        <span class="atual">R$ 450,00</span>
      </div>
    </div>
  </article>
</section>


    

</body>
</html>