<?php
// Inicia a sessão e conecta ao banco
session_start();
require_once '../conexao.php';

// Busca dados do usuário logado (com o endereço mais recente, se existir)
// Helpers
function carregarUsuarioComEndereco(PDO $pdo, int $id): ?array {
  $q = $pdo->prepare(
    'SELECT 
       u.id_usu,
       u.nome,
       u.email,
       u.nome_completo,
       u.data_nascimento,
       u.tell AS telefone,
       u.foto_perfil,
       u.criado_em,
       e.rua,
       e.numero,
       e.complemento,
       e.bairro,
       e.cidade,
       e.estado,
       e.cep
     FROM usuario u
     LEFT JOIN enderecos e ON e.id_usu = u.id_usu
     WHERE u.id_usu = :id
     ORDER BY e.id_end DESC
     LIMIT 1'
  );
  $q->bindValue(':id', $id, PDO::PARAM_INT);
  $q->execute();
  $row = $q->fetch(PDO::FETCH_ASSOC);
  return $row ?: null;
}

$usuario = null;
if (isset($_SESSION['usuario_id'])) {
  try {
    $usuario = carregarUsuarioComEndereco($pdo, (int)$_SESSION['usuario_id']);
  } catch (Exception $e) {
    // logar em prod
  }
}

// Redireciona se não autenticado ou usuário não encontrado
if (!isset($_SESSION['usuario_id']) || !$usuario) {
  header('Location: ../auth/login.php');
  exit;
}

// Processa envio do formulário
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $id = (int)$_SESSION['usuario_id'];
    $nome = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
    $telefone = trim($_POST['telefone'] ?? '');

    $rua = trim($_POST['rua'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $cep = trim($_POST['cep'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');

    // Atualiza usuario
    $updU = $pdo->prepare('UPDATE usuario SET nome=:nome, nome_completo=:nome_completo, email=:email, tell=:tell, data_nascimento=:data_nascimento WHERE id_usu=:id');
    $updU->execute([
      ':nome' => $nome,
      ':nome_completo' => $nome_completo ?: null,
      ':email' => $email,
      ':tell' => $telefone ?: null,
      ':data_nascimento' => $data_nascimento ?: null,
      ':id' => $id,
    ]);

    // Verifica endereço existente
    $selE = $pdo->prepare('SELECT id_end FROM enderecos WHERE id_usu=:id ORDER BY id_end DESC LIMIT 1');
    $selE->execute([':id' => $id]);
    $end = $selE->fetch(PDO::FETCH_ASSOC);

    if ($end) {
      $updE = $pdo->prepare('UPDATE enderecos SET rua=:rua, numero=:numero, bairro=:bairro, cidade=:cidade, estado=:estado, cep=:cep, complemento=:complemento WHERE id_end=:id_end');
      $updE->execute([
        ':rua' => $rua,
        ':numero' => $numero,
        ':bairro' => $bairro,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':cep' => $cep,
        ':complemento' => $complemento ?: null,
        ':id_end' => (int)$end['id_end'],
      ]);
    } else {
      $insE = $pdo->prepare('INSERT INTO enderecos (id_usu, rua, numero, complemento, bairro, cidade, estado, cep) VALUES (:id, :rua, :numero, :complemento, :bairro, :cidade, :estado, :cep)');
      $insE->execute([
        ':id' => $id,
        ':rua' => $rua,
        ':numero' => $numero,
        ':complemento' => $complemento ?: null,
        ':bairro' => $bairro,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':cep' => $cep,
      ]);
    }

    $mensagem = 'Perfil atualizado com sucesso!';
    // Recarrega dados
    $usuario = carregarUsuarioComEndereco($pdo, $id);
  } catch (PDOException $e) {
    $mensagem = 'Erro ao salvar: ' . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil_SkateLab</title>
  <link rel="stylesheet" href="../perfil/perfil.css">
  <link rel="stylesheet" href="../global/global.css">
</head>

<body>

<header>
  <nav>
    <?php include '../componentes/navbar.php'; ?>
  </nav>
</header>

<main class="main-container">

  <!-- Sidebar sempre visível -->
  <aside class="sidebar">
    <button class="menu-item active" data-target="perfil">Perfil</button>
    <button class="menu-item" data-target="favoritos">Favoritos</button>
    <button class="menu-item" data-target="historico">Histórico de Compras</button>
    <button class="menu-item" data-target="customizacoes">Customizações</button>
    <button class="menu-item" data-target="pagamentos">Pagamentos</button>
    <button class="menu-item" data-target="notificacoes">Notificações</button>
  </aside>

  <!-- Conteúdo ao lado -->
  <section class="content">

    <!-- PERFIL -->
    <section id="perfil" class="section active">
      <div class="profile-container">
        <!-- Foto de Perfil -->
        <div class="profile-photo-section">
          <div class="photo-container">
            <?php 
              $fotoPerfil = !empty($usuario['foto_perfil']) ? htmlspecialchars($usuario['foto_perfil']) : '../assets/avatar-default.png';
            ?>
            <img src="<?= $fotoPerfil ?>" alt="Foto de Perfil" id="profile-picture" class="profile-picture">
            <div class="photo-overlay">
              <input type="file" id="photo-upload" accept="image/*" style="display: none;" onchange="handlePhotoUpload(event)">
              <button type="button" class="upload-btn" onclick="document.getElementById('photo-upload').click()">Alterar Foto</button>
            </div>
          </div>
          <div class="photo-info">
            <h3 id="username-display"><?= htmlspecialchars($usuario['nome'] ?? 'Usuário') ?></h3>
            <?php $membroDesde = !empty($usuario['criado_em']) ? ('Membro desde ' . date('m/Y', strtotime($usuario['criado_em']))) : 'Membro desde'; ?>
            <p><?= htmlspecialchars($membroDesde) ?></p>
            <button type="button" class="edit-btn" onclick="toggleEditMode()">Editar Perfil</button>
          </div>
        </div>

        <!-- Formulário de Dados -->
        <?php if (!empty($mensagem)): ?>
          <div class="alert" style="padding:10px;border-radius:6px;margin-bottom:12px;background:#e8d6ff;color:#4b0082;border:1px solid #b38cff;">
            <?= htmlspecialchars($mensagem) ?>
          </div>
        <?php endif; ?>

        <form class="profile-form" id="profile-form" method="POST" action="">
          <div class="form-row">
            <div class="form-group">
              <label for="username">Nome de Usuário *</label>
              <input type="text" id="username" name="username" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required readonly>
            </div>
            <div class="form-group">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="nome_completo">Nome Completo *</label>
              <input type="text" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($usuario['nome_completo'] ?? '') ?>" required readonly>
            </div>
            <div class="form-group">
              <label for="data_nascimento">Data de Nascimento *</label>
              <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($usuario['data_nascimento'] ?? '') ?>" required readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="telefone">Telefone *</label>
              <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>" required readonly>
            </div>
          </div>

          <div class="address-section">
            <h3>Endereço</h3>
            <div class="form-row">
              <div class="form-group large">
                <label for="rua">Rua *</label>
                <input type="text" id="rua" name="rua" value="<?= htmlspecialchars($usuario['rua'] ?? '') ?>" required readonly>
              </div>
              <div class="form-group small">
                <label for="numero">Número *</label>
                <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($usuario['numero'] ?? '') ?>" required readonly>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="bairro">Bairro *</label>
                <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($usuario['bairro'] ?? '') ?>" required readonly>
              </div>
              <div class="form-group">
                <label for="cidade">Cidade *</label>
                <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($usuario['cidade'] ?? '') ?>" required readonly>
              </div>
              <div class="form-group small">
                <label for="estado">Estado *</label>
                <input type="text" id="estado" name="estado" value="<?= htmlspecialchars($usuario['estado'] ?? '') ?>" required readonly>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="cep">CEP *</label>
                <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($usuario['cep'] ?? '') ?>" required readonly>
              </div>
              <div class="form-group large">
                <label for="complemento">Complemento</label>
                <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($usuario['complemento'] ?? '') ?>" readonly>
              </div>
            </div>
          </div>

          <div class="form-actions" id="form-actions" style="display: none;">
            <button type="button" class="cancel-btn" onclick="toggleEditMode()">Cancelar</button>
            <button type="submit" class="save-btn">Salvar Alterações</button>
          </div>
        </form>
      </div>
    </section>

    <!-- OUTRAS SEÇÕES -->
    <section id="favoritos" class="section">
      <h2>Favoritos</h2>
      <p>Itens favoritados</p>
    </section>

    <section id="historico" class="section">
      <h2>Histórico de Compras</h2>
    </section>

    <section id="customizacoes" class="section">
      <h2>Customizações</h2>
    </section>

    <section id="pagamentos" class="section">
      <h2>Pagamentos</h2>
    </section>

    <section id="notificacoes" class="section">
      <h2>Notificações</h2>
    </section>

  </section><!-- Fim do content -->
</main>

<footer>
  <?php include '../componentes/footer.php'; ?>
</footer>

<script src="perfil.js"></script>
</body>
</html>
