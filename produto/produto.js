// Atualiza o número no ícone do carrinho da navbar
function atualizarQtdNavbar() {
  fetch('../carrinho/quantidade_carrinho.php')
    .then(response => response.text())
    .then(qtd => {
      const spanNavbar = document.querySelector('.itenscarrinho1');
      if (spanNavbar) {
        spanNavbar.textContent = qtd;
      }
    });
}

// Atualiza o texto "X itens" na página carrinho.php (se existir)
function atualizarTextoCarrinho() {
  fetch('../carrinho/quantidade_carrinho.php')
    .then(response => response.text())
    .then(qtd => {
      const spanQtd = document.querySelector('.qtdcarrinho');
      if (spanQtd) {
        spanQtd.textContent = `${qtd} itens`;
      }
    });
}

// Função para enviar item ao carrinho
function adicionarAoCarrinho(id, nome, preco, quantidade = 1) {
  fetch('../carrinho/adicionar_carrinho.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `id=${id}&nome=${encodeURIComponent(nome)}&preco=${preco}&quantidade=${quantidade}`
  })
  .then(response => {
    if (response.ok) {
      atualizarQtdNavbar();
      atualizarTextoCarrinho();
      alert('Produto adicionado ao carrinho!');
    } else {
      alert('Erro ao adicionar produto.');
    }
  });
}

// Quando a página carregar
document.addEventListener("DOMContentLoaded", () => {
  atualizarQtdNavbar(); // Atualiza o carrinho da navbar ao carregar a página

  // Exemplo: troca da imagem principal ao clicar nas miniaturas
  const imagemPrincipal = document.getElementById("imagemPrincipal");
  const miniimg = document.querySelectorAll(".miniimg img");

  miniimg.forEach(img => {
    img.addEventListener("click", () => {
      imagemPrincipal.src = img.src;

      miniimg.forEach(i => i.classList.remove("ativa"));
      img.classList.add("ativa");
    });
  });

  // Exemplo: botão de adicionar ao carrinho
  const btnAdd = document.getElementById("btn-adicionar-carrinho");
  if (btnAdd) {
    btnAdd.addEventListener("click", () => {
      const id = btnAdd.dataset.id; // supondo que tenha data-id
      const nome = btnAdd.dataset.nome;
      const preco = btnAdd.dataset.preco;
      const quantidade = document.getElementById("qtdProduto").value || 1;

      adicionarAoCarrinho(id, nome, preco, quantidade);
    });
  }
});
