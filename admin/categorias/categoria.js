document.addEventListener("DOMContentLoaded", () => {
    const btnAbrirModal = document.getElementById("btn-abrir-modal");
    const overlay = document.getElementById("overlayCategoria");
    const btnCancelar = document.getElementById("cancelarModal");
    const btnSalvar = document.getElementById("btn-salvar-categoria");
    const form = document.getElementById("form-nova-categoria");


// --- INÍCIO DA LÓGICA DE FILTRO DE BUSCA ---

    const inputBusca = document.querySelector(".input-busca");
    const tabela = document.querySelector(".tabela-categorias");

    // Verifica se a barra de busca e a tabela existem
    if (inputBusca && tabela) {
        
        // Pega todas as linhas do corpo da tabela (tbody)
        const linhas = tabela.querySelectorAll("tbody tr");

        // Adiciona um "ouvinte" que dispara toda vez que você digita
        inputBusca.addEventListener("input", () => {
            const termoBusca = inputBusca.value.toLowerCase();

            linhas.forEach(linha => {
                // Pega o texto da linha inteira e converte para minúsculo
                const textoLinha = linha.textContent.toLowerCase();

                // Verifica se o texto da linha inclui o termo digitado
                if (textoLinha.includes(termoBusca)) {
                    linha.style.display = ""; // Mostra a linha
                } else {
                    linha.style.display = "none"; // Esconde a linha
                }
            });
        });
    }

    // Abre o modal
    btnAbrirModal.addEventListener("click", () => {
        overlay.style.display = "flex";
    });

    // Fecha o modal
    const fecharModal = () => {
        overlay.style.display = "none";
        form.reset(); // Limpa o formulário ao fechar
    };
    
    btnCancelar.addEventListener("click", fecharModal);
    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) fecharModal();
    });

    // Lógica de Salvar com AJAX (Fetch)
    btnSalvar.addEventListener("click", async () => {
        const formData = new FormData(form);
        
        btnSalvar.disabled = true;
        btnSalvar.textContent = "Salvando...";

        try {
            const response = await fetch('criar_categoria.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.sucesso) {
                mostrarPopup(result.mensagem, 'sucesso');
                fecharModal();
                // Recarrega a página para mostrar a nova categoria na tabela
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                mostrarPopup(result.mensagem, 'erro');
            }

        } catch (error) {
            console.error('Erro na requisição:', error);
            mostrarPopup('Erro de comunicação. Tente novamente.', 'erro');
        }

        btnSalvar.disabled = false;
        btnSalvar.textContent = "Salvar";
    });

    // --- Função do Popup ---
    function mostrarPopup(mensagem, tipo = 'sucesso') {
        const popupExistente = document.getElementById('popup-sucesso-cat');
        if (popupExistente) popupExistente.remove();

        const popup = document.createElement('div');
        popup.id = 'popup-sucesso-cat';
        popup.textContent = mensagem;
        
        // Usa o estilo do popup de sucesso do produto (adicionado abaixo)
        popup.className = 'popup-sucesso'; 
        
        if (tipo === 'erro') {
            popup.style.backgroundColor = '#f8d7da';
            popup.style.color = '#721c24';
        }

        document.body.appendChild(popup);
        popup.classList.add("mostrar"); // Ativa a animação

        setTimeout(() => {
            popup.classList.remove("mostrar");
            setTimeout(() => popup.remove(), 500);
        }, 5000);
    }
    
    // --- Lógica para popups da URL (Update/Delete) ---
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'updated') {
        mostrarPopup('Categoria atualizada com sucesso!', 'sucesso');
    } else if (status === 'deleted') {
        mostrarPopup('Categoria excluída com sucesso.', 'sucesso');
    } else if (status === 'delete_failed') {
        mostrarPopup('Falha ao excluir. A categoria está em uso por produtos.', 'erro');
    }
    
    // Limpa o status da URL
    if (status) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});