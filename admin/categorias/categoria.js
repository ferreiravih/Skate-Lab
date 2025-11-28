document.addEventListener("DOMContentLoaded", () => {
    const btnAbrirModal = document.getElementById("btn-abrir-modal");
    const overlay = document.getElementById("overlayCategoria");
    const btnCancelar = document.getElementById("cancelarModal");
    const btnSalvar = document.getElementById("btn-salvar-categoria");
    const form = document.getElementById("form-nova-categoria");




    const inputBusca = document.querySelector(".input-busca");
    const tabela = document.querySelector(".tabela-categorias");


    if (inputBusca && tabela) {
        

        const linhas = tabela.querySelectorAll("tbody tr");


        inputBusca.addEventListener("input", () => {
            const termoBusca = inputBusca.value.toLowerCase();

            linhas.forEach(linha => {

                const textoLinha = linha.textContent.toLowerCase();


                if (textoLinha.includes(termoBusca)) {
                    linha.style.display = ""; 
                } else {
                    linha.style.display = "none"; 
                }
            });
        });
    }


    btnAbrirModal.addEventListener("click", () => {
        overlay.style.display = "flex";
    });


    const fecharModal = () => {
        overlay.style.display = "none";
        form.reset(); 
    };
    
    btnCancelar.addEventListener("click", fecharModal);
    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) fecharModal();
    });

   
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


    function mostrarPopup(mensagem, tipo = 'sucesso') {
        const popupExistente = document.getElementById('popup-sucesso-cat');
        if (popupExistente) popupExistente.remove();

        const popup = document.createElement('div');
        popup.id = 'popup-sucesso-cat';
        popup.textContent = mensagem;
        popup.className = 'popup-sucesso'; 
        
        if (tipo === 'erro') {
            popup.style.backgroundColor = '#f8d7da';
            popup.style.color = '#721c24';
        }

        document.body.appendChild(popup);
        popup.classList.add("mostrar"); 

        setTimeout(() => {
            popup.classList.remove("mostrar");
            setTimeout(() => popup.remove(), 500);
        }, 5000);
    }
    

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'updated') {
        mostrarPopup('Categoria atualizada com sucesso!', 'sucesso');
    } else if (status === 'deleted') {
        mostrarPopup('Categoria excluída com sucesso.', 'sucesso');
    } else if (status === 'delete_failed') {
        mostrarPopup('Falha ao excluir. A categoria está em uso por produtos.', 'erro');
    }
    
    if (status) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});