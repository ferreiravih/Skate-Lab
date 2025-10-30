// Seleciona os elementos
const userIcon = document.getElementById("userIcon");

// Sidebars (podem ou não existir, dependendo do login)
const sidebarLogin = document.getElementById("sidebarLogin");
const sidebarCadastro = document.getElementById("sidebarCadastro");
const sidebarUsuario = document.getElementById("sidebarUsuario"); // A nova sidebar

// Botões (só existem se o user estiver deslogado)
const abrirCadastroBtn = document.getElementById("abrirCadastro");
const voltarLoginBtn = document.getElementById("voltarLogin");

// Abre o menu de login OU o menu de usuário
userIcon.addEventListener("click", () => {
  if (sidebarUsuario) {
    // Se está logado, só controla o sidebar do usuário
    sidebarUsuario.classList.toggle("active");
  } else if (sidebarLogin) {
    // Se está deslogado, controla o login (e fecha o cadastro)
    sidebarLogin.classList.toggle("active");
    if (sidebarCadastro) sidebarCadastro.classList.remove("active");
  }
});

// --- Lógica de Cadastro (só funciona se estiver deslogado) ---
if (abrirCadastroBtn && voltarLoginBtn) {
    abrirCadastroBtn.addEventListener("click", () => {
      sidebarLogin.classList.remove("active");
      sidebarCadastro.classList.add("active");
    });
    voltarLoginBtn.addEventListener("click", () => {
      sidebarCadastro.classList.remove("active");
      sidebarLogin.classList.add("active");
    });
}

// Fechar sidebar ao clicar fora
document.addEventListener("click", (event) => {
  // Verifica se clicou fora de QUALQUER sidebar ou do ícone
  if (
    (!sidebarLogin || !sidebarLogin.contains(event.target)) &&
    (!sidebarCadastro || !sidebarCadastro.contains(event.target)) &&
    (!sidebarUsuario || !sidebarUsuario.contains(event.target)) &&
    !userIcon.contains(event.target)
  ) {
    if (sidebarLogin) sidebarLogin.classList.remove("active");
    if (sidebarCadastro) sidebarCadastro.classList.remove("active");
    if (sidebarUsuario) sidebarUsuario.classList.remove("active");
  }
});