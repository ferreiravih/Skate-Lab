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

$freteCotacao = $_SESSION['frete_cotacao'] ?? null;
$freteSelecionado = $freteCotacao['selecionado'] ?? null;
$freteValor = isset($freteSelecionado['valor']) ? (float)$freteSelecionado['valor'] : 0.0;
$totalComFrete = $total + $freteValor;

$destino = $freteCotacao['destino'] ?? [];
$cepDestino = preg_replace('/\D/', '', (string)($freteCotacao['cep'] ?? ''));
if (strlen($cepDestino) === 8) {
    $cepDestino = substr($cepDestino, 0, 5) . '-' . substr($cepDestino, 5);
}

$logradouroDestino = $destino['logradouro'] ?? '';
$bairroDestino = $destino['bairro'] ?? '';
$cidadeDestino = $destino['cidade'] ?? '';
$estadoDestino = $destino['uf'] ?? '';
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
                <?php if (!$freteSelecionado): ?>
                    <div style="background: #fff4e5; border: 1px solid #f6c89f; color: #8f4a10; padding: 10px 12px; border-radius: 8px; margin-bottom: 16px;">
                        Ainda nǜo identificamos um frete selecionado. Volte ao carrinho para calcular e garantir prazo e valor exatos.
                    </div>
                <?php else: ?>
                    <div style="background: #e7f7ef; border: 1px solid #88d1b2; color: #10633f; padding: 10px 12px; border-radius: 8px; margin-bottom: 16px;">
                        Frete escolhido: <strong><?= htmlspecialchars($freteSelecionado['label']) ?></strong>, <?= (int)$freteSelecionado['prazo'] ?> dias úteis.
                    </div>
                <?php endif; ?>

                <div class="cardcheckbox">
                    <label for="cep">CEP *</label>
                    <input type="text" id="cep" name="cep" placeholder="00000-000" value="<?= htmlspecialchars($cepDestino) ?>" required>
                </div>
                <div class="cardcheckbox">
                    <label for="address">Rua *</label>
                    <input type="text" id="address" name="address" placeholder="Rua das Flores" value="<?= htmlspecialchars($logradouroDestino) ?>" required>
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
                    <input type="text" id="bairro" name="bairro" placeholder="Centro" value="<?= htmlspecialchars($bairroDestino) ?>" required>
                </div>
                <div class="separarcheckbox">
                    <div class="cardcheckbox">
                        <label for="city">Cidade *</label>
                        <input type="text" id="city" name="city" placeholder="S�o Paulo" value="<?= htmlspecialchars($cidadeDestino) ?>" required>
                    </div>
                    <div class="cardcheckbox">
                        <label for="state">Estado *</label>
                        <input type="text" id="state" name="state" placeholder="SP" value="<?= htmlspecialchars($estadoDestino) ?>" required>
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
                <p>
                    Frete
                    <span>
                        <?php if ($freteSelecionado): ?>
                            R$ <?= number_format($freteValor, 2, ',', '.') ?> (<?= htmlspecialchars($freteSelecionado['label']) ?>)
                        <?php else: ?>
                            Calcule no carrinho
                        <?php endif; ?>
                    </span>
                </p>
                <?php if ($freteSelecionado): ?>
                    <small style="display: block; margin-bottom: 8px; color: #555;">
                        Prazo estimado: <?= (int)$freteSelecionado['prazo'] ?> dias úteis
                        <?php if (!empty($destino['cidade']) && !empty($destino['uf'])): ?>
                            – <?= htmlspecialchars($destino['cidade']) ?>/<?= htmlspecialchars($destino['uf']) ?>
                        <?php endif; ?>
                    </small>
                <?php endif; ?>
                <p class="total">Total <span>R$ <?= number_format($totalComFrete, 2, ',', '.') ?></span></p>
            </div>
        </div>
    </div>

    <?php include '../componentes/footer.php'; ?>
    <script src="pagamento.js"></script>
</body>

</html>
