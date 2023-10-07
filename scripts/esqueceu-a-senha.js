const formulario = document.querySelector('form#formulario')
const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
const inputEmail = document.querySelector('input#email')
const textoErro = document.querySelector('.texto-erro')

formulario.addEventListener('submit', function verificarCampo(e) {
    e.preventDefault()

    const emailValido = validarEmail()

    if (emailValido) {
        // formulario.submit()
        console.log('passou.')
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

inputEmail.addEventListener('input', validarEmail)