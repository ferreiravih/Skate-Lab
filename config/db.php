<?php
// Configuração para PostgreSQL (Compatível com Heroku + Supabase)

// Nome da variável de ambiente que armazenará a URL de conexão do Supabase no Heroku.
// Mantenha o nome 'SUPABASE_DATABASE_URL'
$env_var_name = 'SUPABASE_DATABASE_URL';
$url = getenv($env_var_name);

// 1. Configuração do Banco de Dados
if ($url) {
    // Modo HEROKU/SUPABASE (Lê a URL de conexão da variável de ambiente)
    $db_parts = parse_url($url);
    $host = $db_parts['host'];
    $port = $db_parts['port'] ?? '5432';
    $user = $db_parts['user'];
    $password = $db_parts['pass'];
    $database = ltrim($db_parts['path'], '/');
} else {
    // Modo LOCAL (Use suas credenciais de desenvolvimento)
    // ATENÇÃO: Verifique se sua instalação local usa PostgreSQL!
    $host = 'localhost';
    $port = '5432'; 
    $user = 'seu_usuario_local'; 
    $password = 'sua_senha_local';
    $database = 'seu_banco_local';
}

// 2. Tenta conectar com PDO
try {
    // String DSN para PostgreSQL (pgsql)
    $dsn = "pgsql:host=$host;port=$port;dbname=$database;user=$user;password=$password";
    
    // A maioria dos provedores exige SSL, mas o Supabase geralmente aceita conexões sem 
    // `sslmode=require` para simplificar. Se der erro, você pode adicionar no $dsn.
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Em produção (Heroku), o erro é logado e não exibido ao usuário
    // Em desenvolvimento, o erro aparece para debug
    if ($url) {
        // No Heroku, apenas registra o erro (melhor prática de segurança)
        error_log("Database connection error: " . $e->getMessage());
        die("Ocorreu um erro na conexão do sistema. Tente novamente mais tarde.");
    } else {
        // Localmente, mostra o erro completo
        die("Erro na conexão com o banco de dados local: " . $e->getMessage());
    }
}