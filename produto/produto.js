// Espera o conteúdo carregar
document.addEventListener("DOMContentLoaded", () => {
  const imagemPrincipal = document.getElementById("imagemPrincipal");
  const miniimg = document.querySelectorAll(".miniimg img");

  // percorre todas as miniaturas
  miniimg.forEach(img => {
    img.addEventListener("click", () => {
      // muda a imagem principal pro caminho da miniatura clicada
      imagemPrincipal.src = img.src;

      // opcional: destaque visual na miniatura ativa
      miniimg.forEach(i => i.classList.remove("ativa"));
      img.classList.add("ativa");
    });
  });
});
