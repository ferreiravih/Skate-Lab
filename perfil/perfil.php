<?php
// 1. INICIA A SESSÃO (DEVE SER A PRIMEIRA COISA)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. VERIFICAÇÃO DE LOGIN
// Se 'id_usu' NÃO EXISTE na sessão, o usuário não está logado.
if (!isset($_SESSION['id_usu'])) {

    // 3. Redireciona para a home (onde está o login) com um erro
    header("Location: ../home/index.php?error=auth_required");
    exit; // Para a execução do script
}

// Se o script chegou até aqui, o usuário ESTÁ LOGADO.
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../perfil/perfil.css">
    <link rel="stylesheet" href="../global/global.css">
    <title>Perfil_SkateLab</title>
</head>

<body>

    <header>
        <nav>
            <?php include '../componentes/navbar.php'; ?>
        </nav>
    </header>


    <div class="main-container">
        <aside class="sidebar">
            <?php
            session_start();
            require_once __DIR__ . '/../config/db.php';

            // Proteção: Verifica se o usuário está logado
            if (!isset($_SESSION['id_usu'])) {
                header("Location: ../home/index.php?error=not_logged_in"); // Manda para o login se não estiver
                exit;
            }

            // Proteção: Verifica se o carrinho não está vazio
            if (empty($_SESSION['carrinho'])) {
                header("Location: ../carrinho/carrinho.php?error=empty_cart");
                exit;
            }
            // ...
            ?>
            <button class="menu-item active" data-target="perfil">Perfil</button>
            <button class="menu-item" data-target="favoritos">Favoritos</button>
            <button class="menu-item" data-target="historico">Histórico de Compras</button>
            <button class="menu-item" data-target="customizacoes">Customizações</button>
            <button class="menu-item" data-target="pagamentos">Pagamentos</button>
            <button class="menu-item" data-target="notificacoes">Notificações</button>
        </aside>

        <main class="content">

            <section id="perfil" class="section active">
                <div class="profile-container">
                    <!-- Foto de Perfil -->
                    <div class="profile-photo-section">
                        <div class="photo-container">
                            <img src="../assets/avatar-default.png" alt="Foto de Perfil" id="profile-picture" class="profile-picture">
                            <div class="photo-overlay">
                                <input type="file" id="photo-upload" accept="image/*" style="display: none;" onchange="handlePhotoUpload(event)">
                                <button type="button" class="upload-btn" onclick="document.getElementById('photo-upload').click()">Alterar Foto</button>
                            </div>
                        </div>
                        <div class="photo-info">
                            <h3 id="username-display">sk8_joao</h3>
                            <p>Membro desde Jan 2024</p>
                            <button type="button" class="edit-btn" onclick="toggleEditMode()">Editar Perfil</button>
                        </div>
                    </div>

                    <!-- Formulário de Dados -->
                    <form class="profile-form" id="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="username">Nome de Usuário *</label>
                                <input type="text" id="username" name="username" value="sk8_joao" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" value="joao.skate@email.com" required readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nome_completo">Nome Completo *</label>
                                <input type="text" id="nome_completo" name="nome_completo" value="João Silva Santos" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="data_nascimento">Data de Nascimento *</label>
                                <input type="date" id="data_nascimento" name="data_nascimento" value="1998-05-15" required readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="telefone">Telefone *</label>
                                <input type="tel" id="telefone" name="telefone" value="(11) 98765-4321" required readonly>
                            </div>
                        </div>

                        <div class="address-section">
                            <h3>Endereço</h3>
                            <div class="form-row">
                                <div class="form-group large">
                                    <label for="rua">Rua *</label>
                                    <input type="text" id="rua" name="rua" value="Rua das Flores" required readonly>
                                </div>
                                <div class="form-group small">
                                    <label for="numero">Número *</label>
                                    <input type="text" id="numero" name="numero" value="123" required readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="bairro">Bairro *</label>
                                    <input type="text" id="bairro" name="bairro" value="Centro" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="cidade">Cidade *</label>
                                    <input type="text" id="cidade" name="cidade" value="São Paulo" required readonly>
                                </div>
                                <div class="form-group small">
                                    <label for="estado">Estado *</label>
                                    <input type="text" id="estado" name="estado" value="SP" required readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cep">CEP *</label>
                                    <input type="text" id="cep" name="cep" value="01234-567" required readonly>
                                </div>
                                <div class="form-group large">
                                    <label for="complemento">Complemento</label>
                                    <input type="text" id="complemento" name="complemento" value="Apto 45" readonly>
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


            <section id="favoritos" class="section">
                <h2>Favoritados</h2>
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

        </main>
    </div>

    <script src="perfil.js"></script>
</body>


<footer>
    <?php include '../componentes/footer.php'; ?>
</footer>


</body>

</html>