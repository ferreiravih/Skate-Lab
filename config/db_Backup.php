<?php
// config/db.php
// Configuração para ambiente local (Laragon / XAMPP)

// 1. Defina suas credenciais do Supabase aqui
$host = 'db.gzbeazpbvgiymtmtgffy.supabase.co';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres';
$password = ''; // A senha do seu banco

// 2. Definir a string de conexão (DSN) para o PostgreSQL
// Esta linha agora terá os valores corretos:
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

// 3. Definir opções do PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_PERSISTENT         => false 
];

try {
    // 4. Criar a instância global do PDO
    $pdo = new PDO($dsn, $user, $password, $options);

} catch (PDOException $e) {
    // Modo de Debug: Mostrar o erro real
    die("ERRO REAL DA CONEXÃO: " . $e->getMessage());
}
?>