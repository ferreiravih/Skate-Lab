<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_usu'])) {
        header('Location: ../auth/login.php?msg=faça login para avaliar');
        exit;
    }

    $id_usu = $_SESSION['id_usu'];
    $id_peca = filter_input(INPUT_POST, 'id_peca', FILTER_VALIDATE_INT);
    $nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);
    $comentario = trim($_POST['comentario'] ?? '');

    if (!$id_peca || !$nota || $nota < 1 || $nota > 5) {
        header("Location: produto.php?id=$id_peca&erro=dados_invalidos");
        exit;
    }

    try {
        
        $sql = "INSERT INTO avaliacoes (id_usu, id_pecas, nota, comentario) VALUES (:id_usu, :id_pecas, :nota, :comentario)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_usu' => $id_usu,
            ':id_pecas' => $id_peca,
            ':nota' => $nota,
            ':comentario' => $comentario
        ]);

        header("Location: produto.php?id=$id_peca&sucesso=avaliacao_enviada");
        exit;

    } catch (PDOException $e) {
        header("Location: produto.php?id=$id_peca&erro=erro_banco");
        exit;
    }
} else {
    header("Location: ../home/index.php");
    exit;
}