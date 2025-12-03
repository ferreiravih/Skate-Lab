<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';


$id_pecas = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_pecas) {
    die("ID do produto inválido.");
}


try {
    $stmt_peca = $pdo->prepare("SELECT * FROM public.pecas WHERE id_pecas = :id");
    $stmt_peca->execute([':id' => $id_pecas]);
    $peca = $stmt_peca->fetch();

    if (!$peca) {
        die("Produto não encontrado.");
    }


    $stmt_cat = $pdo->query("SELECT id_cat, nome FROM public.categorias WHERE nome <> 'Arquivados' AND nome <> 'Custom' ORDER BY nome");
    $categorias = $stmt_cat->fetchAll();

} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Produto</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
  <link rel="stylesheet" href="../add_produto/add_prod.css">
  <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>
<body>
    <?php include __DIR__ . '/../partials/headeradmin.php'; ?>

  <div class="container">
    <div class="page-header">
      <h1>Editar Produto</h1>
      <p>Atualize as informações do produto: <?= htmlspecialchars($peca['nome']) ?></p>
    </div>

    <div class="form-container">
      <form action="atualizar_produto.php" method="POST" class="form-produto">
        
        <input type="hidden" name="id_pecas" value="<?= $peca['id_pecas'] ?>">

        <div class="info-basica">
          <h2>Informações Básicas</h2>

          <label for="nome">Nome do Produto</label>
          <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($peca['nome']) ?>" required>

          <label for="imagem">URL da Imagem</label>
          <input type="text" id="imagem" name="url_img" value="<?= htmlspecialchars($peca['url_img']) ?>" required>

          <div class="linha">
            <div>
              <label for="preco">Preço (R$)</label>
              <input type="number" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($peca['preco']) ?>" required>
            </div>
            <div>
              <label for="estoque">Estoque</label>
              <input type="number" id="estoque" name="estoque" value="<?= htmlspecialchars($peca['estoque']) ?>" required>
            </div>
          </div>

          <label for="descricao_curta">Descrição Curta</label>
          <input type="text" id="descricao_curta" name="desc_curta" value="<?= htmlspecialchars($peca['desc_curta']) ?>" required>

          <label for="descricao_completa">Descrição Completa</label>
          <textarea id="descricao_completa" name="dsc_longa" rows="5"><?= htmlspecialchars($peca['dsc_longa']) ?></textarea>
        </div>

        <div class="painel-direito">
          <div class="categoria-status">
            <h2>Categoria e Status</h2>

            <label for="categoria">Categoria</label>
            <select id="categoria" name="id_cat" required>
              <option value="">Selecione uma categoria</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_cat'] ?>" <?= ($cat['id_cat'] == $peca['id_cat']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <div class="status">
              <span>Produto Ativo</span>
              <label class="switch">
                <input type="checkbox" name="status" value="ATIVO" <?= ($peca['status'] === 'ATIVO') ? 'checked' : '' ?>>
                <span class="slider"></span>
              </label>
            </div>
          </div>

          <div class="botoes-form">
            <button type="submit" class="btn-criar">Salvar Alterações</button>
            <a href="produtos.php"><button type="button" class="btn-cancelar">Cancelar</button></a>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>