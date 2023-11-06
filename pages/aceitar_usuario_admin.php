<?php 
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['funcao']) || !$_SESSION['funcao']) {
    header("Location: ../index.php");
    die();
}

require_once("../lib/conexao.php");
require_once("../lib/funcoes_uteis.php");

if(isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']);
    
    $sql_code_select_email_usuario = "SELECT email FROM usuarios WHERE id_usuario = $id_usuario AND status = 'analise' LIMIT 1";

    $sql_query_select_email_usuario = $pdo->prepare($sql_code_select_email_usuario);
    $sql_query_select_email_usuario->execute();

    if($sql_query_select_email_usuario->rowCount() > 0) {
        $email_usuario = $sql_query_select_email_usuario->fetchColumn();

        if($email_usuario) {
            $sql_code_update_status_usuario = "UPDATE usuarios SET status = 'aprovado' WHERE id_usuario = $id_usuario AND status = 'analise' LIMIT 1";

            $sql_query_update_status_usuario = $pdo->prepare($sql_code_update_status_usuario);
            
            if($sql_query_update_status_usuario->execute()) {
                $envio_email = enviar_email("../vendor/autoload.php", $email_usuario, "Aprovado no web app da Doa PE Ticket (" . uniqid() . ")", "<p>Felizmente, você foi <strong>aprovado</strong> no web app da Doa PE! <strong>Boas-vindas!</strong></p>
                <p>Efetue seu login clicando <a href='http://localhost/doa-pe/pages/login-usuario.php'>AQUI</a>.</p>");

                if($envio_email) {
                    header("Location: validar-usuario-ong.php");
                    die();
                } else {
                    die("Erro ao enviar o email para o usuário");
                }

            } else {
                die("Erro ao atualizar o status do usuário.");
            }
        } else {
            die("Erro ao guardar o email do usuário.");
        }
    } else {
        die("Erro ao selecionar o email do usuário.");
    }
} else {
    die("Erro ao receber o id do usuário.");
}
