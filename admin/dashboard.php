<?php include('../admin/partials/headeradmin.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - E-commerce Skate</title>
  <link rel="stylesheet" href="dash.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
</head>
<body>

<main class="dashboard-container">

  <h1>Dashboard</h1>
  <p class="subtitle">Visão geral do seu e-commerce de skate</p>

  <section class="cards">
    <div class="card">
      <h3>Produtos</h3>
      <p class="value">21</p>
      <span>Total cadastrados</span>
    </div>

    <div class="card">
      <h3>Ativos</h3>
      <p class="value">17</p>
      <span>Produtos visíveis na loja</span>
    </div>

    <div class="card">
      <h3>Estoque baixo</h3>
      <p class="value">5</p>
      <span>Itens com 5 ou menos</span>
    </div>

    <div class="card">
      <h3>Categorias</h3>
      <p class="value">5</p>
      <span>Tipos de produto</span>
    </div>
  </section>

  <section class="recent-products">
    <div class="header-section">
      <h2>Produtos recentes</h2>
      <button class="btn-add">+ Novo Produto</button>
    </div>

    <table class="products-table">
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
          <td><img src="img/produto1.png" alt="Cavalo"></td>
          <td>Cavalo</td>
          <td>R$ 24,00</td>
          <td>1</td>
          <td>Roda</td>
          <td><span class="status ativo">Ativo</span></td>
          <td>
            <button class="btn editar">Editar</button>
            <button class="btn ver">Ver</button>
            <button class="btn excluir">Excluir</button>
          </td>
        </tr>

        <tr>
          <td><img src="img/produto2.png" alt="Rolamento ABEC 7"></td>
          <td>Rolamento ABEC 7</td>
          <td>R$ 45,90</td>
          <td>25</td>
          <td>—</td>
          <td><span class="status ativo">Ativo</span></td>
          <td>
            <button class="btn editar">Editar</button>
            <button class="btn ver">Ver</button>
            <button class="btn excluir">Excluir</button>
          </td>
        </tr>

        <tr>
          <td><img src="img/produto3.png" alt="Rodas Bones"></td>
          <td>Rodas Bones</td>
          <td>R$ 79,90</td>
          <td>0</td>
          <td>—</td>
          <td><span class="status inativo">Inativo</span></td>
          <td>
            <button class="btn editar">Editar</button>
            <button class="btn ver">Ver</button>
            <button class="btn excluir">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</main>

</body>
</html>
