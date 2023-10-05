const botaoAbrirModal = document.querySelector('button#abrir-modal')
const fundoModal = document.querySelector('#fundo-modal')

botaoAbrirModal.addEventListener('click', () => {
    fundoModal.classList.add('open')
})

fundoModal.addEventListener('click', (e) => {
    if(e.target.id === 'fundo-modal' || e.target.id === 'botao-fechar-modal' || e.target.id === 'botao-negar') {
        fundoModal.classList.remove('open')
    }
})