<?php

session_start();
require_once __DIR__ . '/../config/db.php';


if (!isset($_SESSION['id_usu'])) {
    header("Location: ../home/index.php?error=not_logged_in");
    exit;
}


if (empty($_SESSION['carrinho'])) {
    header("Location: ../carrinho/carrinho.php?error=empty_cart");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit;
}


$id_usu = $_SESSION['id_usu'];
$carrinho = $_SESSION['carrinho'];
$freteCotacao = $_SESSION['frete_cotacao'] ?? null;
$freteSelecionado = $freteCotacao['selecionado'] ?? null;


$freteValor = isset($freteSelecionado['valor']) ? (float)$freteSelecionado['valor'] : 0.0;
$freteDescricao = isset($freteSelecionado['label']) ? trim((string)$freteSelecionado['label']) : null;


if (!$freteSelecionado) {
    header("Location: ../carrinho/carrinho.php?error=frete_required");
    exit;
}

function obterOuCriarCategoriaFantasma(PDO $pdo): int
{
    static $cachedId = null;
    if ($cachedId !== null) {
        return $cachedId;
    }

    $nomeCategoria = '_gerado_automaticamente';
    $buscar = $pdo->prepare("SELECT id_cat FROM public.categorias WHERE nome = :nome LIMIT 1");
    $buscar->execute([':nome' => $nomeCategoria]);
    $existente = $buscar->fetchColumn();

    if ($existente) {
        $cachedId = (int)$existente;
        return $cachedId;
    }

    $inserir = $pdo->prepare("INSERT INTO public.categorias (nome, descricao) VALUES (:nome, :descricao) RETURNING id_cat");
    $inserir->execute([
        ':nome' => $nomeCategoria,
        ':descricao' => 'Categoria para itens de sistema. Não deve ser visível ou editada.',
    ]);
    $novoId = (int)$inserir->fetchColumn();
    $cachedId = $novoId;

    return $cachedId;
}

function criarProdutoCustomizado(PDO $pdo, array $item): int
{
    $nome = trim((string)($item['nome'] ?? 'Skate Customizado'));
    if ($nome === '') {
        $nome = 'Skate Customizado';
    }

    $descricaoCurta = trim((string)($item['descricao'] ?? 'Customização exclusiva'));
    if ($descricaoCurta === '') {
        $descricaoCurta = 'Customização exclusiva';
    }

    $descricaoLonga = $descricaoCurta;
    $imagem = isset($item['imagem']) ? trim((string)$item['imagem']) : null;
    if ($imagem === '') {
        $imagem = null;
    }

    $categoriaId = obterOuCriarCategoriaFantasma($pdo);
    $stmt = $pdo->prepare("
        INSERT INTO public.pecas
            (id_cat, nome, url_img, url_m3d, preco, estoque, desc_curta, dsc_longa, status)
        VALUES
            (:id_cat, :nome, :url_img, NULL, :preco, 0, :desc_curta, :dsc_longa, 'INATIVO')
        RETURNING id_pecas
    ");

    $stmt->execute([
        ':id_cat' => $categoriaId,
        ':nome' => $nome,
        ':url_img' => $imagem,
        ':preco' => isset($item['preco']) ? (float)$item['preco'] : 0.0,
        ':desc_curta' => $descricaoCurta,
        ':dsc_longa' => $descricaoLonga,
    ]);

    return (int)$stmt->fetchColumn();
}

function resolveIdPecaParaPedido(PDO $pdo, $chave, array $item): int
{
    if (isset($item['id_peca']) && $item['id_peca'] !== null && $item['id_peca'] !== '') {
        return (int)$item['id_peca'];
    }

    $candidato = $item['id'] ?? $chave;
    $candidatoString = (string)$candidato;
    if ($candidatoString !== '' && ctype_digit($candidatoString)) {
        return (int)$candidato;
    }

    return criarProdutoCustomizado($pdo, $item);
}


$cep = trim($_POST['cep'] ?? '');
$rua = trim($_POST['address'] ?? ''); 
$numero = trim($_POST['numero'] ?? ''); 
$bairro = trim($_POST['bairro'] ?? ''); 
$cidade = trim($_POST['city'] ?? '');
$estado = trim($_POST['state'] ?? '');
$complemento = trim($_POST['complement'] ?? '');


$valor_total = 0;
foreach ($carrinho as $item) {
    $valor_total += $item['preco'] * $item['quantidade'];
}
$valor_total += $freteValor;


try {
    $pdo->beginTransaction();


    $sql_pedido = "INSERT INTO public.pedidos 
        (id_usu, valor_total, status, 
         endereco_rua, endereco_numero, endereco_bairro, endereco_cidade, endereco_estado, endereco_cep, endereco_complemento,
         frete_valor, frete_descricao)
        VALUES 
        (:id_usu, :valor_total, 'PENDENTE', 
         :rua, :numero, :bairro, :cidade, :estado, :cep, :complemento,
         :frete_valor, :frete_descricao)";
    
    $stmt_pedido = $pdo->prepare($sql_pedido);
    $stmt_pedido->execute([
        ':id_usu' => $id_usu,
        ':valor_total' => $valor_total,
        ':rua' => $rua,
        ':numero' => $numero,
        ':bairro' => $bairro,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':cep' => $cep,
        ':complemento' => $complemento,
        ':frete_valor' => $freteValor,
        ':frete_descricao' => $freteDescricao
    ]);


    $id_pedido_criado = $pdo->lastInsertId('public.pedidos_id_pedido_seq'); 


    $sql_item = "INSERT INTO public.pedido_itens
        (id_pedido, id_pecas, quantidade, preco_unitario)
        VALUES
        (:id_pedido, :id_pecas, :quantidade, :preco_unitario)";
    
    $stmt_item = $pdo->prepare($sql_item);

    foreach ($carrinho as $chave => $item) {
        $stmt_item->execute([
            ':id_pedido' => $id_pedido_criado,
            ':id_pecas' => resolveIdPecaParaPedido($pdo, $chave, $item),
            ':quantidade' => max(1, (int)($item['quantidade'] ?? 1)),
            ':preco_unitario' => isset($item['preco']) ? (float)$item['preco'] : 0.0
        ]);
    }


    $pdo->commit();


    unset($_SESSION['carrinho'], $_SESSION['frete_cotacao'], $_SESSION['pagamento_error']);

    header("Location: pedido_sucesso.php");
    exit;

} catch (Exception $e) {

    $pdo->rollBack();
    error_log("Erro ao processar pedido: " . $e->getMessage());
    $_SESSION['pagamento_error'] = $e->getMessage();
    header("Location: checkout.php?error=processing_failed");
    exit;
}
?>
