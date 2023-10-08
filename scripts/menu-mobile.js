const iconeMenu = document.querySelector('#icone-menu');

iconeMenu.addEventListener('click', function abrirMenu() {
    const divMenuMobile = document.querySelector('.menu-mobile');

    if (divMenuMobile.classList.contains('open')) {
        divMenuMobile.classList.remove('open')
        iconeMenu.src = "../icons/icone-menu.svg"
    } else {
        divMenuMobile.classList.add('open')
        iconeMenu.src = "../icons/icone-fechar.svg"
    }
})