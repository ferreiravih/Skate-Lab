// produtos.js
document.addEventListener("DOMContentLoaded", () => {

  const botoes = document.querySelectorAll(".acoes .btn-acao");

  botoes.forEach(btn => {

    const icone = btn.querySelector("i.ri-eye-line, i.ri-eye-off-line");
    if (!icone) return; 


    btn.addEventListener("click", (e) => {

      e.preventDefault();


      const linha = btn.closest("tr");
      if (!linha) return;


      const statusSpan = linha.querySelector("span.ativo, span.inativo");
      if (!statusSpan) return;


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
    const inputBuscaProdutos = document.getElementById("buscarProduto");
  const tabelaProdutos = document.querySelector(".tabela");

  if (inputBuscaProdutos && tabelaProdutos) {
      

      const linhasProdutos = tabelaProdutos.querySelectorAll("tbody tr");


      inputBuscaProdutos.addEventListener("input", () => {
          const termoBusca = inputBuscaProdutos.value.toLowerCase();

          linhasProdutos.forEach(linha => {

              const textoLinha = linha.textContent.toLowerCase();


              if (textoLinha.includes(termoBusca)) {
                  linha.style.display = ""; 
              } else {
                  linha.style.display = "none"; 
              }
          });
      });
  }
  });
});
