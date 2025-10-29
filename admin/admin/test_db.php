<?php
// Define um título para a página
echo "<h1>Teste de Conexão com Supabase (PostgreSQL)</h1>";

// Tenta incluir o arquivo de configuração subindo um nível
require_once __DIR__ . '/../config/db.php';

// Se a variável $pdo foi criada com sucesso no 'db.php', a conexão funcionou.
if (isset($pdo)) {
    echo "<p style='color: green; font-size: 20px;'>SUCESSO! Conectado ao banco de dados.</p>";
    
    try {
        // Tenta fazer uma consulta simples para ter certeza
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