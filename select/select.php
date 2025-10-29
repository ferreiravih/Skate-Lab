<?php

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../select/select.css">
    <link rel="stylesheet" href="../global/global.css">
    <title>Select_SkateLab</title>
</head>

<body>

    <header>
        <nav>
            <?php include '../componentes/navbar.php'; ?>
        </nav>
    </header>

    <section class="estilo-skate">
        <h2>DESCUBRA SEU ESTILO DE SKATE</h2>
        <div class="cards">

            <!-- LONG -->
            <button class="card" onclick="irPara('long.php')">
                <div class="imagem">
                    <img src="../img/imgs-select/long.png" alt="Longboard">
                </div>
                <h3>LONG</h3>
                <p>O Long é o modelo ideal para descidas em alta velocidade.</p>
            </button>

            <!-- STREET -->
            <button class="card" onclick="irPara('../customizacao/custom.php')">
                <div class="imagem">
                    <img src="../img/imgs-select/street.png" alt="Street">
                </div>
                <h3>STREET</h3>
                <p>Usado em diversas modalidades, principalmente em manobras na rua.</p>
            </button>

            <!-- CRUISER -->
            <button class="card" onclick="irPara('cruiser.php')">
                <div class="imagem">
                    <img src="../img/imgs-select/cruiser.png" alt="Cruiser">
                </div>
                <h3>CRUISER</h3>
                <p>Usado em diversas modalidades, principalmente em manobras na rua.</p>
            </button>
        </div>
    </section>

    <script src="select.js"></script>

    <?php include '../componentes/footer.php'; ?>
</body>
</html>
