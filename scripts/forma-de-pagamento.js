var btnCopiar = document.getElementById('copiar');

btnCopiar.addEventListener("click", copiarChavePix);

function copiarChavePix(){
    var texto = document.getElementById('chave');
    navigator.clipboard.writeText(texto.value).then(() => {
        alert('Chave Pix copiada para área de tranferencia.')
    })
}

function copiarTransferencia() {
    var instituicao = document.getElementById("instituicao").value;
    var agencia = document.getElementById("agencia").value;
    var conta = document.getElementById("conta").value;
    var tipoConta = document.getElementById("tipo-de-conta").value;

    var clipboardText = "Instituição - " + instituicao + "\n" +
        "Agência - " + agencia + "\n" +
        "Conta - " + conta + "\n" +
        "Tipo de Conta - " + tipoConta;


    var textarea = document.createElement("textarea");
    textarea.value = clipboardText;
    document.body.appendChild(textarea);
    textarea.select();

    try {
        document.execCommand("copy");
        alert("Informações de Transferência Bancária copiadas para a área de transferência.");
    } catch (err) {
        console.error("Erro ao copiar as informações para a área de transferência:", err);
    } finally {
        document.body.removeChild(textarea);
    }
}

document.getElementById("transf-bank").addEventListener("click", copiarTransferencia);
