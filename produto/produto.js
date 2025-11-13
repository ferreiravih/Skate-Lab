document.addEventListener("DOMContentLoaded", () => {
    
    // A LÓGICA DE QUANTIDADE FOI REMOVIDA
    
    // ==================================
    //  INÍCIO DA NOVA LÓGICA DE FAVORITOS
    // ==================================
    // Procura pela nova classe '.btn-favorito-bloco'
    const favButton = document.querySelector('.btn-favorito-bloco');
    
    if (favButton) {
        favButton.addEventListener('click', () => {
            const idPeca = favButton.dataset.idPeca;
            if (idPeca) {
                // Passa o botão e o span de texto para a função
                const textSpan = favButton.querySelector('.btn-fav-text');
                toggleFavoritoProduto(idPeca, favButton, textSpan);
            }
        });
    }
});


/**
 * Função assíncrona para a PÁGINA DE PRODUTO
 * @param {string} idPeca - O ID da peça a ser favoritada
 * @param {HTMLElement} buttonElement - O elemento do botão
 * @param {HTMLElement} textElement - O span que contém o texto
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
                if (textElement) textElement.textContent = 'Salvo nos Favoritos'; // NOVO TEXTO
            } else if (result.status === 'removed') {
                buttonElement.classList.remove('active');
                if (textElement) textElement.textContent = 'Salvar nos Favoritos'; // NOVO TEXTO
            }
        } else if (response.status === 403) {
            // Erro 403 (Forbidden) = Login necessário
            // Tenta achar o popup de login da navbar
            const authPopup = document.getElementById('auth-modal-overlay');
            if (authPopup) {
                authPopup.style.display = 'flex';
            } else {
                alert('Você precisa estar logado para adicionar aos favoritos.');
            }
        } else {
            // Outro erro
            alert(result.message || 'Erro ao processar a solicitação.');
        }

    } catch (error) {
        console.error('Erro no fetch:', error);
        alert('Não foi possível conectar. Verifique sua conexão.');
    }
}