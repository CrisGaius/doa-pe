<?php
require_once("lib/conexao.php");
require_once("lib/funcoes_uteis.php");

if (isset($_GET['id'])) {
    $id_ong = intval($_GET['id']);
    $id_usuario = intval(1); // substituir por $_SESSION['id_usuario']

    $sql_code_select_usuario = "SELECT nome, telefone, email FROM usuarios WHERE id_usuario = $id_usuario LIMIT 1";
    // WHERE $id_usuario = $_SESSION['id_usuario']

    $sql_query_select_usuario = $pdo->prepare($sql_code_select_usuario);
    $sql_query_select_usuario->execute() or die("Erro ao selecionar as informações do usuário.");

    $sql_code_select_nome_ong = "SELECT nome FROM ongs WHERE id_ong = :id_ong LIMIT 1";
    $sql_query_select_nome_ong = $pdo->prepare($sql_code_select_nome_ong);
    $sql_query_select_nome_ong->bindValue("id_ong", $id_ong, PDO::PARAM_INT);

    $sql_query_select_nome_ong->execute() or die("Erro ao buscar pelo nome da ong.");

    if ($sql_query_select_usuario->rowCount() > 0 && $sql_query_select_nome_ong->rowCount() > 0) {
        $dados_usuario = $sql_query_select_usuario->fetch(PDO::FETCH_ASSOC);
        $nome_ong = $sql_query_select_nome_ong->fetchColumn();

        if (isset($_POST['botao-enviar']) && isset($dados_usuario) && isset($nome_ong)) {
            $erro = false;
            if (strlen($_POST['nome']) >= 3 && !empty($_POST['telefone']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                $telefone = preg_replace("/[^0-9]/", "", $_POST['telefone']);
                if (strlen($telefone) < 10 || strlen($telefone) > 11) {
                    $erro = true;
                }

                if(!$erro) {
                    $sql_code_select_ong = "SELECT email FROM ongs WHERE id_ong = :id_ong LIMIT 1";
                    $sql_query_select_ong = $pdo->prepare($sql_code_select_ong);

                    $sql_query_select_ong->bindValue("id_ong", $id_ong, PDO::PARAM_INT);

                    $sql_query_select_ong->execute() or die("Erro ao selecionar o email da ong.");

                    if ($sql_query_select_ong->rowCount() > 0) {
                        $email_ong = $sql_query_select_ong->fetchColumn();
                    } else {
                        $erro = true;
                    }
                }

                if (!$erro && isset($email_ong)) {
                    $nome = $_POST['nome'];
                    $telefone = $_POST['telefone'];
                    $email = $_POST['email'];

                    $envio_email = enviar_email( "vendor/autoload.php",
                        $email_ong,
                        "Novo voluntário para a sua ong! Ticket (" . uniqid() . ")",
                        "<h1>Informações submetidas pelo usuário:</h1>
                <p><strong>Nome: </strong>$nome</p>
                <p><strong>Telefone: </strong>$telefone</p>
                <p><strong>Email: </strong>$email</p>"
                    );

                    if (!$envio_email) {
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
    <title>Formulário de voluntariado - Temporário</title>
</head>

<body>
    <?php if (isset($_GET['id']) && isset($sql_query_select_usuario) && $sql_query_select_usuario->rowCount() > 0 && $sql_query_select_nome_ong->rowCount() > 0) { ?>
        <h1>Formulário de voluntariado (<?php echo $nome_ong?>)</h1>
        <form action="" method="post">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo $dados_usuario['nome'] ?>">

                <label for="telefone">Telefone</label>
                <input type="tel" name="telefone" id="telefone" value="<?php echo formatar_numero($dados_usuario['telefone']) ?>">

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $dados_usuario['email'] ?>">

            <button id="botao-enviar" name="botao-enviar">ENVIAR</button>
        </form>
    <?php } else { ?>
        <h1>Algo deu errado.</h1>
    <?php } ?>
</body>

</html>