<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SkateLab</title>
    <link rel="stylesheet" href="../pagamento/pagamento.css">
    <script src="../pagamento/pagamento.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>
    <main class="container">
        <!-- Coluna Esquerda -->
        <section class="containerdados">
            <h2>Entrega</h2>

            <div class="separarcheckbox">
                <div class="cardcheckbox">
                    <label for="country">País/Região</label>
                    <input type="text" id="country" placeholder="Brasil">
                </div>

                <div class="cardcheckbox">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" placeholder="00000-000">
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



            <section class="pagamentocontainer">
                <h2>Finalizar Compra</h2>
                <p>Escolha sua forma de pagamento preferida</p>

                <div class="opcoesdepagamento">
                    <div class="opcao" data-pagamento="pix">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                        <h3>PIX</h3>
                        <p>Pagamento instantâneo e seguro</p>
                    </div>

                    <div class="opcao" data-pagamento="cartao">
                        <i class="fa-regular fa-credit-card"></i>
                        <h3>Cartão de Crédito</h3>
                        <p>Parcelamento em até 12x</p>
                    </div>

                    <div class="opcao" data-pagamento="boleto">
                        <i class="fa-solid fa-barcode"></i>
                        <h3>Boleto Bancário</h3>
                        <p>Vencimento em 3 dias úteis</p>
                    </div>
                </div>

                <div class="conteudopagamento" id="pix">
                    <h3>Pague com PIX</h3>
                    <div class="pixcard">
                        <div class="qrcode">📷</div>
                        <div class="pixinfo">
                            <label>Chave PIX Copia e Cola</label>
                            <div class="pixinput">
                                <input type="text" value="00020126580014br.gov.bcb.pix..." readonly>
                                <button class="btn-copy">📋</button>
                            </div>
                            <small>Copie o código e cole no app do seu banco</small>
                        </div>
                    </div>
                    <button class="botaolaranja">Pagar Agora</button>
                </div>

                <div class="conteudopagamento" id="cartao">
                    <h3>Dados do Cartão</h3>
                    <form action="" method="POST">
                        <label>Número do Cartão</label>
                        <input type="text" placeholder="0000 0000 0000 0000">

                        <label>Nome no Cartão</label>
                        <input type="text" placeholder="NOME COMPLETO">

                        <div class="linha">
                            <div>
                                <label>Validade</label>
                                <input type="text" placeholder="MM/AA">
                            </div>
                            <div>
                                <label>CVV</label>
                                <input type="text" placeholder="000">
                            </div>
                        </div>

                        <button type="submit" class="botaolaranja">adicionar cartao</button>
                    </form>
                </div>

                <div class="conteudopagamento" id="boleto">
                    <h3>Boleto Bancário</h3>
                    <div class="boletocard">
                        <div class="barcode">||| ||| |||</div>
                        <p>O boleto será gerado e enviado para seu e-mail.<br>Você pode pagar em qualquer banco até a data de vencimento.</p>
                        <button class="botaolaranja">Gerar Boleto</button>
                    </div>
                </div>
            </section>
        </section>

        <!-- Coluna Direita -->
        <section class="containerdadoss" id="containerdadoss">
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
                <label for="desconto">Código de desconto</label>
                <div class="cupomdedesconto">
                    <input type="text" id="desconto" placeholder="Digite o código">
                    <button>Aplicar</button>
                </div>
            </div>

            <div class="totalcard">
                <p>Subtotal <span>R$ 899,90</span></p>
                <p>Frete <span>A calcular</span></p>
                <hr>
                <p class="total">Total <span>R$ 899,90</span></p>
            </div>

            <button class="botaopagar">Pagar agora</button>
            <p class="textodeseguranca">💳 Pagamento seguro e criptografado</p>
        </section>
    </main>
    <?php include '../componentes/footer.php'; ?>
</body>
</html>