<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__. '/../config/db.php';
require_once __DIR__. '/../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../home/index.php"); 
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');
$confirmar_senha = trim($_POST['confirmar_senha'] ?? '');

if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
    header("Location: ../home/index.php?error=register_empty");
    exit;
}
if ($senha !== $confirmar_senha) {
    header("Location: ../home/index.php?error=password_mismatch");
    exit;
}

$hash_senha = password_hash($senha, PASSWORD_DEFAULT);


$codigo_verificacao = random_int(100000, 999999);
$expira_em = date('Y-m-d H:i:s', strtotime('+10 minutes')); 

try {
    $sql_check = "SELECT id_usu FROM public.usuario WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':email' => $email]);
    
    if ($stmt_check->fetch()) {
        header("Location: ../home/index.php?error=email_exists");
        exit;
    }

    $sql_insert = "INSERT INTO public.usuario 
        (nome, email, senha, tipo, codigo_verificacao, codigo_expira_em, verificado) 
        VALUES 
        (:nome, :email, :senha, 'comum', :codigo, :expira, false)";
    
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $hash_senha,
        ':codigo' => $codigo_verificacao,
        ':expira' => $expira_em
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

    //Destinatários
    $mail->setFrom('seu-email@gmail.com', 'SkateLab'); 
    $mail->addAddress($email, $nome);

    //Conteúdo
    $mail->isHTML(true);
    $mail->Subject = 'Seu Código de Verificação - SkateLab';
    $mail->Body    = "Olá $nome! <br><br>Seu código de verificação é: <strong>$codigo_verificacao</strong>";
    $mail->AltBody = "Olá $nome! Seu código de verificação é: $codigo_verificacao";

    $mail->send();
    
    header("Location: ../verificar.php?email=" . urlencode($email));
    exit;

} catch (PDOException $e) {
    error_log("Erro de registro: " . $e->getMessage());
    header("Location: ../home/index.php?error=db_register");
    exit;
} catch (Exception $e) {
    error_log("Erro ao enviar email: " . $mail->ErrorInfo);
    header("Location: ../verificar.php?email=" . urlencode($email) . "&error=email_failed");
    exit;
}
?>