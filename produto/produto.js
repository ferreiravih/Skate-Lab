document.addEventListener("DOMContentLoaded", () => {
  // --- Seletores flexíveis (tenta várias opções) ---
  const searchInput =
    document.querySelector(".search-bar input")
    || document.querySelector(".search-container input")
    || document.querySelector(".search input")
    || document.querySelector("input[placeholder='Pesquisar produtos...']");

  const categoryButtons = document.querySelectorAll(".categories button");
  const cards = Array.from(document.querySelectorAll(".card"));

  console.log("filtros.js carregado", { searchInput, categoryButtonsCount: categoryButtons.length, cardsCount: cards.length });

  if (!cards.length) {
    console.warn("Nenhum .card encontrado. Verifique se as classes estão corretas no HTML.");
    return;
  }

  // função que mostra/esconde cards (usa classe em vez de inline styles)
  function showCard(card) {
    card.classList.remove("card--hidden");
  }
  function hideCard(card) {
    card.classList.add("card--hidden");
  }

  // função que pega categoria do card (data-categoria ou texto)
  function getCardCategory(card) {
    const dataCat = card.getAttribute("data-categoria");
    if (dataCat) return dataCat.trim().toLowerCase();
    const catEl = card.querySelector(".categoria");
    if (catEl) return catEl.textContent.trim().toLowerCase();
    return ""; // fallback
  }

  // === FILTRO POR CATEGORIA ===
  if (categoryButtons.length) {
    categoryButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        // ativa botão
        categoryButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const categoria = btn.textContent.trim().toLowerCase();
        console.log("Filtro categoria:", categoria);

        cards.forEach(card => {
          const cardCat = getCardCategory(card);
          if (categoria === "todos" || categoria === "todos" || cardCat === categoria) {
            showCard(card);
          } else {
            hideCard(card);
          }
        });
      });
    });
  } else {
    console.warn("Nenhum botão de categoria encontrado com .categories button");
  }

  // === PESQUISA ===
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const termo = searchInput.value.trim().toLowerCase();
      // pega categoria ativa (se tiver)
      const activeBtn = document.querySelector(".categories button.active");
      const activeCategoria = activeBtn ? activeBtn.textContent.trim().toLowerCase() : "todos";

      cards.forEach(card => {
        const title = card.querySelector("h3")?.textContent.trim().toLowerCase() || "";
        const cardCat = getCardCategory(card);

        const matchesSearch = termo === "" || title.includes(termo) || cardCat.includes(termo);
        const matchesCategory = (activeCategoria === "todos") || (cardCat === activeCategoria);

        if (matchesSearch && matchesCategory) {
          showCard(card);
        } else {
          hideCard(card);
        }
      });
    });
  } else {
    console.warn("Input de busca não encontrado. Verifique o seletor.");
  }

  // opcional: rodar uma vez para aplicar 'Todos' inicialmente (garante estado)
  const firstBtn = document.querySelector(".categories button.active") || document.querySelector(".categories button");
  if (firstBtn) firstBtn.click();
});





document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector(".search-bar input");
  const categoryButtons = document.querySelectorAll(".categories button");
  const productCards = document.querySelectorAll(".card");

  // ---- FILTRAR POR CATEGORIA ----
  categoryButtons.forEach(button => {
    button.addEventListener("click", () => {
      // Remove classe ativa de todos e adiciona no clicado
      categoryButtons.forEach(btn => btn.classList.remove("active"));
      button.classList.add("active");

      const category = button.textContent.trim().toUpperCase();

      productCards.forEach(card => {
        const cardCategory = card.querySelector(".categoria").textContent.trim().toUpperCase();

        if (category === "TODOS" || cardCategory === category) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    });
  });

  // ---- FILTRAR POR PESQUISA ----
  searchInput.addEventListener("input", () => {
    const searchText = searchInput.value.toLowerCase();

    productCards.forEach(card => {
      const title = card.querySelector("h3").textContent.toLowerCase();
      const category = card.querySelector(".categoria").textContent.toLowerCase();

      if (title.includes(searchText) || category.includes(searchText)) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  });
});
