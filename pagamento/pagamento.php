<?php
// Inicia a sessão para pegar o carrinho e o usuário
session_start();
require_once __DIR__ . '/../config/db.php';

// Proteção: Verifica se o usuário está logado
if (!isset($_SESSION['id_usu'])) {
    header("Location: ../home/index.php?error=not_logged_in");
    exit;
}

// Proteção: Verifica se o carrinho não está vazio
if (empty($_SESSION['carrinho'])) {
    header("Location: ../carrinho/carrinho.php?error=empty_cart");
    exit;
}

// Proteção: Verifica se é um POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit;
}

// 1. Coletar dados
$id_usu = $_SESSION['id_usu'];
$carrinho = $_SESSION['carrinho'];
$freteCotacao = $_SESSION['frete_cotacao'] ?? null;
$freteSelecionado = $freteCotacao['selecionado'] ?? null;
$freteValor = isset($freteSelecionado['valor']) ? (float)$freteSelecionado['valor'] : 0.0;

if (!$freteSelecionado) {
    header("Location: ../carrinho/carrinho.php?error=frete_required");
    exit;
}

function obterOuCriarCategoriaCustom(PDO $pdo): int
{
    static $cachedId = null;
    if ($cachedId !== null) {
        return $cachedId;
    }

    $buscar = $pdo->prepare("SELECT id_cat FROM public.categorias WHERE LOWER(nome) = LOWER(:nome) LIMIT 1");
    $buscar->execute([':nome' => 'Custom']);
    $existente = $buscar->fetchColumn();
    if ($existente) {
        $cachedId = (int)$existente;
        return $cachedId;
    }

    $inserir = $pdo->prepare("INSERT INTO public.categorias (nome, descricao) VALUES (:nome, :descricao) RETURNING id_cat");
    $inserir->execute([
        ':nome' => 'Custom',
        ':descricao' => 'Itens personalizados gerados automaticamente',
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

    $categoriaId = obterOuCriarCategoriaCustom($pdo);
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

// Coleta o endereço do formulário de pagamento.php
// (Os 'name' dos inputs devem bater com o que está aqui)
$cep = trim($_POST['cep'] ?? '');
$rua = trim($_POST['address'] ?? ''); // O seu form usa 'address'
$numero = trim($_POST['numero'] ?? ''); // Você precisa adicionar este campo ao seu form
$bairro = trim($_POST['bairro'] ?? ''); // Você precisa adicionar este campo
$cidade = trim($_POST['city'] ?? '');
$estado = trim($_POST['state'] ?? '');
$complemento = trim($_POST['complement'] ?? '');

// 2. Calcular o valor total (do carrinho da sessão, por segurança)
$valor_total = 0;
foreach ($carrinho as $item) {
    $valor_total += $item['preco'] * $item['quantidade'];
}
$valor_total += $freteValor;

// 3. Iniciar a TRANSAÇÃO (Boa prática!)
// Isso garante que ou TUDO é salvo (pedido + itens), ou NADA é salvo.
try {
    $pdo->beginTransaction();

    // 4. Inserir o PEDIDO na tabela 'pedidos'
    $sql_pedido = "INSERT INTO public.pedidos 
        (id_usu, valor_total, status, 
         endereco_rua, endereco_numero, endereco_bairro, endereco_cidade, endereco_estado, endereco_cep, endereco_complemento)
        VALUES 
        (:id_usu, :valor_total, 'PENDENTE', 
         :rua, :numero, :bairro, :cidade, :estado, :cep, :complemento)";
    
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
        ':complemento' => $complemento
    ]);

    // 5. Obter o ID do pedido que acabamos de criar
    $id_pedido_criado = $pdo->lastInsertId('public.pedidos_id_pedido_seq'); // Sintaxe para PostgreSQL

    // 6. Inserir cada item do carrinho na tabela 'pedido_itens'
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

    // 7. Efetivar a transação (Tudo correu bem!)
    $pdo->commit();

    // 8. Limpar o carrinho e redirecionar
    unset($_SESSION['carrinho'], $_SESSION['frete_cotacao'], $_SESSION['pagamento_error']);

    header("Location: pedido_sucesso.php");
    exit;

} catch (Exception $e) {
    // 9. Desfazer a transação (Algo deu errado!)
    $pdo->rollBack();
    error_log("Erro ao processar pedido: " . $e->getMessage());
    $_SESSION['pagamento_error'] = $e->getMessage();
    header("Location: checkout.php?error=processing_failed");
    exit;
}
?>
