<?php
require_once "../lib/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoEmail = $_POST["email"];
    $novaSenha = $_POST["senha"];

    $consultaUsuario = $conexao->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $consultaUsuario->bind_param("s", $novoEmail);
    $consultaUsuario->execute();
    $consultaUsuario->store_result();

    if ($consultaUsuario->num_rows > 0) {
        $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);

        $atualizarSenha = $conexao->prepare("UPDATE id_usuario SET senha = ? WHERE email = ?");
        $atualizarSenha->bind_param("ss", $senhaHash, $novoEmail);

        if ($atualizarSenha->execute()) {
            header("Location: senha-sucesso.html");
            exit;
        } else {
            header("Location: senha-erro.html");
            exit;
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Nova Senha | Doa PE</title>
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
        <form id="formulario" class="flex-container" method="POST">
            <div id="container" class="flex-container">
                <label id="caixa" for="senha" class="flex-container">
                    <img src="../icons/icone-senha.svg" alt="Ícone de senha" id="icone-senha">
                    <p>Senha:</p>
                </label>
            </div>
            <div id="caixa-input" class="flex-container">
                <input type="password" name="senha" id="senha" placeholder="Digite sua senha..." maxlength="20">
                <img src="../icons/icone-olho.svg" alt="Ícone de olho" id="icone-olho">
            </div>
            <button id="botao-enviar">ENVIAR</button>
        </form>
    </main>
</body>

</html>