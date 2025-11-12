<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__. '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../verificar.php");
    exit;
}

$email = $_POST['email'] ?? '';
$codigo = $_POST['codigo'] ?? '';

if (empty($email) || empty($codigo)) {
    header("Location: ../verificar.php?error=missing_data");
    exit;
}

try {
    $sql = "SELECT * FROM public.usuario 
            WHERE email = :email 
              AND codigo_verificacao = :codigo 
              AND verificado = false";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email, ':codigo' => $codigo]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        header("Location: ../verificar.php?email=" . urlencode($email) . "&error=invalid_code");
        exit;
    }

    $agora = new DateTime();
    $expira_em = new DateTime($usuario['codigo_expira_em']);

    if ($agora > $expira_em) {
        header("Location: ../verificar.php?email=" . urlencode($email) . "&error=invalid_code");
        exit;
    }


    $sql_update = "UPDATE public.usuario 
                   SET verificado = true, 
                       codigo_verificacao = NULL, 
                       codigo_expira_em = NULL 
                   WHERE id_usu = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([':id' => $usuario['id_usu']]);

    session_regenerate_id(true);
    $_SESSION['id_usu'] = $usuario['id_usu'];
    $_SESSION['nome_usu'] = $usuario['nome'];
    $_SESSION['tipo_usu'] = $usuario['tipo'];
    $_SESSION['email_usu'] = $usuario['email'];

   
    header("Location: ../perfil/perfil.php?status=registered"); 
    exit;

} catch (PDOException $e) {
    error_log("Erro na verificação: " . $e->getMessage());
    header("Location: ../verificar.php?email=" . urlencode($email) . "&error=db_error");
    exit;
}
?>