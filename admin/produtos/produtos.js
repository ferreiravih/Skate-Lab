// produtos.js
document.addEventListener("DOMContentLoaded", () => {
  // Seleciona todos os botões de ação (cada botão pode ter diferentes ícones)
  const botoes = document.querySelectorAll(".acoes .btn-acao");

  botoes.forEach(btn => {
    // Só queremos atuar nos botões que tenham um ícone de olho dentro
    const icone = btn.querySelector("i.ri-eye-line, i.ri-eye-off-line");
    if (!icone) return; // se não for botão de "ver", ignora

    // adiciona o listener no botão (não só no <i>) para garantir clique em qualquer lugar do botão
    btn.addEventListener("click", (e) => {
      // impede outros comportamentos indesejados (se houver)
      e.preventDefault();

      // pega a linha (<tr>) correspondente
      const linha = btn.closest("tr");
      if (!linha) return;

      // procura o span de status nessa linha (procura especificamente pelas classes)
      const statusSpan = linha.querySelector("span.ativo, span.inativo");
      if (!statusSpan) return;

      // alterna o ícone e o status
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
