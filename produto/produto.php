<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Skate Completo Street Pro</title>
  <link rel="stylesheet" href="produto.css">
</head>
<body>
      <?php include '../componentes/navbar.php'; ?>
  <main class="container">
    <!-- Galeria de imagens -->
    <section class="galeria">
      <div class="miniaturas">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 1" onclick="mudarImagem('img1.jpg')">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 2" onclick="mudarImagem('img2.jpg')">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 3" onclick="mudarImagem('img3.jpg')">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 4" onclick="mudarImagem('img4.jpg')">
      </div>

      <div class="imagem-principal">
        <img id="imagemPrincipal" src="../img/imgs-skateshop/image.png" alt="Skate completo colorido">
      </div>
    </section>

    <!-- Informações do produto -->
    <section class="info">
      <h1>Skate Completo Street Pro</h1>
      <p class="fornecedor">Fornecedor: <span>BoardMasters</span></p>

      <div class="avaliacoes">
        ⭐⭐⭐⭐⭐ <span>(0 avaliações)</span>
      </div>

      <div class="preco">
        <h2>R$ 599,90</h2>
        <p>ou 12x de R$ 49,99 sem juros</p>
      </div>

      <div class="quantidade">
        <p>Quantidade:</p>
        <button onclick="diminuir()">−</button>
        <input type="number" id="qtd" value="1" min="1">
        <button onclick="aumentar()">+</button>
      </div>

      <button class="btn-comprar">COMPRAR</button>

      <div class="frete">
        <p>Calcular Frete</p>
        <input type="text" placeholder="00000-000">
        <button class="ok">OK</button>
      </div>
    </section>
  </main>

  <!-- Descrição e avaliações -->
<section class="descricao-geral">
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

  <div class="especificacoes">
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
  <div class="avaliacoes-box">
    <i class="fa-regular fa-comment"></i>
    <p><strong>Nenhuma avaliação disponível</strong></p>
    <span>Seja o primeiro a avaliar este produto!</span>
  </div>
</section>

  <script src="produto.js"></script>
</body>
</html>
