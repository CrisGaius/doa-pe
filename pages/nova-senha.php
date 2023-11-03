<?php
require_once "../lib/conexao.php";
require_once "../lib/funcoes_uteis.php";

if(isset($_GET['pass'])) {
    $parametro_senha = $_GET['pass'];
    $sql_code_select_usuario = "SELECT senha FROM usuarios WHERE senha = :parametro_senha LIMIT 1";

    $sql_query_select_usuario = $pdo->prepare($sql_code_select_usuario);
    $sql_query_select_usuario->bindValue(":parametro_senha", $parametro_senha, PDO::PARAM_STR); 
    $sql_query_select_usuario->execute() or die ("Erro ao selecionar usuário.");

    if($sql_query_select_usuario->rowCount() > 0) {
        $senha_banco = $sql_query_select_usuario->fetchColumn();

        if(isset($senha_banco)) {
            if(count($_POST) > 0) {
                if(strlen($_POST['senha']) >= 6 && strlen($_POST['senha']) <= 20) {
                    $senha_criptografada = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
                    $sql_code_update_senha = "UPDATE usuarios SET senha = :senha_criptografada WHERE senha = :senha_banco LIMIT 1";
                    $sql_query_update_senha = $pdo->prepare($sql_code_update_senha);

                    $sql_query_update_senha->bindValue(":senha_criptografada", $senha_criptografada, PDO::PARAM_STR);
                    $sql_query_update_senha->bindValue(":senha_banco", $senha_banco, PDO::PARAM_STR);

                    if($sql_query_update_senha->execute()) {
                        header("Location: senha-sucesso.html");
                    } else {
                        header("Location: senha.erro-html");
                    }
    
                } else {
                    header("Location: senha.erro-html");
                }
            }
        } else {
            header("Location: link-invalido.html");
        }
    } else {
        header("Location: link-invalido.html");
    }
} else {
    header("Location: link-invalido.html");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Nova Senha - Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/esqueceu-a-senha.css">
    <script src="../scripts/nova-senha.js" defer></script>
    <script src="../scripts/botao-voltar-home.js" defer></script>
</head>

<body>
    <header id="cabecalho" class="flex-container">
        <section id="caixa-voltar" class="flex-container">
            <img src="../icons/icone-voltar.svg" alt="Ícone de voltar" id="icone-voltar">
            <p>VOLTAR</p>
        </section>
        <h1>ESQUECEU A SENHA</h1>
    </header>
    <main class="flex-container">
        <form id="formulario" class="flex-container" method="post">
            <div id="container" class="flex-container">
                <label id="caixa" for="senha" class="flex-container">
                    <img src="../icons/icone-senha.svg" alt="Ícone de senha" id="icone-senha">
                    <p>Senha:</p>
                </label>
            </div>
            <div id="caixa-input" class="flex-container">
                <input type="password" name="senha" id="senha" placeholder="Digite sua senha..." maxlength="21">
                <img src="../icons/icone-olho.svg" alt="Ícone de olho" id="icone-olho">
            </div>
            <span class="texto-erro">Senha inválida.</span>
            <button id="botao-enviar">ENVIAR</button>
        </form>
    </main>
</body>

</html>