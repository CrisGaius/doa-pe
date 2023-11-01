
function mascaraTelefone() {
    const telefoneInput = event.target;
    let valorTelefone = telefoneInput.value.replace(/\D/g, '');

    if (valorTelefone.length > 11) {
        valorTelefone = valorTelefone.slice(0, 11); // Limita a 11 caracteres
    }

    if (valorTelefone.length <= 10) {
        // Números residenciais:  (XX) XXXX-XXXX
        telefoneInput.value = valorTelefone.replace(/(\d{2})(\d{0,4})(\d{0,4})/, '($1) $2-$3');
    } else {
        // Números pessoais: (XX) XXXXX-XXXX
        telefoneInput.value = valorTelefone.replace(/(\d{2})(\d{0,5})(\d{0,4})/, '($1) $2-$3');
    }
}
const inputTelefone = document.getElementById('ipt-tel');
inputTelefone.addEventListener('input', mascaraTelefone);

var telefoneInput = document.getElementById("ipt-tel");
telefoneInput.addEventListener("input", formatarTelefone);

function validateField(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    const value = input.value.trim();

    if (value.length === 0) {
        error.textContent = "Campo não pode estar vazio.";
    } else if (inputId === 'ipt-name' && value.length < 5) {
        error.textContent = "Nome deve ter pelo menos 5 caracteres.";
    } else if (inputId === 'ipt-email' && !value.includes("@")) {
        error.textContent = "Email deve conter um '@'.";
    } else {
        error.textContent = "";
    }

    enableSubmitButton();
}

function aplicarMascaraTelefone() {
    const input = document.getElementById('ipt-tel');
    const value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos
    input.value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}

function validarTelefone() {
    const input = document.getElementById('ipt-tel');
    const error = document.getElementById('tel-error');
    const value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos

    if (value.length === 0) {
        error.textContent = "Telefone não pode estar vazio.";
    } else {
        limitarTelefone();
        error.textContent = "";
    }

    enableSubmitButton();
}

function limitarTelefone() {
    const input = document.getElementById('ipt-tel');
    const value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos

    if (value.length > 11) {
        input.value = value.substr(0, 11);
    }
}

function enableSubmitButton() {
    const nomeError = document.getElementById("nome-error").textContent;
    const emailError = document.getElementById("email-error").textContent;
    const telError = document.getElementById("tel-error").textContent;

    if (nomeError === "" && emailError === "" && telError === "") {
        document.getElementById("btn-enviar").removeAttribute("disabled");
    } else {
        document.getElementById("btn-enviar").setAttribute("disabled", "true");
    }
}