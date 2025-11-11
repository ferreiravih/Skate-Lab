<?php
// 1. INICIA A SESSÃO E VERIFICA O LOGIN
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../home/index.php?error=auth_required");
    exit;
}

// 2. INCLUI O BD
require_once __DIR__ . '/../config/db.php';

// 3. BUSCA OS DADOS DO USUÁRIO
$id_usuario_logado = $_SESSION['id_usu'];
$usuario = null;
$pedidos = [];
$endereco_recente = null; // Inicializa a variável
$erro_db = null; // Inicializa o erro como nulo

try {
    // Busca dados do usuário (Consulta CORRETA, sem colunas de endereço)
    $sql_usu = "SELECT nome, email, tell, data_nascimento, apelido 
                FROM public.usuario WHERE id_usu = :id";
    $stmt_usu = $pdo->prepare($sql_usu);
    $stmt_usu->execute([':id' => $id_usuario_logado]);
    $usuario = $stmt_usu->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        session_unset(); session_destroy();
        header("Location: ../home/index.php?error=user_not_found");
        exit;
    }

    // Busca o endereço do último pedido (como fallback)
    $stmt_endereco_fallback = $pdo->prepare(
        "SELECT endereco_rua, endereco_numero, endereco_bairro, endereco_cidade, endereco_estado, endereco_cep, endereco_complemento
         FROM public.pedidos
         WHERE id_usu = :id AND endereco_rua IS NOT NULL
         ORDER BY pedido_em DESC LIMIT 1"
    );
    $stmt_endereco_fallback->execute([':id' => $id_usuario_logado]);
    $endereco_recente = $stmt_endereco_fallback->fetch(PDO::FETCH_ASSOC);
    
    // Busca os 5 últimos pedidos (para o histórico)
    $stmt_pedidos = $pdo->prepare(
        "SELECT id_pedido, status, valor_total, pedido_em 
         FROM public.pedidos 
         WHERE id_usu = :id
         ORDER BY pedido_em DESC LIMIT 5"
    );
    $stmt_pedidos->execute([':id' => $id_usuario_logado]);
    $pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao buscar dados do perfil: " . $e->getMessage());
    $erro_db = "Não foi possível carregar os dados do perfil."; // Define a mensagem de erro
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../perfil/perfil.css?v=1.11"> 
    <link rel="stylesheet" href="../global/global.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <title>Meu Perfil - SkateLab</title>
</head>
<body>

    <div class="page-container">
        
        <header>
            <nav>
                <?php include '../componentes/navbar.php'; ?>
            </nav>
        </header>

        <div class="main-container">
            
            <aside class="profile-sidebar"> 
                <a href="../perfil/perfil.php" class="menu-item active">
                    <i class="ri-user-line"></i> Perfil
                </a>
                <a href="#" class="menu-item disabled">
                    <i class="ri-heart-line"></i> Favoritos
                </a>
                
                <a href="../perfil/customizacoes.php" class="menu-item">
                    <i class="ri-equalizer-line"></i> Customizações
                </a>
                <a href="../carrinho/carrinho.php" class="menu-item">
                    <i class="ri-shopping-cart-line"></i> Carrinho
                </a>
                <a href="../auth/logout.php" class="menu-item sair">
                    <i class="ri-logout-box-r-line"></i> Sair
                </a>
            </aside>

            <main class="profile-content">

                <div id="feedback-message" class="feedback" style="display: none;"></div>
                
                <?php if ($erro_db): ?>
                    <div class="feedback error" style="display: block;"><?php echo htmlspecialchars($erro_db); ?></div>
                
                <?php else: // Se não houve erro, renderiza todo o perfil ?>

                <div class="profile-grid">

                    <div class="card profile-header-card">
                        <div class="photo-container">
                            <img src="../img/imgs-home/home-passo3.png" alt="Foto de Perfil" id="profile-picture" class="profile-picture">
                            <input type="file" id="photo-upload" accept="image/*" style="display: none;">
                            <div class="photo-overlay" id="photo-overlay-btn">
                                <i class="ri-camera-line"></i>
                                <span>Alterar Foto</span>
                            </div>
                        </div>
                        <div class="profile-header-info">
                            <h2 id="username-display"><?php echo htmlspecialchars($usuario['apelido'] ? $usuario['apelido'] : $usuario['nome']); ?></h2>
                            <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                            <button type="button" class="btn-edit" id="edit-btn">
                                <i class="ri-pencil-line"></i> Editar informações
                            </button>
                        </div>
                    </div>

                    <form id="profile-form" class="card info-card" method="POST">
                        <h3>Informações Pessoais</h3>
                        
                        <div class="info-item">
                            <label for="nome">Nome completo</label>
                            <span class="info-text"><?php echo htmlspecialchars($usuario['nome']); ?></span>
                            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required readonly>
                        </div>
                        <div class="info-item">
                            <label for="apelido">Apelido (opcional)</label>
                            <span class="info-text"><?php echo htmlspecialchars($usuario['apelido'] ? $usuario['apelido'] : 'Não cadastrado'); ?></span>
                            <input type="text" id="apelido" name="apelido" value="<?php echo htmlspecialchars($usuario['apelido'] ?? ''); ?>" placeholder="Como você quer ser chamado" readonly>
                        </div>
                        <div class="info-item">
                            <label for="email">E-mail</label>
                            <span class="info-text"><?php echo htmlspecialchars($usuario['email']); ?></span>
                        </div>
                        <div class="info-item">
                            <label for="tell">Telefone</label>
                            <?php $telefone_formatado = $usuario['tell'] ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $usuario['tell']) : 'Não cadastrado'; ?>
                            <span class="info-text"><?php echo htmlspecialchars($telefone_formatado); ?></span>
                            <input type="tel" id="tell" name="tell" value="<?php echo htmlspecialchars($telefone_formatado); ?>" placeholder="(XX) XXXXX-XXXX" readonly>
                        </div>
                        <div class="info-item">
                            <label for="data_nascimento">Data de nascimento</label>
                            <?php $data_formatada = !empty($usuario['data_nascimento']) ? date('d/m/Y', strtotime($usuario['data_nascimento'])) : 'Não cadastrada'; ?>
                            <span class="info-text"><?php echo $data_formatada; ?></span>
                            <input type="text" id="data_nascimento" name="data_nascimento" value="<?php echo $data_formatada === 'Não cadastrada' ? '' : $data_formatada; ?>" placeholder="DD/MM/AAAA" readonly>
                        </div>

                        <div class="form-actions" id="form-actions">
                            <button type="button" class="btn-cancel" id="cancel-btn">Cancelar</button>
                            <button type="submit" class="btn-save" id="save-btn">Salvar Alterações</button>
                        </div>
                    </form>

                    <div class="card security-card">
                        <h3>Segurança e Acesso</h3>
                        <div class="info-item">
                            <label>Senha</label>
                            <span class="info-text password-blur">••••••••</span>
                            <button type="button" class="btn-link" id="btn-show-password-modal">Alterar Senha</button>
                        </div>
                    </div>

                    <div class="card address-card">
                        <h3>Endereço</h3>
                        
                        <?php if ($endereco_recente): // Se encontrou um endereço do último pedido ?>
                            <div class="address-card-full">
                                <div class="info-item">
                                    <label>Rua</label>
                                    <span class="info-text"><?php echo htmlspecialchars($endereco_recente['endereco_rua'] . ', ' . $endereco_recente['endereco_numero']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Bairro</label>
                                    <span class="info-text"><?php echo htmlspecialchars($endereco_recente['endereco_bairro']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Cidade / UF</label>
                                    <span class="info-text"><?php echo htmlspecialchars($endereco_recente['endereco_cidade'] . ' - ' . $endereco_recente['endereco_estado']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>CEP</label>
                                    <span class="info-text"><?php echo htmlspecialchars($endereco_recente['endereco_cep']); ?></span>
                                </div>
                                <small class="address-notice">Este é o endereço da sua última compra.</small>
                            </div>
                        <?php else: // Se não tem pedidos (ou pedidos com endereço) ?>
                            <div class="empty-state">
                                <i class="ri-map-pin-line"></i>
                                <p>Nenhum endereço cadastrado</p>
                                <button type="button" class="btn-add-new" disabled>Cadastrar Endereço</button>
                                <small>(Seu primeiro endereço será salvo após a compra)</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card payment-card card-empty">
                        <h3>Métodos de Pagamento</h3>
                        <div class="empty-state">
                            <i class="ri-bank-card-line"></i>
                            <p>Nenhum cartão cadastrado</p>
                            <button type="button" class="btn-add-new" disabled>Cadastrar Cartão</button>
                            <small>(Será solicitado na sua primeira compra)</small>
                        </div>
                    </div>

                    <div class="card history-card">
                        <h3>Histórico de Pedidos</h3>
                        <?php if (empty($pedidos)): ?>
                            <div class="empty-state compact">
                                <i class="ri-history-line"></i>
                                <p>Você ainda não fez nenhum pedido.</p>
                            </div>
                        <?php else: ?>
                            <ul class="order-list">
                                <?php foreach ($pedidos as $pedido): 
                                    $statusClass = strtolower(str_replace(' ', '', $pedido['status']));
                                ?>
                                    <button type="button" class="order-item-button" data-pedido-id="<?php echo $pedido['id_pedido']; ?>">
                                        <div class="order-info">
                                            <strong>Pedido #<?php echo $pedido['id_pedido']; ?></strong>
                                            <span><?php echo date('d/m/Y', strtotime($pedido['pedido_em'])); ?></span>
                                        </div>
                                        <div class="order-details">
                                            <strong>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></strong>
                                            <span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($pedido['status']); ?></span>
                                        </div>
                                    </button>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                </div> <?php endif; // Fim do 'else' que verifica o $erro_db ?>

            </main>
        </div> <footer>
            <?php include '../componentes/footer.php'; ?>
        </footer>
    </div> <div id="password-modal" class="modal-overlay">
        <div class="modal-content">
            <button type="button" class="modal-close-btn" id="password-modal-close-btn">&times;</button>
            <h3>Alterar Senha</h3>
            <div id="password-feedback-message" class="feedback" style="display: none;"></div>
            <p>Para sua segurança, informe sua senha atual antes de criar uma nova.</p>
            
            <form id="password-form" class="password-form" method="POST">
                <div class="form-group-col">
                    <label for="senha_atual">Senha Atual</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                <div class="form-group-col">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                </div>
                <div class="form-group-col">
                    <label for="confirmar_senha">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>
                
                <div class="form-actions-modal">
                    <button type="button" class="btn-cancel" id="password-modal-cancel-btn">Cancelar</button>
                    <button type="submit" class="btn-save" id="password-save-btn">Salvar Senha</button>
                </div>
            </form>
        </div>
    </div>

    <div id="pedido-detalhes-modal" class="modal-overlay">
        <div class="modal-content modal-lg">
            <button type="button" class="modal-close-btn" id="pedido-modal-close-btn">&times;</button>
            
            <div id="pedido-modal-content">
                <div class="pedido-modal-loading">
                    <i class="ri-loader-4-line ri-spin"></i>
                    <span>Carregando detalhes...</span>
                </div>
            </div>

            <div class="form-actions-modal">
                <button type="button" class="btn-cancel" id="pedido-modal-fechar-btn">Fechar</button>
            </div>
        </div>
    </div>

    <script src="perfil.js?v=1.10"></script> 
</body>
</html>
