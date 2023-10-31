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
function validateNome() {
  const nomeInput = document.getElementById('ipt-nome');
  const erroNome = document.getElementById('erroNome');

  if (nomeInput.value.length < 5) {
      nomeInput.style.border = '2.3px solid var(--amarelo)';
      erroNome.textContent = 'O nome deve ter pelo menos 5 letras';
  } else {
      nomeInput.style.border = '';
      erroNome.textContent = '';
  }
}

function validateEmail() {
  const emailInput = document.getElementById('ipt-email');
  const erroEmail = document.getElementById('erroEmail');
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

  if (!emailRegex.test(emailInput.value)) {
      emailInput.style.border = '2.3px solid var(--amarelo)';
      erroEmail.textContent = 'Email inválido';
  } else {
      emailInput.style.border = '';
      erroEmail.textContent = '';
  }
}

function validateTelefone() {
  const telefoneInput = document.getElementById('ipt-tel');
  const erroTelefone = document.getElementById('erroTelefone');
  const telefoneValue = telefoneInput.value.replace(/\D/g, '');

  if (telefoneValue.length < 11) {
      telefoneInput.style.border = '2.3px solid var(--amarelo)';
      erroTelefone.textContent = 'O telefone deve ter pelo menos 11 números';
  } else {
      telefoneInput.style.border = '';
      erroTelefone.textContent = '';
  }
}

function validateSenha() {
  const senhaInput = document.getElementById('ipt-senha');
  const erroSenha = document.getElementById('erroSenha');

  if (senhaInput.value.length < 8 || senhaInput.value.length > 30) {
      senhaInput.style.border = '2.3px solid var(--amarelo)';
      erroSenha.textContent = 'A senha deve ter entre 8 e 30 caracteres';
  } else {
      senhaInput.style.border = '';
      erroSenha.textContent = '';
  }
}

function validateForm() {
  const nomeInput = document.getElementById('ipt-nome');
  const emailInput = document.getElementById('ipt-email');
  const telefoneInput = document.getElementById('ipt-tel');
  const senhaInput = document.getElementById('ipt-senha');

  validateNome();
  validateEmail();
  validateTelefone();
  validateSenha();

  if (nomeInput.style.border === '2.3px solid var(--amarelo)' || emailInput.style.border === '2.3px solid var(--amarelo)' || telefoneInput.style.border === '2.3px solid var(--amarelo)' || senhaInput.style.border === '2.3px solid var(--amarelo)') {
      alert('Por favor, corrija os erros no formulário antes de enviar.');
  } else {
      document.getElementById('registro-form').submit();
  }
}

document.getElementById('ipt-nome').addEventListener('input', validateNome);
document.getElementById('ipt-email').addEventListener('input', validateEmail);
document.getElementById('ipt-tel').addEventListener('input', validateTelefone);
document.getElementById('ipt-senha').addEventListener('input', validateSenha);

document.getElementById('registro-form').addEventListener('submit', function (e) {
  e.preventDefault();
  validateForm();
});