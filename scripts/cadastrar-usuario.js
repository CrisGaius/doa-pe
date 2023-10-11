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
const form = document.getElementById('registro-form');
const nomeInput = document.getElementById('ipt-nome');
const emailInput = document.getElementById('ipt-email');
const telInput = document.getElementById('ipt-tel');
const senhaInput = document.getElementById('ipt-senha');
const enviarButton = document.getElementById('btn-enviar');

const fieldValidationStatus = {
  nome: false,
  email: false,
  tel: false,
  senha: false,
};

nomeInput.addEventListener('input', () => validateField(nomeInput, 'nome'));
emailInput.addEventListener('input', () => validateField(emailInput, 'email'));
telInput.addEventListener('input', () => validateField(telInput, 'tel'));
senhaInput.addEventListener('input', () => validateField(senhaInput, 'senha'));
form.addEventListener('submit', handleSubmit);

function validateField(input, fieldName) {
  const value = input.value.trim();
  const errorElement = document.getElementById(`${fieldName}-error`);
  errorElement.textContent = '';

  if (value.length === 0) {
    errorElement.textContent = 'Campo é obrigatório.';
    fieldValidationStatus[fieldName] = false;
  } else if (fieldName === 'email' && !/^(.+)@(gmail\.com|outlook\.com|hotmail\.com)$/.test(value)) {
    errorElement.textContent = 'Email deve ser @gmail.com, @outlook.com ou @hotmail.com';
    fieldValidationStatus[fieldName] = false;
  } else if (fieldName === 'tel' && value.replace(/\D/g, '').length !== 11) {
    errorElement.textContent = 'Telefone deve ter 11 números';
    fieldValidationStatus[fieldName] = false;
  } else if (fieldName === 'senha' && value.length < 8) {
    errorElement.textContent = 'Senha deve ter pelo menos 8 caracteres';
    fieldValidationStatus[fieldName] = false;
  } else {
    fieldValidationStatus[fieldName] = true;
  }

  const allFieldsValid = Object.values(fieldValidationStatus).every((status) => status);

  if (allFieldsValid) {
    enviarButton.removeAttribute('disabled');
  } else {
    enviarButton.setAttribute('disabled', 'disabled');
  }
}

function handleSubmit(event) {

  const errorElements = [nomeInput, emailInput, telInput, senhaInput].map((field) =>
      document.getElementById(`${field.id}-error`)
  );

  if (errorElements.every((element) => element.textContent === '')) {

  } else {
      alert('Por favor, corrija os erros no formulário.');
  }
}

window.addEventListener('load', function() {
  document.getElementById('registro-form').reset();
});