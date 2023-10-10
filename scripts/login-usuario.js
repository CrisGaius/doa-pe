var botaoFechar = document.getElementById('caixa-botao-fechar');
var caixaErro = document.getElementById('caixa-erro');
var botaoEntrar = document.getElementById('btn-entrar');
var emailInvalido = document.getElementById('email-invalido');
var inputEmail = document.getElementById('email');

botaoFechar.addEventListener("click", fecharCaixaErro);
inputEmail.addEventListener("input", validarErroEmail);
botaoEntrar.addEventListener("click", entrar);

function validarEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return regex.test(email);
}

function validarErroEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value;
    var senha = document.getElementById('senha').value;

    if(validarEmail(email)){
        emailInvalido.style.display = "none";
    } else {
        emailInvalido.style.display = "block";
    }
}

function entrar(event) {
    event.preventDefault()
    var email = document.getElementById('email').value;
    var senha = document.getElementById('senha').value;

    if(validarErroEmail) {
        if (email == "admin@gmail.com" && senha == "admin"){
            location.href = "teste.html";
        } else {
            caixaErro.style.display = "flex";
        }
    }
}

function fecharCaixaErro() {
    caixaErro.style.display = "none";
    emailInvalido.style.display = "none";
    document.getElementById('email').value = "";
    document.getElementById('senha').value = "";
}

