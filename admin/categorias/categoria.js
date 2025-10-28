// categoria.js

document.addEventListener("DOMContentLoaded", () => {
  const btnNova = document.querySelector(".btn-nova-categoria");
  const overlay = document.getElementById("overlayCategoria");
  const btnCancelar = document.getElementById("cancelarModal");

  // Abre o pop-up
  btnNova.addEventListener("click", () => {
    overlay.style.display = "flex";
  });

  // Fecha ao clicar em Cancelar
  btnCancelar.addEventListener("click", () => {
    overlay.style.display = "none";
  });

  // Fecha ao clicar fora do modal
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) overlay.style.display = "none";
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const btnNova = document.querySelector(".btn-nova-categoria");
  const overlay = document.getElementById("overlayCategoria");
  const btnCancelar = document.getElementById("cancelarModal");
  const btnSalvar = document.querySelector(".btn-salvar");
  const popup = document.getElementById("popupSucesso");

  // Abre o pop-up de nova categoria
  btnNova.addEventListener("click", () => {
    overlay.style.display = "flex";
  });

  // Fecha ao clicar em Cancelar
  btnCancelar.addEventListener("click", () => {
    overlay.style.display = "none";
  });

  // Fecha ao clicar fora do modal
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) overlay.style.display = "none";
  });

  // Salvar categoria
  btnSalvar.addEventListener("click", () => {
    // Aqui no futuro você pode adicionar a lógica de salvar no banco.
    
    // Fecha o modal
    overlay.style.display = "none";

    // Mostra o popup de sucesso
    popup.classList.add("mostrar");

    // Some depois de 3 segundos
    setTimeout(() => {
      popup.classList.remove("mostrar");
    }, 3000);
  });
});
// categoria-status.js
document.addEventListener("DOMContentLoaded", () => {
  // Seleciona todos os botões de ação dentro da coluna de ações
  const botoes = document.querySelectorAll(".coluna-acoes .botao-acao");

  botoes.forEach(btn => {
    // Verifica se o botão tem o ícone de olho (ver/ocultar)
    const icone = btn.querySelector("i.ri-eye-line, i.ri-eye-off-line");
    if (!icone) return; // ignora se não for o botão de "ver"

    btn.addEventListener("click", (e) => {
      e.preventDefault();

      // Pega a linha da tabela referente a esse botão
      const linha = btn.closest("tr");
      if (!linha) return;

      // Pega o span de status (Ativo/Inativo)
      const statusSpan = linha.querySelector("span.status");
      if (!statusSpan) return;

      // Alterna o status e o ícone
      if (statusSpan.classList.contains("ativo")) {
        statusSpan.classList.remove("ativo");
        statusSpan.classList.add("inativo");
        statusSpan.textContent = "Inativo";

        icone.classList.remove("ri-eye-line");
        icone.classList.add("ri-eye-off-line");
      } else {
        statusSpan.classList.remove("inativo");
        statusSpan.classList.add("ativo");
        statusSpan.textContent = "Ativo";

        icone.classList.remove("ri-eye-off-line");
        icone.classList.add("ri-eye-line");
      }
    });
  });
});
