<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';


try {
    $sql = "SELECT p.*, c.nome AS categoria_nome 
            FROM public.pecas p 
            LEFT JOIN public.categorias c ON p.id_cat = c.id_cat 
            WHERE p.status = 'ATIVO'
            ORDER BY p.nome";
    $stmt = $pdo->query($sql);
    $pecas = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Erro ao buscar peças: " . $e->getMessage());
    $pecas = [];
}
?>

<!DOCTYPE html>
<html lang="pt - br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../produtos/produtos.css">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>

<body>
    <?php include __DIR__ . '/../partials/headeradmin.php'; ?>

    <div class="container">
        <div class="topo">
            <div>
                <h1>Produtos</h1>
                <p>Gerencie os produtos do seu e-commerce</p>
            </div>
            <a href="../add_produto/add_prod.php"><button class="btn-novo"><i class="ri-add-line"></i> Novo Produto</button></a>
        </div>


        <section class="area-produtos">
            <div class="tabela-container">
                <div class="barra-pesquisa">
                    <i class="ri-search-line"></i>
                    <input type="text" placeholder="Buscar produtos..." id="buscarProduto">
                </div>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Categoria</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pecas)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">Nenhum produto cadastrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pecas as $peca): ?>
                                <tr>
                                    <td><img src="<?= htmlspecialchars($peca['url_img']) ?>" alt="<?= htmlspecialchars($peca['nome']) ?>"></td>
                                    <td><?= htmlspecialchars($peca['nome']) ?></td>
                                    <td>R$ <?= number_format($peca['preco'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($peca['estoque']) ?></td>
                                    <td><?= htmlspecialchars($peca['categoria_nome'] ?? 'Sem Categoria') ?></td>
                                    <td><span class="<?= strtolower($peca['status']) ?>"><?= htmlspecialchars($peca['status']) ?></span></td>
                                    <td>
                                        <div class="acoes">
                                            <button type="button" class="btn-acao"><i class="ri-<?= $peca['status'] === 'ATIVO' ? 'eye-line' : 'eye-off-line' ?>"></i></button>
                                            
                                            <a href="editar_produto.php?id=<?= $peca['id_pecas'] ?>" class="btn-acao">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            
                                            <a href="excluir_produto.php?id=<?= $peca['id_pecas'] ?>" class="btn-acao" onclick="return confirm('Tem certeza que deseja excluir este produto?');">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script src="../produtos/produtos.js"></script>
</body>

</html>