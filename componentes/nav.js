// Seleciona os elementos
const userIcon = document.getElementById("userIcon");
const sidebarLogin = document.getElementById("sidebarLogin");
const sidebarCadastro = document.getElementById("sidebarCadastro");
const abrirCadastroBtn = document.getElementById("abrirCadastro");
const voltarLoginBtn = document.getElementById("voltarLogin");

// Abre o menu de login
userIcon.addEventListener("click", () => {
  sidebarLogin.classList.toggle("active");
  sidebarCadastro.classList.remove("active");
});

// Vai do login → para o cadastro
abrirCadastroBtn.addEventListener("click", () => {
  sidebarLogin.classList.remove("active");
  sidebarCadastro.classList.add("active");
});

// Volta do cadastro → para o login
voltarLoginBtn.addEventListener("click", () => {
  sidebarCadastro.classList.remove("active");
  sidebarLogin.classList.add("active");
});

// Fechar sidebar ao clicar fora
document.addEventListener("click", (event) => {
  // Verifica se clicou fora das sidebars e do ícone do usuário
  if (
    !sidebarLogin.contains(event.target) &&
    !sidebarCadastro.contains(event.target) &&
    !userIcon.contains(event.target)
  ) {
    sidebarLogin.classList.remove("active");
    sidebarCadastro.classList.remove("active");
  }
});




