var botaoFechar = document.getElementById('caixa-botao-fechar');
var caixaErro = document.getElementById('caixa-erro');
var botaoEntrar = document.getElementById('btn-entrar');
var emailInvalido = document.getElementById('email-invalido');
var inputEmail = document.getElementById('email');

botaoFechar.addEventListener("click", fecharCaixaErro);
inputEmail.addEventListener("input", validarErroEmail);

function validarEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return regex.test(email);
}

function validarErroEmail() {
    var email = inputEmail.value;
    
    if (validarEmail(email)) {
        emailInvalido.style.display = "none";
        inputEmail.style.border = "none";
    } else {
        emailInvalido.style.display = "block";
        inputEmail.style.border = "1px solid #FF6B00";
        inputEmail.style.padding = "8px";
    }
}   

function fecharCaixaErro() {
    caixaErro.style.display = "none";
    emailInvalido.style.display = "none";
}
