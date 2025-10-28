<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="pedidos.css">
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
                <option value="pendente">Pendente</option>
                <option value="preparo">Em preparo</option>
                <option value="enviado">Enviado</option>
                <option value="entregue">Entregue</option>
                <option value="cancelado">Cancelado</option>
            </select>

            <select class="filtro-periodo">
                <option>Período</option>
                <option>Hoje</option>
                <option>Últimos 7 dias</option>
                <option>Últimos 30 dias</option>
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
                <tr>
                    <td>#1234</td>
                    <td>João Silva</td>
                    <td>21/10/2025</td>
                    <td><span class="status enviado">Enviado</span></td>
                    <td>R$ 389,00</td>
                    <td class="acoes">
                        <button type="button" class="btn-acao
                        "><i class="ri-eye-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-pencil-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-close-line"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#1233</td>
                    <td>Maria Santos</td>
                    <td>21/10/2025</td>
                    <td><span class="status pendente">Pendente</span></td>
                    <td>R$ 542,00</td>
                    <td class="acoes">
                        <button type="button" class="btn-acao
                        "><i class="ri-eye-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-pencil-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-close-line"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#1232</td>
                    <td>Pedro Costa</td>
                    <td>20/10/2025</td>
                    <td><span class="status entregue">Entregue</span></td>
                    <td>R$ 219,00</td>
                    <td class="acoes">
                        <button type="button" class="btn-acao
                        "><i class="ri-eye-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-pencil-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-close-line"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#1231</td>
                    <td>Ana Oliveira</td>
                    <td>20/10/2025</td>
                    <td><span class="status cancelado">Cancelado</span></td>
                    <td>R$ 178,00</td>
                    <td class="acoes">
                        <button type="button" class="btn-acao
                        "><i class="ri-eye-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-pencil-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-close-line"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>#1230</td>
                    <td>Carlos Souza</td>
                    <td>19/10/2025</td>
                    <td><span class="status enviado">Enviado</span></td>
                    <td>R$ 645,00</td>
                    <td class="acoes">
                        <button type="button" class="btn-acao
                        "><i class="ri-eye-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-pencil-line"></i></button>
                        <button type="button" class="btn-acao
                        "><i class="ri-close-line"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
    <!-- ===== MODAL DE DETALHES DO PEDIDO ===== -->
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
                <p id="modalEndereco">Rua das Flores, 123 - São Paulo, SP<br>(11) 98765-4321</p>
            </section>

            <section class="modal-secao">
                <h3><i class="ri-bank-card-line"></i> Forma de Pagamento</h3>
                <p id="modalPagamento">Cartão de Crédito</p>
            </section>

            <section class="modal-secao">
                <h3><i class="ri-box-3-line"></i> Produtos</h3>
                <div id="modalProdutos">
                    <div class="produto-item">
                        <span>Shape Pro Model</span>
                        <span>R$ 289,00</span>
                    </div>
                    <div class="produto-item">
                        <span>Rodas 52mm</span>
                        <span>R$ 100,00</span>
                    </div>
                </div>
            </section>

            <section class="modal-total">
                <h3>Valor Total</h3>
                <p id="modalTotal">R$ 389,00</p>
            </section>

            <div class="modal-botoes">
                <button class="btn-preparo" id="btnPreparo">Iniciar Preparo</button>
                <button class="btn-finalizar" id="btnFinalizar">Finalizar Pedido</button>
            </div>
        </div>
    </div>

    <script src="pedidos.js"></script>
</body>

</html>