// Validação
const forms       = document.querySelector('form')
const campos      = document.querySelectorAll('.campos')
const spans       = document.querySelectorAll('.alerta')
const emailRegex  = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;


// Função para mostrar o erro
function setError(index){
    campos[index].style.border  = "2px solid var(--amarelo)"
    spans [index].style.display = "block" 
}
// Função para remover o erro
function removerError(index){
    campos[index].style.border  = ""
    spans [index].style.display = "none" 
}

// Função para validar o nome
function nameValidate(){
    if(campos[0].value.length < 3){
        setError(0)
    } else{
        removerError(0)
    }
}

// Função para validar o endereçco
function enderecoValidate(){
    if (campos[1].value.length < 20){
        setError(1)
    } else{
        removerError(1)
    }
}

// Função para validar o email
function emailValidate(){
    if(emailRegex.test(campos[2].value)){
        removerError(2)
    } else{
        setError(2)
    }
}

// Função para validar o número de contato
function numeroValidate(){
    if(campos[3].value.length < 14){
        setError(3)
    } else{
        removerError(3)
    }
}

// Função para validar o CNPJ
function cnpjValidate(){
    if(campos[4].value.length < 18){
        setError(4)
    } else{
        removerError(4)
    }
}

// Validar o pix
const spanPix  = document.getElementById('spanPix')
const caixaPix = document.getElementById('ipt-pix')
caixaPix.addEventListener('keyup', function(){
    if (caixaPix.value.length < 11){
        console.log('Pix Inválido')
        caixaPix.style.border = "2px solid var(--amarelo)"
        spanPix.style.display = "block"
    } else{
        console.log('Pix Válido')
        caixaPix.style.border = ""
        spanPix.style.display = "none"
    }
})

// Validar o número (conta bancária)
const spanNumConta  = document.getElementById('spanNumConta')
const caixaNumConta = document.getElementById('ipt-NumConta')
caixaNumConta.addEventListener('keyup', function(){
    if (caixaNumConta.value.length < 7){
        console.log('Num Inválido')
        caixaNumConta.style.border = "2px solid var(--amarelo)"
        spanNumConta.style.display = "block"
    } else{
        console.log('Num Válido')
        caixaNumConta.style.border = ""
        spanNumConta.style.display = "none"
    }
})

// Validar a agência (conta bancária)
const spanAgencia  = document.getElementById('spanAgencia')
const caixaAgencia = document.getElementById('ipt-agencia')
caixaAgencia.addEventListener('keyup', function(){
    if (caixaAgencia.value.length < 4){
        console.log('Agência Inválido')
        caixaAgencia.style.border = "2px solid var(--amarelo)"
        spanAgencia.style.display = "block"
    } else{
        console.log('Agência Válido')
        caixaAgencia.style.border = ""
        spanAgencia.style.display = "none"
    }
})

// Validar a instituição (conta bancária)
const spanInstituicao  = document.getElementById('spanInstituicao')
const caixaInstituicao = document.getElementById('ipt-Instituicao')
caixaInstituicao.addEventListener('keyup', function(){
    if (caixaInstituicao.value.length < 4){
        console.log('Instituição Inválido')
        caixaInstituicao.style.border  = "2px solid var(--amarelo)"
        spanInstituicao .style.display = "block"
    } else{
        console.log('Instituição Válido')
        caixaInstituicao.style.border  = ""
        spanInstituicao .style.display = "none"
    }
})


// // Validar o select "Finalidade" (de ONG)
// const spanTipo   = document.getElementById('span-FinalidadeONG')
// const selectTipo = document.getElementById('select-finalidade')
// selectTipo.addEventListener('click', function(){
//     // Mostrar o erro
//     if (selectTipo.value == 'FinalidadeONG'){
//         selectTipo.style.border = "2px solid var(--amarelo)"
//         spanTipo.style.display = "block"
//     } else{ 
//         selectTipo.style.border = "none"
//         spanTipo.style.display = "none"
//     }
// })

// // Validar o select "Tipo de conta"
// const spanPagamento   = document.getElementById('span-TipoConta')
// const selectPagamento = document.getElementById('select-conta')    
// selectPagamento.addEventListener('click', function(){
//     // Mostrar o erro
//     if (selectPagamento.value == 'tipoConta'){
//         selectPagamento.style.border = "2px solid var(--amarelo)"
//         spanPagamento.style.display = "block"
//     } else{ 
//         selectPagamento.style.border = "2px solid rgb(0, 166, 255)"
//         spanPagamento.style.display = "none"
//     }
// })


// // Validar o select "Região"
// const spanRegiao   = document.getElementById('span-regiao')
// const selectRegiao = document.getElementById('select-regiao')
// selectRegiao.addEventListener('click', function(){
//     // Mostrar o erro
//     if(selectRegiao.value == 'SelecioneRegiao'){
//         selectRegiao.style.border = "2px solid var(--amarelo)"
//         spanRegiao.style.display  = "block"
//     } else{
//         selectRegiao.style.border = "none"
//         spanRegiao.style.display  = "none"
//     }
// })


// Função para validar a descrição
function descricaoValidate(){
    const campoTxtArea = document.getElementById('ipt-descricao')
    const spanMin      = document.querySelector('#min-carac')
    
    // Caso tenha menos de 20 caracteres
    if (campoTxtArea.value.length < 20){
        campoTxtArea.style.border = "2px solid var(--amarelo)"
        spanMin.style.display = "block"
    } else{
        campoTxtArea.style.border = ""
        spanMin.style.display = "none"
    }
}

// Mostrar prévia da foto
const inputImg  = document.getElementById('file');
const previaImg = document.getElementById('imagemPreview');
// Deixa vázia a caixa da imagem, sem aparecer o ícone de quando uma imagem não é selecionada
const srcAntigo = previaImg.src 

inputImg.addEventListener('change', function () {
    const file = inputImg.files[0]; // Obtém o arquivo selecionado

    if (file) {
        const reader = new FileReader();

        // Define uma função para ser executada quando o arquivo for lido
        reader.onload = function (e) {
            previaImg.src = e.target.result; // Define o src da tag <img> com o conteúdo da imagem
        };

        // Lê o arquivo como uma URL de dados
        reader.readAsDataURL(file);
    } else {
        // Define a imagem de espaço reservado vazia se nenhum arquivo for selecionado
        previaImg.src = srcAntigo
    }
});




// MÁSCARA

// Máscara pro contato
function mascaraTelefone(event) {
    const telefoneInput = event.target;
    let valorTelefone = telefoneInput.value.replace(/\D/g, '');

    if (valorTelefone.length > 11) {
        valorTelefone = valorTelefone.slice(0, 11); // Limita a 11 caracteres
    }

    if (valorTelefone.length <= 10) {
        // Números residenciais:  (XX) XXXX-XXXX
        telefoneInput.value = valorTelefone.replace(/(\d{2})(\d{0,4})(\d{0,4})/, '($1) $2-$3');
    } else {
        // Números pessoais: (XX) XXXXX-XXXX
        telefoneInput.value = valorTelefone.replace(/(\d{2})(\d{0,5})(\d{0,4})/, '($1) $2-$3');
    }
}
const inputTelefone = document.getElementById('telefone');
// Adicione um ouvinte de evento 'input' para chamar a função
inputTelefone.addEventListener('input', mascaraTelefone);


// Máscara pro CNPJ
// Estrutura:  XX. XXX. XXX/XXXX-XX
const input = document.getElementById('cnpj')
input.addEventListener('keypress', () => {
    let inputLength = input.value.length

    // IF para add o ponto
    if (inputLength === 2 || inputLength === 6){
        input.value += '.'
    }
    // IF para add a barra
    if (inputLength === 10){
        input.value += '/'
    }
    // IF para add o traço
    if (inputLength === 15){
        input.value += '-'
    }
})