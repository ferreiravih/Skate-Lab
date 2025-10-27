<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "loja_skate"; // nome do seu banco criado no MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    // Ativar erros como exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
