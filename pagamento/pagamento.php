<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SkateLab</title>
    <link rel="stylesheet" href="../pagamento/pagamento.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
        <?php include '../componentes/navbar.php'; ?>
    <main class="container">
        <!-- Coluna Esquerda -->
        <section class="containerdados">
            <h2>Contato</h2>
            <div class="cardcheckbox">
                <label for="email">E-mail</label>
                <input type="email" id="email" placeholder="seu@email.com">
            </div>

            <label class="checkboxemail">
                <input type="checkbox">
                <span>Receber novidades e promoções por e-mail</span>
            </label>

            <h2>Entrega</h2>

            <div class="separarcheckbox">
                <div class="cardcheckbox">
                    <label for="country">País/Região</label>
                    <input type="text" id="country" placeholder="Brasil">
                </div>

                <div class="cardcheckbox">
                    <label for="name">Nome</label>
                    <input type="text" id="name" placeholder="João">
                </div>

                <div class="cardcheckbox">
                    <label for="lastname">Sobrenome</label>
                    <input type="text" id="lastname" placeholder="Silva">
                </div>

                <div class="cardcheckbox">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" placeholder="00000-000">
                </div>

                <div class="cardcheckbox">
                    <label for="address">Endereço</label>
                    <input type="text" id="address" placeholder="Rua, número">
                </div>

                <div class="cardcheckbox">
                    <label for="complement">Complemento (opcional)</label>
                    <input type="text" id="complement" placeholder="Apartamento, bloco, etc.">
                </div>

                <div class="cardcheckbox">
                    <label for="city">Cidade</label>
                    <input type="text" id="city" placeholder="São Paulo">
                </div>

                <div class="cardcheckbox">
                    <label for="state">Estado</label>
                    <input type="text" id="state" placeholder="SP">
                </div>
            </div>
        </section>

        <!-- Coluna Direita -->
        <section class="containerdados">
            <h2>Resumo do Pedido</h2>
            <div class="cardproduto">
                <img src="../img/imgs-skateshop/image.png" alt="Shape de Skate">
                <div class="produtoinfo">
                    <p class="produtoname">Shape Street Art Pro</p>
                    <p class="produtoexpecificacoes">8.0" x 31.5"</p>
                    <p class="produtopreco">R$ 899,90</p>
                </div>
            </div>

            <div class="form-group">
                <label for="discount">Código de desconto</label>
                <div class="discount-box">
                    <input type="text" id="discount" placeholder="Digite o código">
                    <button>Aplicar</button>
                </div>
            </div>

            <div class="summary-totals">
                <p>Subtotal <span>R$ 899,90</span></p>
                <p>Frete <span>A calcular</span></p>
                <hr>
                <p class="total">Total <span>R$ 899,90</span></p>
            </div>

            <button class="pay-btn">Pagar agora</button>
            <p class="secure-text">💳 Pagamento seguro e criptografado</p>
        </section>
    </main>
        <?php include '../componentes/footer.php'; ?>
</body>
</html>