<!DOCTYPE html>
<html lang="pt - br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../produtos/produtos.css">
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
                        <tr>
                            <td><img src="../imagens/skate1.png" alt="Skate Completo"></td>
                            <td>Skate Completo Pro</td>
                            <td>R$ 299,90</td>
                            <td>15</td>
                            <td>Skate Completo</td>
                            <td><span class="ativo">Ativo</span></td>
                            <td>
                                <div class="acoes">
                                    <button type="button" class="btn-acao"><i class="ri-eye-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-pencil-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-delete-bin-6-line"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="https://res.cloudinary.com/dunatmsn9/image/upload/v1761511565/shape_sonic_xnhnfa.png" alt="Shape Element"></td>
                            <td>Shape Element</td>
                            <td>R$ 89,90</td>
                            <td>32</td>
                            <td>Shape</td>
                            <td><span class="ativo">Ativo</span></td>
                            <td>
                                <div class="acoes">
                                    <button type="button" class="btn-acao"><i class="ri-eye-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-pencil-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-delete-bin-6-line"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="../imagens/truck1.png" alt="Truck"></td>
                            <td>Truck Independent</td>
                            <td>R$ 159,90</td>
                            <td>8</td>
                            <td>Truck</td>
                            <td><span class="ativo">Ativo</span></td>
                            <td>
                                <div class="acoes">
                                    <button type="button" class="btn-acao"><i class="ri-eye-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-pencil-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-delete-bin-6-line"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="../imagens/rodas1.png" alt="Rodas Bones"></td>
                            <td>Rodas Bones</td>
                            <td>R$ 79,90</td>
                            <td>0</td>
                            <td>Roda</td>
                            <td><span class="inativo">Inativo</span></td>
                            <td>
                                <div class="acoes">
                                    <button type="button" class="btn-acao"><i class="ri-eye-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-pencil-line"></i></button>
                                    <button type="button" class="btn-acao"><i class="ri-delete-bin-6-line"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script src="../produtos/produtos.js"></script>
</body>

</html>