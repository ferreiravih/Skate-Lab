<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// 1. INCLUI O BD
require_once __DIR__ . '/../config/db.php';

try {
  $stmt_shapes = $pdo->prepare(
    "SELECT p.nome, p.preco, p.url_img, p.url_m3d
         FROM public.pecas p
         JOIN public.categorias c ON p.id_cat = c.id_cat
         WHERE c.nome = 'Shapes' AND p.status = 'ATIVO' AND p.url_m3d IS NOT NULL"
  );
  $stmt_shapes->execute();
  $shapes = $stmt_shapes->fetchAll(PDO::FETCH_ASSOC);

  // Busca Trucks
  $stmt_trucks = $pdo->prepare(
    "SELECT p.nome, p.preco, p.url_img, p.url_m3d
         FROM public.pecas p
         JOIN public.categorias c ON p.id_cat = c.id_cat
         WHERE c.nome = 'Trucks' AND p.status = 'ATIVO' AND p.url_m3d IS NOT NULL"
  );
  $stmt_trucks->execute();
  $trucks = $stmt_trucks->fetchAll(PDO::FETCH_ASSOC);

  // Busca Rodinhas
  $stmt_rodinhas = $pdo->prepare(
    "SELECT p.nome, p.preco, p.url_img, p.url_m3d
         FROM public.pecas p
         JOIN public.categorias c ON p.id_cat = c.id_cat
         WHERE c.nome = 'Rodas' AND p.status = 'ATIVO' AND p.url_m3d IS NOT NULL"
  );
  $stmt_rodinhas->execute();
  $rodinhas = $stmt_rodinhas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Erro ao buscar peças da customização: " . $e->getMessage());
  $erro_db = "Erro ao carregar peças. Verifique a conexão com o banco.";
}

// Peças Padrão
$shape_padrao = ['nome' => 'Shape Branco', 'preco' => 120.00, 'url_m3d' => 'white', 'url_img' => null];
$truck_padrao = ['nome' => 'Truck Padrão', 'preco' => 0.00, 'url_m3d' => 'padrao', 'url_img' => null];

$customizacaoInicial = null;
$customId = isset($_GET['custom_id']) ? (int)$_GET['custom_id'] : null;

if ($customId && $customId > 0) {
  try {
    $stmtCustomizacao = $pdo->prepare(
      "SELECT id_customizacao, id_usu, titulo, config
       FROM public.customizacoes
       WHERE id_customizacao = :id
       LIMIT 1"
    );
    $stmtCustomizacao->execute([':id' => $customId]);
    $customizacaoRow = $stmtCustomizacao->fetch(PDO::FETCH_ASSOC);
    if (
      $customizacaoRow &&
      isset($_SESSION['id_usu']) &&
      (int)$customizacaoRow['id_usu'] === (int)$_SESSION['id_usu']
    ) {
      $configDecoded = json_decode($customizacaoRow['config'], true);
      if (is_array($configDecoded)) {
        $customizacaoInicial = [
          'id' => (int)$customizacaoRow['id_customizacao'],
          'titulo' => $customizacaoRow['titulo'],
          'config' => $configDecoded,
        ];
      }
    }
  } catch (PDOException $e) {
    error_log('Erro ao carregar customização inicial: ' . $e->getMessage());
  }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>customização</title>
  <link rel="stylesheet" href="custom.css?v=1.11">
  <link rel="stylesheet" href="../componentes/nav.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php include '../componentes/navbar.php'; ?>
  <div class="custom-page">
    <div class="viewer-area">
      <div id="container"></div>

      <div class="vista-previa">
        <div class="status">
          <span id="configAtual">Carregando...</span>
        </div>
        <div class="mini-botoes">
          <button type="button" onclick="mudarVista('frontal')">Frente</button>
          <button type="button" onclick="mudarVista('lateral')">Lado</button>
          <button type="button" onclick="mudarVista('superior')">Cima</button>
        </div>
      </div>
    </div>

    <div class="controls-area">

      <form id="form-carrinho" action="../carrinho/contr/adicionar_carrinho.php" method="POST">

        <input type="hidden" id="cart-id" name="id" value="custom-skate">
        <input type="hidden" id="cart-nome" name="nome" value="Skate Customizado">
        <input type="hidden" id="cart-preco" name="preco" value="0">
        <input type="hidden" id="cart-imagem" name="imagem" value="../img/imgs-skateshop/image.png">
        <input type="hidden" id="cart-descricao" name="descricao" value="Peças customizadas">
        <input type="hidden" name="quantidade" value="1">
        <input type="hidden" name="redirect_to" value="carrinho">

        <div id="ui">
          <h2></h2>

          <div class="status" id="status">
            <?php if ($erro_db): ?>
              <span style="color: red;"><?php echo $erro_db; ?></span>
            <?php else: ?>
              📦 Sistema carregando...
            <?php endif; ?>
          </div>

          <div class="grupo-botoes">
            <h3 class="centro">Customização</h3>
            <button class="btn-titulo" type="button" onclick="toggleGrupo('shapeGrupo', this)">Shape</button>
            <div id="shapeGrupo" class="grupo-colapsado">

              <button class="pena active" type="button"
                onclick="mostrarShape('<?php echo $shape_padrao['url_m3d']; ?>')"
                data-price="<?php echo $shape_padrao['preco']; ?>"
                data-name="<?php echo htmlspecialchars($shape_padrao['nome']); ?>">
                <div class="btn-imagem" style="background-color:#ffffff;">O</div>
                <span class="nome-pena"><?php echo $shape_padrao['nome']; ?></span>
                <span class="preco-pena">R$ <?php echo number_format($shape_padrao['preco'], 2, ',', '.'); ?></span>
              </button>

              <?php foreach ($shapes as $shape): ?>
                <button class="pena" type="button"
                  onclick="mostrarShape('<?php echo htmlspecialchars($shape['url_m3d']); ?>')"
                  data-price="<?php echo $shape['preco']; ?>"
                  data-name="<?php echo htmlspecialchars($shape['nome']); ?>">
                  <img class="btn-imagem" src="<?php echo htmlspecialchars($shape['url_img']); ?>" alt="<?php echo htmlspecialchars($shape['nome']); ?>">
                  <span class="nome-pena"><?php echo htmlspecialchars($shape['nome']); ?></span>
                  <span class="preco-pena">R$ <?php echo number_format($shape['preco'], 2, ',', '.'); ?></span>
                </button>
              <?php endforeach; ?>
            </div>

            <button class="btn-titulo" type="button" onclick="toggleGrupo('truckGrupo', this)">Truck</button>
            <div id="truckGrupo" class="grupo-colapsado">

              <button class="pena active" type="button"
                onclick="selecionarTrucks('<?php echo $truck_padrao['url_m3d']; ?>')"
                data-price="<?php echo $truck_padrao['preco']; ?>"
                data-name="<?php echo htmlspecialchars($truck_padrao['nome']); ?>">
                <div class="btn-imagem" style="background-color:#ccc;">O</div>
                <span class="nome-pena"><?php echo $truck_padrao['nome']; ?></span>
                <span class="preco-pena">R$ <?php echo number_format($truck_padrao['preco'], 2, ',', '.'); ?></span>
              </button>

              <?php foreach ($trucks as $truck): ?>
                <button class="pena" type="button"
                  onclick="selecionarTrucks('<?php echo htmlspecialchars($truck['url_m3d']); ?>')"
                  data-price="<?php echo $truck['preco']; ?>"
                  data-name="<?php echo htmlspecialchars($truck['nome']); ?>">
                  <img class="btn-imagem" src="<?php echo htmlspecialchars($truck['url_img']); ?>" alt="<?php echo htmlspecialchars($truck['nome']); ?>">
                  <span class="nome-pena"><?php echo htmlspecialchars($truck['nome']); ?></span>
                  <span class="preco-pena">R$ <?php echo number_format($truck['preco'], 2, ',', '.'); ?></span>
                </button>
              <?php endforeach; ?>
            </div>

            <button class="btn-titulo" type="button" onclick="toggleGrupo('rodinhaGrupo', this)">Rodinha</button>
            <div id="rodinhaGrupo" class="grupo-colapsado">

              <?php foreach ($rodinhas as $rodinha): ?>
                <button class="pena" type="button"
                  onclick="mostrarRodinhas('<?php echo htmlspecialchars($rodinha['url_m3d']); ?>')"
                  data-price="<?php echo $rodinha['preco']; ?>"
                  data-name="<?php echo htmlspecialchars($rodinha['nome']); ?>">
                  <img class="btn-imagem" src="<?php echo htmlspecialchars($rodinha['url_img']); ?>" alt="<?php echo htmlspecialchars($rodinha['nome']); ?>">
                  <span class="nome-pena"><?php echo htmlspecialchars($rodinha['nome']); ?></span>
                  <span class="preco-pena">R$ <?php echo number_format($rodinha['preco'], 2, ',', '.'); ?></span>
                </button>
              <?php endforeach; ?>
            </div>

            <h3> Mostrar/Ocultar</h3>
            <button id="btnShape" type="button" onclick="toggleShape()">Ocultar shape</button>
            <button id="btnTrucks" type="button" onclick="toggleTrucks()">Ocultar trucks</button>
            <button id="btnRodinhas" type="button" onclick="toggleRodinhas()">Ocultar rodinhas</button>
          </div>

          <div class="grupo-botoes">
            <h3>ROLAMENTOS</h3>
            <button type="button" onclick="selecionarRolamentos('padrao')" data-price="30" data-name="Padrão">
              <div class="btn-imagem"></div>
              Rolamento padrão (4x)
            </button>
            <button type="button" onclick="removerRolamentos()">
              <div class="btn-imagem"></div>
              Remover rolamentos
            </button>
          </div>

          <div class="grupo-botoes">
            <h3>PARAFUSOS</h3>
            <button type="button" onclick="selecionarParafusos('padrao')" data-price="15" data-name="Padrão">
              <div class="btn-imagem"></div>
              Parafuso padrão (12x)
            </button>
            <button type="button" onclick="removerParafusos()">
              <div class="btn-imagem"></div>
              Remover parafusos
            </button>
          </div>

          <div class="grupo-botoes">
            <h3>CONTROLES</h3>
            <button type="button" id="salvarBtn" onclick="salvarConfiguracao()">SALVAR CONFIGURAÇÃO</button>
            <button type="button" id="exportarBtn" onclick="exportarImagem()">EXPORTAR IMAGEM</button>
            <button type="button" onclick="resetCamera()">Resetar Câmera</button>
          </div>
        </div>
        <div class="custom-checkout-area">
          <div class="preco-total-container">
            <span class="preco-label">Valor Total do Skate:</span>
            <span class="preco-valor" id="preco-total-display">R$ 0,00</span>
          </div>

          <button type="submit" class="btn-add-to-cart">
            <i class="fa-solid fa-cart-shopping"></i> Adicionar ao Carrinho
          </button>
        </div>

      </form>
    </div>>
  </div>
  <div id="salvar-overlay" class="salvar-modal-overlay" style="display: none;">
    <div class="salvar-modal-box">
      <h2>Salvar Customização</h2>
      <p>Dê um nome para sua build:</p>
      <input type="text" id="salvar-nome-input" placeholder="Minha Customização">
      <div class="salvar-modal-botoes">
        <button type="button" id="salvar-btn-cancelar" class="modal-btn-cancelar">Cancelar</button>
        <button type="button" id="salvar-btn-confirmar" class="modal-btn-confirmar">Salvar</button>
      </div>
    </div>
  </div>
  </div>

  <script>
    window.CUSTOMIZACAO_SALVA = <?php echo json_encode(
      $customizacaoInicial['config'] ?? null,
      JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ); ?>;
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

  <script src="custom.js?v=1.10"></script>

  <script>
    // VARIÁVEIS GLOBAIS
    let scene, camera, renderer, controls;
    let skateBase = null;
    let todasAsPecas = {};
    let corTrucksAtual = '#ffffff';

    let visShape = true;
    let visTrucks = true;
    let visRodinhas = true;
    let visRolamentos = true;
    let visParafusos = true;
    const CONFIG_SALVA = (typeof window.CUSTOMIZACAO_SALVA !== 'undefined') ? window.CUSTOMIZACAO_SALVA : null;
    let configInicialAplicada = false;

    // =============================================
    // NOVO: LÓGICA DE PREÇO
    // =============================================
    const PRECOS = {
      shape: 120, // Preço base do shape 'white'
      truck: 0, // Preço base do truck 'padrao'
      rodinha: 0, // Preço base (sem rodinha)
      rolamento: 0,
      parafuso: 0
    };

    function atualizarPrecoTotal() {
      // 1. Soma todos os preços
      const total = PRECOS.shape + PRECOS.truck + PRECOS.rodinha + PRECOS.rolamento + PRECOS.parafuso;

      // 2. Formata como R$
      const precoFormatado = `R$ ${total.toFixed(2).replace('.', ',')}`;

      // 3. Atualiza o visor no topo
      document.getElementById('preco-total-display').textContent = precoFormatado;

      // 4. Atualiza os inputs escondidos do formulário
      const nomeShape = shapeAtual.charAt(0).toUpperCase() + shapeAtual.slice(1);
      const nomeTruck = trucks === trucksModelos.padrao ? 'Padrão' : (Object.keys(trucksModelos).find(key => trucksModelos[key] === trucks) || 'Padrão');
      const nomeRodinha = rodinhasAtuais ? (rodinhasAtuais.charAt(0).toUpperCase() + rodinhasAtuais.slice(1)) : 'Nenhuma';

      const nomeDescricao = `Shape: ${nomeShape}, Truck: ${nomeTruck}, Roda: ${nomeRodinha}`;
      const nomeProduto = `Skate Customizado (${nomeShape})`;
      const idProduto = `custom-${shapeAtual}-${nomeTruck.toLowerCase()}-${rodinhasAtuais || 'none'}`;

      document.getElementById('cart-id').value = idProduto;
      document.getElementById('cart-nome').value = nomeProduto;
      document.getElementById('cart-preco').value = total.toFixed(2);
      document.getElementById('cart-descricao').value = nomeDescricao;
    }


    // util: liga/desliga visibilidade de uma lista de nomes de meshes
    function setListaVisible(lista, on) {
      (lista || []).forEach(n => {
        const m = todasAsPecas[n];
        if (m) m.visible = !!on;
      });
    }

    // util: atualiza label do botão
    function setBtnLabel(id, mostrar) {
      const el = document.getElementById(id);
      if (!el) return;
      let base = el.dataset.base;
      if (!base) {
        base = (el.textContent || '')
          .replace(/\s+/g, ' ')
          .trim()
          .replace(/^(?:Mostrar|Ocultar)\s+/i, '');
        el.dataset.base = base;
      }
      el.textContent = (mostrar ? 'Mostrar ' : 'Ocultar ') + base;
    }

    // ========== TOGGLES ==========
    function toggleShape() {
      const tipo = shapeAtual || 'white';
      const lista = shapes[tipo] || [];
      visShape = !visShape;
      setListaVisible(lista, visShape);
      setBtnLabel('btnShape', !visShape);
    }

    function toggleTrucks() {
      visTrucks = !visTrucks;
      setListaVisible(trucks, visTrucks);
      setBtnLabel('btnTrucks', !visTrucks);
    }

    function toggleRodinhas() {
      const lista = rodinhasAtuais ? (rodinhasModelos[rodinhasAtuais] || []) : [];
      visRodinhas = !visRodinhas;
      setListaVisible(lista, visRodinhas);
      setBtnLabel('btnRodinhas', !visRodinhas);
    }

    function toggleRolamentos() {
      visRolamentos = !visRolamentos;
      setListaVisible(rolamentos, visRolamentos);
      // setBtnLabel('btnRolamentos', !visRolamentos); // Botão não existe
    }

    function toggleParafusos() {
      visParafusos = !visParafusos;
      setListaVisible(parafusos, visParafusos);
      // setBtnLabel('btnParafusos', !visParafusos); // Botão não existe
    }

    // ========== INTEGRAÇÃO SUAVE ==========
    // (Esta seção foi removida para simplificar,
    // as funções originais abaixo agora cuidam disso)

    // 🎯 SHAPES
    const shapes = {
      white: ['Object_15004', 'Object_15005', 'Object_16002'],
      luffy: ['Object_15037', 'Object_15038', 'Object_16020'],
      killjoy: ['Object_15021', 'Object_15022', 'Object_16019'],
      yoru: ['Object_15013', 'Object_15040', 'Object_16011'],
      witcher: ['Object_150139', 'Object_150149', 'Object_160079'],
      viper: ['Object_15333', 'Object_16333', 'Object_15332'],
      sonic: ['Object_15222', 'Object_16223', 'Object_15221'],
      omen: ['Object_15111', 'Object_16112', 'Object_15113'],
      circus: ['Object_15441', 'Object_16442', 'Object_15443'],
      // Apenas bases
      whiteBase: ['Object_15004'],
      luffyBase: ['Object_15037'],
      killjoyBase: ['Object_15021'],
      yoruBase: ['Object_15013'],
      witcherBase: ['Object_150139'],
      viperBase: ['Object_15333'],
      sonicBase: ['Object_15222'],
      omenBase: ['Object_15111'],
      circusBase: ['Object_15441'],
    };

    // ✅ RODINHAS
    const rodinhasModelos = {
      sasuke: [
        'Cylinder003', 'Cylinder003_1', 'Cylinder003_2', 'Cylinder003_3', 'Cylinder003_4',
        'Cylinder008', 'Cylinder008_1', 'Cylinder008_2', 'Cylinder008_3', 'Cylinder008_4',
        'Cylinder010', 'Cylinder010_1', 'Cylinder010_2', 'Cylinder010_3', 'Cylinder010_4',
        'Cylinder011', 'Cylinder011_1', 'Cylinder011_2', 'Cylinder011_3', 'Cylinder011_4'
      ],
      circus: [
        'Cylinder001', 'Cylinder001_1', 'Cylinder001_2', 'Cylinder001_3', 'Cylinder001_4',
        'Cylinder005', 'Cylinder005_1', 'Cylinder005_2', 'Cylinder005_3', 'Cylinder005_4',
        'Cylinder006', 'Cylinder006_1', 'Cylinder006_2', 'Cylinder006_3', 'Cylinder006_4',
        'Cylinder007', 'Cylinder007_1', 'Cylinder007_2', 'Cylinder007_3', 'Cylinder007_4'
      ],
      tails: [
        'Cylinder014', 'Cylinder014_1', 'Cylinder014_2', 'Cylinder014_3', 'Cylinder014_4',
        'Cylinder015', 'Cylinder015_1', 'Cylinder015_2', 'Cylinder015_3', 'Cylinder015_4',
        'Cylinder016', 'Cylinder016_1', 'Cylinder016_2', 'Cylinder016_3', 'Cylinder016_4',
        'Cylinder017', 'Cylinder017_1', 'Cylinder017_2', 'Cylinder017_3', 'Cylinder017_4'
      ],
      stitch: [
        'Cylinder018', 'Cylinder018_1', 'Cylinder018_2', 'Cylinder018_3', 'Cylinder018_4',
        'Cylinder019', 'Cylinder019_1', 'Cylinder019_2', 'Cylinder019_3', 'Cylinder019_4',
        'Cylinder020', 'Cylinder020_1', 'Cylinder020_2', 'Cylinder020_3', 'Cylinder020_4',
        'Cylinder021', 'Cylinder021_1', 'Cylinder021_2', 'Cylinder021_3', 'Cylinder021_4'
      ],
      hq: [
        'Cylinder022', 'Cylinder022_1', 'Cylinder022_2', 'Cylinder022_3', 'Cylinder022_4',
        'Cylinder023', 'Cylinder023_1', 'Cylinder023_2', 'Cylinder023_3', 'Cylinder023_4',
        'Cylinder024', 'Cylinder024_1', 'Cylinder024_2', 'Cylinder024_3', 'Cylinder024_4',
        'Cylinder025', 'Cylinder025_1', 'Cylinder025_2', 'Cylinder025_3', 'Cylinder025_4'
      ],
      hello: [
        'Cylinder030', 'Cylinder030_1', 'Cylinder030_2', 'Cylinder030_3', 'Cylinder030_4',
        'Cylinder031', 'Cylinder031_1', 'Cylinder031_2', 'Cylinder031_3', 'Cylinder031_4',
        'Cylinder032', 'Cylinder032_1', 'Cylinder032_2', 'Cylinder032_3', 'Cylinder032_4',
        'Cylinder033', 'Cylinder033_1', 'Cylinder033_2', 'Cylinder033_3', 'Cylinder033_4'
      ],
      fire: [
        'Cylinder034', 'Cylinder034_1', 'Cylinder034_2', 'Cylinder034_3', 'Cylinder034_4',
        'Cylinder035', 'Cylinder035_1', 'Cylinder035_2', 'Cylinder035_3', 'Cylinder035_4',
        'Cylinder036', 'Cylinder036_1', 'Cylinder036_2', 'Cylinder036_3', 'Cylinder036_4',
        'Cylinder037', 'Cylinder037_1', 'Cylinder037_2', 'Cylinder037_3', 'Cylinder037_4'
      ],
      kuro: [
        'Cylinder042', 'Cylinder042_1', 'Cylinder042_2', 'Cylinder042_3', 'Cylinder042_4',
        'Cylinder043', 'Cylinder043_1', 'Cylinder043_2', 'Cylinder043_3', 'Cylinder043_4',
        'Cylinder044', 'Cylinder044_1', 'Cylinder044_2', 'Cylinder044_3', 'Cylinder044_4',
        'Cylinder045', 'Cylinder045_1', 'Cylinder045_2', 'Cylinder045_3', 'Cylinder045_4'
      ],
      lisa: []
    };

    // ✅ TRUCKS
    const trucks_padrao = [];
    const trucks_kuromi = [
      'Circle002', 'Circle003', 'Circle002_1', 'Circle002_2', 'Circle002_3', 'Circle002_4',
      'Circle003_1', 'Circle003_2', 'Circle003_3', 'Circle003_4'
    ];
    const trucks_stitch = [
      'Circle001', 'Circle009', 'Circle001_1', 'Circle001_2', 'Circle001_3', 'Circle001_4',
      'Circle009_1', 'Circle009_2', 'Circle009_3', 'Circle009_4'
    ];
    const trucks_black = [
      'Circle004', 'Circle004_1', 'Circle004_2', 'Circle004_3', 'Circle004_4',
      'Circle006', 'Circle006_1', 'Circle006_2', 'Circle006_3', 'Circle006_4'
    ];
    const trucks_hello = [
      'Circle008', 'Circle008_1', 'Circle004_2', 'Circle008_3', 'Circle008_4',
      'Circle010', 'Circle010_1', 'Circle010_2', 'Circle010_3', 'Circle010_4'
    ];
    const trucks_miranha = [
      'Circle005', 'Circle005_1', 'Circle005_2', 'Circle005_3', 'Circle005_4',
      'Circle015', 'Circle015_1', 'Circle015_2', 'Circle015_3', 'Circle015_4'
    ];
    const trucks_hq = [
      'Circle012', 'Circle012_1', 'Circle012_2', 'Circle012_3', 'Circle012_4',
      'Circle014', 'Circle014_1', 'Circle014_2', 'Circle014_3', 'Circle014_4'
    ];
    const trucks_lisa = [
      'Circle024', 'Circle024_1', 'Circle024_2', 'Circle024_3', 'Circle024_4',
      'Circle025', 'Circle025_1', 'Circle025_2', 'Circle025_3', 'Circle025_4'
    ];

    const trucksModelos = {
      padrao: trucks_padrao,
      stitch: trucks_stitch,
      kuromi: trucks_kuromi,
      black: trucks_black,
      hello: trucks_hello,
      miranha: trucks_miranha,
      hq: trucks_hq,
      lisa: trucks_lisa
    };
    let trucks = trucksModelos.padrao; // conjunto ativo de trucks
    let shapeAtual = 'white'; // shape atual
    let rodinhasAtuais = null; // modelo de rodinha ativo (ou null)

    // ✅ ROLAMENTOS & PARAFUSOS
    const rolamentos_padrao = [
      "Radial_ball_bearing_type_1000088001",
      "Radial_ball_bearing_type_1000088002",
      "Radial_ball_bearing_type_1000088003",
      "Radial_ball_bearing_type_1000088004"
    ];
    const parafusos_padrao = [
      "Bolt001", "Bolt002", "Bolt003", "Bolt004", "Bolt005", "Bolt006",
      "Bolt007", "Bolt008", "Bolt009", "Bolt010", "Bolt011", "Bolt073"
    ];


    // Estados ativos (vazios ao iniciar)
    let rolamentos = [];
    let parafusos = [];

    const rolamentosModelos = {
      padrao: rolamentos_padrao,
    };
    const parafusosModelos = {
      padrao: parafusos_padrao,
    };

    // ===== Utils UI =====
    function atualizarStatus(mensagem, tipo = 'info') {
      const status = document.getElementById('status');
      status.innerHTML = mensagem;
      status.style.borderLeftColor = (tipo === 'erro') ? '#ff4444' :
        (tipo === 'sucesso') ? '#00ff88' : '#9c6bff';
    }

    // ===== Seletores =====
    function selecionarTrucks(modelo) {
      // Esconde todos os trucks
      const todas = Object.values(trucksModelos).flat();
      todas.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });

      // Mostra o modelo selecionado
      const lista = trucksModelos[modelo] || [];
      lista.forEach(n => {
        const mesh = todasAsPecas[n];
        if (mesh) {
          mesh.visible = visTrucks; // Respeita o toggle
          if (mesh.material && mesh.material.color) mesh.material.color.set(corTrucksAtual);
        } else {
          console.warn('Truck não encontrado no GLB:', n);
        }
      });
      trucks = lista; // Atualiza o estado

      // =============================================
      // ATUALIZA O PREÇO
      // =============================================
      // Encontra o botão que foi clicado para pegar o data-price
      const
        botaoClicado = [...document.querySelectorAll('#truckGrupo .pena')].find(b => b.getAttribute('onclick') === `selecionarTrucks('${modelo}')`);
      PRECOS.truck = parseFloat(botaoClicado?.dataset.price || 0);
      atualizarPrecoTotal();
    }

    // 🔁 SELECIONAR ROLAMENTOS
    function selecionarRolamentos(modelo) {
      const todosRolos = Object.values(rolamentosModelos).flat();
      todosRolos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });

      const lista = rolamentosModelos[modelo] || [];
      lista.forEach(n => {
        const mesh = todasAsPecas[n];
        if (mesh) {
          mesh.visible = visRolamentos; // Respeita o toggle
        }
      });
      rolamentos = lista;

      // =============================================
      // ATUALIZA O PREÇO
      // =============================================
      const botaoClicado = document.querySelector(`button[onclick="selecionarRolamentos('${modelo}')"]`);
      PRECOS.rolamento = parseFloat(botaoClicado?.dataset.price || 0);
      atualizarPrecoTotal();
    }

    // 🔁 SELECIONAR PARAFUSOS
    function selecionarParafusos(modelo) {
      const todosParafusos = Object.values(parafusosModelos).flat();
      todosParafusos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });

      const lista = parafusosModelos[modelo] || [];
      lista.forEach(n => {
        const mesh = todasAsPecas[n];
        if (mesh) {
          mesh.visible = visParafusos; // Respeita o toggle
        }
      });
      parafusos = lista;

      // =============================================
      // ATUALIZA O PREÇO
      // =============================================
      const botaoClicado = document.querySelector(`button[onclick="selecionarParafusos('${modelo}')"]`);
      PRECOS.parafuso = parseFloat(botaoClicado?.dataset.price || 0);
      atualizarPrecoTotal();
    }

    // ❌ REMOVER ROLAMENTOS
    function removerRolamentos() {
      const todosRolos = Object.values(rolamentosModelos).flat();
      todosRolos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });
      rolamentos = [];

      // =============================================
      // ATUALIZA O PREÇO
      // =============================================
      PRECOS.rolamento = 0;
      atualizarPrecoTotal();
    }

    // ❌ REMOVER PARAFUSOS
    function removerParafusos() {
      const todosParafusos = Object.values(parafusosModelos).flat();
      todosParafusos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });
      parafusos = [];

      // =============================================
      // ATUALIZA O PREÇO
      // =============================================
      PRECOS.parafuso = 0;
      atualizarPrecoTotal();
    }


    function resetCamera() {
      controls.reset();
      camera.position.set(1, 0, 0);
      camera.lookAt(0, 1, 0);
      controls.update();
      atualizarStatus('📷 Câmera resetada - Vista frontal', 'sucesso');
    }

    function mudarVista(tipo) {
      switch (tipo) {
        case 'frontal':
          camera.position.set(1, 0, 0);
          break;
        case 'lateral':
          camera.position.set(0.5, 0, 8);
          break;
        case 'superior':
          camera.position.set(0, 5, 0.1);
          break;
      }
      camera.lookAt(0, 1, 0);
      controls.update();
      atualizarStatus(` Vista ${tipo} ativada`, 'info');
    }

    function exportarImagem() {
      renderer.render(scene, camera);
      const canvas = renderer.domElement;
      const image = canvas.toDataURL('image/png');
      const link = document.createElement('a');
      link.download = `skate-config-${Date.now()}.png`;
      link.href = image;
      link.click();
      atualizarStatus('📸 Imagem exportada com sucesso!', 'sucesso');
    }

    function capturarPreviewSkate() {
      try {
        if (!renderer || !renderer.domElement) {
          return null;
        }
        renderer.render(scene, camera);
        return renderer.domElement.toDataURL('image/png');
      } catch (error) {
        console.error('Erro ao capturar a imagem do skate:', error);
        return null;
      }
    }

    function salvarConfiguracao() {
      const config = {
        shape: shapeAtual,
        rodinhas: rodinhasAtuais,
        truck: Object.keys(trucksModelos).find(key => trucksModelos[key] === trucks) || 'padrao',
        data: new Date().toLocaleString('pt-BR')
      };
      localStorage.setItem('skateConfig', JSON.stringify(config, null, 2));
      document.getElementById('configAtual').textContent =
        `Shape: ${config.shape} | Rodinhas: ${config.rodinhas} | Truck: ${config.truck}`;
      atualizarStatus('💾 Configuração salva!', 'sucesso');
    }

    // ===== Mostrar =====
    function mostrarShape(tipo) {
      // Esconde apenas os shapes atuais
      const todosShapes = Object.values(shapes).flat();
      todosShapes.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });

      // Mostra o shape selecionado
      let pecasEncontradas = 0;
      (shapes[tipo] || []).forEach(nome => {
        if (todasAsPecas[nome]) {
          todasAsPecas[nome].visible = visShape; // Respeita o toggle
          pecasEncontradas++;
        }
      });
      shapeAtual = tipo;

      // Re-aplica as outras partes (trucks, rodas, etc.)
      // para que apareçam com o novo shape
      setListaVisible(trucks || [], visTrucks);
      const rodasLista = rodinhasAtuais ? (rodinhasModelos[rodinhasAtuais] || []) : [];
      setListaVisible(rodasLista, visRodinhas);
      setListaVisible(rolamentos || [], visRolamentos);
      setListaVisible(parafusos || [], visParafusos);

      // =============================================
      // ATUALIZA O PREÇO
      // =============================================
      const botaoClicado = [...document.querySelectorAll('#shapeGrupo .pena')].find(b => b.getAttribute('onclick') === `mostrarShape('${tipo}')`);
      PRECOS.shape = parseFloat(botaoClicado?.dataset.price || 0);
      atualizarPrecoTotal();

      // Atualiza UI dos botões
      document.querySelectorAll('#shapeGrupo .pena').forEach(btn => btn.classList.remove('ativo'));
      if (botaoClicado) botaoClicado.classList.add('ativo');
    }

    function mostrarRodinhas(tipo) {
      // Esconde todas as rodinhas
      const todas = Object.values(rodinhasModelos).flat();
      todas.forEach(nome => {
        if (todasAsPecas[nome]) todasAsPecas[nome].visible = false;
      });

      // Mostra o modelo selecionado
      const lista = rodinhasModelos[tipo] || [];
      lista.forEach(nome => {
        if (todasAsPecas[nome]) {
          todasAsPecas[nome].visible = visRodinhas; // Respeita o toggle
        }
      });
      rodinhasAtuais = tipo;

      // =============================================
      // ATUALIZA O PREÇO
      // =============================================
      const botaoClicado = [...document.querySelectorAll('#rodinhaGrupo .pena')].find(b => b.getAttribute('onclick') === `mostrarRodinhas('${tipo}')`);
      PRECOS.rodinha = parseFloat(botaoClicado?.dataset.price || 0);
      atualizarPrecoTotal();

      // Atualiza UI dos botões
      document.querySelectorAll('#rodinhaGrupo .pena').forEach(btn => btn.classList.remove('ativo'));
      if (botaoClicado) botaoClicado.classList.add('ativo');
    }


    // ===== Init 3D =====
    function ajustarTamanhoCanvas() {
      if (!renderer || !camera) return;
      const container = document.getElementById('container');
      if (!container) return;
      const width = Math.max(container.clientWidth, 320);
      const height = Math.max(container.clientHeight, 320);
      renderer.setSize(width, height);
      camera.aspect = width / height;
      camera.updateProjectionMatrix();
    }

    function init() {
      scene = new THREE.Scene();
      scene.background = new THREE.Color(0xcccccc);

      camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
      camera.position.set(1, 0, 0);
      camera.lookAt(0, 1, 0);

      renderer = new THREE.WebGLRenderer({
        antialias: true,
        preserveDrawingBuffer: true
      });
      renderer.shadowMap.enabled = true;
      renderer.setPixelRatio(window.devicePixelRatio || 1);
      const canvasContainer = document.getElementById('container');
      if (canvasContainer) {
        canvasContainer.innerHTML = '';
        canvasContainer.appendChild(renderer.domElement);
        ajustarTamanhoCanvas();
        if (window.ResizeObserver) {
          const ro = new ResizeObserver(() => ajustarTamanhoCanvas());
          ro.observe(canvasContainer);
          window.__skateLabRO = ro;
        }
      }

      controls = new THREE.OrbitControls(camera, renderer.domElement);
      controls.enableDamping = true;
      controls.dampingFactor = 0.1;
      controls.rotateSpeed = 0.5;
      controls.minDistance = 3.5;
      controls.maxDistance = 10;

      const ambientLight = new THREE.AmbientLight(0xffffff, 1);
      scene.add(ambientLight);

      const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
      directionalLight.position.set(10, 6, 0);
      directionalLight.castShadow = true;
      scene.add(directionalLight);

      carregarSkate();
      animate();
    }
    // Função que organiza todas as peças do modelo 3D
    function organizarTodasAsPecas(modelo) {
      todasAsPecas = {}; // limpa o dicionário
      modelo.traverse((child) => {
        if (child.isMesh && child.name) {
          todasAsPecas[child.name] = child;
        }
      });
      console.log("Peças detectadas:", Object.keys(todasAsPecas)); // debug
    }

    // Função que esconde todas as peças do modelo
    function esconderTudo() {
      if (!todasAsPecas) return;
      Object.values(todasAsPecas).forEach(peca => {
        if (peca.isMesh) peca.visible = false;
      });
    }

    function carregarSkate() {
      const loader = new THREE.GLTFLoader();
      atualizarStatus('📦 Carregando modelo...');
      const proxy = 'https://corsproxy.io/?';
      const modelUrl = 'https://github.com/ferreiravih/Skate-Lab/releases/download/v1.0/montagem1.glb';

      loader.load(proxy + modelUrl, (gltf) => {
        skateBase = gltf.scene;
        const box = new THREE.Box3().setFromObject(skateBase);
        const center = box.getCenter(new THREE.Vector3());
        skateBase.position.sub(center);

        scene.add(skateBase);
        organizarTodasAsPecas(skateBase);

        // Inicializa o estado padrão
        esconderTudo();
        removerRolamentos();
        removerParafusos();
        selecionarTrucks('padrao'); // Define truck padrão (preço 0)
        mostrarShape('white'); // Define shape padrão (preço 120)

        document.getElementById('configAtual').textContent =
          'Shape: White | Rodinhas: — | Truck: Padrão';

        atualizarStatus('✅ Modelo carregado com sucesso!', 'sucesso');

        // Define o preço inicial
        atualizarPrecoTotal();
        if (CONFIG_SALVA) {
          aplicarConfiguracaoSalva(CONFIG_SALVA);
        }

      }, undefined, (error) => {
        console.error('❌ Erro ao carregar modelo:', error);
        atualizarStatus('❌ Erro ao carregar modelo', 'erro');
      });
    }

    function animate() {
      requestAnimationFrame(animate);
      controls.update();
      renderer.render(scene, camera);
    }

    window.addEventListener('resize', ajustarTamanhoCanvas);

    init();
  </script>
  <script>
    // Sobrescreve salvarConfiguracao para salvar no perfil (backend)
    // 
    function salvarConfiguracao() {
        const overlay = document.getElementById('salvar-overlay');
        const input = document.getElementById('salvar-nome-input');
        
        const tituloPadrao = `Minha Customização (${new Date().toLocaleDateString('pt-BR')})`;
        input.value = tituloPadrao;
        
        overlay.style.display = 'flex';
        setTimeout(() => overlay.classList.add('visivel'), 10);
        
        input.focus();
        input.select();
    }
    const baseUrl = '<?php echo $baseUrl; ?>';
    const defaultPreviewImg = `${baseUrl || ''}/img/imgs-skateshop/image.png`;
    const cartImagemInput = document.getElementById('cart-imagem');
    if (cartImagemInput && (!cartImagemInput.value || cartImagemInput.value.startsWith('..'))) {
        cartImagemInput.value = defaultPreviewImg;
    }
    async function executarSalvamento() {
        const overlay = document.getElementById('salvar-overlay');
        const input = document.getElementById('salvar-nome-input');
        const btnConfirmar = document.getElementById('salvar-btn-confirmar');
        const tituloPadrao = `Minha Customização (${new Date().toLocaleDateString('pt-BR')})`;
        
        const titulo = input.value;
        const tituloFinal = (titulo || tituloPadrao).trim();

        if (!tituloFinal) {
            input.focus();
            input.style.borderColor = 'red'; 
            return;
        }
        btnConfirmar.disabled = true;
        btnConfirmar.textContent = 'Salvando...';

        try { if (typeof atualizarPrecoTotal === 'function') atualizarPrecoTotal(); } catch(e) {}
        const truckNome = (typeof trucksModelos !== 'undefined' && trucks)
          ? (Object.keys(trucksModelos).find(key => trucksModelos[key] === trucks) || 'padrao')
          : 'padrao';
        const total = (typeof PRECOS !== 'undefined')
          ? (PRECOS.shape + PRECOS.truck + PRECOS.rodinha + PRECOS.rolamento + PRECOS.parafuso)
          : 0;
        const previewCapturado = capturarPreviewSkate();
        const previewImg = previewCapturado || defaultPreviewImg;
        if (cartImagemInput) {
            cartImagemInput.value = previewImg;
        }

        const config = {
          shape: (typeof shapeAtual !== 'undefined') ? shapeAtual : null,
          rodinhas: (typeof rodinhasAtuais !== 'undefined') ? (rodinhasAtuais || null) : null,
          truck: truckNome,
          corTrucks: (typeof corTrucksAtual !== 'undefined') ? (corTrucksAtual || '#ffffff') : '#ffffff',
          precos: (typeof PRECOS !== 'undefined') ? {
            shape: PRECOS.shape, truck: PRECOS.truck, rodinha: PRECOS.rodinha,
            rolamento: PRECOS.rolamento, parafuso: PRECOS.parafuso, total
          } : { total },
          salvo_em: new Date().toISOString()
        };

        try { localStorage.setItem('skateConfig', JSON.stringify(config)); } catch (e) {}

        try {
          const resp = await fetch(`${baseUrl}/customizacao/funcoes/salvar_customizacao.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ titulo: tituloFinal, config, preco_total: total, preview_img: previewImg })
          });
          
          if (resp.status === 401) {
            window.location.href = '../home/index.php?error=auth_required';
            return;
          }
          const data = await resp.json().catch(() => ({}));
          if (resp.ok && data && data.sucesso) {
            if (typeof atualizarStatus === 'function') { atualizarStatus('✅ Customização salva com sucesso!', 'sucesso'); }
            setTimeout(() => { window.location.href = '../perfil/funcoes/customizacoes.php'; }, 600);
          } else {
            if (typeof atualizarStatus === 'function') { atualizarStatus('⚠️ Não foi possível salvar agora. Tente novamente.', 'erro'); }
          }
        } catch (e) {
          if (typeof atualizarStatus === 'function') { atualizarStatus('⚠️ Erro de rede ao salvar.', 'erro'); }
        }
        btnConfirmar.disabled = false;
        btnConfirmar.textContent = 'Salvar';
        
        const inputNome = document.getElementById('salvar-nome-input');
        inputNome.style.borderColor = '#ddd'; 

        overlay.classList.remove('visivel');
        setTimeout(() => overlay.style.display = 'none', 300); 
    }

    function aplicarConfiguracaoSalva(config) {
        if (!config || typeof config !== 'object' || configInicialAplicada) {
            return;
        }
        try {
            if (config.corTrucks) {
                corTrucksAtual = config.corTrucks;
            }
            if (config.shape && shapes[config.shape]) {
                mostrarShape(config.shape);
            }
            if (config.truck && trucksModelos[config.truck]) {
                selecionarTrucks(config.truck);
            }
            if (config.rodinhas && rodinhasModelos[config.rodinhas]) {
                mostrarRodinhas(config.rodinhas);
            }

            const truckAtual = Object.keys(trucksModelos).find(key => trucksModelos[key] === trucks) || 'padrao';
            const statusTexto = `Shape: ${shapeAtual} | Rodinhas: ${rodinhasAtuais || 'Nenhuma'} | Truck: ${truckAtual}`;
            const statusElemento = document.getElementById('configAtual');
            if (statusElemento) {
                statusElemento.textContent = statusTexto;
            }

            atualizarPrecoTotal();
            configInicialAplicada = true;
            atualizarStatus('✨ Customização carregada do salvamento.', 'sucesso');
        } catch (error) {
            console.error('Erro ao aplicar customização salva:', error);
            atualizarStatus('⚠️ Não foi possível carregar a customização salva.', 'erro');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const overlay = document.getElementById('salvar-overlay');
        const btnCancelar = document.getElementById('salvar-btn-cancelar');
        const btnConfirmar = document.getElementById('salvar-btn-confirmar');
        const inputNome = document.getElementById('salvar-nome-input');

        if (overlay) { 
            function fecharModal() {
                overlay.classList.remove('visivel');
                setTimeout(() => overlay.style.display = 'none', 300); 
            }
            btnCancelar.addEventListener('click', fecharModal);
            btnConfirmar.addEventListener('click', executarSalvamento);
            
            inputNome.addEventListener('keyup', (e) => {
                if (e.key === 'Enter') {
                    executarSalvamento();
                }
            });

            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    fecharModal();
                }
            });
        }

        const formCarrinho = document.getElementById('form-carrinho');
        if (formCarrinho) {
            formCarrinho.addEventListener('submit', () => {
                const preview = capturarPreviewSkate();
                if (preview && cartImagemInput) {
                    cartImagemInput.value = preview;
                } else if (cartImagemInput && !cartImagemInput.value) {
                    cartImagemInput.value = defaultPreviewImg;
                }
            });
        }
    });
     
    
  </script>
  <script>
    function toggleGrupo(id, botao) {
      const grupo = document.getElementById(id);
      const aberto = grupo.classList.toggle("aberto");
      botao.classList.toggle("ativo", aberto);
      if (typeof ajustarTamanhoCanvas === 'function') {
        requestAnimationFrame(ajustarTamanhoCanvas);
      }
    }
  </script>
</body>

</html>
