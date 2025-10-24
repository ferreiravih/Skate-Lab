document.addEventListener("DOMContentLoaded", function() {
    const userIcon = document.getElementById("userIcon");
    const sidebar = document.getElementById("sidebar");

    // Alterna a sidebar ao clicar no ícone
    userIcon.addEventListener("click", function(e) {
        e.stopPropagation(); // impede que o clique feche imediatamente
        sidebar.classList.toggle("active");
    });

    // Fecha a sidebar se clicar fora dela
    document.addEventListener("click", function(e) {
        if (!sidebar.contains(e.target) && !userIcon.contains(e.target)) {
            sidebar.classList.remove("active");
        }
    });
});
