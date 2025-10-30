<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Proteção: Verifica se o usuário está logado
if (!isset($_SESSION['id_usu'])) {
    header("Location: ../home/index.php?error=not_logged_in"); // Manda para o login se não estiver
    exit;
}

// Proteção: Verifica se o carrinho não está vazio
if (empty($_SESSION['carrinho'])) {
    header("Location: ../carrinho/carrinho.php?error=empty_cart");
    exit;
}

// Calcular o total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pagamento - SkateLab</title>
    <link rel="stylesheet" href="pagamento.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>

    <div class="container">
        <div class="containerdados">
            <form action="pagamento.php" method="POST">
                <h2>Informações de Entrega</h2>
                <p>Preencha seus dados para a entrega.</p>

                <div class="cardcheckbox">
                    <label for="cep">CEP *</label>
                    <input type="text" id="cep" name="cep" placeholder="00000-000" required>
                </div>
                <div class="cardcheckbox">
                    <label for="address">Rua *</label>
                    <input type="text" id="address" name="address" placeholder="Rua das Flores" required>
                </div>
                <div class="separarcheckbox">
                    <div class="cardcheckbox">
                        <label for="numero">Número *</label>
                        <input type="text" id="numero" name="numero" placeholder="123" required>
                    </div>
                    <div class="cardcheckbox">
                        <label for="complement">Complemento</label>
                        <input type="text" id="complement" name="complement" placeholder="Apto 45">
                    </div>
                </div>
                <div class="cardcheckbox">
                    <label for="bairro">Bairro *</label>
                    <input type="text" id="bairro" name="bairro" placeholder="Centro" required>
                </div>
                <div class="separarcheckbox">
                    <div class="cardcheckbox">
                        <label for="city">Cidade *</label>
                        <input type="text" id="city" name="city" placeholder="São Paulo" required>
                    </div>
                    <div class="cardcheckbox">
                        <label for="state">Estado *</label>
                        <input type="text" id="state" name="state" placeholder="SP" required>
                    </div>
                </div>

                <div class="pagamentocontainer">
                    <h2>Forma de Pagamento</h2>
                    <p>Selecione como deseja pagar (simulação)</p>
                    <div class="opcoesdepagamento">
                        <div class="opcao" data-pagamento="pix">
                            <i class="fa-brands fa-pix"></i>
                            <h3>PIX</h3>
                        </div>
                        <div class="opcao" data-pagamento="cartao">
                            <i class="fa-solid fa-credit-card"></i>
                            <h3>Cartão de Crédito</h3>
                        </div>
                        <div class="opcao" data-pagamento="boleto">
                            <i class="fa-solid fa-barcode"></i>
                            <h3>Boleto</h3>
                        </div>
                    </div>
                    <div id="pix" class="conteudopagamento">
                        <p>O pagamento com PIX será simulado. Ao clicar em "Finalizar", o pedido será registrado como "PENDENTE".</p>
                    </div>
                    <div id="cartao" class="conteudopagamento">
                        <p>O pagamento com Cartão será simulado. Ao clicar em "Finalizar", o pedido será registrado como "PENDENTE".</p>
                    </div>
                    <div id="boleto" class="conteudopagamento">
                        <p>O pagamento com Boleto será simulado. Ao clicar em "Finalizar", o pedido será registrado como "PENDENTE".</p>
                    </div>
                </div>

                <button type="submit" class="botaopagar">
                    <i class="fa-solid fa-lock"></i> Finalizar Pedido
                </button>
            </form>
        </div>

        <div class="containerdadoss">
            <h2>Resumo do Pedido</h2>
            <?php foreach ($_SESSION['carrinho'] as $item): ?>
                <div class="cardproduto">
                    <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                    <div class="produtoinfo">
                        <p><?= htmlspecialchars($item['nome']) ?> (x<?= $item['quantidade'] ?>)</p>
                        <p class="produtopreco">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <hr>
            <div class="totalcard">
                <p>Subtotal <span>R$ <?= number_format($total, 2, ',', '.') ?></span></p>
                <p>Frete <span style="color: green;">Grátis</span></p>
                <p class="total">Total <span>R$ <?= number_format($total, 2, ',', '.') ?></span></p>
            </div>
        </div>
    </div>

    <?php include '../componentes/footer.php'; ?>
    <script src="pagamento.js"></script>
</body>

</html>