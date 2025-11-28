<?php

require_once __DIR__ . '/config/db.php';


$email_admin = 'admin@skatelab.com';
$senha_admin_plana = 'admin1234'; 
$nome_admin = 'Admin Skatelab';


$hash_senha = password_hash($senha_admin_plana, PASSWORD_DEFAULT);

echo "<h1>Criando Admin...</h1>";
echo "Email: " . $email_admin . "<br>";
echo "Senha Plana: " . $senha_admin_plana . "<br>";
echo "Hash Gerado: " . $hash_senha . "<br><hr>";

try {

    $sql_check = "SELECT id_usu FROM public.usuario WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':email' => $email_admin]);
    $usuario_existente = $stmt_check->fetch();

    if ($usuario_existente) {

        echo "Usuário encontrado. Atualizando para admin e resetando a senha...<br>";
        $sql_update = "UPDATE public.usuario SET senha = :senha, tipo = 'admin' WHERE id_usu = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':senha' => $hash_senha,
            ':id' => $usuario_existente['id_usu']
        ]);
        echo "<h2 style='color: green;'>SUCESSO! Senha do admin atualizada.</h2>";

    } else {

        echo "Usuário não encontrado. Criando novo admin...<br>";
        $sql_insert = "INSERT INTO public.usuario (nome, email, senha, tipo) 
                       VALUES (:nome, :email, :senha, 'admin')";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            ':nome' => $nome_admin,
            ':email' => $email_admin,
            ':senha' => $hash_senha
        ]);
        echo "<h2 style='color: green;'>SUCESSO! Novo admin criado.</h2>";
    }

    echo "<hr><strong>Agora tente logar em /home/index.php com:</strong><br>";
    echo "<strong>Email:</strong> " . $email_admin . "<br>";
    echo "<strong>Senha:</strong> " . $senha_admin_plana . "<br>";
    echo "<p style='color: red;'>POR SEGURANÇA, DELETE ESTE ARQUIVO (criar_admin.php) AGORA!</p>";


} catch (PDOException $e) {
    echo "<h2 style='color: red;'>ERRO NO BANCO DE DADOS:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>