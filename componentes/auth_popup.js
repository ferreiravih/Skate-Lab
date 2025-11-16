document.addEventListener("DOMContentLoaded", () => {
 
    const authOverlay = document.getElementById("auth-modal-overlay");
    const authCloseBtn = document.getElementById("auth-modal-close");
    const authLoginBtn = document.getElementById("auth-modal-login-btn");
    const userIcon = document.getElementById("userIcon");
    const sidebarLogin = document.getElementById("sidebarLogin");

    if (typeof isUserLoggedIn !== 'undefined' && !isUserLoggedIn && authOverlay) {

        const protectedButtons = document.querySelectorAll(".form-protegido");

        protectedButtons.forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault(); 
                e.stopPropagation();
                authOverlay.classList.add("show");
            });
        });
        function closeModal() {
            authOverlay.classList.remove("show");
        }
        if (authCloseBtn) {
            authCloseBtn.addEventListener("click", closeModal);
        }
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