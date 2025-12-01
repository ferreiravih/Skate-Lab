<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';


try {
    $sql = "SELECT p.*, u.nome AS cliente_nome 
            FROM public.pedidos p
            JOIN public.usuario u ON p.id_usu = u.id_usu
            ORDER BY p.pedido_em DESC"; 
    
    $stmt = $pdo->query($sql);
    $pedidos = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Erro ao buscar pedidos: " . $e->getMessage());
    $pedidos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="pedidos.css">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>
<body>
    <?php include __DIR__ . '/../partials/headeradmin.php'; ?>
    <main class="pedidos-page">
        <div class="pedidos-header">
            <h1>Pedidos</h1>
            <p>Gerencie todos os pedidos da loja</p>
        </div>

        <div class="pedidos-filtros">
            <div class="filtro-busca">
                <i class="ri-search-line"></i>
                <input type="text" placeholder="Buscar por número do pedido ou cliente...">
            </div>
            <select class="filtro-status">
                <option value="todos">Todos</option>
                <option value="PENDENTE">Pendente</option>
                <option value="EM PREPARO">Em preparo</option>
                <option value="ENVIADO">Enviado</option>
                <option value="ENTREGUE">Entregue</option>
                <option value="CANCELADO">Cancelado</option>
            </select>
        </div>

        <table class="tabela-pedidos">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Valor Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Nenhum pedido encontrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr data-id-pedido="<?= $pedido['id_pedido'] ?>"> <td>#<?= htmlspecialchars($pedido['id_pedido']) ?></td>
                            <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
                            <td><?= date('d/m/Y', strtotime($pedido['pedido_em'])) ?></td>
                            <td>
                                <span class="status <?= strtolower(str_replace(' ', '', $pedido['status'])) ?>">
                                    <?= htmlspecialchars($pedido['status']) ?>
                                </span>
                            </td>
                            <td>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                            <td class="acoes">
                                <button type="button" class="btn-acao btn-ver-detalhes">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <a href="cancelar_pedido.php?id=<?= $pedido['id_pedido'] ?>" class="btn-acao" onclick="return confirm('Tem certeza que deseja cancelar este pedido?');">
                                    <i class="ri-close-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    
    <div class="modal-pedido" id="modalPedido">
        <div class="modal-conteudo">
            <button class="fechar-modal" id="fecharModal"><i class="ri-close-line"></i></button>

            <h2>Detalhes do Pedido <span id="modalNumero"></span></h2>

            <div class="modal-header-info">
                <p><strong>Cliente:</strong> <span id="modalCliente"></span></p>
                <span id="modalStatus" class="status"></span>
            </div>

            <p class="modal-data"><strong>Data do pedido:</strong> <span id="modalData"></span></p>

            <section class="modal-secao">
                <h3><i class="ri-map-pin-line"></i> Endereço de Entrega</h3>
                <p id="modalEndereco"></p>
            </section>

            <section class="modal-secao">
                <h3><i class="ri-bank-card-line"></i> Forma de Pagamento</h3>
                 <p id="modalPagamento">Forma de Pagamento Indisponível</p>
            </section>

            <section class="modal-secao">
                <h3><i class="ri-box-3-line"></i> Produtos</h3>
                 <div id="modalProdutos"></div>
            </section>

            <section class="modal-total">
                <h3>Valor Total</h3>
                <p id="modalTotal">R$ 0,00</p>
            </section>

            <div class="modal-botoes">
                <button class="btn-preparo" id="btnPreparo">Iniciar Preparo</button>
                <button class="btn-finalizar" id="btnFinalizar">Marcar como Enviado</button> </div>
        </div>
    </div>
    
    <script src="pedidos.js"></script>
    </body>
</html>