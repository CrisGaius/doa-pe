<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    die();
} else {
    $id_usuario = intval($_SESSION['id_usuario']);
}

require_once("../lib/conexao.php");
require_once("../lib/funcoes_uteis.php");

if (isset($_GET['id'])) {
    $id_ong = intval($_GET['id']);

    $sql_code_select_usuario = "SELECT nome, telefone, email FROM usuarios WHERE id_usuario = $id_usuario LIMIT 1";

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
            if (strlen($_POST['input-name']) >= 3 && !empty($_POST['input-tel']) && filter_var($_POST['input-email'], FILTER_VALIDATE_EMAIL)) {

                $telefone = preg_replace("/[^0-9]/", "", $_POST['input-tel']);
                if (strlen($telefone) < 10 || strlen($telefone) > 11) {
                    $erro = true;
                }

                if (!$erro) {
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
                    $nome = $_POST['input-name'];
                    $telefone = $_POST['input-tel'];
                    $email = $_POST['input-email'];

                    $envio_email = enviar_email(
                        "../vendor/autoload.php",
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
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/formulario-voluntario.css">
    <link rel="stylesheet" href="../styles/config.css">
    <script src="../scripts/formulario-voluntario.js"></script>
    <title>Formulário de Voluntário</title>
</head>

<body>
    <main>
        <div class="sla">
            <a href="../index.php">
                <button id="btn-voltar">
                    <img src="../images/seta-voltar.png" alt="Botão Voltar">Voltar
                </button>
            </a>
        </div>
        <?php if (isset($_GET['id']) && isset($sql_query_select_usuario) && $sql_query_select_usuario->rowCount() > 0 && $sql_query_select_nome_ong->rowCount() > 0) { ?>
            <section class="flex-container">
                <div class="div-itens">
                    <h1>Formulário de Voluntário</h1>
                    <h2 id="nome-ong">(<?php echo $nome_ong ?>)</h2>

                    <form action="" id="registro-form" method="post">
                        <div class="inputs">
                            <label for="ipt-name">Nome</label>
                            <input type="text" placeholder="Seu nome..." name="input-name" id="ipt-name" oninput="validateField('ipt-name', 'nome-error')" value="<?php echo $dados_usuario['nome'] ?>">
                            <p id="nome-error" class="error"></p>
                        </div>
                        <div class="inputs">
                            <label for="ipt-email">Email</label>
                            <input type="email" placeholder="Seu email..." name="input-email" id="ipt-email" oninput="validateField('ipt-email', 'email-error')" value="<?php echo $dados_usuario['email'] ?>">
                            <p id="email-error" class="error"></p>
                        </div>
                        <div class="inputs">
                            <label for="ipt-tel">Telefone</label>
                            <input type="text" placeholder="(xx) xxxxx-xxxx" name="input-tel" id="ipt-tel" oninput="mascaraTelefone(); validarTelefone()" value="<?php echo formatar_numero($dados_usuario['telefone']) ?>">
                            <p id="tel-error" class="error"></p>
                        </div>
                        <div>
                            <button id="btn-enviar" name="botao-enviar" type="submit">ENVIAR</button>
                        </div>
                    </form>

                    <?php if(isset($envio_email) && $envio_email) { ?>
                        <section id="acerto" class="flex-container">
                            <div id="conteudo-acerto">
                                <p><strong>Dados enviados com sucesso!</strong></p>
                                <img id="botao-fechar" src="../icons/icone-fechar.svg" alt="ícone de fechar">
                            </div>
                        </section>
                    <?php } else if (isset($envio_email) && !$envio_email){ ?>
                        <section id="acerto" class="flex-container">
                            <div id="conteudo-acerto" style="background-color: var(--vermelho);">
                                <p ><strong>Erro ao enviar dados!</strong></p>
                                <img id="botao-fechar" src="../icons/icone-fechar.svg" alt="ícone de fechar">
                            </div>
                        </section>
                    <?php }?>

                </div>
            </section>
        <?php } else { ?>
            <h1 class="titulo">Algo deu errado.</h1>
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