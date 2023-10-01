const formulario = document.querySelector('form#formulario')
const inputSenha = document.querySelector('input#senha')
const textoErro = document.querySelector('.texto-erro')
const iconeOlho = document.querySelector('img#icone-olho')

formulario.addEventListener('submit', function verificarCampo(e) {
    e.preventDefault()

    const senhaValida = validarSenha()

    if (senhaValida) {
        // formulario.submit()
        console.log('passou.')
    }
})

function validarSenha() {
    if (inputSenha.value.length < 6 || inputSenha.value.length >= 20) {
        inputSenha.classList.add('erro')
        textoErro.classList.add('mostrar')
        return false
    } else {
        inputSenha.classList.remove('erro')
        textoErro.classList.remove('mostrar')
        return true
    }
}

inputSenha.addEventListener('input', validarSenha)

iconeOlho.addEventListener('click', function visualizarSenha() {
    if (iconeOlho.classList.contains('ver')) {
        iconeOlho.classList.remove('ver')
        inputSenha.type = 'password'
        iconeOlho.src = "../icons/icone-olho.svg"
    } else {
        iconeOlho.classList.add('ver')
        inputSenha.type = 'text'
        iconeOlho.src = "../icons/icone-olho-fechado.svg"
    }
})