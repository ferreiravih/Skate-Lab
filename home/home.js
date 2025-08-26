document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener("click", function(e) {
    e.preventDefault();

    const destino = document.querySelector(this.getAttribute("href"));
    destino.scrollIntoView({
      behavior: "smooth",
      block: "start"
    });
  });
});