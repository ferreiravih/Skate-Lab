<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$id_usu = $_SESSION['id_usu'];
$nome = trim($_POST['nome'] ?? '');
$apelido = trim($_POST['apelido'] ?? ''); 
$tell = trim($_POST['tell'] ?? ''); 
$data_nascimento = trim($_POST['data_nascimento'] ?? ''); 

// --- Validações ---
if (empty($nome)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'O campo "Nome completo" é obrigatório.']);
    exit;
}
if (strlen($apelido) > 50) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'O apelido deve ter no máximo 50 caracteres.']);
    exit;
}

$tell_limpo = preg_replace('/\D/', '', $tell);
if (strlen($tell_limpo) > 11) $tell_limpo = substr($tell_limpo, 0, 11);

$data_nascimento_db = null; 
if (!empty($data_nascimento)) {
    $dataObj = DateTime::createFromFormat('d/m/Y', $data_nascimento);
    if ($dataObj && $dataObj->format('d/m/Y') === $data_nascimento) {
        $data_nascimento_db = $dataObj->format('Y-m-d'); 
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Data de nascimento inválida.']);
        exit;
    }
}
$apelido_db = empty($apelido) ? null : $apelido;

// --- Upload da Foto ---
$caminho_foto_db = null; 
$novaUrlFotoFrontend = null; 

if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $arquivo = $_FILES['foto_perfil'];
    
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extensao, $extensoesPermitidas)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Formato de imagem inválido (use JPG, PNG ou WEBP).']);
        exit;
    }


    $diretorioDestino = __DIR__ . '/../../img/usuarios/';
    if (!is_dir($diretorioDestino)) {
        mkdir($diretorioDestino, 0777, true);
    }

    $novoNomeArquivo = $id_usu . '_' . time() . '.' . $extensao;
    $caminhoCompleto = $diretorioDestino . $novoNomeArquivo;

    if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
        $caminho_foto_db = "../img/usuarios/" . $novoNomeArquivo;
        $novaUrlFotoFrontend = $caminho_foto_db;
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar a imagem no servidor.']);
        exit;
    }
}

// --- atualização no banco ---
try {
    if ($caminho_foto_db) {
        $sql = "UPDATE public.usuario SET 
                    nome = :nome, 
                    apelido = :apelido,
                    tell = :tell,
                    data_nascimento = :data_nascimento,
                    url_perfil = :url_perfil 
                WHERE id_usu = :id_usu";
        $params = [
            ':nome' => $nome,
            ':apelido' => $apelido_db, 
            ':tell' => $tell_limpo,
            ':data_nascimento' => $data_nascimento_db, 
            ':url_perfil' => $caminho_foto_db,
            ':id_usu' => $id_usu
        ];
    } else {
        $sql = "UPDATE public.usuario SET 
                    nome = :nome, 
                    apelido = :apelido,
                    tell = :tell,
                    data_nascimento = :data_nascimento
                WHERE id_usu = :id_usu";
        $params = [
            ':nome' => $nome,
            ':apelido' => $apelido_db, 
            ':tell' => $tell_limpo,
            ':data_nascimento' => $data_nascimento_db, 
            ':id_usu' => $id_usu
        ];
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // atualiza nome na sessão
    $nome_display = !empty($apelido_db) ? $apelido_db : $nome;
    $_SESSION['nome_usu'] = $nome_display;

    // --- atualiza a foto na sessão se houve upload ---
    if ($caminho_foto_db) {
        $_SESSION['url_perfil'] = $caminho_foto_db;
    }

    echo json_encode([
        'sucesso' => true, 
        'mensagem' => 'Perfil atualizado com sucesso!',
        'novoNomeDisplay' => $nome_display,
        'novaUrlFoto' => $novaUrlFotoFrontend 
    ]);
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar perfil: " . $e->getMessage());
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados.']);
    exit;
}
?>