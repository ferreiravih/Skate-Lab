
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - SkateLab</title>
    <link rel="stylesheet" href="../carrinho/carrinho.css">
</head>
<body>
    <?php include '../componentes/navbar.php'; ?>
    <main class="container">
        <h1 class="titulo">🛒 Meu Carrinho <span class="item-count">3 itens</span></h1>

        <section class="carrinho-items">
            <div class="carrinho-item">
                <img src="https://via.placeholder.com/100" alt="Skate Completo" class="produto-img">
                <div class="produto-info">
                    <h2 class="produto-nome">Skate Completo Street Pro</h2>
                    <p class="produto-desc">Shape 8.0 com rodas 53mm, rolamentos ABEC 7 e trucks de alta qualidade</p>
                    <div class="quantidade">
                        <label>Quantidade:</label>
                        <button>-</button>
                        <input type="text" value="1" readonly>
                        <button>+</button>
                    </div>
                </div>
                <div class="produto-preco">
                    <p class="preco">R$ 289,90</p>
                    <small>R$ 289,90 cada</small>
                    <button class="remover">Remover</button>
                </div>
            </div>

            <div class="carrinho-item">
                <img src="https://via.placeholder.com/100" alt="Rodas Premium" class="produto-img">
                <div class="produto-info">
                    <h2 class="produto-nome">Rodas Premium 54mm</h2>
                    <p class="produto-desc">Conjunto com 4 rodas de alta performance para street</p>
                    <div class="quantidade">
                        <label>Quantidade:</label>
                        <button>-</button>
                        <input type="text" value="2" readonly>
                        <button>+</button>
                    </div>
                </div>
                <div class="produto-preco">
                    <p class="preco">R$ 319,80</p>
                    <small>R$ 159,90 cada</small>
                    <button class="remover">Remover</button>
                </div>
            </div>

            <div class="carrinho-item">
                <img src="https://via.placeholder.com/100" alt="Trucks Profissionais" class="produto-img">
                <div class="produto-info">
                    <h2 class="produto-nome">Trucks Profissionais</h2>
                    <p class="produto-desc">Par de trucks resistentes com acabamento premium</p>
                    <div class="quantidade">
                        <label>Quantidade:</label>
                        <button>-</button>
                        <input type="text" value="1" readonly>
                        <button>+</button>
                    </div>
                </div>
                <div class="produto-preco">
                    <p class="preco">R$ 199,90</p>
                    <small>R$ 199,90 cada</small>
                    <button class="remover">Remover</button>
                </div>
            </div>
        </section>

        <aside class="resumo-pedido">
            <h2>Resumo do Pedido</h2>
            <p>Subtotal <span>R$ 809,60</span></p>
            <p>Frete <span class="gratis">Grátis</span></p>
            <p class="total">Total <span>R$ 809,60</span></p>
            <button class="finalizar">Finalizar Compra</button>
            <button class="adicionar">Adicionar Mais Itens</button>
        </aside>

        <section class="frete-cupom">
            <h2>Calcular Frete e Aplicar Cupom</h2>
            <label for="cep">CEP de Entrega</label>
            <input type="text" id="cep" placeholder="00000-000">
            <button class="calcular">Calcular</button>
        </section>
    </main>
</body>
</html>