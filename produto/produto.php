<?php
session_start();

// 🛹 Pega o ID do produto vindo da URL
$id = $_GET['id'] ?? 1;

// 📦 Lista de produtos (exemplo — você pode adicionar quantos quiser)
$produtos = [
  1 => [
    'nome' => 'Street Art Complete',
    'preco' => 279.90,
    'descricao' => 'Skate completo para iniciantes e intermediários. Shape de maple com rolamentos ABEC-7.',
    'imagem' => '../img/imgs-skateshop/image.png',
  ],
  2 => [
    'nome' => 'Graffiti Deck Pro',
    'preco' => 159.90,
    'descricao' => 'Shape leve e resistente, ideal para manobras técnicas e street style.',
    'imagem' => '../img/imgs-skateshop/skt.webp',
  ],
  3 => [
    'nome' => 'Skate Cruiser Retrô',
    'preco' => 349.90,
    'descricao' => 'Cruiser clássico com rodas macias e shape compacto, perfeito para rolês urbanos.',
    'imagem' => '../img/imgs-skateshop/',
  ],
  4 => [
    'nome' => 'Longboard Profissional Azul',
    'preco' => 459.90,
    'descricao' => 'Longboard de alta performance, ideal para descidas e velocidade.',
    'imagem' => '../img/imgs-skateshop/image.png',
  ],
];

// Se o ID não existir, mostra o primeiro
$produto = $produtos[$id] ?? $produtos[1];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($produto['nome']) ?> - Skate Lab</title>
  <link rel="stylesheet" href="produto.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php include '../componentes/navbar.php'; ?>

  <main class="container">
    <!-- imagens -->
    <section class="galeria">
      <div class="miniimg">
        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="Skate ângulo 1" onclick="mudarImagem('<?= htmlspecialchars($produto['imagem']) ?>')">
        <img src="../img/imgs-skateshop/skt.webp" alt="Skate ângulo 2" onclick="mudarImagem('../img/imgs-skateshop/skt.webp')">
        <img src="../img/imgs-skateshop/image.png" alt="Skate ângulo 3" onclick="mudarImagem('../img/imgs-skateshop/image.png')">
        <img src="../img/imgs-skateshop/banner.jpg" alt="Skate ângulo 4" onclick="mudarImagem('../img/imgs-skateshop/banner.jpg')">
      </div>

      <div class="imgprincipal">
        <img id="imagemPrincipal" src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
      </div>
    </section>

    <!-- informações do produto -->
    <section class="info">
      <div class="estrelas">
        ⭐⭐⭐⭐⭐ <span>(0 avaliações)</span>
      </div>
      <h1><?= htmlspecialchars($produto['nome']) ?></h1>

      <div class="precoproduto">
        <h2>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></h2>
        <p>ou 12x sem juros</p>
      </div>

      <form action="../carrinho/adicionar_carrinho.php" method="post">
        <div class="quantidadecard">
          <p>Quantidade:</p>
          <div class="quantidade">
            <button type="button" class="botaomenos">−</button>
            <span class="num">1</span>
            <button type="button" class="botaomais">+</button>
          </div>
        </div>

        <!-- Dados do produto enviados ao carrinho -->
        <input type="hidden" name="quantidade" id="inputQuantidade" value="1">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <input type="hidden" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>">
        <input type="hidden" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>">
        <input type="hidden" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>">
        <input type="hidden" name="imagem" value="<?= htmlspecialchars($produto['imagem']) ?>">

        <button type="submit" class="botaoadcarrinho">Adicionar ao carrinho</button>
      </form>

      <div class="frete">
        <p>Calcular Frete</p>
        <input type="text" placeholder="00000-000">
        <button class="ok">OK</button>
      </div>
    </section>
  </main>

  <!-- Descrição e avaliações -->
  <section class="descricaogeral">
    <h2>DESCRIÇÃO GERAL</h2>
    <p><?= htmlspecialchars($produto['descricao']) ?></p>

    <div class="skateespecificacoes">
      <h3>Especificações Técnicas</h3>
      <table>
        <tr><td>Shape</td><td>Maple 7 Lâminas</td></tr>
        <tr><td>Tamanho</td><td>8.0" x 31.5"</td></tr>
        <tr><td>Rodas</td><td>52mm 99A Dureza</td></tr>
        <tr><td>Rolamentos</td><td>ABEC-7</td></tr>
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

  <?php include '../componentes/footer.php'; ?>

  <script>
    // Galeria
    function mudarImagem(src) {
      document.getElementById('imagemPrincipal').src = src;
    }

    // Quantidade
    const spanQtd = document.querySelector('.num');
    const inputQtd = document.getElementById('inputQuantidade');
    let quantidade = 1;

    document.querySelector('.botaomais').addEventListener('click', () => {
      quantidade++;
      spanQtd.innerText = quantidade;
      inputQtd.value = quantidade;
    });

    document.querySelector('.botaomenos').addEventListener('click', () => {
      if (quantidade > 1) {
        quantidade--;
        spanQtd.innerText = quantidade;
        inputQtd.value = quantidade;
      }
    });
  </script>
</body>
</html>
