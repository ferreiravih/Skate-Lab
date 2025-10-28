<!DOCTYPE html>
<html lang="pt - br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="categoria.css">
    <title>Document</title>
</head>

<body>
    <?php include __DIR__ . '/../partials/headeradmin.php'; ?>


    <main class="conteudo">
        <div class="topo-pagina">
            <div>
                <h1>Categorias</h1>
                <p>Organize os produtos em categorias</p>
            </div>
            <button class="btn-nova-categoria">+ Nova Categoria</button>
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
                        <th>Produtos</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Completos</strong></td>
                        <td>Skates completos prontos para uso</td>
                        <td><span class="qtd-itens">48 itens</span></td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="coluna-acoes">
                            <button type="button" class="botao-acao"><i class="ri-eye-line"></i></button>
                            <button type="button" class="botao-acao editar"><i class="ri-pencil-line"></i></button>
                            <button type="button" class="botao-acao deletar"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Shapes</strong></td>
                        <td>Shapes para montagem personalizada</td>
                        <td><span class="qtd-itens">72 itens</span></td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="coluna-acoes">
                            <button type="button" class="botao-acao"><i class="ri-eye-line"></i></button>
                            <button type="button" class="botao-acao editar"><i class="ri-pencil-line"></i></button>
                            <button type="button" class="botao-acao deletar"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Rodas</strong></td>
                        <td>Rodas de diversos tamanhos e durezas</td>
                        <td><span class="qtd-itens">54 itens</span></td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="coluna-acoes">
                            <button type="button" class="botao-acao"><i class="ri-eye-line"></i></button>
                            <button type="button" class="botao-acao editar"><i class="ri-pencil-line"></i></button>
                            <button type="button" class="botao-acao deletar"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Trucks</strong></td>
                        <td>Eixos para skate</td>
                        <td><span class="qtd-itens">36 itens</span></td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="coluna-acoes">
                            <button type="button" class="botao-acao"><i class="ri-eye-line"></i></button>
                            <button type="button" class="botao-acao editar"><i class="ri-pencil-line"></i></button>
                            <button type="button" class="botao-acao deletar"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Acessórios</strong></td>
                        <td>Lixas, parafusos e outros acessórios</td>
                        <td><span class="qtd-itens">38 itens</span></td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="coluna-acoes">
                            <button type="button" class="botao-acao"><i class="ri-eye-line"></i></button>
                            <button type="button" class="botao-acao editar"><i class="ri-pencil-line"></i></button>
                            <button type="button" class="botao-acao deletar"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <div class="overlay" id="overlayCategoria">
            <div class="modal-categoria">
                <h2>Nova Categoria</h2>
                <label for="nomeCategoria">Nome da Categoria</label>
                <input type="text" id="nomeCategoria" placeholder="Ex: Shapes" require>
                <label for="descricaoCategoria">Descrição</label>
                <textarea id="descricaoCategoria" rows="4" placeholder="Descrição da categoria..."></textarea>
                <div class="botoes-modal">
                    <button class="btn-cancelar" id="cancelarModal">Cancelar</button>
                    <button class="btn-salvar">Salvar</button>
                </div>
            </div>
        </div>
        <div class="popup-sucesso" id="popupSucesso">
            <p>Categoria salva com sucesso!</p>
        </div>
    </main>
    <script src="categoria.js"></script>
</body>

</html>