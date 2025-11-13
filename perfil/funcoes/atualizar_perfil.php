<?php
// 1. Inicia a sessão e verifica o login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

// 2. Verifica se é um método POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit;
}

// 3. Inclui o BD
require_once __DIR__ . '/../../config/db.php';

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// 4. Coleta e valida os dados (DO FORMULÁRIO DE PERFIL)
$id_usu = $_SESSION['id_usu'];
$nome = trim($_POST['nome'] ?? '');
$apelido = trim($_POST['apelido'] ?? ''); 
$tell = trim($_POST['tell'] ?? ''); 
$data_nascimento = trim($_POST['data_nascimento'] ?? ''); 

// Validação
if (empty($nome)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'O campo "Nome completo" é obrigatório.']);
    exit;
}
if (strlen($apelido) > 50) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'O apelido deve ter no máximo 50 caracteres.']);
    exit;
}

// Limpa a máscara do telefone
$tell_limpo = preg_replace('/\D/', '', $tell);
if (strlen($tell_limpo) > 11) {
    $tell_limpo = substr($tell_limpo, 0, 11);
}

// Valida e formata a Data de Nascimento (de DD/MM/AAAA para AAAA-MM-DD)
$data_nascimento_db = null; 
if (!empty($data_nascimento)) {
    $dataObj = DateTime::createFromFormat('d/m/Y', $data_nascimento);
    if ($dataObj && $dataObj->format('d/m/Y') === $data_nascimento) {
        $data_nascimento_db = $dataObj->format('Y-m-d'); 
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Data de nascimento inválida. Use o formato DD/MM/AAAA.']);
        exit;
    }
}

// Se o apelido for salvo como vazio, trata como NULL
$apelido_db = empty($apelido) ? null : $apelido;

// 5. Atualiza o banco de dados
try {
    $sql = "UPDATE public.usuario SET 
                nome = :nome, 
                apelido = :apelido,
                tell = :tell,
                data_nascimento = :data_nascimento
            WHERE id_usu = :id_usu";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':apelido' => $apelido_db, 
        ':tell' => $tell_limpo,
        ':data_nascimento' => $data_nascimento_db, 
        ':id_usu' => $id_usu
    ]);

    // 6. Atualiza o nome de exibição na SESSÃO
    $nome_display = !empty($apelido_db) ? $apelido_db : $nome;
    $_SESSION['nome_usu'] = $nome_display;

    // 7. Retorna sucesso
    echo json_encode([
        'sucesso' => true, 
        'mensagem' => 'Perfil atualizado com sucesso!',
        'novoNomeDisplay' => $nome_display 
    ]);
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar perfil: " . $e->getMessage());
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados. Tente novamente.']);
    exit;
}
?>
