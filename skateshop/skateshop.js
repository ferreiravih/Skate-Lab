// Aguarda o carregamento completo da página
document.addEventListener("DOMContentLoaded", () => {
  const botoes = document.querySelectorAll(".categories button");
  const cards = document.querySelectorAll(".card");
  const inputPesquisa = document.querySelector(".search-bar input");

  // === FILTRO POR CATEGORIA ===
  botoes.forEach(botao => {
    botao.addEventListener("click", () => {
      // Remove a classe 'active' de todos os botões e adiciona ao clicado
      botoes.forEach(btn => btn.classList.remove("active"));
      botao.classList.add("active");

      const categoria = botao.textContent.trim().toLowerCase();

      // Mostra ou esconde cards conforme a categoria
      cards.forEach(card => {
        const cardCategoria = card.querySelector(".categoria")?.textContent.toLowerCase();

        if (categoria === "todos" || cardCategoria === categoria) {
          card.style.visibility = "visible";
          card.style.opacity = "1";
          card.style.position = "static";
          card.style.pointerEvents = "auto";
        } else {
          card.style.visibility = "hidden";
          card.style.opacity = "0";
          card.style.position = "absolute";
          card.style.pointerEvents = "none";
        }
      });
    });
  });

  // === FILTRO POR PESQUISA ===
  inputPesquisa.addEventListener("input", () => {
    const termo = inputPesquisa.value.toLowerCase();

    cards.forEach(card => {
      const titulo = card.querySelector("h3")?.textContent.toLowerCase();
      const categoria = card.querySelector(".categoria")?.textContent.toLowerCase();

      if (titulo.includes(termo) || categoria.includes(termo) || termo === "") {
        card.style.visibility = "visible";
        card.style.opacity = "1";
        card.style.position = "static";
        card.style.pointerEvents = "auto";
      } else {
        card.style.visibility = "hidden";
        card.style.opacity = "0";
        card.style.position = "absolute";
        card.style.pointerEvents = "none";
      }
    });
  });
});
