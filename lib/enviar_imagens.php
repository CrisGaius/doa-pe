<?php 
function enviar_imagens($error, $size, $name, $tmp_name) {
    if ($error) {
        die('Falha ao enviar arquivo.');
    }

    if ($size > 1e+7) {
        die('Arquivo muito grande! Máx: 10MB');
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
