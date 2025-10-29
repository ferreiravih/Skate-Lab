<?php
// 1. Autenticação e Conexão com o BD
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

// 2. Buscar Dados para os Cards e Visão Geral
try {
    // Cards Superiores (Produtos e Categorias)
    $stmt_total_prod = $pdo->query("SELECT COUNT(*) FROM public.pecas");
    $total_produtos = $stmt_total_prod->fetchColumn();

    $stmt_ativos_prod = $pdo->query("SELECT COUNT(*) FROM public.pecas WHERE status = 'ATIVO'");
    $produtos_ativos = $stmt_ativos_prod->fetchColumn();

    $stmt_baixo_estoque = $pdo->query("SELECT COUNT(*) FROM public.pecas WHERE estoque <= 5 AND status = 'ATIVO'");
    $baixo_estoque = $stmt_baixo_estoque->fetchColumn();

    $stmt_categorias = $pdo->query("SELECT COUNT(*) FROM public.categorias");
    $total_categorias = $stmt_categorias->fetchColumn();

    // Cards de Visão Geral (Vendas, Pedidos, Usuários)
    // Considera pedidos pagos (não pendentes ou cancelados) para o total de vendas
    $stmt_vendas_totais = $pdo->query("SELECT SUM(valor_total) FROM public.pedidos WHERE status NOT IN ('PENDENTE', 'CANCELADO')");
    $vendas_totais = $stmt_vendas_totais->fetchColumn() ?? 0; // Usa ?? 0 se não houver vendas

    $stmt_total_pedidos = $pdo->query("SELECT COUNT(*) FROM public.pedidos");
    $total_pedidos = $stmt_total_pedidos->fetchColumn();

    // Contando apenas usuários 'comum'
    $stmt_total_usuarios = $pdo->query("SELECT COUNT(*) FROM public.usuario WHERE tipo = 'comum'");
    $total_usuarios = $stmt_total_usuarios->fetchColumn();

    // Pedidos Recentes (LIMIT 5)
    $stmt_recentes = $pdo->query("
        SELECT p.id_pedido, p.valor_total, p.status, u.nome AS cliente_nome
        FROM public.pedidos p
        JOIN public.usuario u ON p.id_usu = u.id_usu
        ORDER BY p.pedido_em DESC
        LIMIT 5
    ");
    $pedidos_recentes = $stmt_recentes->fetchAll();

    // Produtos Mais Vendidos (LIMIT 4)
    // Soma a quantidade vendida de cada peça em pedidos não cancelados/pendentes
    $stmt_mais_vendidos = $pdo->query("
        SELECT p.nome AS produto_nome, SUM(pi.quantidade) AS total_vendido, SUM(pi.quantidade * pi.preco_unitario) AS valor_vendido
        FROM public.pedido_itens pi
        JOIN public.pecas p ON pi.id_pecas = p.id_pecas
        JOIN public.pedidos ped ON pi.id_pedido = ped.id_pedido
        WHERE ped.status NOT IN ('PENDENTE', 'CANCELADO')
        GROUP BY p.id_pecas, p.nome
        ORDER BY total_vendido DESC
        LIMIT 4
    ");
    $mais_vendidos = $stmt_mais_vendidos->fetchAll();

    // Produtos Recentes na Tabela Inferior (LIMIT 3, como no seu HTML original)
    $stmt_prod_recentes_tabela = $pdo->query("
        SELECT p.*, c.nome AS categoria_nome
        FROM public.pecas p
        LEFT JOIN public.categorias c ON p.id_cat = c.id_cat
        ORDER BY p.criado_em DESC
        LIMIT 3
    ");
    $produtos_recentes_tabela = $stmt_prod_recentes_tabela->fetchAll();


} catch (PDOException $e) {
    // Em caso de erro, define valores padrão para evitar que a página quebre
    error_log("Erro no Dashboard: " . $e->getMessage());
    $total_produtos = $produtos_ativos = $baixo_estoque = $total_categorias = 0;
    $vendas_totais = $total_pedidos = $total_usuarios = 0;
    $pedidos_recentes = $mais_vendidos = $produtos_recentes_tabela = [];
    $erro_db = "Erro ao carregar dados do dashboard.";
}

// Função auxiliar para classes CSS de status do pedido
function getStatusClass($status) {
    switch (strtoupper($status)) {
        case 'CONCLUIDO': return 'concluido';
        case 'EM PREPARO': return 'preparando'; 
        case 'ENVIADO': return 'enviado'; 
        case 'PENDENTE': return 'pendente';
        case 'CANCELADO': return 'cancelado'; 
        default: return 'pendente';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SkateLab</title>
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
  </head>
  <body>
  <?php include __DIR__ . '/../admin/partials/headeradmin.php'; ?>
  <main class="dashboard-container">

    <h1 class="title">Dashboard</h1>
    <p class="subtitle">Visão geral do seu e-commerce de skate</p>

    <?php if (isset($erro_db)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?= $erro_db ?></p>
    <?php endif; ?>

    <section class="cards">
      <div class="card">
        <div class="card-content">
          <div class="icon">📦</div>
          <div>
            <h3>Produtos</h3>
            <p class="value"><?= $total_produtos ?></p>
            <span>Total cadastrados</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-content">
          <div class="icon">✅</div>
          <div>
            <h3>Ativos</h3>
            <p class="value"><?= $produtos_ativos ?></p>
            <span>Produtos visíveis na loja</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-content">
          <div class="icon">📉</div>
          <div>
            <h3>Estoque baixo</h3>
            <p class="value"><?= $baixo_estoque ?></p>
            <span>Itens com 5 ou menos</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-content">
          <div class="icon">🗂️</div>
          <div>
            <h3>Categorias</h3>
            <p class="value"><?= $total_categorias ?></p>
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
          <p class="value">R$ <?= number_format($vendas_totais, 2, ',', '.') ?></p>
          <span class="growth">Reflete pedidos pagos</span>
        </div>
      </div>

      <div class="overview-card">
        <div class="icon">🛒</div>
        <div>
          <h3>Pedidos</h3>
          <p class="value"><?= $total_pedidos ?></p>
           <span class="growth">Total de pedidos criados</span>
        </div>
      </div>

      <div class="overview-card">
        <div class="icon">👥</div>
        <div>
          <h3>Usuários</h3>
          <p class="value"><?= $total_usuarios ?></p>
          <span class="growth">Clientes cadastrados</span>
        </div>
      </div>
    </section>

    <div class="dashboard-columns">
      <section class="recent-orders">
        <h2>Pedidos Recentes</h2>
        <ul class="order-list">
          <?php if (empty($pedidos_recentes)): ?>
              <li>Nenhum pedido recente.</li>
          <?php else: ?>
              <?php foreach ($pedidos_recentes as $pedido): ?>
                  <li>
                    <div>
                      <strong>#<?= $pedido['id_pedido'] ?> - <?= htmlspecialchars($pedido['cliente_nome']) ?></strong><br>
                      <span>Pedido recente</span>
                    </div>
                    <div class="order-value">R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></div>
                    <span class="status <?= getStatusClass($pedido['status']) ?>"><?= htmlspecialchars($pedido['status']) ?></span>
                  </li>
              <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </section>

      <section class="best-products">
        <h2>Produtos Mais Vendidos</h2>
        <ul class="product-list">
           <?php if (empty($mais_vendidos)): ?>
              <li>Nenhum produto vendido ainda.</li>
          <?php else: ?>
               <?php foreach ($mais_vendidos as $produto): ?>
                  <li>
                    <div>
                      <strong><?= htmlspecialchars($produto['produto_nome']) ?></strong><br>
                      <span><?= $produto['total_vendido'] ?> vendas</span>
                    </div>
                    <div class="product-value">R$ <?= number_format($produto['valor_vendido'], 2, ',', '.') ?></div>
                     <span class="growth positive">--%</span>
                  </li>
              <?php endforeach; ?>
           <?php endif; ?>
        </ul>
      </section>
     </div>

     <section class="recent-products">
      <div class="header-section">
        <h2>Produtos Adicionados Recentemente</h2>
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
          <?php if (empty($produtos_recentes_tabela)): ?>
              <tr><td colspan="7" style="text-align:center;">Nenhum produto cadastrado.</td></tr>
          <?php else: ?>
              <?php foreach ($produtos_recentes_tabela as $produto): ?>
                  <tr>
                    <td><img src="<?= htmlspecialchars($produto['url_img']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>"></td>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($produto['estoque']) ?></td>
                    <td><?= htmlspecialchars($produto['categoria_nome'] ?? 'Sem Categoria') ?></td>
                    <td><span class="status <?= strtolower($produto['status']) ?>"><?= htmlspecialchars($produto['status']) ?></span></td>
                    <td>
                        <a href="../produtos/editar_produto.php?id=<?= $produto['id_pecas'] ?>" class="btn editar">Editar</a>
                       <a href="../produtos/excluir_produto.php?id=<?= $produto['id_pecas'] ?>" class="btn excluir" onclick="return confirm('Tem certeza?');">Excluir</a>
                       </td>
                  </tr>
              <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>
  </body>
</html>