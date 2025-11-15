<?php
// 1. INICIA A SESSÃƒO (DEVE SER A PRIMEIRA COISA)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. VERIFICAÃ‡ÃƒO DE LOGIN
// Se 'id_usu' NÃƒO EXISTE na sessÃ£o, o usuÃ¡rio nÃ£o estÃ¡ logado.
if (!isset($_SESSION['id_usu'])) {
    
    // 3. Redireciona para a home (onde estÃ¡ o login) com um erro
    header("Location: ../home/index.php?error=auth_required");
    exit; // Para a execuÃ§Ã£o do script
}

// O restante do seu cÃ³digo original comeÃ§a aqui
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
                <p style="padding: 20px;">Seu carrinho estÃ¡ vazio.</p>
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
        $subtotal = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        $freteCotacao = $_SESSION['frete_cotacao'] ?? null;
        $freteSelecionado = $freteCotacao['selecionado'] ?? null;
        $valorFrete = $freteSelecionado['valor'] ?? 0.0;
        $totalComFrete = $subtotal + $valorFrete;
        $destinoCep = $freteCotacao['cep'] ?? '';
        $destinoCep = preg_replace('/\D/', '', (string)$destinoCep);
        if (strlen($destinoCep) === 8) {
            $destinoCep = substr($destinoCep, 0, 5) . '-' . substr($destinoCep, 5);
        }
        ?>
        <div class="cardtotal" data-subtotal="<?= number_format($subtotal, 2, '.', '') ?>">
            <h3>Resumo do Pedido</h3>
            <p>
                Subtotal
                <span id="subtotal-valor" data-value="<?= number_format($subtotal, 2, '.', '') ?>">
                    R$ <?= number_format($subtotal, 2, ',', '.') ?>
                </span>
            </p>
            <p>
                Frete
                <span class="frete" id="frete-valor" data-value="<?= number_format($valorFrete, 2, '.', '') ?>">
                    <?= $valorFrete > 0 ? 'R$ ' . number_format($valorFrete, 2, ',', '.') : 'Calcule' ?>
                </span>
            </p>
            <div class="frete-selected" id="frete-selected-text">
                <?php if ($freteSelecionado): ?>
                    <?= htmlspecialchars($freteSelecionado['label']) ?> · <?= $freteSelecionado['prazo'] ?> dias úteis
                <?php else: ?>
                    Informe seu CEP para estimar prazo e valor de entrega.
                <?php endif; ?>
            </div>
            <p class="total">
                Total
                <span id="total-valor" data-value="<?= number_format($totalComFrete, 2, '.', '') ?>">
                    R$ <?= number_format($totalComFrete, 2, ',', '.') ?>
                </span>
            </p>

            <div class="frete-box">
                <span class="frete-box__title">Calcular frete e prazo</span>
                <form id="frete-form">
                    <label for="cep-frete" class="sr-only">CEP</label>
                    <input
                        type="text"
                        id="cep-frete"
                        name="cep"
                        inputmode="numeric"
                        maxlength="9"
                        placeholder="00000-000"
                        value="<?= htmlspecialchars($destinoCep ?? '') ?>">
                    <button type="submit" id="btn-calcular-frete">Calcular</button>
                </form>
                <small class="frete-feedback" id="frete-feedback"></small>
                <div
                    id="frete-opcoes"
                    class="frete-opcoes"
                    data-frete='<?= htmlspecialchars(json_encode($freteCotacao ?? null, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                </div>
            </div>
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
    <script src='frete.js'></script>
</body>
</html>



