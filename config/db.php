<?php

$host = 'db.gzbeazpbvgiymtmtgffy.supabase.co';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres';
$password = '3tecSk@teL@b'; 


$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_PERSISTENT         => false 
];

try {

    $pdo = new PDO($dsn, $user, $password, $options);

} catch (PDOException $e) {
    die("ERRO REAL DA CONEXÃO: " . $e->getMessage());
}
?>