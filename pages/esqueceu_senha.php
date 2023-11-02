<?php
require_once "../lib/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    $consulta = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();
    
    if ($resultado->num_rows > 0) {
        $linkRedefinicao = "nova_senha.php?email=" . urlencode($email);
        $assunto = "Redefinição de Senha";
        $mensagem = "Clique no seguinte link para redefinir sua senha: $linkRedefinicao";
        mail($email, $assunto, $mensagem);

        header("Location: email-sucesso.html");
        exit;
    } else {
        header("Location: email-erro.html");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Esqueceu a senha | Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/esqueceu-a-senha.css">
</head>
<body>
    <header id="cabecalho" class="flex-container">
        <h1>ESQUECEU A SENHA</h1>
    </header>
    <main class="flex-container">
        <form id="formulario" class="flex-container" method="post">
            <div id="container" class="flex-container">
                <label id="caixa" for="email" class="flex-container">
                    <img src="../icons/icone-email.svg" alt="Ícone de email" id="icone-email">
                    <p>Email:</p>
                </label>
            </div>
            <div id="caixa-input">
                <input type="email" name="email" id="email" placeholder="Digite seu email..." required>
            </div>
            <span class="texto-erro"><?php echo $mensagemErro; ?></span>
            <button type="submit" id="botao-enviar">ENVIAR</button>
        </form>
    </main>
</body>
</html>