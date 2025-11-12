<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__. '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../home/index.php"); 
    exit;
}


$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($email) || empty($senha)) {
    header("Location: ../home/index.php?error=empty");
    exit;
}

try {
    $sql = "SELECT id_usu, nome, email, senha, tipo, verificado 
            FROM public.usuario WHERE email = :email";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

 
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        
       
        if ($usuario['verificado'] == false && $usuario['tipo'] != 'admin') {
           
            header("Location: ../verificar.php?error=not_verified&email=" . urlencode($email));
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['id_usu'] = $usuario['id_usu'];
        $_SESSION['nome_usu'] = $usuario['nome'];
        $_SESSION['tipo_usu'] = $usuario['tipo']; 
        $_SESSION['email_usu'] = $usuario['email'];
        
        if ($usuario['tipo'] === 'admin') {
            header("Location: ../admin/dashboard.php?status=login_success"); 
        } else {
            header("Location: ../perfil/perfil.php?status=login_success");
        }
        exit;

    } else {
        header("Location: ../home/index.php?error=invalid");
        exit;
    }

} catch (PDOException $e) {
    error_log("Erro de login: " . $e->getMessage());
    header("Location: ../home/index.php?error=db");
    exit;
}
?>