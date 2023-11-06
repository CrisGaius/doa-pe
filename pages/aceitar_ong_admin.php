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

if (isset($_GET['id'])) {
    $id_ong = intval($_GET['id']);

    $sql_code_select_info_ong = "SELECT nome, email FROM ongs WHERE id_ong = $id_ong AND status = 'analise' LIMIT 1";

    $sql_query_select_info_ong = $pdo->prepare($sql_code_select_info_ong);
    $sql_query_select_info_ong->execute();

    if($sql_query_select_info_ong->rowCount() > 0) {
        $dados = $sql_query_select_info_ong->fetch(PDO::FETCH_ASSOC);

        if($dados) {
            $sql_code_update_status_ong = "UPDATE ongs SET status = 'aprovado' WHERE id_ong = $id_ong AND status = 'analise' LIMIT 1";

            $sql_query_update_status_ong = $pdo->prepare($sql_code_update_status_ong);
            
            if($sql_query_update_status_ong->execute()) {
                $envio_email = enviar_email("../vendor/autoload.php", $dados['email'], "ONG aprovada no web app da Doa PE Ticket (" . uniqid() . ")", "<p>Felizmente, sua ong <strong>" . $dados['nome'] . "</strong> foi aprovada no web app da Doa PE! <strong>Boas-vindas!</strong></p>");

                if($envio_email) {
                    header("Location: validar-usuario-ong.php");
                    die();
                } else {
                    die("Erro ao enviar o email para a ong.");
                }

            } else {
                die("Erro ao atualizar o status da ong.");
            }
        } else {
            die("Erro ao guardar os dados da ong.");
        }
    } else {
        die("Erro ao selecionar os dados da ong.");
    }
} else {
    die("ID da ong não informado.");
}
