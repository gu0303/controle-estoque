// Script de verificação de estoque zerado

document.addEventListener('DOMContentLoaded', function () {
    const itemSelect = document.getElementById('item_id');
    const tipoSelect = document.getElementById('tipo');

    function atualizarTipo() {
        const selectedOption = itemSelect.selectedOptions[0];
        const quantidade = selectedOption ? parseInt(selectedOption.dataset.quantidade) : null;

        if (quantidade === 0) {
            // Desabilita a opção de saída
            Array.from(tipoSelect.options).forEach(option => {
                if (option.value === 'saida') option.disabled = true;
            });

            // Se estava selecionada, troca para entrada
            if (tipoSelect.value === 'saida') {
                tipoSelect.value = 'entrada';
            }
        } else {
            // Habilita a opção de saída
            Array.from(tipoSelect.options).forEach(option => {
                if (option.value === 'saida') option.disabled = false;
            });
        }
    }

    // Atualiza ao carregar a página (old() selecionado)
    atualizarTipo();

    // Atualiza quando o usuário muda o item
    itemSelect.addEventListener('change', atualizarTipo);
});
