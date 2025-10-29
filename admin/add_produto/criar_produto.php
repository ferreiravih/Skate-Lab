<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';

// --- INÍCIO: Resposta JSON ---
// Define que a resposta será em JSON
header('Content-Type: application/json');

// Função para enviar a resposta e parar o script
function enviarResposta($sucesso, $mensagem) {
    echo json_encode([
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ]);
    exit;
}
// --- FIM: Resposta JSON ---

// 2. VERIFICAR O MÉTODO SE É POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    enviarResposta(false, "Método inválido.");
}

// 3. coletar e sanetizar os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$url_img = trim($_POST['url_img'] ?? '');
$desc_curta = trim($_POST['desc_curta'] ?? '');
$dsc_longa = trim($_POST['dsc_longa'] ?? null);
$id_cat = filter_var($_POST['id_cat'] ?? '', FILTER_VALIDATE_INT);
$preco = filter_var($_POST['preco'] ?? '', FILTER_VALIDATE_FLOAT);
$estoque = filter_var($_POST['estoque'] ?? 0, FILTER_VALIDATE_INT);
$status = ($_POST['status'] ?? 'INATIVO') === 'ATIVO' ? 'ATIVO' : 'INATIVO';

// 4. VALIDAÇÃO MÍNIMA (enviando JSON em caso de erro)
if (empty($nome)) {
    enviarResposta(false, "Erro: O campo 'Nome' é obrigatório.");
}
if (empty($url_img)) {
    enviarResposta(false, "Erro: O campo 'URL da Imagem' é obrigatório.");
}
if (empty($desc_curta)) {
    enviarResposta(false, "Erro: O campo 'Descrição Curta' é obrigatório.");
}
if ($id_cat === false || $id_cat === 0) {
    enviarResposta(false, "Erro: Você precisa selecionar uma 'Categoria' válida.");
}
if ($preco === false) {
    enviarResposta(false, "Erro: O campo 'Preço' é obrigatório e deve ser um número válido.");
}
if ($estoque === false) { // Verifica se o estoque é um número válido
    enviarResposta(false, "Erro: O campo 'Estoque' deve ser um número válido.");
}

// 5. INSERIR NO BANCO DE DADOS
$sql = "INSERT INTO public.pecas 
            (id_cat, nome, url_img, url_m3d, preco, estoque, desc_curta, dsc_longa, status) 
        VALUES 
            (:id_cat, :nome, :url_img, :url_m3d, :preco, :estoque, :desc_curta, :dsc_longa, :status)";

try {
    // 6. PREPARED STATEMENTS
    $stmt = $pdo->prepare($sql);
    
    // 7. EXECUTAR A CONSULTA
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

    // 8. ENVIAR RESPOSTA DE SUCESSO
    enviarResposta(true, "Produto cadastrado com sucesso!");

} catch (PDOException $e) {
    // 9. TRATAR ERROS
    error_log("Erro ao criar produto: " . $e->getMessage());
    enviarResposta(false, "Erro de banco de dados. Verifique os logs.");
}
?>