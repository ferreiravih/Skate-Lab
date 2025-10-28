const formProduto = document.querySelector('.form-produto');

if (formProduto) {
  // Cria o popup dinamicamente
  const popup = document.createElement('div');
  popup.id = 'popup-sucesso';
  popup.textContent = 'Produto cadastrado com sucesso!';
  document.body.appendChild(popup);

  formProduto.addEventListener('submit', function (e) {
    e.preventDefault();

    // Só dispara para o botão "Criar Produto"
    if (e.submitter && e.submitter.classList.contains('btn-criar')) {

      // Limpa todos os inputs e textarea do formulário
      formProduto.querySelectorAll('input, textarea, select').forEach(field => {
        if (field.type === 'checkbox' || field.type === 'radio') {
          field.checked = false;
        } else {
          field.value = '';
        }
      });

      // Mostra o popup
      popup.classList.add('show');

      // Oculta após 3 segundos
      setTimeout(() => {
        popup.classList.remove('show');
      }, 5000);
    }
  });
}

// --- Alternar texto do switch ---
const switchAtivo = document.getElementById('switchAtivo');
const statusTexto = document.getElementById('statusTexto');

if (switchAtivo && statusTexto) {
switchAtivo.addEventListener('change', () => {
  statusTexto.textContent = switchAtivo.checked ? 'Produto Ativo' : 'Produto Inativo';
});
}
