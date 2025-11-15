(() => {
  const form = document.getElementById('frete-form');
  const cepInput = document.getElementById('cep-frete');
  const feedbackEl = document.getElementById('frete-feedback');
  const opcoesContainer = document.getElementById('frete-opcoes');
  const freteSpan = document.getElementById('frete-valor');
  const totalSpan = document.getElementById('total-valor');
  const subtotalSpan = document.getElementById('subtotal-valor');
  const selectedText = document.getElementById('frete-selected-text');
  const calcButton = document.getElementById('btn-calcular-frete');
  const endpoint = 'contr/calcular_frete.php';

  if (!form || !cepInput || !opcoesContainer) {
    return;
  }

  const safeParse = (value) => {
    if (!value || value === 'null') {
      return null;
    }
    try {
      return JSON.parse(value);
    } catch (error) {
      console.warn('Frete: não foi possível interpretar o cache salvo.', error);
      return null;
    }
  };

  const sanitizeCep = (value) => value.replace(/\D/g, '').slice(0, 8);

  const formatCep = (digits) => {
    if (digits.length !== 8) {
      return digits;
    }
    return `${digits.slice(0, 5)}-${digits.slice(5)}`;
  };

  const formatCurrency = (value) =>
    Number(value || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

  const setFeedback = (message, type = 'info') => {
    if (!feedbackEl) {
      return;
    }
    feedbackEl.textContent = message ?? '';
    feedbackEl.className = `frete-feedback ${type}`;
  };

  const toggleLoading = (isLoading) => {
    if (!calcButton) {
      return;
    }
    calcButton.disabled = isLoading;
    calcButton.textContent = isLoading ? 'Calculando...' : 'Calcular';
  };

  const updateResumoValores = (resumo) => {
    if (typeof resumo?.frete === 'number' && freteSpan) {
      freteSpan.dataset.value = resumo.frete.toFixed(2);
      freteSpan.textContent = resumo.frete > 0 ? formatCurrency(resumo.frete) : 'Grátis';
    }
    if (typeof resumo?.total === 'number' && totalSpan) {
      totalSpan.dataset.value = resumo.total.toFixed(2);
      totalSpan.textContent = formatCurrency(resumo.total);
    }
  };

  const updateSelecionado = (opcao, destino) => {
    if (!selectedText) {
      return;
    }

    if (!opcao) {
      selectedText.textContent = 'Informe seu CEP para estimar prazo e valor de entrega.';
      return;
    }

    const destinoLabel = destino?.cidade && destino?.uf
      ? ` · ${destino.cidade}/${destino.uf}`
      : '';
    selectedText.textContent = `${opcao.label} · ${opcao.prazo} dias úteis${destinoLabel}`;
  };

  const persistCotacao = (cotacao, selecionado) => {
    opcoesContainer.dataset.frete = JSON.stringify({
      cep: cotacao?.destino?.cep ?? '',
      destino: cotacao?.destino ?? null,
      opcoes: cotacao?.opcoes ?? [],
      selecionado,
    });
  };

  const renderOpcoes = (cotacao, selecionado) => {
    if (!cotacao?.opcoes?.length) {
      opcoesContainer.innerHTML = '<p class="frete-empty">Não foi possível gerar opções de entrega.</p>';
      opcoesContainer.classList.remove('is-visible');
      return;
    }

    opcoesContainer.innerHTML = '';
    opcoesContainer.classList.add('is-visible');
    persistCotacao(cotacao, selecionado);

    const destinoLabel = cotacao.destino?.cidade && cotacao.destino?.uf
      ? `${cotacao.destino.cidade}/${cotacao.destino.uf}`
      : `CEP ${formatCep(cotacao.destino?.cep ?? '')}`;
    const destinoEl = document.createElement('p');
    destinoEl.className = 'frete-destino';
    destinoEl.textContent = `Entrega para ${destinoLabel}`;
    opcoesContainer.appendChild(destinoEl);

    cotacao.opcoes.forEach((opcao) => {
      const label = document.createElement('label');
      label.className = 'frete-option';

      const radio = document.createElement('input');
      radio.type = 'radio';
      radio.name = 'frete-option';
      radio.value = opcao.codigo;
      radio.checked = selecionado?.codigo === opcao.codigo;
      radio.addEventListener('change', () => {
        enviarCotacao(cotacao.destino.cep, opcao.codigo);
      });

      const info = document.createElement('div');
      info.className = 'frete-option__info';

      const title = document.createElement('strong');
      title.textContent = opcao.label;

      const meta = document.createElement('span');
      meta.textContent = `${formatCurrency(opcao.valor)} • ${opcao.prazo} dias úteis`;

      const desc = document.createElement('small');
      desc.textContent = opcao.descricao;

      info.appendChild(title);
      info.appendChild(meta);
      info.appendChild(desc);

      label.appendChild(radio);
      label.appendChild(info);
      opcoesContainer.appendChild(label);
    });
  };

  const enviarCotacao = async (cep, servico) => {
    const data = {
      cep,
    };
    if (servico) {
      data.servico = servico;
    }

    toggleLoading(true);
    setFeedback('Calculando frete...', 'info');

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      });
      const payload = await response.json();

      if (!response.ok || !payload.success) {
        throw new Error(payload.message || 'Não foi possível calcular o frete.');
      }

      renderOpcoes(payload.cotacao, payload.selecionado);
      updateResumoValores(payload.resumo);
      updateSelecionado(payload.selecionado, payload.cotacao.destino);
      setFeedback(`Valor atualizado. Prazo estimado: ${payload.selecionado.prazo} dias úteis.`, 'success');
    } catch (error) {
      console.error('Frete', error);
      setFeedback(error.message || 'Falha ao calcular o frete.', 'error');
    } finally {
      toggleLoading(false);
    }
  };

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const digits = sanitizeCep(cepInput.value);

    if (digits.length !== 8) {
      setFeedback('Informe um CEP válido com 8 números.', 'error');
      return;
    }

    enviarCotacao(digits);
  });

  cepInput.addEventListener('input', () => {
    const digits = sanitizeCep(cepInput.value);
    cepInput.value = formatCep(digits);
  });

  const initialState = safeParse(opcoesContainer.dataset.frete);
  if (initialState?.opcoes?.length) {
    renderOpcoes(initialState, initialState.selecionado);
    updateSelecionado(initialState.selecionado, initialState.destino);
    if (initialState.cep && cepInput.value.trim() === '') {
      cepInput.value = formatCep(initialState.cep);
    }
  }
})();
