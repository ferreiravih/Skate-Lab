<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>skatelab</title>
    <link rel="stylesheet" href="../componentes/nav.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <header class="header">
        <div class="logo">
            <h1>SKATELAB</h1>
        </div>
        <nav class="menu">
            <a href="../skateshop/skateee.php">SkateShop</a>
            <a href="#">Customizar</a>
        </nav>
        <div class="icones1">
            <i class="fa-regular fa-user user-icon" id="userIcon"></i>
                <div id="sidebar" class="sidebar">
                    <h2>Login</h2>
                    <form>
                        <label>Usuário</label>
                        <input type="text" placeholder="Digite seu usuário" />
                        <label>Senha</label>
                        <input type="password" placeholder="Digite sua senha" />
                        <button type="submit">Entrar</button>
                    </form>
                    <button id="editarPerfil">Continuar com E-mail </button>
                    <br>
                    <button id="sair">Sair</button>
                </div>
            <a><i class="fa-regular fa-heart"></i></a>
            <div class="carrinho1">
                <a href="../carrinho/carrinho.php"><i class="fa-solid fa-cart-shopping"></i></a>
                <span class="itenscarrinho1">3</span>
            </div>
        </div>
        
    </header>
    <script src="../componentes/nav.js"></script>
</body>
</html>