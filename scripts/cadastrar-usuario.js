// Função para adicionar máscara de telefone
function formatarTelefone() {
    var input = document.getElementById("ipt-tel");
    var value = input.value.replace(/\D/g, '');
    var formattedValue = '';

    if (value.length > 0) {
        formattedValue = '(' + value;
    }

    if (value.length > 2) {
        formattedValue = '(' + value.substring(0, 2) + ') ' + value.substring(2);
    }

    if (value.length > 7) {
        formattedValue = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7, 11);
    }

    input.value = formattedValue;
}

var telefoneInput = document.getElementById("ipt-tel");
telefoneInput.addEventListener("input", formatarTelefone);

// Funções para validar o formulário
