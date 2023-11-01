<?php
# projeto usando .env também seria uma boa
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviar_email($destinario, $assunto, $mensagem_html) {
    require_once '../vendor/autoload.php';

    $email = new PHPMailer(true);
    $email->isSMTP();
    $email->SMTPDebug = 2;
    $email->Host = 'smtp.office365.com';
    $email->Port = 587;
    $email->SMTPAuth = true;
    $email->Username = 'doape.testes@outlook.com';
    $email->Password = 'Pixo_2023@';

    $email->SMTPSecure = 'tls';
    $email->isHTML(true);
    $email->CharSet = "UTF-8";

    $email->setFrom("cristianocris12@hotmail.com", "Doa PE");
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