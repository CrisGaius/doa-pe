const botoesAbrirModal = document.querySelectorAll('.abrir-modal')
const fundoModal = document.querySelector('#fundo-modal')
const botaoConfirmar = document.querySelector('#botao-confirmar')

botoesAbrirModal.forEach((botao) => {
    botao.addEventListener('click', (e) => {
        e.preventDefault()
        fundoModal.classList.add('open')
        botaoConfirmar.href = botao.href
    })
})

fundoModal.addEventListener('click', (e) => {
    if(e.target.id === 'fundo-modal' || e.target.id === 'botao-fechar-modal' || e.target.id === 'botao-negar') {
        fundoModal.classList.remove('open')
    }
})