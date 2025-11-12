<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__. '/../config/db.php';
require_once __DIR__. '/../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_GET['email'] ?? '');

if (empty($email)) {
    header("Location: ../verificar.php?error=missing_data");
    exit;
}

try {
    $sql_check = "SELECT * FROM public.usuario WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':email' => $email]);
    $usuario = $stmt_check->fetch();

 
    if (!$usuario) {
        header("Location: ../verificar.php?email=" . urlencode($email) . "&error=invalid_user");
        exit;
    }
    if ($usuario['verificado'] == true) {
        session_regenerate_id(true);
        $_SESSION['id_usu'] = $usuario['id_usu'];
        $_SESSION['nome_usu'] = $usuario['nome'];
        $_SESSION['tipo_usu'] = $usuario['tipo'];
        $_SESSION['email_usu'] = $usuario['email'];
        header("Location: ../perfil/perfil.php?status=already_verified");
        exit;
    }

    $codigo_verificacao = random_int(100000, 999999);
    $expira_em = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $sql_update = "UPDATE public.usuario 
                   SET codigo_verificacao = :codigo, 
                       codigo_expira_em = :expira 
                   WHERE id_usu = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        ':codigo' => $codigo_verificacao,
        ':expira' => $expira_em,
        ':id'     => $usuario['id_usu']
    ]);

    
    $mail = new PHPMailer(true);
    

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    
    $mail->Username   = 'skatelab.tcc@gmail.com'; 
    $mail->Password   = 'drti ujaa rsjg jdsm'; 
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';


    $mail->setFrom('skatelab.tcc@gmail.com', 'SkateLab'); 
    $mail->addAddress($usuario['email'], $usuario['nome']);

    $mail->isHTML(true);
    $mail->Subject = 'Seu Novo Código de Verificação - SkateLab';
    $mail->Body    = "Olá " . $usuario['nome'] . "! <br><br>Seu **novo** código de verificação é: <strong>$codigo_verificacao</strong>";
    $mail->AltBody = "Olá " . $usuario['nome'] . "! Seu novo código de verificação é: $codigo_verificacao";

    $mail->send();
    
    header("Location: ../verificar.php?email=" . urlencode($email) . "&status=resent");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao reenviar código (DB): " . $e->getMessage());
    header("Location: ../verificar.php?email=" . urlencode($email) . "&error=db_error");
    exit;
} catch (Exception $e) {
    error_log("Erro ao reenviar email (PHPMailer): " . $mail->ErrorInfo);
    header("Location: ../verificar.php?email=" . urlencode($email) . "&error=email_failed");
    exit;
}
?>