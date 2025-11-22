<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

$id_cat = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_cat) {
    die("ID da categoria inválido.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM public.categorias WHERE id_cat = :id");
    $stmt->execute([':id' => $id_cat]);
    $categoria = $stmt->fetch();

    if (!$categoria) {
        die("Categoria não encontrada.");
    }
} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="../add_produto/add_prod.css">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>
<body>
    <?php include __DIR__ . '/../partials/headeradmin.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Editar Categoria</h1>
            <p>Modifique os dados da categoria: <?= htmlspecialchars($categoria['nome']) ?></p>
        </div>

        <div class="form-container">
            <form action="atualizar_categoria.php" method="POST" class="form-produto">
                
                <input type="hidden" name="id_cat" value="<?= $categoria['id_cat'] ?>">

                <div class="info-basica">
                    <h2>Dados da Categoria</h2>

                    <label for="nome">Nome da Categoria</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($categoria['nome']) ?>" required>

                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="5" required><?= htmlspecialchars($categoria['descricao']) ?></textarea>
                </div>
                
                <div class="painel-direito">
                    <div class="botoes-form">
                        <button type="submit" class="btn-criar">Salvar Alterações</button>
                        <a href="categoria.php"><button type="button" class="btn-cancelar">Cancelar</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
