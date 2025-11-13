// /perfil/favoritos/favoritos.js
document.addEventListener('DOMContentLoaded', () => {
    
    const grid = document.getElementById('favoritos-grid');
    if (!grid) return;

    grid.addEventListener('click', (event) => {
        // Encontra o botão que foi clicado
        const removeButton = event.target.closest('.btn-remover-fav');
        
        if (removeButton) {
            const idParaRemover = removeButton.dataset.idPeca;
            if (!idParaRemover) return;

            // Chama a função para remover
            removerFavorito(idParaRemover);
        }
    });

});

async function removerFavorito(idPeca) {
    if (!confirm('Tem certeza que deseja remover este item dos favoritos?')) {
        return;
    }

    try {
        const response = await fetch('toggle_favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_pecas: idPeca })
        });

        const result = await response.json();

        if (response.ok && result.status === 'removed') {
            // Remove o card do item da tela
            const cardParaRemover = document.getElementById(`fav-card-${idPeca}`);
            if (cardParaRemover) {
                cardParaRemover.style.transition = 'opacity 0.5s ease';
                cardParaRemover.style.opacity = '0';
                setTimeout(() => {
                    cardParaRemover.remove();
                    
                    // Verifica se a grid ficou vazia
                    const grid = document.getElementById('favoritos-grid');
                    if (grid.children.length === 0) {
                        grid.innerHTML = `
                            <div class="empty-state-favoritos">
                                <i class="ri-heart-add-line"></i>
                                <p>Você removeu todos os seus favoritos.</p>
                                <a href="../../skateshop/skateee.php" class="btn-add-new">Ver produtos</a>
                            </div>
                        `;
                    }
                }, 500);
            }
        } else {
            alert(`Erro ao remover: ${result.message || 'Erro desconhecido'}`);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
        alert('Não foi possível conectar ao servidor para remover o favorito.');
    }
}