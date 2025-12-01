<?php

echo "<h1>Teste de Conexão com Supabase (PostgreSQL)</h1>";


require_once __DIR__ . '/../config/db.php';


if (isset($pdo)) {
    echo "<p style='color: green; font-size: 20px;'>SUCESSO! Conectado ao banco de dados.</p>";
    
    try {
        $stmt = $pdo->query("SELECT 1");
        $resultado = $stmt->fetchColumn();
        
        if ($resultado == 1) {
            echo "<p>Consulta 'SELECT 1' executada com sucesso.</p>";
        } else {
            echo "<p style='color: red;'>Consulta falhou de forma inesperada.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p style='color: red; font-weight: bold;'>ERRO AO EXECUTAR CONSULTA:</p>";
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    }
    
} else {
    echo "<p style='color: red; font-size: 20px;'>FALHA! O arquivo db.php foi incluído, mas a variável \$pdo não foi criada.</p>";
}
?>