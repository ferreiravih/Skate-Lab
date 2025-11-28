document.addEventListener('DOMContentLoaded', () => {
  const opcoes = document.querySelectorAll('.opcao');
  const conteudos = document.querySelectorAll('.conteudopagamento');

  opcoes.forEach(opcao => {
    opcao.addEventListener('click', () => {
      const id = opcao.getAttribute('data-pagamento');
      const conteudo = document.getElementById(id);

      
      if (opcao.classList.contains('ativo')) {
        opcao.classList.remove('ativo');
        conteudo.style.display = 'none';
        return;
      }


      opcoes.forEach(o => o.classList.remove('ativo'));
      conteudos.forEach(c => c.style.display = 'none');


      opcao.classList.add('ativo');
      conteudo.style.display = 'block';
    });
  });
});


window.addEventListener('scroll', () => {
  const scrollY = window.scrollY;
  const card = document.querySelector('.containerdadoss');
  card.style.top = 100 + scrollY * 0.5 + 'px';
});