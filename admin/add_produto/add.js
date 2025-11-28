document.addEventListener('DOMContentLoaded', () => {


    const switchInput = document.querySelector('.switch input[name="status"]');
    const statusTextoSpan = document.querySelector('.status span');

    if (switchInput && statusTextoSpan) {
        statusTextoSpan.textContent = switchInput.checked ? 'Produto Ativo' : 'Produto Inativo';
        switchInput.addEventListener('change', () => {
            statusTextoSpan.textContent = switchInput.checked ? 'Produto Ativo' : 'Produto Inativo';
        });
    }


    const form = document.getElementById('form-add-produto');
    const submitButton = document.getElementById('btn-submit-form');

    if (form && submitButton) {
        submitButton.addEventListener('click', async () => {
            

            const formData = new FormData(form);


            submitButton.disabled = true;
            submitButton.textContent = 'Cadastrando...';

            try {

                const response = await fetch('criar_produto.php', {
                    method: 'POST',
                    body: formData
                });


                const result = await response.json();


                if (result.sucesso) {

                    mostrarPopup(result.mensagem, 'sucesso');
                    form.reset(); 
                    

                    if(switchInput && statusTextoSpan) {
                        switchInput.checked = true;
                        statusTextoSpan.textContent = 'Produto Ativo';
                    }

                } else {

                    mostrarPopup(result.mensagem, 'erro');
                }

            } catch (error) {

                console.error('Erro na requisição:', error);
                mostrarPopup('Erro de comunicação. Tente novamente.', 'erro');
            }


            submitButton.disabled = false;
            submitButton.textContent = 'Criar Produto';
        });
    }


    function mostrarPopup(mensagem, tipo = 'sucesso') {
        const popupExistente = document.getElementById('popup-sucesso');
        if (popupExistente) {
            popupExistente.remove();
        }


        const popup = document.createElement('div');
        popup.id = 'popup-sucesso';
        popup.textContent = mensagem;


        if (tipo === 'erro') {
            popup.style.backgroundColor = '#f8d7da'; 
            popup.style.color = '#721c24'; 
        }
        
        document.body.appendChild(popup);
        popup.classList.add('show');


        setTimeout(() => {
            popup.classList.remove('show');
            setTimeout(() => popup.remove(), 500); 
        }, 5000);
    }
});