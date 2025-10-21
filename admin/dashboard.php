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
  <?php include '../admin/partials/headeradmin.php'; ?>
  <main class="dashboard-container">

    <h1>Dashboard</h1>
    <p class="subtitle">Visão geral do seu e-commerce de skate</p>

    <!-- <section class="cards">
    <div class="card">
      <div class="icon" style="background:#F39C12;">📦</div>
      <h3>Produtos</h3>
      <p class="value">21</p>
      <span>Total cadastrados</span>
    </div>

    <div class="card">
      <div class="icon" style="background:#2ECC71;">✅</div>
      <h3>Ativos</h3>
      <p class="value">17</p>
      <span>Produtos visíveis na loja</span>
    </div>

    <div class="card">
      <div class="icon" style="background:#E74C3C;">📉</div>
      <h3>Estoque baixo</h3>
      <p class="value">5</p>
      <span>Itens com 5 ou menos</span>
    </div>

    <div class="card">
      <div class="icon" style="background:#9B59B6;">🗂️</div>
      <h3>Categorias</h3>
      <p class="value">5</p>
      <span>Tipos de produto</span>
    </div>
  </section> -->

    <section class="cards">
      <div class="card">
        <div class="card-content">
          <div class="icon">📦</div>
          <div>
            <h3>Produtos</h3>
            <p class="value">21</p>
            <span>Total cadastrados</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-content">
          <div class="icon">✅</div>
          <div>
            <h3>Ativos</h3>
            <p class="value">17</p>
            <span>Produtos visíveis na loja</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-content">
          <div class="icon">📉</div>
          <div>
            <h3>Estoque baixo</h3>
            <p class="value">5</p>
            <span>Itens com 5 ou menos</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-content">
          <div class="icon">🗂️</div>
          <div>
            <h3>Categorias</h3>
            <p class="value">5</p>
            <span>Tipos de produto</span>
          </div>
        </div>
      </div>
    </section>


    <section class="overview-cards">
      <div class="overview-card">
        <div class="icon">💰</div>
        <div>
          <h3>Vendas Totais</h3>
          <p class="value">R$ 45.231</p>
          <span class="growth">+20.1% em relação ao mês passado</span>
        </div>
      </div>

      <div class="overview-card">
        <div class="icon">🛒</div>
        <div>
          <h3>Pedidos</h3>
          <p class="value">152</p>
          <span class="growth">+12.5% em relação ao mês passado</span>
        </div>
      </div>

      <div class="overview-card">
        <div class="icon">📦</div>
        <div>
          <h3>Produtos</h3>
          <p class="value">89</p>
          <span class="growth">5 novos produtos este mês</span>
        </div>
      </div>

      <div class="overview-card">
        <div class="icon">👥</div>
        <div>
          <h3>Usuários</h3>
          <p class="value">1.245</p>
          <span class="growth">+180 novos usuários</span>
        </div>
      </div>
    </section>

    <div class="dashboard-columns">
      <section class="recent-orders">
        <h2>Pedidos Recentes</h2>
        <ul class="order-list">
          <li>
            <div>
              <strong>João Silva</strong><br>
              <span>Skate Completo Pro</span>
            </div>
            <div class="order-value">R$ 299,90</div>
            <span class="status entregue">Entregue</span>
          </li>
          <li>
            <div>
              <strong>Maria Santos</strong><br>
              <span>Shape Element</span>
            </div>
            <div class="order-value">R$ 89,90</div>
            <span class="status preparando">Em preparo</span>
          </li>
          <li>
            <div>
              <strong>Pedro Costa</strong><br>
              <span>Truck Independent</span>
            </div>
            <div class="order-value">R$ 159,90</div>
            <span class="status enviado">Enviado</span>
          </li>
          <li>
            <div>
              <strong>Ana Oliveira</strong><br>
              <span>Rodas Bones</span>
            </div>
            <div class="order-value">R$ 79,90</div>
            <span class="status pendente">Pendente</span>
          </li>
        </ul>
      </section>

      <section class="best-products">
        <h2>Produtos Mais Vendidos</h2>
        <ul class="product-list">
          <li>
            <div>
              <strong>Skate Completo Pro</strong><br>
              <span>45 vendas</span>
            </div>
            <div class="product-value">R$ 13.495,50</div>
            <span class="growth positive">+45%</span>
          </li>
          <li>
            <div>
              <strong>Shape Element</strong><br>
              <span>32 vendas</span>
            </div>
            <div class="product-value">R$ 2.876,80</div>
            <span class="growth positive">+32%</span>
          </li>
          <li>
            <div>
              <strong>Truck Independent</strong><br>
              <span>28 vendas</span>
            </div>
            <div class="product-value">R$ 4.477,20</div>
            <span class="growth positive">+28%</span>
          </li>
          <li>
            <div>
              <strong>Rodas Bones</strong><br>
              <span>25 vendas</span>
            </div>
            <div class="product-value">R$ 1.997,50</div>
            <span class="growth positive">+25%</span>
          </li>
        </ul>
      </section>
    </div>






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