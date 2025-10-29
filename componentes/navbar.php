<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skatelab</title>

    <link rel="stylesheet" href="../componentes/nav.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="../../Skate-Lab/home/home.php">
                <h1>SKATELAB</h1>
            </a>
        </div>
        <nav class="menu">
            <a href="../skateshop/skateee.php">SkateShop</a>
            <a href="../select/select.php">Customizar</a>
        </nav>
        <div class="icones1">
            <a><i class="fa-regular fa-user user-icon" id="userIcon"></i></a>
      <!--  Sidebar de LOGIN -->
      <div id="sidebarLogin" class="sidebar">
        <h2>Login</h2>
        <form action="../auth/login.php" method="POST">
          <label>Email</label> <input type="email" name="email" placeholder="Digite seu email" required />
          <label>Senha</label>
          <input type="password" name="senha" placeholder="Digite sua senha" required />
          <button type="submit">Entrar</button>
        </form>

        <button type="button" id="continuarcomemail">Continuar com E-mail</button>
        <button type="button" id="abrirCadastro">Faça seu cadastro</button>
      </div>

      <!-- Sidebar de CADASTRO -->
      <div id="sidebarCadastro" class="sidebar">
        <h2>Cadastro</h2>
        <form>
          <label>Nome completo</label>
          <input type="text" placeholder="Digite seu nome" required />

          <label>Email</label>
          <input type="email" placeholder="Digite seu email" required />

          <label>Usuário</label>
          <input type="text" placeholder="Crie um nome de usuário" required />

          <label>Senha</label>
          <input type="password" placeholder="Crie sua senha" required />

          <button type="submit">Cadastrar</button>
          <button type="button" id="voltarLogin">Voltar ao login</button>
        </form>
      </div>
            <a><i class="fa-regular fa-heart"></i></a>
            <div class="carrinho1">
                <a href="../carrinho/carrinho.php"><i class="fa-solid fa-cart-shopping"></i></a>
                <span class="itenscarrinho1">
                    <?= isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0 ?>
                </span>
            </div>
        </div>
    </header>

    <script src="../componentes/nav.js"></script>
</body>

</html>