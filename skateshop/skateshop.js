document.addEventListener("DOMContentLoaded", () => {
  const categoriesContainer = document.querySelector(".categories");
  const botoes = categoriesContainer ? Array.from(categoriesContainer.querySelectorAll("button")) : [];
  const cards = Array.from(document.querySelectorAll(".card"));
  const inputPesquisa = document.querySelector(".search-bar input");

  if (!categoriesContainer || botoes.length === 0 || cards.length === 0) {
    return;
  }

  const normalizar = (valor = "") => {
    const texto = valor.toString().trim().toLowerCase();
    return typeof texto.normalize === "function"
      ? texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
      : texto;
  };

  let filtroCategoriaAtual = normalizar(categoriesContainer.dataset.default || "todos");
  let termoPesquisaNormalizado = "";

  const atualizarEstadoBotoes = () => {
    botoes.forEach((botao) => {
      const categoriaBotao = normalizar(botao.dataset.categoria || botao.textContent);
      botao.classList.toggle("active", categoriaBotao === filtroCategoriaAtual);
    });
  };

  const deveriaMostrarCard = (card) => {
    const categoriaDataset = normalizar(card.dataset.categoria || "");
    const titulo = normalizar(card.querySelector("h3")?.textContent || "");
    const categoriaTexto = normalizar(card.querySelector(".categoria")?.textContent || "");

    const coincideCategoria =
      filtroCategoriaAtual === "todos" ||
      categoriaDataset === filtroCategoriaAtual ||
      categoriaTexto === filtroCategoriaAtual;

    const coincidePesquisa =
      termoPesquisaNormalizado === "" ||
      titulo.includes(termoPesquisaNormalizado) ||
      categoriaTexto.includes(termoPesquisaNormalizado);

    return coincideCategoria && coincidePesquisa;
  };

  const atualizarCards = () => {
    cards.forEach((card) => {
      const mostrar = deveriaMostrarCard(card);
      card.style.visibility = mostrar ? "visible" : "hidden";
      card.style.opacity = mostrar ? "1" : "0";
      card.style.position = mostrar ? "static" : "absolute";
      card.style.pointerEvents = mostrar ? "auto" : "none";
    });
  };

  const aplicarFiltro = (novaCategoria = "todos") => {
    filtroCategoriaAtual = normalizar(novaCategoria);
    atualizarEstadoBotoes();
    atualizarCards();
  };

  botoes.forEach((botao) => {
    botao.addEventListener("click", (event) => {
      event.preventDefault();
      aplicarFiltro(botao.dataset.categoria || botao.textContent);
    });
  });

  if (inputPesquisa) {
    inputPesquisa.addEventListener("input", () => {
      termoPesquisaNormalizado = normalizar(inputPesquisa.value);
      atualizarCards();
    });
  }

  aplicarFiltro(categoriesContainer.dataset.default || "todos");
});


