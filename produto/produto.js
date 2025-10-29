document.addEventListener("DOMContentLoaded", () => {
    // Seleciona os botões de quantidade
    const decreaseBtn = document.querySelector(".qtd-btn[data-action='decrease']");
    const increaseBtn = document.querySelector(".qtd-btn[data-action='increase']");
    
    // Seleciona o INPUT de quantidade (que mudamos no passo 2)
    const quantityInput = document.querySelector("input.num[name='quantidade']");

    // Verifica se os elementos existem
    if (!decreaseBtn || !increaseBtn || !quantityInput) {
        console.warn("Controles de quantidade (+/-) ou input 'quantidade' não encontrados.");
        return;
    }

    // Adiciona evento ao botão de AUMENTAR
    increaseBtn.addEventListener("click", () => {
        let currentValue = parseInt(quantityInput.value, 10);
        if (isNaN(currentValue)) {
            currentValue = 1;
        }
        quantityInput.value = currentValue + 1;
    });

    // Adiciona evento ao botão de DIMINUIR
    decreaseBtn.addEventListener("click", () => {
        let currentValue = parseInt(quantityInput.value, 10);
        if (isNaN(currentValue)) {
            currentValue = 1;
        }
        
        // Impede que a quantidade seja menor que 1
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });
});