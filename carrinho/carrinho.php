<?php
// 1. INICIA A SESSÃO (DEVE SER A PRIMEIRA COISA)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. VERIFICAÇÃO DE LOGIN
// Se 'id_usu' NÃO EXISTE na sessão, o usuário não está logado.
if (!isset($_SESSION['id_usu'])) {
    
    // 3. Redireciona para a home (onde está o login) com um erro
    header("Location: ../home/home.php?error=auth_required");
    exit; // Para a execução do script
}

// O restante do seu código original começa aqui
// Garante que o carrinho exista
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeuCarrinhoSkateLab</title>
    <link rel="stylesheet" href="../carrinho/carrinho.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>

    <div class="carrinho">
        <i class="fa-solid fa-bag-shopping" style="color: #e46a25; font-size: 50px;"></i>
        <h1 class="textocarrinho">Meu Carrinho</h1>
        <span class="qtdcarrinho">
            <?= count($_SESSION['carrinho']) ?> itens
        </span>
    </div>

    <div class="carrinhocontainer">
        <div class="carrinhoitens">

            <?php if (empty($_SESSION['carrinho'])): ?>
                <p style="padding: 20px;">Seu carrinho está vazio.</p>
            <?php else: ?>
                <?php foreach ($_SESSION['carrinho'] as $item): ?>
                    <div class="itens">
                        <img src="<?= htmlspecialchars($item['imagem']) ?>"
                             alt="<?= htmlspecialchars($item['nome']) ?>">

                        <div class="textoitem">
                            <h3><?= htmlspecialchars($item['nome']) ?></h3>
                            <p><?= htmlspecialchars($item['descricao']) ?></p>

                            <div class="quantidade">
                                <form action="contr/atualizar_quantidade.php" method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="acao" value="menos">
                                    <button type="submit">-</button>
                                </form>

                                <span><?= $item['quantidade'] ?></span>

                                <form action="contr/atualizar_quantidade.php" method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="acao" value="mais">
                                    <button type="submit">+</button>
                                </form>
                            </div>
                        </div>

                        <div class="preco">
                            <p>
                                R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?>
                            </p>
                            <span class="precounitario">
                                R$ <?= number_format($item['preco'], 2, ',', '.') ?> cada
                            </span>
                            <br>
                            <form method="post" action="contr/remover_item.php" style="margin-top: 8px;">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button class="removeritem">Remover</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <?php
        $total = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
        ?>
        <div class="cardtotal">
            <h3>Resumo do Pedido</h3>
            <p>Subtotal <span>R$ <?= number_format($total, 2, ',', '.') ?></span></p>
            <p>Frete <span class="frete">Grátis</span></p>
            <p class="total">Total <span>R$ <?= number_format($total, 2, ',', '.') ?></span></p>

            <?php if (!empty($_SESSION['carrinho'])): ?>
                <a href="../pagamento/checkout.php" class="botaofinalizar" 
                   style="text-decoration: none; display: inline-block; text-align: center; box-sizing: border-box;">
                   Finalizar Compra
                </a>
            <?php else: ?>
                <button class="botaofinalizar" disabled style="background-color: #999;">Carrinho Vazio</button>
            <?php endif; ?>

            <button class="additens" onclick="window.location.href='../skateshop/skateee.php'">Adicionar Mais Itens</button>
        </div>
    </div>

    <?php include '../componentes/footer.php'; ?>
</body>
</html>