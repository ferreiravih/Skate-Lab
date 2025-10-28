<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

// 2. VERIFICAR O MÉTODO SE É POST, SENÃO REDIRECIONA.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Se alguém tentar acessar este URL diretamente, é expulso.
    header("Location: add_prod.php");
    exit;
}

// 3. coletar e sanetizar os dados do formulário, trim remove espaços desnecessários
$nome = trim($_POST['nome'] ?? '');
$url_img = trim($_POST['url_img'] ?? '');
$desc_curta = trim($_POST['desc_curta'] ?? '');
$dsc_longa = trim($_POST['dsc_longa'] ?? null); // Este pode ser nulo
$id_cat = filter_var($_POST['id_cat'] ?? '', FILTER_VALIDATE_INT);
$preco = filter_var($_POST['preco'] ?? '', FILTER_VALIDATE_FLOAT);
$estoque = filter_var($_POST['estoque'] ?? 0, FILTER_VALIDATE_INT);

// Refifica se o checkbox está marcado, desmarcado nao envia nada.
$status = ($_POST['status'] ?? 'INATIVO') === 'ATIVO' ? 'ATIVO' : 'INATIVO';

// 4. VALIDAÇÃO MÍNIMA DOS DADOS
if (empty($nome) || empty($url_img) || empty($desc_curta) || !$id_cat || $preco === false) {
    // Se dados obrigatórios faltarem, morre e exibe um erro.
    // (Uma implementação melhor usaria $_SESSION para enviar o erro de volta ao form)
    die("Erro: Nome, URL, Descrição Curta, Preço e Categoria são obrigatórios.");
}

// 5. INSERIR NO BANCO DE DADOS
// Usamos a tabela 'pecas' e as colunas do seu schema
$sql = "INSERT INTO pecas 
            (id_cat, nome, url_img, url_m3d, preco, estoque, desc_curta, dsc_longa, status) 
        VALUES 
            (:id_cat, :nome, :url_img, :url_m3d, :preco, :estoque, :desc_curta, :dsc_longa, :status)";

try {
    // 6. PREPARED STATEMENTS (A MELHOR PRÁTICA DE SEGURANÇA)
    // Isso previne 100% dos ataques de SQL Injection.
    $stmt = $pdo->prepare($sql);
    
    // 7. EXECUTAR A CONSULTA
    // Passamos os dados em um array separado. O PDO cuida da segurança.
    $stmt->execute([
        ':id_cat' => $id_cat,
        ':nome' => $nome,
        ':url_img' => $url_img,
        ':url_m3d' => null, // Coluna do seu schema, não está no formulário
        ':preco' => $preco,
        ':estoque' => $estoque,
        ':desc_curta' => $desc_curta,
        ':dsc_longa' => $dsc_longa,
        ':status' => $status
    ]);

    // 8. REDIRECIONAR COM SUCESSO
    // Se tudo deu certo, envia o admin para a lista de produtos.
    header("Location: ../produtos/produtos.php?status=success");
    exit;

} catch (PDOException $e) {
    // 9. TRATAR ERROS
    // Se algo der errado (ex: um 'nome' duplicado), registra no log
    // e mostra uma mensagem amigável.
    error_log("Erro ao criar produto: " . $e->getMessage());
    die("Erro ao salvar produto no banco de dados. Verifique os logs.");
}
?>