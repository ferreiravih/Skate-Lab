document.addEventListener("DOMContentLoaded", () => {
    
    const favButton = document.querySelector('.btn-favorito-bloco');

    // 'isUserLoggedIn' é uma variável global definida no navbar.php
    // Verificamos se ela existe e se é 'true'
    if (favButton && typeof isUserLoggedIn !== 'undefined' && isUserLoggedIn) {
        
        // --- O USUÁRIO ESTÁ LOGADO ---
        // Adicionamos o listener de clique que faz o fetch
        favButton.addEventListener('click', () => {
            const idPeca = favButton.dataset.idPeca;
            if (idPeca) {
                const textSpan = favButton.querySelector('.btn-fav-text');
                toggleFavoritoProduto(idPeca, favButton, textSpan);
            }
        });

    } 
    
    // --- O USUÁRIO ESTÁ DESLOGADO ---
    // Este script não faz NADA.
    // O auth_popup.js vai cuidar do clique (por causa da classe .form-protegido)
    // e não haverá nenhum conflito.
    
});


/**
 * Função assíncrona para a PÁGINA DE PRODUTO
 * (Esta função SÓ é chamada se o usuário está logado)
 */
async function toggleFavoritoProduto(idPeca, buttonElement, textElement) {
    try {
        // Estamos em /produto/, a API está em /favoritos/
        const response = await fetch('../favoritos/toggle_favorito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_pecas: idPeca })
        });

        const result = await response.json();

        if (response.ok) {
            // Sucesso! Alterna a classe e o texto
            if (result.status === 'added') {
                buttonElement.classList.add('active');
                if (textElement) textElement.textContent = 'Salvo nos Favoritos';
            } else if (result.status === 'removed') {
                buttonElement.classList.remove('active');
                if (textElement) textElement.textContent = 'Salvar nos Favoritos';
            }
        } else {
            // Se não deu 'ok', foi outro erro (Ex: 500 - Erro de servidor)
            alert(result.message || 'Erro ao processar a solicitação.');
        }

    } catch (error) {
        console.error('Erro no fetch:', error);
        alert('Não foi possível conectar. Verifique sua conexão.');
    }
}