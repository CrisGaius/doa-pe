<?php
// Incluindo o conexao.php para ter acesso ao banco
require_once("../lib/conexao.php");
require_once("../lib/funcoes_uteis.php");

// Verifica se o array $_POST contém algum dado, evitando o padrão do php de criar um array $_POST por padrão
if (count($_POST) > 0) {
    $erro = false;
    if (strlen($_POST['nome']) >= 5 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['telefone']) && strlen($_POST['senha']) >= 6 && strlen($_POST['senha']) <= 20) {

        $telefone = preg_replace("/[^0-9]/", "", $_POST['telefone']);

        if (strlen($telefone) < 10 || strlen($telefone) > 11) {
            $erro = true;
        }

        if (!$erro) {
            $email = trim($_POST['email']);

            $sql_code_select_email = "SELECT email FROM usuarios WHERE email = :email";

            $sql_query_select_email = $pdo->prepare($sql_code_select_email);
            $sql_query_select_email->bindValue(":email", $email, PDO::PARAM_STR);
            $sql_query_select_email->execute();

            if ($sql_query_select_email->rowCount() === 0) {
                $sql_code_select_telefone = "SELECT telefone FROM usuarios WHERE telefone = :telefone";

                $sql_query_select_telefone = $pdo->prepare($sql_code_select_telefone);
                $sql_query_select_telefone->bindValue(":telefone", $telefone, PDO::PARAM_STR);
                $sql_query_select_telefone->execute();

                if ($sql_query_select_telefone->rowCount() === 0) {
                    $nome = $_POST['nome'];
                    $senha_criptografada = password_hash($_POST['senha'], PASSWORD_DEFAULT);

                    $sql_code_insert_usuario = "INSERT INTO usuarios (id_usuario, nome, email, telefone, senha) VALUES (NULL, :nome, :email, :telefone, :senha)";
                    $sql_code_insert_usuario = $pdo->prepare($sql_code_insert_usuario);

                    $sql_code_insert_usuario->bindValue(":nome", $nome, PDO::PARAM_STR);
                    $sql_code_insert_usuario->bindValue(":email", $email, PDO::PARAM_STR);
                    $sql_code_insert_usuario->bindValue(":telefone", $telefone, PDO::PARAM_STR);
                    $sql_code_insert_usuario->bindValue(":senha", $senha_criptografada, PDO::PARAM_STR);

                    if ($sql_code_insert_usuario->execute()) {
                        $erro = false;
                        unset($_POST);
                        header("Location: analise-dados-usuario.html");
                    } else {
                        $erro = true;
                    }
                } else {
                    $erro = true;
                    $mensagem_erro = "Telefone já cadastrado!";
                    // die("<h1>Telefone já cadastrado no sistema. Tente novamente com outro telefone.</h1>");
                }
            } else {
                $erro = true;
                $mensagem_erro = "Email já cadastrado!";
                // die("<h1>Email já cadastrado no sistema. Tente novamente com outro email.</h1>");
            }
        }
    } else {
        $erro = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Cadastrar Usuário | Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/cadastrar-usuario.css">
    <script src="../scripts/cadastrar-usuario.js" defer></script>
</head>

<body>
    <header>
        <a href="login-usuario.html">
            <button id="btn-voltar">
                <img src="../icons/icone-voltar.svg" alt="Botão Voltar">voltar
            </button>
        </a>
    </header>

    <main>
        <section class="flex-container">
            <h1>Cadastre-se aqui!</h1>

            <form action="" method="post" id="registro-form">
                <div class="inputs">
                    <label for="ipt-nome">Nome</label>
                    <input type="text" name="nome" id="ipt-nome" class="caixa-input" placeholder="Digite o seu nome..." oninput="validateNome()" value="<?php if (isset($_POST['nome'])) echo $_POST['nome'] ?>">
                    <p id="erroNome" class="erro"></p>
                </div>

                <div class="inputs">
                    <label for="ipt-email">E-mail</label>
                    <input type="text" name="email" id="ipt-email" class="caixa-input" placeholder="Digite seu e-mail..." oninput="validateEmail()" value="<?php if (isset($_POST['email'])) echo $_POST['email'] ?>">
                    <p id="erroEmail" class="erro"></p>
                </div>

                <div class="inputs">
                    <label for="ipt-tel">Telefone</label>
                    <input type="text" name="telefone" id="ipt-tel" class="caixa-input" placeholder="Digite seu número de telefone..." oninput="validateTelefone()" value="<?php if (isset($_POST['telefone'])) echo formatar_numero($_POST['telefone']) ?>">
                    <p id="erroTelefone" class="erro"></p>
                </div>

                <div class="inputs">
                    <label for="ipt-senha">Senha</label>
                    <input type="password" name="senha" id="ipt-senha" class="caixa-input" placeholder="Crie uma senha..." oninput="validateSenha()" value="<?php if (isset($_POST['senha'])) echo $_POST['senha'] ?>">
                    <p id="erroSenha" class="erro"></p>
                </div>

                <div class="btn">
                    <button id="btn-enviar" type="submit">CADASTRAR</button>
                </div>

            </form>
            <?php if (isset($erro) && $erro) { ?>
                <section id="acerto">
                    <div id="conteudo-acerto" style="background-color: var(--vermelho);">
                        <?php if (isset($mensagem_erro)) { ?>
                            <p><strong><?php echo $mensagem_erro ?></strong></p>
                        <?php } else { ?>
                            <p><strong>Erro ao enviar dados!</strong></p>
                        <?php } ?>
                        <img id="botao-fechar" src="../icons/icone-fechar.svg" alt="ícone de fechar">
                    </div>
                </section>
            <?php } ?>

    </main>
    <script>
        const botaoFecharCaixaAcerto = document.querySelector("img#botao-fechar")

        botaoFecharCaixaAcerto.addEventListener('click', () => {
            const sectionAcerto = document.querySelector('section#acerto')

            sectionAcerto.style.display = 'none'
        })
    </script>
</body>

</html>