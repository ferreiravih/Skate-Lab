function initFreteCalculator(config) {
    const {
        cepInputId,
        calculateBtnId,
        resultContainerId,
        subtotalId,
        shippingValueId,
        totalWithShippingId,
        formId
    } = config;

    const cepInput = document.getElementById(cepInputId);
    const calculateBtn = document.getElementById(calculateBtnId);
    const resultContainer = document.getElementById(resultContainerId);
    const subtotalSpan = document.getElementById(subtotalId);
    const shippingValueSpan = document.getElementById(shippingValueId);
    const totalWithShippingSpan = document.getElementById(totalWithShippingId);
    const form = document.getElementById(formId);

    if (!cepInput || !resultContainer) {
        console.warn('Frete: Elementos essenciais não encontrados.');
        return;
    }

    const endpoint = '../carrinho/contr/calcular_frete.php';

    const formatCurrency = (value) =>
        Number(value || 0).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });

    const sanitizeCep = (value) => value.replace(/\D/g, '').slice(0, 8);

    const toggleLoading = (isLoading) => {
        if (calculateBtn) {
            calculateBtn.disabled = isLoading;
            calculateBtn.textContent = isLoading ? 'Calculando...' : 'Calcular';
        }
    };

    const updateSummary = (resumo) => {
        if (subtotalSpan && typeof resumo.subtotal === 'number') {
            subtotalSpan.textContent = formatCurrency(resumo.subtotal);
        }
        if (shippingValueSpan) {
            if (typeof resumo.frete === 'number' && resumo.frete > 0) {
                shippingValueSpan.textContent = formatCurrency(resumo.frete);
            } else if (resumo.frete === 0) {
                shippingValueSpan.textContent = 'Grátis';
            } else {
                shippingValueSpan.textContent = 'A calcular';
            }
        }
        if (totalWithShippingSpan && typeof resumo.total === 'number') {
            totalWithShippingSpan.textContent = formatCurrency(resumo.total);
        }
    };

    window.enviarCotacao = async (cep, servico) => {
        const cleanCep = sanitizeCep(cep);
        if (cleanCep.length !== 8) {
            resultContainer.innerHTML = '<p style="color: red;">CEP inválido. Por favor, digite 8 números.</p>';
            return;
        }

        toggleLoading(true);

        
        let optionsContainer = resultContainer.querySelector('.frete-opcoes');
        if (!optionsContainer) { 
            resultContainer.innerHTML = '<p>Calculando...</p>';
        } else { 
            optionsContainer.classList.add('is-loading');
        }

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ cep: cleanCep, servico: servico }),
            });

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Não foi possível calcular o frete.');
            }

            renderOptions(payload.cotacao, payload.selecionado);
            updateSummary(payload.resumo);
            
            
            const freteCalculadoEvent = new CustomEvent('frete-calculado', {
                detail: {
                    cep: cleanCep,
                    destino: payload.cotacao.destino
                }
            });
            document.body.dispatchEvent(freteCalculadoEvent);

            
            try {
                if (cleanCep) {
                    sessionStorage.setItem('cepCalculado', cleanCep);
                }
                if (payload.selecionado) {
                    const freteSelecionado = {
                        id: `frete-option-${payload.selecionado.codigo}`, 
                        codigo: payload.selecionado.codigo,
                        valor: payload.selecionado.valor,
                        nome: payload.selecionado.label,
                        prazo: `${payload.selecionado.prazo} dias úteis`
                    };
                    sessionStorage.setItem('freteEscolhido', JSON.stringify(freteSelecionado));
                    console.log('Opção de frete salva na sessão:', freteSelecionado);
                }
            } catch (e) {
                console.error('Falha ao salvar dados do frete na sessão.', e);
            }


        } catch (error) {
            console.error('Frete Error:', error);
            resultContainer.innerHTML = `<p style="color: red;">${error.message}</p>`;
        } finally {
            toggleLoading(false);
        }
    };

    const renderOptions = (cotacao, selecionado) => {
        
        resultContainer.innerHTML = ''; 

        
        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'frete-opcoes';

        if (!cotacao || !cotacao.opcoes || cotacao.opcoes.length === 0) {
            optionsContainer.innerHTML = '<p style="color: orange;">Nenhuma opção de frete encontrada para este CEP.</p>';
            resultContainer.appendChild(optionsContainer);
            return;
        }

        cotacao.opcoes.forEach(opcao => {
            const isChecked = selecionado && selecionado.codigo === opcao.codigo;
            const optionDiv = document.createElement('div');
            optionDiv.className = 'frete-option';
            optionDiv.innerHTML = `
                <div class="frete-option">
                    <input type="radio" id="frete-option-${opcao.codigo}" name="frete-option" value="${opcao.codigo}" ${isChecked ? 'checked' : ''} onchange="enviarCotacao('${cotacao.destino.cep}', this.value)">
                    <div class="frete-option__info">
                        <strong>${opcao.label}</strong>
                        <span>${formatCurrency(opcao.valor)}</span>
                        <small>Prazo: ${opcao.prazo} dias úteis</small>
                    </div>
                </div>
            `;
            optionsContainer.appendChild(optionDiv.firstElementChild);
        });
        resultContainer.appendChild(optionsContainer);
    };
    
    if(calculateBtn) {
        calculateBtn.addEventListener('click', () => {
            enviarCotacao(cepInput.value);
        });
    }

    if(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            enviarCotacao(cepInput.value);
        })
    }

    cepInput.addEventListener('input', () => {
        cepInput.value = cepInput.value.replace(/\D/g, '').replace(/^(\d{5})(\d)/, '$1-$2');
    });
}