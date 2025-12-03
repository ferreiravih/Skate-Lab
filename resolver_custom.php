<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/resolver_custom.php';

echo "Iniciando processo para remover a categoria 'Custom'...\n<br>";

try {
    $pdo->beginTransaction();

    // 1. Definir nomes das categorias
    $custom_cat_name = 'Custom';
    $archive_cat_name = 'Arquivados';
    $archive_cat_desc = 'Categoria para itens de pedidos antigos ou customizações descontinuadas.';

    // 2. Encontrar ID da categoria 'Custom'
    $stmt_custom = $pdo->prepare("SELECT id_cat FROM public.categorias WHERE nome = :nome");
    $stmt_custom->execute([':nome' => $custom_cat_name]);
    $custom_cat = $stmt_custom->fetch(PDO::FETCH_ASSOC);

    if (!$custom_cat) {
        die("ERRO: A categoria 'Custom' não foi encontrada. O processo foi interrompido.");
    }
    $id_cat_custom = $custom_cat['id_cat'];
    echo "Categoria 'Custom' encontrada (ID: $id_cat_custom).\n<br>";

    // 3. Encontrar ou criar a categoria 'Arquivados'
    $stmt_archive = $pdo->prepare("SELECT id_cat FROM public.categorias WHERE nome = :nome");
    $stmt_archive->execute([':nome' => $archive_cat_name]);
    $archive_cat = $stmt_archive->fetch(PDO::FETCH_ASSOC);

    if ($archive_cat) {
        $id_cat_archive = $archive_cat['id_cat'];
        echo "Categoria '$archive_cat_name' já existe (ID: $id_cat_archive).\n<br>";
    } else {
        echo "Categoria '$archive_cat_name' não encontrada. Criando nova categoria...\n<br>";
        $stmt_create = $pdo->prepare("INSERT INTO public.categorias (nome, descricao) VALUES (:nome, :desc)");
        $stmt_create->execute([':nome' => $archive_cat_name, ':desc' => $archive_cat_desc]);
        $id_cat_archive = $pdo->lastInsertId();
        echo "Categoria '$archive_cat_name' criada com sucesso (ID: $id_cat_archive).\n<br>";
    }

    // 4. Mover as peças da categoria 'Custom' para 'Arquivados' e inativá-las
    $stmt_move = $pdo->prepare("UPDATE public.pecas SET id_cat = :id_archive, status = 'INATIVO' WHERE id_cat = :id_custom");
    $stmt_move->execute([':id_archive' => $id_cat_archive, ':id_custom' => $id_cat_custom]);
    $count_moved = $stmt_move->rowCount();
    echo "$count_moved peças foram movidas para '$archive_cat_name' e definidas como INATIVO.\n<br>";

    // 5. Deletar a categoria 'Custom'
    $stmt_delete = $pdo->prepare("DELETE FROM public.categorias WHERE id_cat = :id_custom");
    $stmt_delete->execute([':id_custom' => $id_cat_custom]);
    echo "Categoria 'Custom' foi excluída com sucesso.\n<br>";

    $pdo->commit();
    echo "\n<br>PROCESSO CONCLUÍDO COM SUCESSO!";

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Ocorreu um erro e a operação foi revertida: " . $e->getMessage());
}
?>