document.addEventListener("DOMContentLoaded", () => {
    
    const favButton = document.querySelector('.btn-favorito-bloco');
    if (favButton && typeof isUserLoggedIn !== 'undefined' && isUserLoggedIn) {
        favButton.addEventListener('click', () => {
            const idPeca = favButton.dataset.idPeca;
            if (idPeca) {
                const textSpan = favButton.querySelector('.btn-fav-text');
                toggleFavoritoProduto(idPeca, favButton, textSpan);
            }
        });

    } 
    
});
async function toggleFavoritoProduto(idPeca, buttonElement, textElement) {
    try {
        const response = await fetch('../favoritos/toggle_favorito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_pecas: idPeca })
        });

        const result = await response.json();

        if (response.ok) {
            if (result.status === 'added') {
                buttonElement.classList.add('active');
                if (textElement) textElement.textContent = 'Salvo nos Favoritos';

                mostrarToast("Adicionado aos favoritos!", "success");

            } else if (result.status === 'removed') {
                buttonElement.classList.remove('active');
                if (textElement) textElement.textContent = 'Salvar nos Favoritos';

                mostrarToast("Removido dos favoritos.", "removed");
            }
        } else {
            alert(result.message || 'Erro ao processar a solicitação.');
        }

    } catch (error) {
        console.error('Erro no fetch:', error);
        mostrarToast('Erro de conexão.', 'removed');
    }
}
function mostrarToast(mensagem, tipo) {
    const toast = document.createElement("div");
    toast.className = `toast-notification ${tipo}`;
    
    const icone = tipo === 'success' ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-trash"></i>';
    
    toast.innerHTML = `${icone} <span>${mensagem}</span>`;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}