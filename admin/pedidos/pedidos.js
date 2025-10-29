document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalPedido');
    const fechar = document.getElementById('fecharModal');
    const btnPreparo = document.getElementById('btnPreparo');
    const btnFinalizar = document.getElementById('btnFinalizar');

    // Elementos do Modal
    const modalNumero = document.getElementById('modalNumero');
    const modalCliente = document.getElementById('modalCliente');
    const modalData = document.getElementById('modalData');
    const modalTotal = document.getElementById('modalTotal');
    const modalStatus = document.getElementById('modalStatus');
    const modalEndereco = document.getElementById('modalEndereco'); // <- Verifique este ID no HTML
    const modalProdutosDiv = document.getElementById('modalProdutos'); // <- Verifique este ID no HTML

    let idPedidoSelecionado = null;
    let spanStatusSelecionado = null;

    // Verifica se os elementos do modal foram encontrados
    if (!modal || !modalEndereco || !modalProdutosDiv) {
        console.error("ERRO: Elementos essenciais do modal não encontrados no HTML!");
        return; // Impede a execução do resto do script
    }

    document.querySelectorAll('.btn-ver-detalhes').forEach((icone) => {
        icone.addEventListener('click', async (e) => {
            const linha = icone.closest('tr');
            idPedidoSelecionado = linha.dataset.idPedido;
            spanStatusSelecionado = linha.querySelector('.status');

            // Pré-preenche o modal
            modalNumero.textContent = linha.children[0].textContent;
            modalCliente.textContent = linha.children[1].textContent;
            modalData.textContent = linha.children[2].textContent;
            modalTotal.textContent = linha.children[4].textContent;
            modalStatus.textContent = spanStatusSelecionado.textContent;
            modalStatus.className = spanStatusSelecionado.className;

            // --- LIMPA PLACEHOLDERS E MOSTRA CARREGANDO ---
            modalEndereco.textContent = 'Carregando endereço...'; // Limpa texto antigo
            modalProdutosDiv.innerHTML = '<p>Carregando itens...</p>'; // Limpa itens antigos
            modal.style.display = 'flex';
            console.log(`Buscando detalhes para pedido ID: ${idPedidoSelecionado}`); // Log para debug

            // Buscar Detalhes com AJAX
            try {
                const response = await fetch(`obter_detalhes_pedido.php?id=${idPedidoSelecionado}`);
                console.log('Resposta Fetch recebida:', response); // Log para debug

                if (!response.ok) { // Verifica se a requisição HTTP foi bem-sucedida (status 200-299)
                   throw new Error(`Erro HTTP: ${response.status} - ${response.statusText}`);
                }

                // Tenta parsear como JSON
                let result;
                try {
                    result = await response.json();
                    console.log('Dados JSON recebidos:', result); // Log para debug
                } catch (jsonError) {
                    console.error('Erro ao parsear JSON:', jsonError);
                    throw new Error("Resposta do servidor não é um JSON válido.");
                }


                if (result.sucesso && result.dados) {
                    const { pedido, itens } = result.dados;

                    // 1. Preenche o Endereço (Verifica se os campos existem)
                    if (pedido) {
                        modalEndereco.innerHTML = `
                            ${pedido.endereco_rua || 'Rua não informada'}, ${pedido.endereco_numero || 'S/N'}<br>
                            ${pedido.endereco_bairro || 'Bairro não informado'} - ${pedido.endereco_cidade || 'Cidade não informada'}, ${pedido.endereco_estado || 'UF'}<br>
                            CEP: ${pedido.endereco_cep || 'N/A'}<br>
                            Complemento: ${pedido.endereco_complemento || 'Nenhum'}
                        `;
                    } else {
                        modalEndereco.textContent = 'Dados do pedido não encontrados na resposta.';
                    }

                    // 2. Preenche os Itens
                    modalProdutosDiv.innerHTML = ''; // Limpa o "Carregando..."
                    if (itens && itens.length > 0) {
                         console.log(`Encontrados ${itens.length} itens.`); // Log para debug
                        itens.forEach(item => {
                            const precoTotalItem = parseFloat(item.preco_unitario || 0) * parseInt(item.quantidade || 1);
                            const itemHtml = `
                                <div class="produto-item">
                                    <span>(${item.quantidade || 'N/A'}x) ${item.peca_nome || 'Nome Indisponível'}</span>
                                    <span>R$ ${precoTotalItem.toFixed(2)}</span>
                                </div>
                            `;
                            modalProdutosDiv.innerHTML += itemHtml;
                        });
                    } else {
                         console.log('Nenhum item encontrado na resposta.'); // Log para debug
                        modalProdutosDiv.innerHTML = '<p>Nenhum item encontrado para este pedido.</p>';
                    }

                } else {
                    // Se result.sucesso for false ou result.dados não existir
                    console.error('Falha na resposta do servidor:', result.dados || 'Nenhum dado retornado.'); // Log para debug
                    modalEndereco.textContent = `Erro: ${result.dados || 'Resposta inválida do servidor.'}`;
                    modalProdutosDiv.innerHTML = `<p>Erro ao buscar itens.</p>`;
                }
            } catch (error) {
                // Erro no fetch ou no processamento do JSON
                console.error('Erro geral ao buscar detalhes:', error); // Log detalhado do erro
                modalEndereco.textContent = `Erro de comunicação: ${error.message}`;
                modalProdutosDiv.innerHTML = `<p>Erro de comunicação ao buscar itens.</p>`;
            }
        });
    });

    // Função para atualizar o status via AJAX (mantém a mesma)
    async function atualizarStatus(novoStatus) {
        // ... (código anterior está correto)
         if (!idPedidoSelecionado) return;
        try {
            const response = await fetch('atualizar_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_pedido: idPedidoSelecionado,
                    novo_status: novoStatus
                })
            });
            const result = await response.json();
            if (result.sucesso) {
                spanStatusSelecionado.textContent = result.novoStatus;
                spanStatusSelecionado.className = 'status ' + result.novaClasse;
                modalStatus.textContent = result.novoStatus;
                modalStatus.className = 'status ' + result.novaClasse;
                mostrarPopup(result.mensagem, 'sucesso');
                modal.style.display = 'none';
            } else {
                mostrarPopup(result.mensagem, 'erro');
            }
        } catch (error) {
            console.error('Erro no Fetch ao atualizar status:', error);
            mostrarPopup('Erro de comunicação. Tente novamente.', 'erro');
        }
    }

    // Botões e Fechar Modal (mantém os mesmos)
    btnPreparo.onclick = () => atualizarStatus('EM PREPARO');
    btnFinalizar.onclick = () => atualizarStatus('ENVIADO');
    fechar.addEventListener('click', () => modal.style.display = 'none');
    modal.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    // Função de Popup (mantém a mesma)
    function mostrarPopup(mensagem, tipo = 'sucesso') {
       // ... (código anterior está correto)
        const popupExistente = document.getElementById('popup-sucesso-pedido');
        if (popupExistente) popupExistente.remove();
        const popup = document.createElement('div');
        popup.id = 'popup-sucesso-pedido';
        popup.textContent = mensagem;
        popup.style.position = 'fixed';
        popup.style.bottom = '30px';
        popup.style.right = '30px';
        popup.style.padding = '20px';
        popup.style.background = (tipo === 'sucesso') ? '#d4edda' : '#f8d7da';
        popup.style.color = (tipo === 'sucesso') ? '#155724' : '#721c24';
        popup.style.borderRadius = '8px';
        popup.style.boxShadow = '0 6px 24px rgba(0,0,0,0.25)';
        popup.style.zIndex = '9999';
        popup.style.fontFamily = 'Poppins, sans-serif';
        popup.style.fontWeight = '800';
        popup.style.opacity = '0';
        popup.style.transform = 'translateY(20px)';
        popup.style.transition = 'opacity 0.4s, transform 0.4s';
        document.body.appendChild(popup);
        setTimeout(() => {
            popup.style.opacity = '1';
            popup.style.transform = 'translateY(0)';
        }, 10);
        setTimeout(() => {
            popup.style.opacity = '0';
            popup.style.transform = 'translateY(20px)';
            setTimeout(() => popup.remove(), 500);
        }, 5000);
    }

    // Popups da URL (mantém o mesmo)
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status === 'cancelled') {
        mostrarPopup('Pedido cancelado com sucesso!', 'sucesso');
    } else if (status === 'cancel_failed') {
        mostrarPopup('Falha ao cancelar o pedido.', 'erro');
    }
    if (status) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});