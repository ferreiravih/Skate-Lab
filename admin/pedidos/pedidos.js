document.addEventListener('DOMContentLoaded', () => {
    const inputBuscaPedidos = document.querySelector(".filtro-busca input");
    const selectStatusPedidos = document.querySelector(".filtro-status");
    const tabelaPedidos = document.querySelector(".tabela-pedidos");

    // Verifica se todos os elementos necessários existem
    if (inputBuscaPedidos && selectStatusPedidos && tabelaPedidos) {
        
        const linhasPedidos = tabelaPedidos.querySelectorAll("tbody tr");

        // 1. Cria a função de filtro principal
        function filtrarPedidos() {
            // Pega os valores atuais dos dois filtros
            const termoBusca = inputBuscaPedidos.value.toLowerCase();
            const statusSelecionado = selectStatusPedidos.value; // ex: "PENDENTE" ou "todos"

            linhasPedidos.forEach(linha => {
                const textoLinha = linha.textContent.toLowerCase();
                
                // Pega o texto do status da linha (ex: "PENDENTE")
                // Usamos .trim() para remover espaços em branco
                const statusDaLinha = linha.querySelector(".status").textContent.trim();

                // --- Regras de visibilidade ---

                // Regra 1: O texto da busca bate?
                // (Se o termo de busca estiver vazio, passa direto)
                const passouNaBusca = (termoBusca === "" || textoLinha.includes(termoBusca));

                // Regra 2: O status bate?
                // (Se "Todos" estiver selecionado, passa direto)
                const passouNoStatus = (statusSelecionado === "todos" || statusDaLinha === statusSelecionado);

                // A linha SÓ aparece se passar nos DOIS filtros
                if (passouNaBusca && passouNoStatus) {
                    linha.style.display = ""; // Mostra a linha
                } else {
                    linha.style.display = "none"; // Esconde a linha
                }
            });
        }

        // 2. Adiciona o "ouvinte" na barra de busca
        inputBuscaPedidos.addEventListener("input", filtrarPedidos);

        // 3. Adiciona o "ouvinte" no select de status
        selectStatusPedidos.addEventListener("change", filtrarPedidos);
    }
    
    const modal = document.getElementById('modalPedido');
    const fechar = document.getElementById('fecharModal');
    const btnPreparo = document.getElementById('btnPreparo');
    const btnFinalizar = document.getElementById('btnFinalizar');

    // Elementos do Modal
    const modalNumero = document.getElementById('modalNumero');
    const modalCliente = document.getElementById('modalCliente');
    const modalData = document.getElementById('modalData');
    const modalTotalSection = document.querySelector('.modal-total'); // Alterado para pegar a seção
    const modalStatus = document.getElementById('modalStatus');
    const modalEndereco = document.getElementById('modalEndereco'); 
    const modalProdutosDiv = document.getElementById('modalProdutos'); 

    let idPedidoSelecionado = null;
    let spanStatusSelecionado = null;

    // Verifica se os elementos do modal foram encontrados
    if (!modal || !modalEndereco || !modalProdutosDiv || !modalTotalSection) {
        console.error("ERRO: Elementos essenciais do modal não encontrados no HTML!");
        return; 
    }

    document.querySelectorAll('.btn-ver-detalhes').forEach((icone) => {
        icone.addEventListener('click', async (e) => {
            const linha = icone.closest('tr');
            idPedidoSelecionado = linha.dataset.idPedido;
            spanStatusSelecionado = linha.querySelector('.status');

            // Pré-preenche o modal com dados da tabela
            modalNumero.textContent = linha.children[0].textContent;
            modalCliente.textContent = linha.children[1].textContent;
            modalData.textContent = linha.children[2].textContent;
            modalStatus.textContent = spanStatusSelecionado.textContent;
            modalStatus.className = spanStatusSelecionado.className;
            
            // Limpa a seção de total e mostra carregando
            modalTotalSection.innerHTML = `<h3>Valor Total</h3><p>Carregando...</p>`;

            // --- LIMPA PLACEHOLDERS E MOSTRA CARREGANDO ---
            modalEndereco.textContent = 'Carregando endereço...'; 
            modalProdutosDiv.innerHTML = '<p>Carregando itens...</p>'; 
            modal.style.display = 'flex';
            
            // Buscar Detalhes com AJAX
            try {
                // --- CORREÇÃO AQUI: Apontando para o arquivo local do ADMIN, não do perfil ---
                const response = await fetch(`obter_detalhes_pedido.php?id=${idPedidoSelecionado}`);
                
                if (!response.ok) { 
                   throw new Error(`Erro HTTP: ${response.status}`);
                }

                // Tenta parsear como JSON
                let result;
                try {
                    result = await response.json();
                } catch (jsonError) {
                    throw new Error("Resposta do servidor não é um JSON válido.");
                }

                if (result.sucesso && result.dados) {
                    const { pedido, itens } = result.dados;

                    // 1. Preenche o Endereço
                    if (pedido) {
                        modalEndereco.innerHTML = `
                            ${pedido.endereco_rua || 'Rua não informada'}, ${pedido.endereco_numero || 'S/N'}<br>
                            ${pedido.endereco_bairro || 'Bairro não informado'} - ${pedido.endereco_cidade || 'Cidade não informada'}, ${pedido.endereco_estado || 'UF'}<br>
                            CEP: ${pedido.endereco_cep || 'N/A'}<br>
                            Complemento: ${pedido.endereco_complemento || 'Nenhum'}
                        `;

                        // Preenche a seção de valores
                        const freteValor = parseFloat(pedido.frete_valor) || 0;
                        const valorTotal = parseFloat(pedido.valor_total) || 0;
                        const subTotal = valorTotal - freteValor;
                        const freteDescricao = pedido.frete_descricao || 'Não especificado';

                        modalTotalSection.innerHTML = `
                            <div class="valores-detalhados">
                                <p><span>Subtotal</span> <strong>R$ ${subTotal.toFixed(2).replace('.', ',')}</strong></p>
                                <p><span>Frete (${freteDescricao})</span> <strong>R$ ${freteValor.toFixed(2).replace('.', ',')}</strong></p>
                                
                                <p class="total-final"><span>Valor Total</span> <strong>R$ ${valorTotal.toFixed(2).replace('.', ',')}</strong></p>
                            </div>
                        `;

                    } else {
                        modalEndereco.textContent = 'Endereço não encontrado.';
                        modalTotalSection.innerHTML = `<h3>Valor Total</h3><p>Erro ao carregar.</p>`;
                    }

                    // 2. Preenche os Itens
                    modalProdutosDiv.innerHTML = ''; // Limpa o "Carregando..."
                    if (itens && itens.length > 0) {
                        itens.forEach(item => {
                            const precoUnit = parseFloat(item.preco_unitario || 0);
                            const qtd = parseInt(item.quantidade || 1);
                            const totalItem = precoUnit * qtd;
                            
                            // Tenta usar a imagem, se não tiver usa um placeholder
                            const imgUrl = item.url_img ? item.url_img : '../../img/imgs-icon/icon.png';

                            const itemHtml = `
                                <div class="produto-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <img src="${imgUrl}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        <div>
                                            <strong>${item.peca_nome || 'Produto'}</strong><br>
                                            <small>Qtd: ${qtd} x R$ ${precoUnit.toFixed(2).replace('.', ',')}</small>
                                        </div>
                                    </div>
                                    <span>R$ ${totalItem.toFixed(2).replace('.', ',')}</span>
                                </div>
                            `;
                            modalProdutosDiv.innerHTML += itemHtml;
                        });
                    } else {
                        modalProdutosDiv.innerHTML = '<p>Nenhum item encontrado para este pedido.</p>';
                    }

                } else {
                    modalEndereco.textContent = `Erro: ${result.dados || 'Resposta inválida.'}`;
                    modalProdutosDiv.innerHTML = `<p>Erro ao buscar itens.</p>`;
                }
            } catch (error) {
                console.error('Erro geral:', error); 
                modalEndereco.textContent = `Erro de comunicação.`;
                modalProdutosDiv.innerHTML = `<p>Não foi possível carregar os itens.</p>`;
            }
        });
    });

    // Função para atualizar o status via AJAX
    async function atualizarStatus(novoStatus) {
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
                // Atualiza na tabela
                spanStatusSelecionado.textContent = result.novoStatus;
                spanStatusSelecionado.className = 'status ' + result.novaClasse;
                // Atualiza no modal
                modalStatus.textContent = result.novoStatus;
                modalStatus.className = 'status ' + result.novaClasse;
                
                mostrarPopup(result.mensagem, 'sucesso');
                modal.style.display = 'none';
            } else {
                mostrarPopup(result.mensagem, 'erro');
            }
        } catch (error) {
            console.error('Erro no Fetch:', error);
            mostrarPopup('Erro de comunicação.', 'erro');
        }
    }

    btnPreparo.onclick = () => atualizarStatus('EM PREPARO');
    btnFinalizar.onclick = () => atualizarStatus('ENVIADO');
    fechar.addEventListener('click', () => modal.style.display = 'none');
    modal.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    function mostrarPopup(mensagem, tipo = 'sucesso') {
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