<?php
require_once __DIR__ . '/../admin_auth.php';
require_once __DIR__ . '/../../config/db.php';


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: produtos.php");
    exit;
}


$id_pecas = filter_input(INPUT_POST, 'id_pecas', FILTER_VALIDATE_INT);
$nome = trim($_POST['nome'] ?? '');
$url_img = trim($_POST['url_img'] ?? '');
$desc_curta = trim($_POST['desc_curta'] ?? '');
$dsc_longa = trim($_POST['dsc_longa'] ?? null);
$id_cat = filter_var($_POST['id_cat'] ?? '', FILTER_VALIDATE_INT);
$preco = filter_var($_POST['preco'] ?? '', FILTER_VALIDATE_FLOAT);
$estoque = filter_var($_POST['estoque'] ?? 0, FILTER_VALIDATE_INT);
$status = ($_POST['status'] ?? 'INATIVO') === 'ATIVO' ? 'ATIVO' : 'INATIVO';


if (!$id_pecas || empty($nome) || empty($url_img) || !$id_cat || $preco === false) {
    die("Erro: ID, Nome, URL, Preço e Categoria são obrigatórios.");
}


$sql = "UPDATE public.pecas SET
            id_cat = :id_cat,
            nome = :nome,
            url_img = :url_img,
            preco = :preco,
            estoque = :estoque,
            desc_curta = :desc_curta,
            dsc_longa = :dsc_longa,
            status = :status
        WHERE id_pecas = :id_pecas";

try {

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_cat' => $id_cat,
        ':nome' => $nome,
        ':url_img' => $url_img,
        ':preco' => $preco,
        ':estoque' => $estoque,
        ':desc_curta' => $desc_curta,
        ':dsc_longa' => $dsc_longa,
        ':status' => $status,
        ':id_pecas' => $id_pecas
    ]);


    header("Location: produtos.php?status=updated");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar produto: " . $e->getMessage());
    die("Erro ao atualizar produto. Verifique os logs.");
}
?>