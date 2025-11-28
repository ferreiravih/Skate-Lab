// /perfil/favoritos/favoritos.js
document.addEventListener('DOMContentLoaded', () => {
    
    const grid = document.getElementById('favoritos-grid');
    if (!grid) return;

    // --- Configuração do Modal ---
    const modal = document.getElementById('confirm-remove-modal');
    const closeModalBtn = document.getElementById('confirm-modal-close-btn');
    const cancelModalBtn = document.getElementById('confirm-modal-cancel-btn');
    const removeModalBtn = document.getElementById('confirm-modal-remove-btn');

    // variável para guardar o ID do item a ser removido
    let idParaRemover = null;

    if (!modal || !closeModalBtn || !cancelModalBtn || !removeModalBtn) {
        console.error('Elementos do modal de confirmação não encontrados. Verifique o HTML.');
        return;
    }


    function openConfirmModal(idPeca) {
        idParaRemover = idPeca; 
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }

    function closeConfirmModal() {
        idParaRemover = null; 
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }




    grid.addEventListener('click', (event) => {
        const removeButton = event.target.closest('.btn-remover-fav');
        
        if (removeButton) {
            const idPeca = removeButton.dataset.idPeca;
            if (idPeca) {
                openConfirmModal(idPeca);
            }
        }
    });


    closeModalBtn.addEventListener('click', closeConfirmModal);
    cancelModalBtn.addEventListener('click', closeConfirmModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeConfirmModal();
        }
    });


    removeModalBtn.addEventListener('click', () => {
        if (idParaRemover) {
            executarRemocao(idParaRemover);
        }
    });
});


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


        const cancelBtn = document.getElementById('confirm-modal-cancel-btn');
        if(cancelBtn) cancelBtn.click(); 

        if (response.ok && result.status === 'removed') {
            // remove o card do item da tela
            const cardParaRemover = document.getElementById(`fav-card-${idPeca}`);
            if (cardParaRemover) {
                cardParaRemover.style.transition = 'opacity 0.5s ease';
                cardParaRemover.style.opacity = '0';
                setTimeout(() => {
                    cardParaRemover.remove();
                    

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
            alert(`Erro ao remover: ${result.message || 'Erro desconhecido'}`);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
        alert('Não foi possível conectar ao servidor para remover o favorito.');
    }

    if (removeModalBtn) {
        removeModalBtn.disabled = false;
        removeModalBtn.textContent = 'Remover';
    }
}