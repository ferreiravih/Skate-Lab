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

// =============================================
// LÓGICA DE PREÇO
// =============================================
const configAtual = {
    shape: 'Shape Branco',
    truck: 'Truck Padrão',
    rodinha: 'Nenhuma',
    rolamento: 'Nenhum',
    parafuso: 'Nenhum'
};

const PRECOS = {
    shape: 120,    // Preço base do shape 'white'
    truck: 0,      // Preço base do truck 'padrao'
    rodinha: 0,  // Preço base (sem rodinha)
    rolamento: 0,
    parafuso: 0
};

function atualizarPrecoTotal() {
    // 1. Soma todos os preços
    const total = PRECOS.shape + PRECOS.truck + PRECOS.rodinha + PRECOS.rolamento + PRECOS.parafuso;
    
    // 2. Formata como R$
    const precoFormatado = `R$ ${total.toFixed(2).replace('.', ',')}`;
    
    // 3. Atualiza o visor no topo
    const precoDisplay = document.getElementById('preco-total-display');
    if (precoDisplay) {
        precoDisplay.textContent = precoFormatado;
    }

    // 4. Atualiza os inputs escondidos do formulário
    const nomeProduto = `Skate Customizado (${configAtual.shape})`;
    // Gera um ID único baseado nas peças
    const idProduto = `custom-${configAtual.shape}-${configAtual.truck}-${configAtual.rodinha}`.toLowerCase().replace(/ /g, '-');
    
    // Cria a descrição
    let descItens = [];
    if (configAtual.shape) descItens.push(`Shape: ${configAtual.shape}`);
    if (configAtual.truck && configAtual.truck !== 'Truck Padrão') descItens.push(`Truck: ${configAtual.truck}`);
    if (configAtual.rodinha && configAtual.rodinha !== 'Nenhuma') descItens.push(`Roda: ${configAtual.rodinha}`);
    if (configAtual.rolamento && configAtual.rolamento !== 'Nenhum') descItens.push(`Rolamento: ${configAtual.rolamento}`);
    if (configAtual.parafuso && configAtual.parafuso !== 'Nenhum') descItens.push(`Parafuso: ${configAtual.parafuso}`);

    const cartId = document.getElementById('cart-id');
    const cartNome = document.getElementById('cart-nome');
    const cartPreco = document.getElementById('cart-preco');
    const cartDesc = document.getElementById('cart-descricao');

    if (cartId) cartId.value = idProduto;
    if (cartNome) cartNome.value = nomeProduto;
    if (cartPreco) cartPreco.value = total.toFixed(2);
    if (cartDesc) cartDesc.value = descItens.join(', ');
}


// =============================================
// LÓGICA 3D (O SEU CÓDIGO)
// =============================================

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
  const tipo = (configAtual.shape || 'white').toLowerCase().replace(/ /g, '-'); // Garante que o nome corresponda
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
  const lista = (rodinhasAtuais && rodinhasModelos[rodinhasAtuais]) ? rodinhasModelos[rodinhasAtuais] : [];
  visRodinhas = !visRodinhas;
  setListaVisible(lista, visRodinhas);
  setBtnLabel('btnRodinhas', !visRodinhas);
}

function toggleRolamentos() {
  visRolamentos = !visRolamentos;
  setListaVisible(rolamentos, visRolamentos);
}

function toggleParafusos() {
  visParafusos = !visParafusos;
  setListaVisible(parafusos, visParafusos);
}

// 🎯 SHAPES (Nomes-chave do modelo 3D)
// VERIFIQUE SE ESTES NOMES BATEM COM O SEU url_m3d
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
  sasuke: ['Object_15...'] // Exemplo: Adicione os seus aqui
  // ... adicione todos os nomes da sua coluna url_m3d
};

// ✅ RODINHAS (Nomes-chave do modelo 3D)
// VERIFIQUE SE ESTES NOMES BATEM COM O SEU url_m3d
const rodinhasModelos = {
  sasuke: ['Cylinder003', 'Cylinder003_1', 'Cylinder003_2', 'Cylinder003_3', 'Cylinder003_4', 'Cylinder008', 'Cylinder008_1', 'Cylinder008_2', 'Cylinder008_3', 'Cylinder008_4', 'Cylinder010', 'Cylinder010_1', 'Cylinder010_2', 'Cylinder010_3', 'Cylinder010_4', 'Cylinder011', 'Cylinder011_1', 'Cylinder011_2', 'Cylinder011_3', 'Cylinder011_4'],
  circus: ['Cylinder001', 'Cylinder001_1', 'Cylinder001_2', 'Cylinder001_3', 'Cylinder001_4', 'Cylinder005', 'Cylinder005_1', 'Cylinder005_2', 'Cylinder005_3', 'Cylinder005_4', 'Cylinder006', 'Cylinder006_1', 'Cylinder006_2', 'Cylinder006_3', 'Cylinder006_4', 'Cylinder007', 'Cylinder007_1', 'Cylinder007_2', 'Cylinder007_3', 'Cylinder007_4'],
  tails: ['Cylinder014', 'Cylinder014_1', 'Cylinder014_2', 'Cylinder014_3', 'Cylinder014_4', 'Cylinder015', 'Cylinder015_1', 'Cylinder015_2', 'Cylinder015_3', 'Cylinder015_4', 'Cylinder016', 'Cylinder016_1', 'Cylinder016_2', 'Cylinder016_3', 'Cylinder016_4', 'Cylinder017', 'Cylinder017_1', 'Cylinder017_2', 'Cylinder017_3', 'Cylinder017_4'],
  stitch: ['Cylinder018', 'Cylinder018_1', 'Cylinder018_2', 'Cylinder018_3', 'Cylinder018_4', 'Cylinder019', 'Cylinder019_1', 'Cylinder019_2', 'Cylinder019_3', 'Cylinder019_4', 'Cylinder020', 'Cylinder020_1', 'Cylinder020_2', 'Cylinder020_3', 'Cylinder020_4', 'Cylinder021', 'Cylinder021_1', 'Cylinder021_2', 'Cylinder021_3', 'Cylinder021_4'],
  hq: ['Cylinder022', 'Cylinder022_1', 'Cylinder022_2', 'Cylinder022_3', 'Cylinder022_4', 'Cylinder023', 'Cylinder023_1', 'Cylinder023_2', 'Cylinder023_3', 'Cylinder023_4', 'Cylinder024', 'Cylinder024_1', 'Cylinder024_2', 'Cylinder024_3', 'Cylinder024_4', 'Cylinder025', 'Cylinder025_1', 'Cylinder025_2', 'Cylinder025_3', 'Cylinder025_4'],
  hello: ['Cylinder030', 'Cylinder030_1', 'Cylinder030_2', 'Cylinder030_3', 'Cylinder030_4', 'Cylinder031', 'Cylinder031_1', 'Cylinder031_2', 'Cylinder031_3', 'Cylinder031_4', 'Cylinder032', 'Cylinder032_1', 'Cylinder032_2', 'Cylinder032_3', 'Cylinder032_4', 'Cylinder033', 'Cylinder033_1', 'Cylinder033_2', 'Cylinder033_3', 'Cylinder033_4'],
  fire: ['Cylinder034', 'Cylinder034_1', 'Cylinder034_2', 'Cylinder034_3', 'Cylinder034_4', 'Cylinder035', 'Cylinder035_1', 'Cylinder035_2', 'Cylinder035_3', 'Cylinder035_4', 'Cylinder036', 'Cylinder036_1', 'Cylinder036_2', 'Cylinder036_3', 'Cylinder036_4', 'Cylinder037', 'Cylinder037_1', 'Cylinder037_2', 'Cylinder037_3', 'Cylinder037_4'],
  kuro: ['Cylinder042', 'Cylinder042_1', 'Cylinder042_2', 'Cylinder042_3', 'Cylinder042_4', 'Cylinder043', 'Cylinder043_1', 'Cylinder043_2', 'Cylinder043_3', 'Cylinder043_4', 'Cylinder044', 'Cylinder044_1', 'Cylinder044_2', 'Cylinder044_3', 'Cylinder044_4', 'Cylinder045', 'Cylinder045_1', 'Cylinder045_2', 'Cylinder045_3', 'Cylinder045_4'],
  lisa: [] // Se 'lisa' é um truck, não devia estar aqui
};

// ✅ TRUCKS (Nomes-chave do modelo 3D)
// VERIFIQUE SE ESTES NOMES BATEM COM O SEU url_m3d
const trucks_padrao = [];
const trucks_kuromi = ['Circle002', 'Circle003', 'Circle002_1', 'Circle002_2', 'Circle002_3', 'Circle002_4', 'Circle003_1', 'Circle003_2', 'Circle003_3', 'Circle003_4'];
const trucks_stitch = ['Circle001', 'Circle009', 'Circle001_1', 'Circle001_2', 'Circle001_3', 'Circle001_4', 'Circle009_1', 'Circle009_2', 'Circle009_3', 'Circle009_4'];
const trucks_black = ['Circle004', 'Circle004_1', 'Circle004_2', 'Circle004_3', 'Circle004_4', 'Circle006', 'Circle006_1', 'Circle006_2', 'Circle006_3', 'Circle006_4'];
const trucks_hello = ['Circle008', 'Circle008_1', 'Circle004_2', 'Circle008_3', 'Circle008_4', 'Circle010', 'Circle010_1', 'Circle010_2', 'Circle010_3', 'Circle010_4'];
const trucks_miranha = ['Circle005', 'Circle005_1', 'Circle005_2', 'Circle005_3', 'Circle005_4', 'Circle015', 'Circle015_1', 'Circle015_2', 'Circle015_3', 'Circle015_4'];
const trucks_hq = ['Circle012', 'Circle012_1', 'Circle012_2', 'Circle012_3', 'Circle012_4', 'Circle014', 'Circle014_1', 'Circle014_2', 'Circle014_3', 'Circle014_4'];
const trucks_lisa = ['Circle024', 'Circle024_1', 'Circle024_2', 'Circle024_3', 'Circle024_4', 'Circle025', 'Circle025_1', 'Circle025_2', 'Circle025_3', 'Circle025_4'];
// ... adicione todos os nomes da sua coluna url_m3d

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
let trucks = trucksModelos.padrao;
let shapeAtual = 'white';
let rodinhasAtuais = null;

// ✅ ROLAMENTOS & PARAFUSOS
const rolamentos_padrao = ["Radial_ball_bearing_type_1000088001", "Radial_ball_bearing_type_1000088002", "Radial_ball_bearing_type_1000088003", "Radial_ball_bearing_type_1000088004"];
const parafusos_padrao = ["Bolt001", "Bolt002", "Bolt003", "Bolt004", "Bolt005", "Bolt006", "Bolt007", "Bolt008", "Bolt009", "Bolt010", "Bolt011", "Bolt073"];
let rolamentos = [];
let parafusos = [];
const rolamentosModelos = { padrao: rolamentos_padrao };
const parafusosModelos = { padrao: parafusos_padrao };

// ===== Utils UI =====
function atualizarStatus(mensagem, tipo = 'info') {
  const status = document.getElementById('status');
  if(status) status.innerHTML = mensagem;
}

// ===== Seletores =====
function selecionarTrucks(modelo) {
  const todas = Object.values(trucksModelos).flat();
  todas.forEach(n => {
    if (todasAsPecas[n]) todasAsPecas[n].visible = false;
  });
  const lista = trucksModelos[modelo] || [];
  lista.forEach(n => {
    const mesh = todasAsPecas[n];
    if (mesh) {
      mesh.visible = visTrucks; // Respeita o toggle
      if (mesh.material && mesh.material.color) mesh.material.color.set(corTrucksAtual);
    }
  });
  trucks = lista;
  
  const botaoClicado = [...document.querySelectorAll('#truckGrupo .pena')].find(b => b.getAttribute('onclick') === `selecionarTrucks('${modelo}')`);
  PRECOS.truck = parseFloat(botaoClicado?.dataset.price || 0);
  configAtual.truck = botaoClicado?.dataset.name || 'Padrão';
  
  document.querySelectorAll('#truckGrupo .pena').forEach(btn => btn.classList.remove('ativo'));
  if (botaoClicado) botaoClicado.classList.add('ativo');

  atualizarPrecoTotal();
}

function selecionarRolamentos(modelo) {
  const todosRolos = Object.values(rolamentosModelos).flat();
  todosRolos.forEach(n => {
    if (todasAsPecas[n]) todasAsPecas[n].visible = false;
  });
  const lista = rolamentosModelos[modelo] || [];
  lista.forEach(n => {
    const mesh = todasAsPecas[n];
    if (mesh) {
      mesh.visible = visRolamentos;
    }
  });
  rolamentos = lista;

  const botaoClicado = document.querySelector(`button[onclick="selecionarRolamentos('${modelo}')"]`);
  PRECOS.rolamento = parseFloat(botaoClicado?.dataset.price || 0);
  configAtual.rolamento = botaoClicado?.dataset.name || 'Padrão';
  atualizarPrecoTotal();
}

function selecionarParafusos(modelo) {
  const todosParafusos = Object.values(parafusosModelos).flat();
  todosParafusos.forEach(n => {
    if (todasAsPecas[n]) todasAsPecas[n].visible = false;
  });
  const lista = parafusosModelos[modelo] || [];
  lista.forEach(n => {
    const mesh = todasAsPecas[n];
    if (mesh) {
      mesh.visible = visParafusos;
    }
  });
  parafusos = lista;
  
  const botaoClicado = document.querySelector(`button[onclick="selecionarParafusos('${modelo}')"]`);
  PRECOS.parafuso = parseFloat(botaoClicado?.dataset.price || 0);
  configAtual.parafuso = botaoClicado?.dataset.name || 'Padrão';
  atualizarPrecoTotal();
}

function removerRolamentos() {
  const todosRolos = Object.values(rolamentosModelos).flat();
  todosRolos.forEach(n => {
    if (todasAsPecas[n]) todasAsPecas[n].visible = false;
  });
  rolamentos = [];
  
  PRECOS.rolamento = 0;
  configAtual.rolamento = 'Nenhum';
  atualizarPrecoTotal();
}

function removerParafusos() {
  const todosParafusos = Object.values(parafusosModelos).flat();
  todosParafusos.forEach(n => {
    if (todasAsPecas[n]) todasAsPecas[n].visible = false;
  });
  parafusos = [];
  
  PRECOS.parafuso = 0;
  configAtual.parafuso = 'Nenhum';
  atualizarPrecoTotal();
}

function resetCamera() {
  if (controls) controls.reset();
  camera.position.set(1, 0, 0);
  camera.lookAt(0, 1, 0);
  if (controls) controls.update();
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
  if (controls) controls.update();
  atualizarStatus(` Vista ${tipo} ativada`, 'info');
}

function exportarImagem() {
  if (renderer) renderer.render(scene, camera);
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
    shape: configAtual.shape,
    rodinhas: configAtual.rodinha,
    truck: configAtual.truck,
    data: new Date().toLocaleString('pt-BR')
  };
  localStorage.setItem('skateConfig', JSON.stringify(config, null, 2));
  
  const configAtualSpan = document.getElementById('configAtual');
  if (configAtualSpan) {
    configAtualSpan.textContent = `Shape: ${config.shape} | Rodinhas: ${config.rodinha} | Truck: ${config.truck}`;
  }
  atualizarStatus('💾 Configuração salva!', 'sucesso');
}

// ===== Mostrar =====
function mostrarShape(tipo) {
  // Esconde todos os shapes
  const todosShapes = Object.values(shapes).flat();
  todosShapes.forEach(n => {
    if(todasAsPecas[n]) todasAsPecas[n].visible = false;
  });

  // Mostra o shape selecionado
  (shapes[tipo] || []).forEach(nome => {
    if (todasAsPecas[nome]) {
      todasAsPecas[nome].visible = visShape; // Respeita o toggle
    }
  });
  shapeAtual = tipo; // Guarda o ID (ex: 'luffy')

  // Re-aplica as outras partes para ficarem visíveis
  setListaVisible(trucks, visTrucks);
  setListaVisible(rodinhasAtuais ? rodinhasModelos[rodinhasAtuais] : [], visRodinhas);
  setListaVisible(rolamentos, visRolamentos);
  setListaVisible(parafusos, visParafusos);

  // Atualiza Preço e Config
  const botaoClicado = [...document.querySelectorAll('#shapeGrupo .pena')].find(b => b.getAttribute('onclick') === `mostrarShape('${tipo}')`);
  PRECOS.shape = parseFloat(botaoClicado?.dataset.price || 0);
  configAtual.shape = botaoClicado?.dataset.name || 'Desconhecido';
  
  document.querySelectorAll('#shapeGrupo .pena').forEach(btn => btn.classList.remove('ativo'));
  if (botaoClicado) botaoClicado.classList.add('ativo');

  atualizarPrecoTotal();
}

function mostrarRodinhas(tipo) {
  const todas = Object.values(rodinhasModelos).flat();
  todas.forEach(nome => {
    if (todasAsPecas[nome]) todasAsPecas[nome].visible = false;
  });
  const lista = rodinhasModelos[tipo] || [];
  lista.forEach(nome => {
    if (todasAsPecas[nome]) {
      todasAsPecas[nome].visible = visRodinhas; // Respeita o toggle
    }
  });
  rodinhasAtuais = tipo; // Guarda o ID (ex: 'sasuke')

  const botaoClicado = [...document.querySelectorAll('#rodinhaGrupo .pena')].find(b => b.getAttribute('onclick') === `mostrarRodinhas('${tipo}')`);
  PRECOS.rodinha = parseFloat(botaoClicado?.dataset.price || 0);
  configAtual.rodinha = botaoClicado?.dataset.name || 'Nenhuma';
  
  document.querySelectorAll('#rodinhaGrupo .pena').forEach(btn => btn.classList.remove('ativo'));
  if (botaoClicado) botaoClicado.classList.add('ativo');
  
  atualizarPrecoTotal();
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

function organizarTodasAsPecas(modelo) {
  todasAsPecas = {}; 
  modelo.traverse((child) => {
    if (child.isMesh && child.name) {
      todasAsPecas[child.name] = child;
    }
  });
  console.log("Peças detectadas:", Object.keys(todasAsPecas));
}

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

    const configAtualSpan = document.getElementById('configAtual');
    if (configAtualSpan) {
        configAtualSpan.textContent = 'Shape: White | Rodinhas: — | Truck: Padrão';
    }

    atualizarStatus('✅ Modelo carregado com sucesso!', 'sucesso');
    
    // Define o preço inicial
    atualizarPrecoTotal(); 

  }, undefined, (error) => {
    console.error('❌ Erro ao carregar modelo:', error);
    atualizarStatus('❌ Erro ao carregar modelo', 'erro');
  });
}

function animate() {
  requestAnimationFrame(animate);
  if (controls) controls.update();
  if (renderer) renderer.render(scene, camera);
}

window.addEventListener('resize', ajustarTamanhoCanvas);

// Inicia a cena 3D
init();