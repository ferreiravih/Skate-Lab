// /perfil/favoritos/favoritos.js
document.addEventListener('DOMContentLoaded', () => {
    
    const grid = document.getElementById('favoritos-grid');
    if (!grid) return;

    // --- Configuração do Modal ---
    const modal = document.getElementById('confirm-remove-modal');
    const closeModalBtn = document.getElementById('confirm-modal-close-btn');
    const cancelModalBtn = document.getElementById('confirm-modal-cancel-btn');
    const removeModalBtn = document.getElementById('confirm-modal-remove-btn');

    // Variável para guardar o ID do item a ser removido
    let idParaRemover = null;

    // Verifica se todos os elementos do modal existem
    if (!modal || !closeModalBtn || !cancelModalBtn || !removeModalBtn) {
        console.error('Elementos do modal de confirmação não encontrados. Verifique o HTML.');
        return;
    }

    // --- Funções para controlar o Modal ---
    function openConfirmModal(idPeca) {
        idParaRemover = idPeca; // Armazena o ID
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }

    function closeConfirmModal() {
        idParaRemover = null; // Limpa o ID
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }

    // --- Event Listeners ---

    // 1. Abrir o modal ao clicar em "Remover" no card
    grid.addEventListener('click', (event) => {
        const removeButton = event.target.closest('.btn-remover-fav');
        
        if (removeButton) {
            const idPeca = removeButton.dataset.idPeca;
            if (idPeca) {
                // Em vez de chamar a remoção, abre o modal de confirmação
                openConfirmModal(idPeca);
            }
        }
    });

    // 2. Fechar o modal (no 'X', 'Cancelar' ou clicando fora)
    closeModalBtn.addEventListener('click', closeConfirmModal);
    cancelModalBtn.addEventListener('click', closeConfirmModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeConfirmModal();
        }
    });

    // 3. Executar a remoção ao clicar em "Remover" DENTRO do modal
    removeModalBtn.addEventListener('click', () => {
        if (idParaRemover) {
            // Chama a função que realmente faz o fetch
            executarRemocao(idParaRemover);
        }
    });
});

/**
 * Função que executa o fetch para remover o favorito.
 * (Anteriormente 'removerFavorito', agora sem o 'confirm')
 */
async function executarRemocao(idPeca) {
    const removeModalBtn = document.getElementById('confirm-modal-remove-btn');
    
    // Desabilita o botão para evitar cliques duplos
    if (removeModalBtn) {
        removeModalBtn.disabled = true;
        removeModalBtn.textContent = 'Removendo...';
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

        // Sempre fecha o modal, independente do resultado
        const cancelBtn = document.getElementById('confirm-modal-cancel-btn');
        if(cancelBtn) cancelBtn.click(); // Simula clique no cancelar para fechar e limpar

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
                    if (grid && grid.children.length === 0) {
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
            // Opcional: substituir este alert() por um modal de erro
            alert(`Erro ao remover: ${result.message || 'Erro desconhecido'}`);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
        // Opcional: substituir este alert() por um modal de erro
        alert('Não foi possível conectar ao servidor para remover o favorito.');
    }

    // Reabilita o botão
    if (removeModalBtn) {
        removeModalBtn.disabled = false;
        removeModalBtn.textContent = 'Remover';
    }
}