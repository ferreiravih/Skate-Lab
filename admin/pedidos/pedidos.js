document.addEventListener('DOMContentLoaded', () => {
  let menuAberto = null;

  // ===== Carregar status salvos (ou padrão Pendente) =====
  document.querySelectorAll('.status').forEach((status, index) => {
    const salvo = localStorage.getItem(`pedidoStatus_${index}`);
    if (salvo) {
      status.textContent = salvo;
      status.className = 'status ' + salvo.toLowerCase().replace(/\s+/g, '');
    } else {
      status.textContent = 'Pendente';
      status.className = 'status pendente';
    }
  });

  // ===== Ações de clique para trocar status =====
  document.querySelectorAll('.status').forEach((status, index) => {
    status.addEventListener('click', e => {
      if (menuAberto) menuAberto.remove();

      const menu = document.createElement('div');
      menu.className = 'status-menu';
      const opcoes = ['Pendente', 'Em preparo', 'Enviado', 'Entregue', 'Cancelado'];

      opcoes.forEach(op => {
        const span = document.createElement('span');
        span.textContent = op;
        span.addEventListener('click', () => {
          // Atualiza o texto e classe
          status.textContent = op;
          status.className = 'status ' + op.toLowerCase().replace(/\s+/g, '');

          // ===== Salva no localStorage =====
          localStorage.setItem(`pedidoStatus_${index}`, op);

          menu.remove();
        });
        menu.appendChild(span);
      });

      document.body.appendChild(menu);
      const rect = e.target.getBoundingClientRect();
      menu.style.left = `${rect.left}px`;
      menu.style.top = `${rect.bottom + window.scrollY + 4}px`;
      menuAberto = menu;
    });
  });

  // ===== Fecha o menu se clicar fora =====
  document.addEventListener('click', e => {
    if (!e.target.classList.contains('status')) {
      if (menuAberto) menuAberto.remove();
    }
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modalPedido');
  const fechar = document.getElementById('fecharModal');
  const btnPreparo = document.getElementById('btnPreparo');
  const btnFinalizar = document.getElementById('btnFinalizar');

  document.querySelectorAll('.ri-eye-line').forEach((icone) => {
    icone.addEventListener('click', () => {
      const linha = icone.closest('tr');
      const numero = linha.children[0].textContent;
      const cliente = linha.children[1].textContent;
      const data = linha.children[2].textContent;
      const status = linha.querySelector('.status');
      const valor = linha.children[4].textContent;

      document.getElementById('modalNumero').textContent = numero;
      document.getElementById('modalCliente').textContent = cliente;
      document.getElementById('modalData').textContent = data;
      document.getElementById('modalTotal').textContent = valor;

      // Atualiza status dentro do modal
      const modalStatus = document.getElementById('modalStatus');
      modalStatus.textContent = status.textContent;
      modalStatus.className = status.className;

      modal.style.display = 'flex';

      // "Iniciar Preparo"
      btnPreparo.onclick = () => {
        status.textContent = 'Em preparo';
        status.className = 'status empreparo';
        modalStatus.textContent = 'Em preparo';
        modalStatus.className = 'status empreparo';
        modal.style.display = 'none';
      };

      // "Finalizar Pedido"
      btnFinalizar.onclick = () => {
        status.textContent = 'Enviado';
        status.className = 'status enviado';
        modalStatus.textContent = 'Enviado';
        modalStatus.className = 'status enviado';
        modal.style.display = 'none';
      };
    });
  });

  fechar.addEventListener('click', () => modal.style.display = 'none');
  modal.addEventListener('click', e => {
    if (e.target === modal) modal.style.display = 'none';
  });
});
