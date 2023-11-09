<?php 
function enviar_imagens($caminho_mover_imagem, $error, $size, $name, $tmp_name) {
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
    
    $voltar_uma_pasta = "";
    
    if($caminho_mover_imagem) {
        $voltar_uma_pasta = "../";
    }

    $caminho = $voltar_uma_pasta . $pasta . $novo_nome_do_arquivo . "." . $extensao;

    $deu_certo = move_uploaded_file($tmp_name, $caminho);

    if (isset($deu_certo) && $deu_certo) {
        if(strpos($caminho, "../") !== false) {
            $caminho = str_replace("../", "", $caminho);
        }

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
    return preg_replace('/^(\d{2})(\d{4,5})(\d{4})$/', '($1) $2-$3', $contato);
}

# projeto usando .env também seria uma boa
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviar_email($caminho_vendor, $destinario, $assunto, $mensagem_html) {
    require_once("$caminho_vendor");

    $email = new PHPMailer(true);
    $email->isSMTP();
    $email->SMTPDebug = 0;
    $email->Host = 'smtp.office365.com';
    $email->Port = 587;
    $email->SMTPAuth = true;
    $email->Username = 'email';
    $email->Password = 'password';

    $email->SMTPSecure = 'tls';
    $email->isHTML(true);
    $email->CharSet = "UTF-8";

    $email->setFrom("email", "Doa PE");
    $email->addAddress($destinario);
    $email->Subject = $assunto;

    $email->Body = $mensagem_html;

    if($email->send()) {
        return true;
    } else {
        return false;
    }

    $email->smtpClose();
}