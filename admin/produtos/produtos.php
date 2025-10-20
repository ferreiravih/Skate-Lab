
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="../produtos/produtos.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/headeradmin.php'; ?>

    <main class="container">
        <section class="header-section">
            <div>
                <h1>Produtos</h1>
                <p>Gerencie os produtos do seu e-commerce</p>
            </div>
            <button class="btn-novo-produto">+ Novo Produto</button>
        </section>

        <section class="tabela-produtos">
            <table>
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
                        <td><img src="../img/skate.jpg" alt="Skate Completo Pro"></td>
                        <td>Skate Completo Pro</td>
                        <td>R$ 299,90</td>
                        <td>15</td>
                        <td>Skate Completo</td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="acoes">
                            <button class="btn-editar"><i class="ri-edit-line"></i></button>
                            <button class="btn-ver"><i class="ri-eye-line"></i></button>
                            <button class="btn-excluir"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td><img src="../img/shape.jpg" alt="Shape Element"></td>
                        <td>Shape Element</td>
                        <td>R$ 89,90</td>
                        <td>32</td>
                        <td>Shape</td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="acoes">
                            <button class="btn-editar"><i class="ri-edit-line"></i></button>
                            <button class="btn-ver"><i class="ri-eye-line"></i></button>
                            <button class="btn-excluir"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td><img src="../img/truck.jpg" alt="Truck Independent"></td>
                        <td>Truck Independent</td>
                        <td>R$ 159,90</td>
                        <td class="estoque-baixo">8</td>
                        <td>Truck</td>
                        <td><span class="status ativo">Ativo</span></td>
                        <td class="acoes">
                            <button class="btn-editar"><i class="ri-edit-line"></i></button>
                            <button class="btn-ver"><i class="ri-eye-line"></i></button>
                            <button class="btn-excluir"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td><img src="../img/rodas.jpg" alt="Rodas Bones"></td>
                        <td>Rodas Bones</td>
                        <td>R$ 79,90</td>
                        <td class="estoque-zero">0</td>
                        <td>Roda</td>
                        <td><span class="status inativo">Inativo</span></td>
                        <td class="acoes">
                            <button class="btn-editar"><i class="ri-edit-line"></i></button>
                            <button class="btn-ver"><i class="ri-eye-line"></i></button>
                            <button class="btn-excluir"><i class="ri-delete-bin-line"></i></button>
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"></script>
</body>
</html>
