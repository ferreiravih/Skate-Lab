<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

// BUSCAR CATEGORIAS (READ)
try {
    $stmt = $pdo->query("SELECT * FROM public.categorias ORDER BY nome");
    $categorias = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Erro ao buscar categorias: " . $e->getMessage());
    $categorias = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="categoria.css">
    <title>Categorias - SkateLab</title>
</head>
    <body>
        <?php include __DIR__ . '/../partials/headeradmin.php'; ?>

        <main class="conteudo">
            <div class="topo-pagina">
                <div>
                    <h1>Categorias</h1>
                    <p>Organize os produtos em categorias</p>
                </div>
                <button class="btn-nova-categoria" id="btn-abrir-modal">+ Nova Categoria</button>
            </div>

            <section class="area-categorias">
                <div class="campo-busca">
                    <i class="ri-search-line"></i>
                    <input type="text" placeholder="Buscar categorias..." class="input-busca">
                </div>

                <table class="tabela-categorias">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categorias)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center;">Nenhuma categoria cadastrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categorias as $cat): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($cat['nome']) ?></strong></td>
                                    <td><?= htmlspecialchars($cat['descrição']) ?></td>
                                    <td class="coluna-acoes">
                                        <a href="editar_categoria.php?id=<?= $cat['id_cat'] ?>" class="botao-acao editar">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <a href="excluir_categoria.php?id=<?= $cat['id_cat'] ?>" class="botao-acao deletar" onclick="return confirm('Atenção: Excluir uma categoria pode falhar se ela já estiver sendo usada por produtos. Deseja continuar?');">
                                            <i class="ri-delete-bin-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
            
            <div class="overlay" id="overlayCategoria">
                <div class="modal-categoria">
                    <form id="form-nova-categoria">
                        <h2>Nova Categoria</h2>
                        <label for="nomeCategoria">Nome da Categoria</label>
                        <input type="text" id="nomeCategoria" name="nome" placeholder="Ex: Shapes" required>
                        
                        <label for="descricaoCategoria">Descrição</label>
                        <textarea id="descricaoCategoria" name="descricao" rows="4" placeholder="Descrição da categoria..." required></textarea>
                        
                        <div class="botoes-modal">
                            <button type="button" class="btn-cancelar" id="cancelarModal">Cancelar</button>
                            <button type="button" class="btn-salvar" id="btn-salvar-categoria">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
            
            </main>
        <script src="categoria.js"></script>
    </body>
</html>