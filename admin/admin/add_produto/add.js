document.addEventListener('DOMContentLoaded', () => {

    // --- Lógica do Switch (Igual a antes) ---
    const switchInput = document.querySelector('.switch input[name="status"]');
    const statusTextoSpan = document.querySelector('.status span');

    if (switchInput && statusTextoSpan) {
        statusTextoSpan.textContent = switchInput.checked ? 'Produto Ativo' : 'Produto Inativo';
        switchInput.addEventListener('change', () => {
            statusTextoSpan.textContent = switchInput.checked ? 'Produto Ativo' : 'Produto Inativo';
        });
    }

    // --- Lógica do Envio com AJAX ---
    const form = document.getElementById('form-add-produto');
    const submitButton = document.getElementById('btn-submit-form');

    if (form && submitButton) {
        submitButton.addEventListener('click', async () => {
            
            // 1. Coleta TODOS os dados do formulário
            const formData = new FormData(form);

            // 2. Desabilita o botão para evitar cliques duplos
            submitButton.disabled = true;
            submitButton.textContent = 'Cadastrando...';

            try {
                // 3. Envia os dados para o 'criar_produto.php'
                const response = await fetch('criar_produto.php', {
                    method: 'POST',
                    body: formData
                });

                // 4. Espera a resposta JSON
                const result = await response.json();

                // 5. Verifica se foi sucesso ou falha
                if (result.sucesso) {
                    // SUCESSO!
                    mostrarPopup(result.mensagem, 'sucesso');
                    form.reset(); // Limpa o formulário
                    
                    // Reseta o switch para o padrão (Ativo)
                    if(switchInput && statusTextoSpan) {
                        switchInput.checked = true;
                        statusTextoSpan.textContent = 'Produto Ativo';
                    }

                } else {
                    // FALHA!
                    mostrarPopup(result.mensagem, 'erro');
                }

            } catch (error) {
                // Erro de rede ou JSON inválido
                console.error('Erro na requisição:', error);
                mostrarPopup('Erro de comunicação. Tente novamente.', 'erro');
            }

            // 6. Reabilita o botão
            submitButton.disabled = false;
            submitButton.textContent = 'Criar Produto';
        });
    }

    // --- Função do Popup ---
    // (Agora com tipo 'sucesso' ou 'erro')
    function mostrarPopup(mensagem, tipo = 'sucesso') {
        // Remove popup antigo se existir
        const popupExistente = document.getElementById('popup-sucesso');
        if (popupExistente) {
            popupExistente.remove();
        }

        // Cria o popup
        const popup = document.createElement('div');
        popup.id = 'popup-sucesso';
        popup.textContent = mensagem;

        // Adiciona cor de erro
        if (tipo === 'erro') {
            popup.style.backgroundColor = '#f8d7da'; // Vermelho claro
            popup.style.color = '#721c24'; // Vermelho escuro
        }
        
        document.body.appendChild(popup);
        popup.classList.add('show');

        // Oculta após 5 segundos
        setTimeout(() => {
            popup.classList.remove('show');
            // Remove do DOM após a animação
            setTimeout(() => popup.remove(), 500); 
        }, 5000);
    }
});