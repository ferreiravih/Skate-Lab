document.addEventListener('DOMContentLoaded', function() {
    

    const editBtn = document.getElementById('edit-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const saveBtn = document.getElementById('save-btn');
    const profileForm = document.getElementById('profile-form');
    const photoOverlayBtn = document.getElementById('photo-overlay-btn');
    const photoInput = document.getElementById('photo-upload'); // O input de arquivo
    const profilePic = document.getElementById('profile-picture');
    const feedbackMessage = document.getElementById('feedback-message'); 
    const usernameDisplay = document.getElementById('username-display');
    const profileContainer = document.querySelector('.profile-grid'); 

    if (profileForm && editBtn && cancelBtn) {
        
        const editableFields = profileForm.querySelectorAll('input[name="nome"], input[name="apelido"], input[name="tell"], input[name="data_nascimento"]');
        let originalValues = {};

        function saveOriginals() {
            originalValues = {};
            editableFields.forEach(field => {
                originalValues[field.name] = field.value;
            });
        }

        function restoreOriginals() {
            editableFields.forEach(field => {
                field.value = originalValues[field.name];
            });
        }

        function enterEditMode() {
            saveOriginals();
            if (profileContainer) profileContainer.classList.add('is-editing');
            editableFields.forEach(field => { field.readOnly = false; });
            document.getElementById('form-actions').style.display = 'flex'; 
            editBtn.style.display = 'none'; 
        }

        function exitEditMode(restore = false) {
            if (restore) {
                restoreOriginals();
            }
            if (profileContainer) profileContainer.classList.remove('is-editing');
            editableFields.forEach(field => { field.readOnly = true; });
            document.getElementById('form-actions').style.display = 'none'; 
            editBtn.style.display = 'inline-flex'; 
        }

        editBtn.addEventListener('click', enterEditMode);
        cancelBtn.addEventListener('click', () => exitEditMode(true));


        profileForm.addEventListener('submit', async (e) => {
            e.preventDefault(); 
            saveBtn.disabled = true;
            saveBtn.textContent = 'Salvando...';
            

            const formData = new FormData(profileForm);


            if (photoInput && photoInput.files.length > 0) {
                formData.append('foto_perfil', photoInput.files[0]);
            }

            try {
                const response = await fetch('funcoes/atualizar_perfil.php', { method: 'POST', body: formData });
                const result = await response.json();
                
                if (result.sucesso) {
                    showGlobalFeedback(result.mensagem, 'success');
                    if (usernameDisplay && result.novoNomeDisplay) {
                        usernameDisplay.textContent = result.novoNomeDisplay;
                    }

                    editableFields.forEach(field => {
                        const span = profileForm.querySelector(`.info-item label[for="${field.name}"] + .info-text`);
                        if (span) {
                            span.textContent = field.value ? field.value : `Não cadastrado`;
                        }
                    });
                    

                    if (result.novaUrlFoto && profilePic) {
                        profilePic.src = result.novaUrlFoto + '?t=' + new Date().getTime();
                    }

                    exitEditMode(false); 
                } else {
                    showGlobalFeedback(result.mensagem || 'Ocorreu um erro.', 'error');
                }
            } catch (error) {
                console.error(error);
                showGlobalFeedback('Erro de conexão. Tente novamente.', 'error');
            }
            saveBtn.disabled = false;
            saveBtn.textContent = 'Salvar Alterações';
        });


        if (photoOverlayBtn && photoInput && profilePic) {
            photoOverlayBtn.addEventListener('click', () => {
                if (profileContainer && profileContainer.classList.contains('is-editing')) {
                    photoInput.click(); 
                }
            });
            photoInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => { profilePic.src = e.target.result; };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    const passwordModal = document.getElementById('password-modal');
    const btnShowPasswordModal = document.getElementById('btn-show-password-modal');
    const passwordModalCloseBtn = document.getElementById('password-modal-close-btn');
    const passwordModalCancelBtn = document.getElementById('password-modal-cancel-btn');
    const passwordForm = document.getElementById('password-form');
    const passwordSaveBtn = document.getElementById('password-save-btn');
    const passwordFeedbackMessage = document.getElementById('password-feedback-message'); 

    if (passwordModal && btnShowPasswordModal && passwordModalCloseBtn && passwordModalCancelBtn && passwordForm) {

        function openPasswordModal() {
            passwordModal.classList.add('active');
            document.body.classList.add('modal-open'); 
        }

        function showPasswordFeedback(message, type = 'error') {
            if (!passwordFeedbackMessage) return;
            passwordFeedbackMessage.textContent = message;
            passwordFeedbackMessage.className = `feedback ${type}`;
            passwordFeedbackMessage.style.display = 'block';
        }
        function hidePasswordFeedback() {
             if (passwordFeedbackMessage) {
                passwordFeedbackMessage.style.display = 'none';
             }
        }

        function closePasswordModal() {
            passwordModal.classList.remove('active');
            document.body.classList.remove('modal-open');
            hidePasswordFeedback();
            passwordForm.reset(); 
        }

        btnShowPasswordModal.addEventListener('click', openPasswordModal);
        passwordModalCloseBtn.addEventListener('click', closePasswordModal);
        passwordModalCancelBtn.addEventListener('click', closePasswordModal);
        
        passwordModal.addEventListener('click', (e) => {
            if (e.target === passwordModal) {
                closePasswordModal();
            }
        });

        passwordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            passwordSaveBtn.disabled = true;
            passwordSaveBtn.textContent = 'Salvando...';
            hidePasswordFeedback(); 

            const formData = new FormData(passwordForm);

            try {
                const response = await fetch('funcoes/atualizar_senha.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.sucesso) {
                    showGlobalFeedback(result.mensagem, 'success'); 
                    closePasswordModal(); 
                } else {
                    showPasswordFeedback(result.mensagem || 'Ocorreu um erro.', 'error');
                }
            } catch (error) {
                showPasswordFeedback('Erro de conexão ao salvar senha.', 'error');
            }
            
            passwordSaveBtn.disabled = false;
            passwordSaveBtn.textContent = 'Salvar Senha';
        });
    }


    const pedidoModal = document.getElementById('pedido-detalhes-modal');
    const pedidoModalContent = document.getElementById('pedido-modal-content');
    const pedidoModalCloseBtn = document.getElementById('pedido-modal-close-btn');
    const pedidoModalFecharBtn = document.getElementById('pedido-modal-fechar-btn');
    const orderButtons = document.querySelectorAll('.order-item-button');

    if (pedidoModal && pedidoModalContent && pedidoModalCloseBtn && pedidoModalFecharBtn) {
        
        function openPedidoModal() {
            pedidoModal.classList.add('active');
            document.body.classList.add('modal-open'); 
        }
        
        function closePedidoModal() {
            pedidoModal.classList.remove('active');
            document.body.classList.remove('modal-open'); 
        }

        pedidoModalCloseBtn.addEventListener('click', closePedidoModal);
        pedidoModalFecharBtn.addEventListener('click', closePedidoModal);
        pedidoModal.addEventListener('click', (e) => {
            if (e.target === pedidoModal) {
                closePedidoModal();
            }
        });

        orderButtons.forEach(button => {
            button.addEventListener('click', async () => {
                const pedidoId = button.getAttribute('data-pedido-id');
                
                openPedidoModal();
                pedidoModalContent.innerHTML = `
                    <div class="pedido-modal-loading">
                        <i class="ri-loader-4-line ri-spin"></i>
                        <span>Carregando detalhes...</span>
                    </div>`;

                try {
                    const response = await fetch(`funcoes/obter_detalhes_pedido.php?id=${pedidoId}`);
                    const result = await response.json();

                    if (result.sucesso) {
                        renderizarDetalhesPedido(result.dados);
                    } else {
                        throw new Error(result.mensagem);
                    }
                } catch (error) {
                    pedidoModalContent.innerHTML = `<p style="color: red;">Erro ao buscar detalhes: ${error.message}</p>`;
                }
            });
        });

        function renderizarDetalhesPedido(dados) {
            const { pedido, itens } = dados;

            const dataPedido = new Date(pedido.pedido_em).toLocaleDateString('pt-BR', {
                day: '2-digit', month: '2-digit', year: 'numeric'
            });

            const statusClass = pedido.status ? pedido.status.toLowerCase().replace(' ', '') : 'pendente';
            
            const rastreioHtml = pedido.codigo_rastreio
                ? `<p class="rastreio-code">${pedido.codigo_rastreio}</p>`
                : `<p>Nenhum código disponível</p>`;


            let itensHtml = '';
            let subtotalItens = 0;
            itens.forEach(item => {
                const precoTotalItem = parseFloat(item.preco_unitario) * parseInt(item.quantidade);
                subtotalItens += precoTotalItem;
                itensHtml += `
                    <div class="item-pedido">
                        <img src="${item.url_img}" alt="${item.nome}">
                        <div class="item-info">
                            <p>${item.nome}</p>
                            <span>${item.quantidade} x R$ ${parseFloat(item.preco_unitario).toFixed(2).replace('.', ',')}</span>
                        </div>
                        <span class="item-preco">R$ ${precoTotalItem.toFixed(2).replace('.', ',')}</span>
                    </div>
                `;
            });
            
  
            const freteValor = parseFloat(pedido.frete_valor) || 0;
            const freteDescricao = pedido.frete_descricao || "Não especificado";
            const valorTotal = parseFloat(pedido.valor_total) || 0;

            
            let valoresHtml = `
                <div class="detalhe-bloco">
                    <label>Subtotal dos Itens</label>
                    <p>R$ ${subtotalItens.toFixed(2).replace('.', ',')}</p>
                </div>
                <div class="detalhe-bloco">
                    <label>Frete (${freteDescricao})</label>
                    <p>R$ ${freteValor.toFixed(2).replace('.', ',')}</p>
                </div>
                <div class="detalhe-bloco">
                    <label>Valor Total</label>
                    <p>R$ ${valorTotal.toFixed(2).replace('.', ',')}</p>
                </div>
            `;

            const html = `
                <div class="pedido-detalhes-header">
                    <h3>Detalhes do Pedido #${pedido.id_pedido}</h3>
                    <span class="status-badge ${statusClass}">${pedido.status}</span>
                </div>

                <div class="pedido-detalhes-grid">
                    <div class="detalhe-bloco">
                        <label>Data do Pedido</label>
                        <p>${dataPedido}</p>
                    </div>
                    ${valoresHtml}
                    <div class="detalhe-bloco full-width">
                        <label>Código de Rastreio</label>
                        ${rastreioHtml}
                    </div>
                </div>

                <div class="pedido-itens-lista">
                    <h4>Itens do Pedido (${itens.length})</h4>
                    ${itensHtml}
                </div>
            `;
            
            pedidoModalContent.innerHTML = html;
        }
    }


    const feedbackMessageEl = document.getElementById('feedback-message');
    
    function showGlobalFeedback(message, type = 'success') {
        if (!feedbackMessageEl) return;
        feedbackMessageEl.textContent = message;
        feedbackMessageEl.className = `feedback ${type}`;
        feedbackMessageEl.style.display = 'block';
        window.scrollTo(0, 0); 
        setTimeout(() => {
            feedbackMessageEl.style.display = 'none';
        }, 5000);
    }


    const telInput = document.getElementById('tell');
    if (telInput) {
        telInput.addEventListener('input', (e) => {
            if (e.target.readOnly) return; 
            let value = e.target.value.replace(/\D/g, ''); 
            value = value.substring(0, 11); 
            if (value.length > 10) { value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3'); }
            else if (value.length > 6) { value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3'); }
            else if (value.length > 2) { value = value.replace(/^(\d{2})(\d{0,4}).*/, '($1) $2'); }
            else if (value.length > 0) { value = value.replace(/^(\d{0,2}).*/, '($1'); }
            e.target.value = value;
        });
    }


    const dateInput = document.getElementById('data_nascimento');
    if(dateInput) {
        dateInput.addEventListener('input', (e) => {
            if (e.target.readOnly) return;
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 8);
            if (value.length > 4) { value = value.replace(/^(\d{2})(\d{2})(\d{0,4}).*/, '$1/$2/$3'); }
            else if (value.length > 2) { value = value.replace(/^(\d{2})(\d{0,2}).*/, '$1/$2'); }
            e.target.value = value;
        });
    }
    

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status) {
        if (status === 'login_success') showGlobalFeedback("Login realizado com sucesso!", 'success');
        if (status === 'registered') showGlobalFeedback("Cadastro realizado com sucesso!", 'success');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});