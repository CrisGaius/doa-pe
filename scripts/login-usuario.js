// var botaoFechar = document.getElementById('caixa-botao-fechar');
// var caixaErro = document.getElementById('caixa-erro');
// var botaoEntrar = document.getElementById('btn-entrar');
// var emailInvalido = document.getElementById('email-invalido');
// var inputEmail = document.getElementById('email');

// botaoFechar.addEventListener("click", fecharCaixaErro);
// inputEmail.addEventListener("input", validarErroEmail);

// function validarEmail(email) {
//     const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
//     return regex.test(email);
// }

// function validarErroEmail() {
//     var email = inputEmail.value;
    
//     if (validarEmail(email)) {
//         emailInvalido.style.display = "none";
//         inputEmail.style.border = "none";
//     } else {
//         emailInvalido.style.display = "block";
//         inputEmail.style.border = "1px solid #FF6B00";
//         inputEmail.style.padding = "8px";
//     }
// }   

// function fecharCaixaErro() {
//     caixaErro.style.display = "none";
//     emailInvalido.style.display = "none";
// }

const formulario = document.querySelector("form")
const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
const caixaErro = document.getElementById('caixa-erro');
const textoErro = document.getElementById('email-invalido')
const inputEmail = document.getElementById('email')
const inputSenha = document.querySelector('input#senha')
const botaoFechar = document.getElementById('caixa-botao-fechar');


formulario.addEventListener('submit', (e) => {
    e.preventDefault()

    const emailValido = validarEmail()
    const tamanhoSenha = inputSenha.value

    if(emailValido && (tamanhoSenha.length >= 6 && tamanhoSenha.length <= 20)) {
        formulario.submit()
    } else {
        caixaErro.style.display = 'flex';
        console.log("tá aqui.");
    }
})

function validarEmail() {
    if (!regexEmail.test(inputEmail.value)) {
        inputEmail.classList.add('erro')
        textoErro.classList.add('mostrar')
        return false
    } else {
        inputEmail.classList.remove('erro')
        textoErro.classList.remove('mostrar')
        return true
    }
}

function fecharCaixaErro() {
    caixaErro.style.display = "none"
}

inputEmail.addEventListener('input', validarEmail)
botaoFechar.addEventListener("click", fecharCaixaErro)