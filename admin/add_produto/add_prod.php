<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

// 3. BUSCAR AS CATEGORIAS NO BANCO (Precisa ter categorias cadastradas)
try {
    $stmt_cat = $pdo->query("SELECT id_cat, nome FROM categorias ORDER BY nome");
    $categorias = $stmt_cat->fetchAll();
} catch (PDOException $e) {
    // Se falhar, registra o erro e continua com um array vazio
    error_log("Erro ao buscar categorias: " . $e->getMessage());
    $categorias = []; // Isso evita que a página quebre
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Novo Produto</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
  <link rel="stylesheet" href="add_prod.css">
  <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>
<body>
    <?php include __DIR__ . '/../partials/headeradmin.php'; ?>

  <div class="container">
    <div class="page-header">
      <h1>Novo Produto</h1>
      <p>Adicione um novo produto ao seu catálogo</p>
    </div>

    <div class="form-container">
      <form action="criar_produto.php" id="form-add-produto" method="POST" class="form-produto">
        
      <!-- LADO ESQUERDO -->
        <div class="info-basica">
          <h2>Informações Básicas</h2>

          <label for="nome">Nome do Produto</label>
          <input type="text" id="nome" name="nome" placeholder="Ex: Shape Pro Model" required>

          <label for="imagem">URL da Imagem</label>
          <input type="text" id="imagem" name="url_img" placeholder="https://exemplo.com/imagem.jpg" required>

          <div class="linha">
            <div>
              <label for="preco">Preço (R$)</label>
              <input type="number" id="preco" name="preco" step="0.01" placeholder="199.99" required>
            </div>
            <div>
              <label for="estoque">Estoque</label>
              <input type="number" id="estoque" name="estoque" placeholder="10" required>
            </div>
          </div>

          <label for="descricao_curta">Descrição Curta</label>
          <input type="text" id="descricao_curta" name="desc_curta" placeholder="Descrição resumida do produto" required>

          <label for="descricao_completa">Descrição Completa</label>
          <textarea id="descricao_completa" name="dsc_longa" placeholder="Descrição detalhada do produto..." rows="5"></textarea>
        </div>

        <!-- LADO DIREITO -->
        <div class="painel-direito">

          <div class="categoria-status">
            <h2>Categoria e Status</h2>

            <label for="categoria">Categoria</label>
            <select id="categoria" name="id_cat" required>
              <option value="">Selecione uma categoria</option>
              
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat['id_cat']) ?>">
                  <?= htmlspecialchars($cat['nome']) ?>
                </option>
              <?php endforeach; ?>

            </select>

            <div class="status">
              <span>Produto Ativo</span>
              <label class="switch">
                <input type="checkbox" name="status" value="ATIVO" checked>
                <span class="slider"></span>
              </label>
            </div>
          </div>

          <div class="botoes-form">
            <button type="submit" id="btn-submit-form" class="btn-criar">Criar Produto</button>
            <a href="../produtos/produtos.php"><button type="button" class="btn-cancelar">Cancelar</button></a>
          </div>

        </div>

      </form>
    </div>
  </div>
<script src="add.js"></script>
</body>
</html>
