<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';


header('Content-Type: application/json');


function enviarResposta($sucesso, $mensagem) {
    echo json_encode([
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ]);
    exit;
}



if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    enviarResposta(false, "Método inválido.");
}


$nome = trim($_POST['nome'] ?? '');
$url_img = trim($_POST['url_img'] ?? '');
$desc_curta = trim($_POST['desc_curta'] ?? '');
$dsc_longa = trim($_POST['dsc_longa'] ?? null);
$id_cat = filter_var($_POST['id_cat'] ?? '', FILTER_VALIDATE_INT);
$preco = filter_var($_POST['preco'] ?? '', FILTER_VALIDATE_FLOAT);
$estoque = filter_var($_POST['estoque'] ?? 0, FILTER_VALIDATE_INT);
$status = ($_POST['status'] ?? 'INATIVO') === 'ATIVO' ? 'ATIVO' : 'INATIVO';


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
if ($estoque === false) { 
    enviarResposta(false, "Erro: O campo 'Estoque' deve ser um número válido.");
}


$sql = "INSERT INTO public.pecas 
            (id_cat, nome, url_img, url_m3d, preco, estoque, desc_curta, dsc_longa, status) 
        VALUES 
            (:id_cat, :nome, :url_img, :url_m3d, :preco, :estoque, :desc_curta, :dsc_longa, :status)";

try {

    $stmt = $pdo->prepare($sql);
    

    $stmt->execute([
        ':id_cat' => $id_cat,
        ':nome' => $nome,
        ':url_img' => $url_img,
        ':url_m3d' => null, 
        ':preco' => $preco,
        ':estoque' => $estoque,
        ':desc_curta' => $desc_curta,
        ':dsc_longa' => $dsc_longa,
        ':status' => $status
    ]);


    enviarResposta(true, "Produto cadastrado com sucesso!");

} catch (PDOException $e) {

    error_log("Erro ao criar produto: " . $e->getMessage());
    enviarResposta(false, "Erro de banco de dados. Verifique os logs.");
}
?>