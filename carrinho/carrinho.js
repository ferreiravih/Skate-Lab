
  let qtd = 1;
  const precoUnit = 200;

  function atualizarTotal() {
    document.getElementById("qtd").innerText = qtd;
    document.getElementById("total").innerText = "R$ " + (qtd * precoUnit).toFixed(2).replace(".", ",");
  }

  function aumentar() {
    qtd++;
    atualizarTotal();
  }

  function diminuir() {
    if (qtd > 1) {
      qtd--;
      atualizarTotal();
    }
  }
