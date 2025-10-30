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
    header("Location: pagamento.php");
    exit;
}

// 1. Coletar dados
$id_usu = $_SESSION['id_usu'];
$carrinho = $_SESSION['carrinho'];

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

    foreach ($carrinho as $id_peca => $item) {
        $stmt_item->execute([
            ':id_pedido' => $id_pedido_criado,
            ':id_pecas' => $id_peca,
            ':quantidade' => $item['quantidade'],
            ':preco_unitario' => $item['preco'] // Salva o preço daquele momento
        ]);
    }

    // 7. Efetivar a transação (Tudo correu bem!)
    $pdo->commit();

    // 8. Limpar o carrinho e redirecionar
    unset($_SESSION['carrinho']);
    
    // (O ideal é ter uma página "pedido_concluido.php")
    header("Location: ../perfil/perfil.php?status=order_success");
    exit;

} catch (Exception $e) {
    // 9. Desfazer a transação (Algo deu errado!)
    $pdo->rollBack();
    error_log("Erro ao processar pedido: " . $e->getMessage());
    header("Location: pagamento.php?error=processing_failed");
    exit;
}
?>