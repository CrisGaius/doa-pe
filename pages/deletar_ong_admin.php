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

    $sql_code_select_dados_ong = "SELECT foto, nome, email FROM ongs WHERE id_ong = $id_ong AND status = 'analise' LIMIT 1";

    $sql_query_select_dados_ong = $pdo->prepare($sql_code_select_dados_ong);
    $sql_query_select_dados_ong->execute();

    if ($sql_query_select_dados_ong->rowCount() > 0) {
        $dados = $sql_query_select_dados_ong->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            if (unlink("../" . $dados['foto'])) {
                $sql_code_deletar_informacoes_bancarias = "DELETE FROM informacoes_bancarias WHERE id_ong = :id_ong LIMIT 1";

                $sql_query_deletar_informacoes_bancarias = $pdo->prepare($sql_code_deletar_informacoes_bancarias);
                $sql_query_deletar_informacoes_bancarias->bindValue(":id_ong", $id_ong, PDO::PARAM_INT);

                if ($sql_query_deletar_informacoes_bancarias->execute()) {
                    $sql_code_deletar_ong = "DELETE FROM ongs WHERE id_ong = :id_ong AND status = 'analise' LIMIT 1"; //colocar a validação com o $_SESSION['id'] (AND id_usuario = $_SESSION['id_usuario'])

                    $sql_query_deletar_ong = $pdo->prepare($sql_code_deletar_ong);
                    $sql_query_deletar_ong->bindValue(":id_ong", $id_ong, PDO::PARAM_STR);

                    if ($sql_query_deletar_ong->execute()) {
                        $envio_email = enviar_email("../vendor/autoload.php", $dados['email'], "ONG reprovada no web app da Doa PE Ticket (" . uniqid() . ")", "<p>Infelizmente, sua ong <strong>" . $dados['nome'] . "</strong> foi reprovada no web app da Doa PE devido à alguma descrepância encontrada nas informações inseridas. <strong>Se ainda tiver interesse, tente novamente e você será analisado.</strong></p>");

                        if ($envio_email) {
                            header("Location: validar-usuario-ong.php");
                            die();
                        } else {
                            die("Erro ao enviar o email para a ong.");
                        }

                    } else {
                        die("Erro ao excluir a ONG no banco de dados. Entrentanto, a imagem foi excluída. Caminho: " . $dados['foto']);
                    }
                } else {
                    die("Erro ao excluir as info. bancárias no banco de dados. Entrentanto, a imagem foi excluída. Caminho: " . $dados['foto']);
                }
            } else {
                die("Erro ao fazer o unlink da imagem.");
            }
        } else {
            die("Erro ao guardar os dados da ong.");
        }
    } else {
        die("Erro ao selecionar os dados da ong.");
    }
} else {
    die("Erro ao receber o id da ong.");
}
