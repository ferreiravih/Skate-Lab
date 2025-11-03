// Espera o HTML carregar antes de rodar o script
document.addEventListener("DOMContentLoaded", () => {

  // --- LÓGICA EXISTENTE DA SIDEBAR ---
  const userIcon = document.getElementById("userIcon");
  const sidebarLogin = document.getElementById("sidebarLogin");
  const sidebarCadastro = document.getElementById("sidebarCadastro");
  const sidebarUsuario = document.getElementById("sidebarUsuario");
  const abrirCadastroBtn = document.getElementById("abrirCadastro");
  const voltarLoginBtn = document.getElementById("voltarLogin");

  if (userIcon) {
    userIcon.addEventListener("click", () => {
      if (sidebarUsuario) {
        sidebarUsuario.classList.toggle("active");
      } else if (sidebarLogin) {
        sidebarLogin.classList.toggle("active");
        if (sidebarCadastro) sidebarCadastro.classList.remove("active");
      }
    });
  }

  if (abrirCadastroBtn && voltarLoginBtn) {
    abrirCadastroBtn.addEventListener("click", () => {
      if (sidebarLogin) sidebarLogin.classList.remove("active");
      if (sidebarCadastro) sidebarCadastro.classList.add("active");
    });
    voltarLoginBtn.addEventListener("click", () => {
      if (sidebarCadastro) sidebarCadastro.classList.remove("active");
      if (sidebarLogin) sidebarLogin.classList.add("active");
    });
  }

  document.addEventListener("click", (event) => {
    if (
      (!sidebarLogin || !sidebarLogin.contains(event.target)) &&
      (!sidebarCadastro || !sidebarCadastro.contains(event.target)) &&
      (!sidebarUsuario || !sidebarUsuario.contains(event.target)) &&
      userIcon && !userIcon.contains(event.target)
    ) {
      if (sidebarLogin) sidebarLogin.classList.remove("active");
      if (sidebarCadastro) sidebarCadastro.classList.remove("active");
      if (sidebarUsuario) sidebarUsuario.classList.remove("active");
    }
  });

  
  // --- NOVA LÓGICA PARA MOSTRAR/OCULTAR SENHA ---

  // Função reutilizável para alternar a visibilidade
  const togglePasswordVisibility = (toggleBtn, input) => {
    if (input.type === "password") {
      input.type = "text";
      toggleBtn.classList.remove("fa-eye-slash"); // Ícone de olho fechado
      toggleBtn.classList.add("fa-eye"); // Ícone de olho aberto
    } else {
      input.type = "password";
      toggleBtn.classList.remove("fa-eye");
      toggleBtn.classList.add("fa-eye-slash");
    }
  };

  // 1. Para o formulário de Login
  const toggleLoginBtn = document.getElementById("toggleLoginPassword");
  const loginInput = document.getElementById("loginSenhaInput");
  if (toggleLoginBtn && loginInput) {
    toggleLoginBtn.addEventListener("click", () => {
      togglePasswordVisibility(toggleLoginBtn, loginInput);
    });
  }

  // 2. Para o formulário de Cadastro (Senha)
  const toggleRegisterBtn = document.getElementById("toggleRegisterPassword");
  const registerInput = document.getElementById("registerSenhaInput");
  if (toggleRegisterBtn && registerInput) {
    toggleRegisterBtn.addEventListener("click", () => {
      togglePasswordVisibility(toggleRegisterBtn, registerInput);
    });
  }

  // 3. Para o formulário de Cadastro (Confirmar Senha)
  const toggleConfirmBtn = document.getElementById("toggleConfirmPassword");
  const confirmInput = document.getElementById("confirmSenhaInput");
  if (toggleConfirmBtn && confirmInput) {
    toggleConfirmBtn.addEventListener("click", () => {
      togglePasswordVisibility(toggleConfirmBtn, confirmInput);
    });
  }


  // --- NOVA LÓGICA PARA VALIDAR "CONFIRMAR SENHA" (Front-end) ---
  const registerForm = document.getElementById("formCadastro");

  if (registerForm && registerInput && confirmInput) {
    registerForm.addEventListener("submit", (e) => {
      
      // Verifica se as senhas são diferentes
      if (registerInput.value !== confirmInput.value) {
        e.preventDefault(); // Impede o envio do formulário
        alert("As senhas não coincidem. Por favor, verifique.");
        confirmInput.focus(); // Coloca o foco no campo de confirmação
      }
    });
  }

}); // Fim do DOMContentLoaded