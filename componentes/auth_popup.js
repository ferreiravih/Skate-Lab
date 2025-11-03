// Arquivo: /componentes/auth_popup.js
document.addEventListener("DOMContentLoaded", () => {
    
    // Pega os elementos do novo Modal
    const authOverlay = document.getElementById("auth-modal-overlay");
    const authCloseBtn = document.getElementById("auth-modal-close");
    const authLoginBtn = document.getElementById("auth-modal-login-btn");
    
    // Pega os elementos da Sidebar de Login (do nav.js)
    const userIcon = document.getElementById("userIcon");
    const sidebarLogin = document.getElementById("sidebarLogin");

    // 'isUserLoggedIn' foi definido no navbar.php
    if (typeof isUserLoggedIn !== 'undefined' && !isUserLoggedIn && authOverlay) {
    
        // 1. Encontra TODOS os botões de comprar/carrinho
        const protectedButtons = document.querySelectorAll(".form-protegido");
        
        // 2. Adiciona um "ouvinte" de clique em cada um
        protectedButtons.forEach(button => {
            button.addEventListener("click", (e) => {
                
                // 3. Impede o formulário de ser enviado!
                e.preventDefault(); 
                
                // 4. MOSTRA O NOVO MODAL (em vez do alert)
                authOverlay.classList.add("show");
            });
        });

        // 5. Função para fechar o modal
        function closeModal() {
            authOverlay.classList.remove("show");
        }

        // 6. Ações dos botões do modal
        if (authCloseBtn) {
            authCloseBtn.addEventListener("click", closeModal);
        }
        
        // Clicar fora do modal também fecha
        authOverlay.addEventListener("click", (e) => {
            if (e.target === authOverlay) {
                closeModal();
            }
        });

        
        if (authLoginBtn && userIcon && sidebarLogin) {
            authLoginBtn.addEventListener("click", () => {
                closeModal();
                
               
                sidebarLogin.classList.add("active");
            });
        }
    }
});