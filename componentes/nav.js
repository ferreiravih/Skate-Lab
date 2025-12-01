
document.addEventListener("DOMContentLoaded", () => {


  const userIcon = document.getElementById("userIcon");
  const sidebarLogin = document.getElementById("sidebarLogin");
  const sidebarCadastro = document.getElementById("sidebarCadastro");
  const sidebarUsuario = document.getElementById("sidebarUsuario");
  const abrirCadastroBtn = document.getElementById("abrirCadastro");
  const voltarLoginBtn = document.getElementById("voltarLogin");
  const authModal = document.getElementById("auth-modal-overlay");
  

  const loginErrorDiv = document.getElementById("login-error-message");
  const registerErrorDiv = document.getElementById("register-error-message");

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
      (!authModal || !authModal.contains(event.target)) &&
      userIcon && !userIcon.contains(event.target)
    ) {
      if (sidebarLogin) sidebarLogin.classList.remove("active");
      if (sidebarCadastro) sidebarCadastro.classList.remove("active");
      if (sidebarUsuario) sidebarUsuario.classList.remove("active");
    }
  });

  


  const togglePasswordVisibility = (toggleBtn, input) => {
    if (input.type === "password") {
      input.type = "text";
      toggleBtn.classList.remove("fa-eye-slash"); 
      toggleBtn.classList.add("fa-eye"); 
    } else {
      input.type = "password";
      toggleBtn.classList.remove("fa-eye");
      toggleBtn.classList.add("fa-eye-slash");
    }
  };

  const toggleLoginBtn = document.getElementById("toggleLoginPassword");
  const loginInput = document.getElementById("loginSenhaInput");
  if (toggleLoginBtn && loginInput) {
    toggleLoginBtn.addEventListener("click", () => {
      togglePasswordVisibility(toggleLoginBtn, loginInput);
    });
  }

  const toggleRegisterBtn = document.getElementById("toggleRegisterPassword");
  const registerInput = document.getElementById("registerSenhaInput");
  if (toggleRegisterBtn && registerInput) {
    toggleRegisterBtn.addEventListener("click", () => {
      togglePasswordVisibility(toggleRegisterBtn, registerInput);
    });
  }

  const toggleConfirmBtn = document.getElementById("toggleConfirmPassword");
  const confirmInput = document.getElementById("confirmSenhaInput");
  if (toggleConfirmBtn && confirmInput) {
    toggleConfirmBtn.addEventListener("click", () => {
      togglePasswordVisibility(toggleConfirmBtn, confirmInput);
    });
  }


  const registerForm = document.getElementById("formCadastro");

  if (registerForm && registerInput && confirmInput) {
    registerForm.addEventListener("submit", (e) => {
      
      if (registerInput.value !== confirmInput.value) {
        e.preventDefault(); 
        

        if(registerErrorDiv) {
            registerErrorDiv.textContent = "As senhas não coincidem. Por favor, verifique.";
            registerErrorDiv.style.display = "block";
        } else {
            alert("As senhas não coincidem. Por favor, verifique.");
        }
        confirmInput.focus(); 
      }
    });
  }


  
  const urlParams = new URLSearchParams(window.location.search);
  const error = urlParams.get('error');

  if (error && (loginErrorDiv || registerErrorDiv)) {
      let errorMessage = "Ocorreu um erro desconhecido.";
      let sidebarToOpen = null;
      let divToUse = null;

      switch (error) {
          case 'empty':
              errorMessage = "Por favor, preencha todos os campos.";
              sidebarToOpen = sidebarLogin;
              divToUse = loginErrorDiv;
              break;
          case 'invalid':
              errorMessage = "Email ou senha incorretos.";
              sidebarToOpen = sidebarLogin;
              divToUse = loginErrorDiv;
              break;
          case 'register_empty':
              errorMessage = "Por favor, preencha todos os campos.";
              sidebarToOpen = sidebarCadastro;
              divToUse = registerErrorDiv;
              break;
          case 'password_mismatch':
              errorMessage = "As senhas não coincidem.";
              sidebarToOpen = sidebarCadastro;
              divToUse = registerErrorDiv;
              break;
          case 'email_exists':
              errorMessage = "Este email já está cadastrado.";
              sidebarToOpen = sidebarCadastro;
              divToUse = registerErrorDiv;
              break;
          case 'db':
          case 'db_register':
              errorMessage = "Erro no banco de dados. Tente mais tarde.";
              sidebarToOpen = sidebarLogin;
              divToUse = loginErrorDiv;
              break;
      }

      if (divToUse && sidebarToOpen) {
          divToUse.textContent = errorMessage;
          divToUse.style.display = "block"; 
          sidebarToOpen.classList.add("active"); 
      }

      window.history.replaceState({}, document.title, window.location.pathname);
  }

}); 