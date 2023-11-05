<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) && isset($_SESSION['funcao'])) {
    header("Location: home-logado.php");
    die();
} else {
    $id_usuario = intval($_SESSION['id_usuario']);
    $funcao = intval($_SESSION['funcao']);
}

require_once("../lib/conexao.php");

if (isset($_GET['id'])) {
    $erro = false;
    $id_ong = intval($_GET['id']);

    $sql_code_select_ong = "SELECT id_usuario FROM ongs WHERE id_ong = $id_ong";

    if (!$funcao) {
        $sql_code_select_ong .= " AND id_usuario = $id_usuario";
    }

    $sql_code_select_ong .= " LIMIT 1";

    $sql_query_select_ong = $pdo->prepare($sql_code_select_ong);
    $sql_query_select_ong->execute() or die("Erro ao selecionar id_usuario");

    if ($sql_query_select_ong->rowCount() !== 0) {

        $sql_code_select_foto = "SELECT foto FROM ongs WHERE id_ong = :id_ong "; //colocar a validação com o $_SESSION['id'] (AND id_usuario = $_SESSION['id_usuario'])

        $sql_query_select_foto = $pdo->prepare($sql_code_select_foto);
        $sql_query_select_foto->bindValue(":id_ong", $id_ong, PDO::PARAM_STR);
        $sql_query_select_foto->execute();

        if ($sql_query_select_foto->rowCount() > 0) {
            $foto = $sql_query_select_foto->fetchColumn();

            if (isset($foto)) {
                if (unlink("../" . $foto)) {
                    $sql_code_deletar_informacoes_bancarias = "DELETE FROM informacoes_bancarias WHERE id_ong = :id_ong LIMIT 1"; //colocar a validação com o $_SESSION['id'] (AND id_usuario = $_SESSION['id_usuario'])

                    $sql_query_deletar_informacoes_bancarias = $pdo->prepare($sql_code_deletar_informacoes_bancarias);
                    $sql_query_deletar_informacoes_bancarias->bindValue(":id_ong", $id_ong, PDO::PARAM_INT);

                    if ($sql_query_deletar_informacoes_bancarias->execute()) {
                        $sql_code_deletar_ong = "DELETE FROM ongs WHERE id_ong = :id_ong LIMIT 1"; //colocar a validação com o $_SESSION['id'] (AND id_usuario = $_SESSION['id_usuario'])

                        $sql_query_deletar_ong = $pdo->prepare($sql_code_deletar_ong);
                        $sql_query_deletar_ong->bindValue(":id_ong", $id_ong, PDO::PARAM_STR);

                        if ($sql_query_deletar_ong->execute()) {
                            $erro = false;
                        } else {
                            $erro = true;
                            // die("Erro ao excluir a ONG no banco de dados. Entrentanto, a imagem foi excluída. Caminho: $foto");
                        }
                    } else {
                        $erro = true;
                        // die("Erro ao excluir as informações bancárias da ONG. Entretanto, a imagem foi excluída. Caminho: $foto");
                    }
                } else {
                    $erro = true;
                    // die("Erro ao excluir a imagem com caminho ../$foto. Informe ao suporte!");
                }
            } else {
                $erro = true;
            }
        } else {
            $erro = true;
        }
    } else {
        $erro = true;
    }
} else {
    $erro = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Deletar ONG - Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/mensagem-esqueceu-a-senha-erro.css">
    <script src="../scripts/botao-voltar-home.js" defer></script>
</head>

<body>
    <header id="cabecalho" class="flex-container">
        <section id="caixa-voltar" class="flex-container">
            <img src="../icons/icone-voltar.svg" alt="Ícone de voltar" id="icone-voltar">
            <p>VOLTAR</p>
        </section>
        <h1>DELETAR ONG</h1>
    </header>
    <main class="flex-container">
        <?php if (isset($erro) && !$erro) { ?>
            <section id="mensagem" class="flex-container">
                <div id="caixa-texto-principal" class="flex-container">
                    <img src="../icons/icone-check.svg" alt="Ícone de ong deletada com sucesso">
                    <p id="texto-principal" style="color: var(--verde);">ONG DELETADA COM SUCESSO!</p>
                </div>
            </section>

        <?php } else if (isset($erro) && $erro) { ?>
            <section id="mensagem" class="flex-container">
                <div id="caixa-texto-principal" class="flex-container">
                    <img src="../icons/icone-xmark.svg" alt="Ícone de email enviado">
                    <p id="texto-principal">ERRO AO DELETAR ONG!</p>
                </div>
            </section>

        <?php } ?>
    </main>
</body>

</html>