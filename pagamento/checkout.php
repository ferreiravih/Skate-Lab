<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// verifica se o usuário está logado
if (!isset($_SESSION['id_usu'])) {
    header("Location: ../home/index.php?error=not_logged_in"); // manda para o login se não estiver
    exit;
}

// verifica se o carrinho não está vazio
if (empty($_SESSION['carrinho'])) {
    header("Location: ../carrinho/carrinho.php?error=empty_cart");
    exit;
}

// calcula o total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

$freteCotacao = $_SESSION['frete_cotacao'] ?? null;
$freteSelecionado = $freteCotacao['selecionado'] ?? null;
$freteValor = isset($freteSelecionado['valor']) ? (float)$freteSelecionado['valor'] : 0.0;
$totalComFrete = $total + $freteValor;
$freteDisponivel = $freteSelecionado !== null;

$destino = $freteCotacao['destino'] ?? [];
$cepDestino = preg_replace('/\D/', '', (string)($freteCotacao['cep'] ?? ''));
if (strlen($cepDestino) === 8) {
    $cepDestino = substr($cepDestino, 0, 5) . '-' . substr($cepDestino, 5);
}

$logradouroDestino = $destino['logradouro'] ?? '';
$bairroDestino = $destino['bairro'] ?? '';
$cidadeDestino = $destino['cidade'] ?? '';
$estadoDestino = $destino['uf'] ?? '';
$erroPagamento = $_SESSION['pagamento_error'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pagamento - SkateLab</title>
    <link rel="stylesheet" href="pagamento.css?v=1.0.3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../../Skate-Lab/img/imgs-icon/icon.png">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>

    <div class="container">
        <div class="containerdados">
            <form action="pagamento.php" method="POST" id="checkout-form">
                <h2>Informações de Entrega</h2>
                <p>Preencha seus dados para a entrega.</p>

                <?php if ($erroPagamento): ?>
                    <div style="background: #fdeaea; border: 1px solid #f5a6a0; color: #851d1a; padding: 10px 12px; border-radius: 8px; margin-bottom: 16px;">
                        <?= htmlspecialchars($erroPagamento) ?>
                    </div>
                <?php unset($_SESSION['pagamento_error']); endif; ?>

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
                        <input type="text" id="city" name="city" placeholder="São Paulo" value="<?= htmlspecialchars($cidadeDestino) ?>" required>
                    </div>
                    <div class="cardcheckbox">
                        <label for="state">Estado *</label>
                        <input type="text" id="state" name="state" placeholder="SP" value="<?= htmlspecialchars($estadoDestino) ?>" required>
                    </div>
                </div>
                <div class="frete-container">
                    <button type="button" id="calcular-frete-btn-merged" class="botaopagar">Calcular Frete</button>
                    <div id="frete-resultado" class="frete-resultado"></div>
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

                <button
                    type="submit"
                    id="botaopagar"
                    class="botaopagar">
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
                <p>Subtotal <span id="subtotal">R$ <?= number_format($total, 2, ',', '.') ?></span></p>
                <p>Frete <span id="valor-frete">A calcular</span></p>
                <p class="total">Total <span id="total-com-frete">R$ <?= number_format($total, 2, ',', '.') ?></span></p>
            </div>
        </div>
    </div>

    <?php include '../componentes/footer.php'; ?>
    <script src="pagamento.js"></script>
    <script src="../carrinho/frete.js?v=1.0.2"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        initFreteCalculator({
            cepInputId: 'cep', 
            calculateBtnId: 'calcular-frete-btn-merged', 
            resultContainerId: 'frete-resultado',
            subtotalId: 'subtotal',
            shippingValueId: 'valor-frete',
            totalWithShippingId: 'total-com-frete',
            formId: null 
        });


        document.body.addEventListener('frete-calculado', function(e) {
            const cepCalculado = e.detail.cep.replace(/\D/g, '');
            const endereco = e.detail.destino;


            if (endereco) {
                document.getElementById('address').value = endereco.logradouro || '';
                document.getElementById('bairro').value = endereco.bairro || '';
                document.getElementById('city').value = endereco.cidade || '';
                document.getElementById('state').value = endereco.uf || '';
            }
        });


        try {
            const cepSalvo = sessionStorage.getItem('cepCalculado');
            const freteSalvoJSON = sessionStorage.getItem('freteEscolhido');

            if (cepSalvo) {
                console.log('CEP encontrado na sessão:', cepSalvo);
                const cepInput = document.getElementById('cep');
                if (cepInput.value === '' || cepInput.value.replace(/\D/g, '') !== cepSalvo) {
                    cepInput.value = cepSalvo;
                }


                const tentarSelecionarFrete = (event) => {
                    if (freteSalvoJSON) {
                        const freteSalvo = JSON.parse(freteSalvoJSON);
                        const radioParaMarcar = document.getElementById(freteSalvo.id);
                        if (radioParaMarcar && !radioParaMarcar.checked) {
                            console.log('Aplicando opção de frete salva:', freteSalvo.nome);
                            radioParaMarcar.checked = true;
                            
                            radioParaMarcar.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                  
                    document.body.removeEventListener('frete-calculado', tentarSelecionarFrete);
                };

                document.body.addEventListener('frete-calculado', tentarSelecionarFrete);
                window.enviarCotacao(cepSalvo); 
            }
        } catch (e) {
            console.error('Falha ao aplicar dados da sessão.', e);
        }
    });
    </script>
</body>

</html>