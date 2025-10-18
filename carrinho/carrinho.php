<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeuCarrinhoSkateLab</title>
    <link rel="stylesheet" href="../carrinho/carrinho.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="../carrinho/carrinho.js"></script>
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>
    <div class="carrinho">
        <i class="fa-solid fa-bag-shopping" style="color: #e46a25; font-size: 50px;"></i>
        <h1 class="textocarrinho">Meu Carrinho</h1>
        <span class="qtdcarrinho">3 itens</span>
    </div>

    <div class="carrinhocontainer">
        <!-- Lista de produtos -->
        <div class="carrinhoitens">
            <div class="itens">
                <img src="../img/imgs-skateshop/image.png" alt="Skate Completo Street Pro">
                <div class="textoitem">
                    <h3>Skate Completo Street Pro</h3>
                    <p>Shape 8.0 com rodas 53mm, rolamentos ABEC 7 e trucks de alta qualidade</p>
                    <div class="quantidade">
                        <button onclick="diminuir()">-</button>
                        <span id="qtd">1</span>
                        <button onclick="aumentar()">+</button>
                    </div>
                </div>
                <div class="preco">
                    <p><span id="total"> R$ 200,00</span></p>
                    <span id="preco" class="precounitario"> R$ 200 cada</span> <br>
                    <button class="removeritem">🗑 Remover</button>
                </div>
            </div>
            <div class="itens">
                <img src="../img/imgs-skateshop/image.png" alt="Skate Completo Street Pro">
                <div class="textoitem">
                    <h3>Skate Completo Street Pro</h3>
                    <p>Shape 8.0 com rodas 53mm, rolamentos ABEC 7 e trucks de alta qualidade</p>
                    <div class="quantidade">
                        <button onclick="diminuir()">-</button>
                        <span id="qtd">2</span>
                        <button onclick="aumentar()">+</button>
                    </div>
                </div>
                <div class="preco">
                    <p><span id="total"> R$ 500,00</span></p>
                    <span id="preco" class="precounitario"> R$ 250 cada</span> <br>
                    <button class="removeritem">🗑 Remover</button>
                </div>
            </div>
            <div class="itens">
                <img src="../img/imgs-skateshop/image.png" alt="Skate Completo Street Pro">
                <div class="textoitem">
                    <h3>Skate Completo Street Pro</h3>
                    <p>Shape 8.0 com rodas 53mm, rolamentos ABEC 7 e trucks de alta qualidade</p>
                    <div class="quantidade">
                        <button onclick="diminuir()">-</button>
                        <span id="qtd">2</span>
                        <button onclick="aumentar()">+</button>
                    </div>
                </div>
                <div class="preco">
                    <p><span id="total"> R$ 500,00</span></p>
                    <span id="preco" class="precounitario"> R$ 250 cada</span> <br>
                    <button class="removeritem">🗑 Remover</button>
                </div>
            </div>

        </div>

        <!-- Resumo do Pedido -->
        <div class="cardtotal">
            <h3>Resumo do Pedido</h3>
            <p>Subtotal <span>R$ 809,60</span></p>
            <p>Frete <span class="frete">Grátis</span></p>
            <p class="total">Total <span>R$ 809,60</span></p>
            <button class="botaofinalizar">Finalizar Compra</button>
            <button class="additens">Adicionar Mais Itens</button>
        </div>
    </div>

    <?php include '../componentes/footer.php'; ?>
</body>
</html>