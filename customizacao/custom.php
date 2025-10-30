<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>customização</title>
  <link rel="stylesheet" href="custom.css">
  <link rel="stylesheet" href="../componentes/nav.css">
</head>

<body>
  <?php include '../componentes/navbar.php'; ?>
  <div class="custom-page">
    <div class="viewer-area">
      <div id="container"></div>

      <!-- VISTA PR�%VIA LATERAL -->
      <div class="vista-previa">
        <div class="status">
          <span id="configAtual">Carregando...</span>
        </div>
        <div class="mini-botoes">
          <button onclick="mudarVista('frontal')">Frente</button>
          <button onclick="mudarVista('lateral')">Lado</button>
          <button onclick="mudarVista('superior')">Cima</button>
        </div>
      </div>
    </div>

    <div class="controls-area">
      <div id="ui">
        <h2></h2>

        <div class="status" id="status">
          �Ys? Sistema carregando...
        </div>
        <!-- SHAPES -->
        <div class="grupo-botoes">
          <h3 class="centro">Customização</h3>

          <div class="grupo-botoes">

          </div>
          <!-- Botão principal SHAPE -->
          <button class="btn-titulo" onclick="toggleGrupo('shapeGrupo', this)">Shape</button>
          <div id="shapeGrupo" class="grupo-colapsado">
            <!-- seus botões de shape aqui -->
            <button class="pena" onclick="mostrarShape('luffy')">
              <img class="btn-imagem" src="image/shape luffy.png" alt="Luffy"><br>
              Luffy
            </button>
            <button class="pena" onclick="mostrarShape('killjoy')">
              <img class="btn-imagem" src="image/shape killjoy.png" alt="killjoy"><br>
              Killjoy
            </button>
            <button class="pena" id="btnYoru" onclick="mostrarShape('yoru')">
              <img class="btn-imagem" src="image/shape yoru.png" alt="Yoru"><br>
              Yoru
            </button>
            <button class="pena" onclick="mostrarShape('witcher')">
              <img class="btn-imagem" src="image/shape the wicher.png" alt="witcher"><br>
              Witcher
            </button>
            <button class="pena" onclick="mostrarShape('viper')">
              <img class="btn-imagem" src="image/viper.png" alt="viper"><br>
              Viper
            </button>
            <button class="pena" onclick="mostrarShape('sonic')">
              <img class="btn-imagem" src="image/shape sonic.png" alt="sonic"><br>
              Sonic
            </button>
            <button class="pena" onclick="mostrarShape('omen')">
              <img class="btn-imagem" src="image/transferir1.png" alt="omen"><br>
              omen
            </button>
            <button class="pena" onclick="mostrarShape('circus')">
              <img class="btn-imagem" src="image/shape circo digital.png" alt="circus"><br>
              circus
            </button>
            <button class="pena" class="ativo" onclick="mostrarShape('white')">
              <div class="btn-imagem" style="background-color:#ffffff;">O</div><br>
              white
            </button>
          </div>

          <!-- Botão principal TRUCK -->
          <button class="btn-titulo" onclick="toggleGrupo('truckGrupo', this)">Truck</button>
          <div id="truckGrupo" class="grupo-colapsado">

            <button class="pena" onclick="selecionarTrucks('kuromi')">
              <img class="btn-imagem" src="image/truck kuromi.png" alt="kuromi"><br>
              kuromi
            </button>
            <button class="pena" onclick="selecionarTrucks('black')">
              <img class="btn-imagem" src="image/truck preto.png" alt="black"><br>
              black
            </button>
            <button class="pena" onclick="selecionarTrucks('hello')">
              <img class="btn-imagem" src="image/truck hello_kitty.png" alt="hello"><br>
              hello
            </button>
            <button class="pena" onclick="selecionarTrucks('miranha')">
              <img class="btn-imagem" src="image/truck homem_aranha.png" alt="miranha"><br>
              spider
            </button>
            <button class="pena" onclick="selecionarTrucks('hq')">
              <img class="btn-imagem" src="image/truck marvel.png" alt="hq"><br>
              HQ
            </button>
            <button class="pena" onclick="selecionarTrucks('lisa')">
              <img class="btn-imagem" src="image/trucklisa_czddxp.jpeg" alt="lisa"><br>
              lisa
            </button>
            <button class="pena" onclick="selecionarTrucks('stitch')">
              <img class="btn-imagem" src="image/truck stitch.png" alt="stitch"><br>
              stitch
            </button>

          </div>
          <button class="btn-titulo" onclick="toggleGrupo('rodinhaGrupo', this)">rodinha</button>
          <div id="rodinhaGrupo" class="grupo-colapsado">

            <button class="pena" onclick="mostrarRodinhas('circus')">
              <img class="btn-imagem" src="image/rodinha_circo_digital.png" alt="circus"><br>
              Circus
            </button>
            <button class="pena" onclick="mostrarRodinhas('sasuke')">
              <img class="btn-imagem" src="image/rodinha_sasuke.png" alt="sasuke"><br>
              Sasuke
            </button>
            <button class="pena" onclick="mostrarRodinhas('tails')">
              <img class="btn-imagem" src="image/rodinah_tails.png" alt="shanks"><br>
              Tails
            </button>
            <button class="pena" onclick="mostrarRodinhas('stitch')">
              <img class="btn-imagem" src="image/rodinha_stitch.png" alt="shanks"><br>
              stich
            </button>
            <button class="pena" onclick="mostrarRodinhas('hq')">
              <img class="btn-imagem" src="image/rodinha_marvel.png" alt="shanks"><br>
              hq
            </button>
            <button class="pena" onclick="mostrarRodinhas('hello')">
              <img class="btn-imagem" src="image/rodinha_hello_kitty.png" alt="shanks"><br>
              hello
            </button>
            <button class="pena" onclick="mostrarRodinhas('fire')">
              <img class="btn-imagem" src="image/rodinhafire.png" alt="shanks"><br>
              fire
            </button>
            <button class="pena" onclick="mostrarRodinhas('kuro')">
              <img class="btn-imagem" src="image/rodinha_kuromi.png" alt="shanks"><br>
              kuro
            </button>
          </div>
        

          <h3> Mostrar/Ocultar</h3>

          <button id="btnShape" onclick="toggleShape()">Ocultar shape</button>
          <button id="btnTrucks" onclick="toggleTrucks()">Ocultar trucks</button>
          <button id="btnRodinhas" onclick="toggleRodinhas()">Ocultar rodinhas</button>

          <!-- Exemplos de botões prontos para futuros modelos:
      <button onclick="mostrarRodinhas('viper')"><div class="btn-imagem" style="background-color:#4ecdc4;">V</div>Viper</button>
      <button onclick="mostrarRodinhas('omen')"><div class="btn-imagem" style="background-color:#230e40;">O</div>Omen</button>
      -->
        </div>
        <!-- ROLAMENTOS -->
        <div class="grupo-botoes">
          <h3>ROLAMENTOS</h3>
          <button onclick="selecionarRolamentos('padrao')">
            <div class="btn-imagem" style=</div>
            Rolamento padrão (4x)
          </button>
          <button onclick="removerRolamentos()">
            <div class="btn-imagem"/div>
            Remover rolamentos
          </button>
        </div>

        <!-- PARAFUSOS -->
        <div class="grupo-botoes">
          <h3>PARAFUSOS</h3>
          <button onclick="selecionarParafusos('padrao')">
            <div class="btn-imagem" </div>
            Parafuso padrão (12x)
          </button>
          <button onclick="removerParafusos()">
            <div class="btn-imagem"</div>
            Remover parafusos
          </button>
        </div>


        <!-- CONTROLES -->
        <div class="grupo-botoes">
          <h3>CONTROLES</h3>
          <button onclick="resetCamera()">
            <div class="btn-imagem" </div>
            Resetar Câmera
          </button>
          <button id="salvarBtn" onclick="salvarConfiguracao()">
            <div class="btn-imagem" </div>
            SALVAR CONFIGURAÇÃO
          </button>
          <button id="exportarBtn" onclick="exportarImagem()">
            <div class="btn-imagem" </div>
            EXPORTAR IMAGEM
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- THREE.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

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
      const base = el.dataset.base || el.textContent.replace(/^Mostrar |^Ocultar /, '');
      el.dataset.base = base; // guarda uma vez
      el.textContent = (mostrar ? 'Mostrar ' : 'Ocultar ') + base;
    }

    // ========== TOGGLES ==========
    function toggleShape() {
      // garante shapeAtual
      const tipo = shapeAtual || 'white';
      const lista = shapes[tipo] || [];
      visShape = !visShape;
      setListaVisible(lista, visShape);
      setBtnLabel('btnShape', !visShape);
    }

    function toggleTrucks() {
      // usa o conjunto de trucks atualmente selecionado
      visTrucks = !visTrucks;
      setListaVisible(trucks, visTrucks);
      setBtnLabel('btnTrucks', !visTrucks);
    }

    function toggleRodinhas() {
      // pega a lista do modelo ativo de rodinhas
      const lista = rodinhasAtuais ? (rodinhasModelos[rodinhasAtuais] || []) : [];
      visRodinhas = !visRodinhas;
      setListaVisible(lista, visRodinhas);
      setBtnLabel('btnRodinhas', !visRodinhas);
    }

    function toggleRolamentos() {
      visRolamentos = !visRolamentos;
      setListaVisible(rolamentos, visRolamentos);
      setBtnLabel('btnRolamentos', !visRolamentos);
    }

    function toggleParafusos() {
      visParafusos = !visParafusos;
      setListaVisible(parafusos, visParafusos);
      setBtnLabel('btnParafusos', !visParafusos);
    }

    // ========== INTEGRAÇÃO SUAVE ==========
    // sempre que você trocar de shape/rodinhas/trucks, respeite os toggles:
    const _mostrarShape_orig = mostrarShape;
    mostrarShape = function(tipo) {
      _mostrarShape_orig(tipo); // faz seu fluxo normal

      // re-aplica visibilidades conforme toggles atuais:
      // shape
      setListaVisible(shapes[shapeAtual] || [], visShape);
      // trucks
      setListaVisible(trucks || [], visTrucks);
      // rodinhas
      const rodasLista = rodinhasAtuais ? (rodinhasModelos[rodinhasAtuais] || []) : [];
      setListaVisible(rodasLista, visRodinhas);
      // rolamentos/parafusos
      setListaVisible(rolamentos || [], visRolamentos);
      setListaVisible(parafusos || [], visParafusos);
    };

    // se você chama selecionarTrucks/mostrarRodinhas/selecionarRolamentos/Parafusos em outros lugares,
    // é bom também re-aplicar o toggle depois que os arrays mudarem. exemplo:
    const _selecionarTrucks_orig = selecionarTrucks;
    selecionarTrucks = function(modelo) {
      _selecionarTrucks_orig(modelo);
      setListaVisible(trucks || [], visTrucks);
    };

    const _mostrarRodinhas_orig = mostrarRodinhas;
    mostrarRodinhas = function(tipo) {
      _mostrarRodinhas_orig(tipo);
      const lista = rodinhasAtuais ? (rodinhasModelos[rodinhasAtuais] || []) : [];
      setListaVisible(lista, visRodinhas);
    };

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

    // ✅ ROLAMENTOS & PARAFUSOS (NOMES REAIS DO SEU GLB)
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
    let rolamentos = []; // 4 peças quando selecionar
    let parafusos = []; // 12 peças quando selecionar

    // Mapas de modelos (pelo menos o 'padrao')
    const rolamentosModelos = {
      padrao: rolamentos_padrao,
      // ex: stitch: [...], hello: [...]
    };

    const parafusosModelos = {
      padrao: parafusos_padrao,
      // ex: stitch: [...], hello: [...]
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
      const todas = Object.values(trucksModelos).flat();
      todas.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });
      const lista = trucksModelos[modelo] || [];
      let count = 0;
      lista.forEach(n => {
        const mesh = todasAsPecas[n];
        if (mesh) {
          mesh.visible = true;
          if (mesh.material && mesh.material.color) mesh.material.color.set(corTrucksAtual);
          count++;
        } else {
          console.warn('Truck não encontrado no GLB:', n);
        }
      });
      trucks = lista;
    }

    // 🔁 SELECIONAR ROLAMENTOS (mostra 4 de uma vez)
    function selecionarRolamentos(modelo) {
      const todosRolos = Object.values(rolamentosModelos).flat();
      todosRolos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });

      const lista = rolamentosModelos[modelo] || [];
      let count = 0;
      lista.forEach(n => {
        const mesh = todasAsPecas[n];
        if (mesh) {
          mesh.visible = true;
          count++;
        }
      });

      rolamentos = lista; // guarda estado ativo
    }

    // 🔁 SELECIONAR PARAFUSOS (mostra 12 de uma vez)
    function selecionarParafusos(modelo) {
      const todosParafusos = Object.values(parafusosModelos).flat();
      todosParafusos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });

      const lista = parafusosModelos[modelo] || [];
      let count = 0;
      lista.forEach(n => {
        const mesh = todasAsPecas[n];
        if (mesh) {
          mesh.visible = true;
          count++;
        }
      });

      parafusos = lista; // guarda estado ativo
    }

    // ❌ REMOVER ROLAMENTOS (de todos os modelos)
    function removerRolamentos() {
      const todosRolos = Object.values(rolamentosModelos).flat();
      todosRolos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });
      rolamentos = [];
    }

    // ❌ REMOVER PARAFUSOS (de todos os modelos)
    function removerParafusos() {
      const todosParafusos = Object.values(parafusosModelos).flat();
      todosParafusos.forEach(n => {
        if (todasAsPecas[n]) todasAsPecas[n].visible = false;
      });
      parafusos = [];
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

    function salvarConfiguracao() {
      const config = {
        shape: shapeAtual,
        rodinhas: rodinhasAtuais,
        corTrucks: corTrucksAtual,
        data: new Date().toLocaleString('pt-BR')
      };
      localStorage.setItem('skateConfig', JSON.stringify(config, null, 2));
      document.getElementById('configAtual').textContent =
        `Shape: ${shapeAtual} | Rodinhas: ${rodinhasAtuais} | Cor: ${corTrucksAtual}`;
      atualizarStatus('💾 Configuração salva!', 'sucesso');
    }

    // ===== Mostrar =====
    function mostrarShape(tipo) {
      esconderTudo();

      // trucks
      trucks.forEach(nome => {
        if (todasAsPecas[nome]) {
          todasAsPecas[nome].visible = true;
          if (todasAsPecas[nome].material) {
            todasAsPecas[nome].material.color.set(corTrucksAtual);
          }
        }
      });

      // rolamentos
      rolamentos.forEach(nome => {
        if (todasAsPecas[nome]) todasAsPecas[nome].visible = true;
      });

      // parafusos
      parafusos.forEach(nome => {
        if (todasAsPecas[nome]) todasAsPecas[nome].visible = true;
      });

      // shape
      let pecasEncontradas = 0;
      (shapes[tipo] || []).forEach(nome => {
        if (todasAsPecas[nome]) {
          todasAsPecas[nome].visible = true;
          pecasEncontradas++;
        }
      });

      // rodinhas
      mostrarRodinhas(rodinhasAtuais);
      shapeAtual = tipo;


      document.querySelectorAll('button').forEach(btn => btn.classList.remove('ativo'));
      if (event && event.target) event.target.classList.add('ativo');
    }

    function mostrarRodinhas(tipo) {
      const todas = Object.values(rodinhasModelos).flat();
      todas.forEach(nome => {
        if (todasAsPecas[nome]) todasAsPecas[nome].visible = false;
      });
      const lista = rodinhasModelos[tipo] || [];
      let count = 0;
      lista.forEach(nome => {
        if (todasAsPecas[nome]) {
          todasAsPecas[nome].visible = true;
          count++;
        }
      });
      rodinhasAtuais = tipo;

      document.querySelectorAll('button').forEach(btn => {
        const t = btn.dataset?.rodinhas;
        if (t) btn.classList.toggle('ativo', t === tipo);
      });
    }

    // ===== Base-only helper (se quiser usar)
    function mostrarSomenteBase(tipoShape) {
      esconderTudo();
      const baseKey = tipoShape + 'white';
      const listaBase = shapes[baseKey] || [];
      let count = 0;
      listaBase.forEach(white => {
        if (todasAsPecas[white]) {
          todasAsPecas[white].visible = true;
          count++;
        }
      });
      trucks = [];
      rodinhasAtuais = null;
      rolamentos = [];
      parafusos = [];
      shapeAtual = tipoShape;
      const cfg = document.getElementById('configAtual');
      if (cfg) cfg.textContent = `Shape: ${tipoShape} (Base) | Rodinhas: — | Cor: ${corTrucksAtual}`;
    }

    // ===== Init 3D =====
    function ajustarTamanhoCanvas() {
      if (!renderer || !camera) return;
      const container = document.getElementById('container');
      if (!container) return;
      const bounds = container.getBoundingClientRect();
      const width = Math.max(bounds.width, 320);
      const height = Math.max(bounds.height, 320);
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
      }

      controls = new THREE.OrbitControls(camera, renderer.domElement);
      controls.enableDamping = true;
      controls.dampingFactor = 0.1;
      controls.rotateSpeed = 0.5;
      controls.minDistance = 3.5;
      controls.maxDistance = 10;

      const ambientLight = new THREE.AmbientLight(0xffffff, 3);
      scene.add(ambientLight);

      const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
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
        esconderTudo();
        removerRolamentos();
        removerParafusos();
        mostrarShape('white');

        document.getElementById('configAtual').textContent =
          'Shape: White | Rodinhas: — | Cor: Branco';

        atualizarStatus('✅ Modelo carregado com sucesso!', 'sucesso');
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
  </script>
  <script>
    function toggleGrupo(id, botao) {
      const grupo = document.getElementById(id);
      const aberto = grupo.classList.toggle("aberto");
      botao.classList.toggle("ativo", aberto);
    }
  </script>
</body>

</html>