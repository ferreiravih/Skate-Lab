<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../auth/login.css">
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <title>Document</title>
</head>

<body>
    <?php include '../componentes/navbar.php'; ?>
    <main>
        <div class="containerlogin">
            <h3>CRIAR CONTA</h3>
            <p>Crie sua conta gratuitamente</p>
            <div>
                <form action="" method="POST" class="form">
                    <label for="email"> Email:</label>
                    <input type="email" name="email" id="email" min="7" max="100" placeholder="Digite seu email:" class="inputcss"> <br>

                    <label for="email">senha:</label>
                    <input type="password" name="email" id="email" min="6" max="100" placeholder="Digite uma senha:" class="inputcss"> <br>

                    <label for="email">confirme sua senha:</label>
                    <input type="password" name="email" id="email" min="6" max="100" placeholder="Confirme sua senha:" class="inputconfirmarsenha"> <br>

                    <input type="button" value="criar conta" class="formbotao">
                </form>
            </div>
            <div class="linhaou">
                <span>OU</span>
            </div>

            <h5>Ja tem uma conta?<a href="../auth/login.php"> Login<a></a></h5>
        </div>
    </main>
</body>

</html>