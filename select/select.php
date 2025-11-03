<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estilos de Skate</title>
  <link rel="stylesheet" href="../../Skate-Lab/select/select.css">
</head>
<body>
    <?php include '../componentes/navbar.php'; ?>
  <section class="skate-section">
    <h1>DESCUBRA SEU ESTILO DE SKATE</h1>
    <div class="underline"></div>

    <div class="skate-container">
      <div class="skate-card">
        <img src="../../Skate-Lab/img/imgs-select/long.png" alt="Longboard">
        <h2>Longboard</h2>
        <p>O Long é o modelo ideal para descidas em alta velocidade.</p>
        <button type="button">Selecionar</button>
      </div>

      <div class="skate-card">
        <img src="../../Skate-Lab/img/imgs-select/street.png" alt="Street">
        <h2>Street</h2>
        <p>Usado em diversas modalidades, principalmente em manobras de rua.</p>
        <button onclick="window.location.href='../../Skate-Lab/customizacao/custom.php'" type="button">Selecionar</button>
      </div>

      <div class="skate-card">
        <img src="../../Skate-Lab/img/imgs-select/cruiser.png" alt="Cruiser">
        <h2>Cruiser</h2>
        <p>Ideal para passeios urbanos e deslocamentos curtos.</p>
        <button type="button">Selecionar</button>
      </div>
    </div>
  </section>
    <?php include '../componentes/footer.php'; ?>
</body>
</html>
