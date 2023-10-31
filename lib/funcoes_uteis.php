<?php 
function enviar_imagens($error, $size, $name, $tmp_name) {
    if ($error) {
        die('Falha ao enviar arquivo.');
    }

    if ($size > 2e+6) {
        die('Arquivo muito grande! Máx: 2MB');
    }

    $pasta = "fotos-ongs/";
    $novo_nome_do_arquivo = uniqid();
    $extensao = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if ($extensao !== "jpeg" && $extensao !== "jpg" && $extensao !== "png") {
        die("Tipo de arquivo inválido!");
    }

    $caminho = $pasta . $novo_nome_do_arquivo . "." . $extensao;
    $deu_certo = move_uploaded_file($tmp_name, $caminho);

    if (isset($deu_certo)) {
        return $caminho;
    } else {
        return false;
    }
}

function formatar_cnpj($cnpj) {
    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
}

function formatar_conta($conta) {
    $primeira_parte = substr($conta, 0, 7);
    $ultimo_digito = substr($conta, -1);
    
    return "$primeira_parte-$ultimo_digito";
}

function formatar_numero($contato) {
    return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '$1 $2-$3', $contato);
}