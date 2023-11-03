<?php
require_once("../lib/conexao.php");

if (count($_POST) > 0) {
    $erro = false;
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && strlen($_POST['senha']) >= 6) {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);

        $sql_code_select_usuario = "SELECT id_usuario, funcao, senha FROM usuarios WHERE email = :email LIMIT 1";
        $sql_query_select_usuario = $pdo->prepare($sql_code_select_usuario);
        $sql_query_select_usuario->bindValue(":email", $email);

        $sql_query_select_usuario->execute() or die("Erro ao selecionar as informações do usuário");

        if ($sql_query_select_usuario->rowCount() > 0) {
            $dados = $sql_query_select_usuario->fetch(PDO::FETCH_ASSOC);

            if (password_verify($senha, $dados['senha'])) {
                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['id_usuario'] = $dados['id_usuario'];
                $_SESSION['funcao'] =  $dados['funcao'];

                header("Location: home-logado.php");
            } else {
                $erro = true;
            }
        } else {
            $erro = true;
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
    <!-- <meta http-equiv="refresh" content="1"> -->
    <link rel="stylesheet" href="../styles/login-usuario.css">
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <script src="../scripts/login-usuario.js" defer></script>
    <script src="https://kit.fontawesome.com/c687b2a461.js" crossorigin="anonymous" defer></script>
    <title>Login | DOA PE</title>
</head>

<body>
    <header id="cabecalho" class="flex-container">
        <section id="caixa-voltar" class="flex-container">
            <img src="../icons/icone-voltar.svg" alt="Ícone de voltar" id="icone-voltar">
            <p>VOLTAR</p>
        </section>
    </header>
    <main>
        <section id="sct-principal" class="flex-container">
            <h1>Login</h1>
            <form action="" method="post">
                <div class="inputs">
                    <label for="email"><img src="../icons/icone-email-login.svg" alt="Icone Email">Email</label>
                    <input type="text" name="email" id="email" placeholder="Digite seu e-mail" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>">
                    <span id="email-invalido">Email inválido.</span>
                </div>
                <div class="inputs">
                    <label for="senha"><img id="cadeado-senha" src="../icons/icone-senha-login.svg" alt="Icone Senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Digite sua senha" value="<?php if(isset($_POST['senha'])) echo $_POST['senha']?>">
                </div>

                <?php if (isset($erro) && $erro) { ?>
                    <div id="caixa-erro" style="display: flex;">
                        <i class="fa-solid fa-xmark"></i>
                        <p>Email ou senha Incorretos!</p>
                        <div class="flex-container" id="caixa-botao-fechar">
                            <i class="fa-solid fa-xmark" id="fechar"></i>
                        </div>
                    </div>
                <?php } ?>
                
                <div id="caixa-erro">
                        <i class="fa-solid fa-xmark"></i>
                        <p>Email ou senha Incorretos!</p>
                        <div class="flex-container" id="caixa-botao-fechar">
                            <i class="fa-solid fa-xmark" id="fechar"></i>
                        </div>
                </div>

                <button id="btn-entrar" name="btn-entrar">Entrar</button>
                <div id="esqueceu-cadastro" class="flex-container">
                    <p id="esqueceu-senha">Esqueceu a senha?</p>
                    <p>Cadastre-se</p>
                </div>
            </form>
        </section>
    </main>
</body>
</html>