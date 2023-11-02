<?php

// Incluindo o conexao.php para ter acesso ao banco
require_once("lib/conexao.php");


// Verifica se o array $_POST contém algum dado, evitando o padrão do php de criar um array $_POST por padrão
if(count($_POST) > 0 ){
    $erro = false;
    // Criando variáveis e atribuindo o valor dos campos dos inputs
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    
    // Verificando se as variáveis estão vazias 
    if(empty($nome) || empty($email) || empty($telefone) || empty($senha)){
            $erro = true;
    }
    
    // Verifica se o dado dentro da variável $nome tem menos que 5 caracteres
    if(strlen($nome) < 5 ){
            $erro = true;
    }

    // Verifica se o dado dentro da variável $telefone contém menos de 9 dígitos
    if(strlen($telefone) < 9){
            $erro = true;
    }
    
    // Verifica o email negando o filter_var 
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro = true;
    }

    // Criptografa a senha usando a função password_hash
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Verificando se o email e telefone já existem no banco de dados
    $stmtEmail = $pdo->prepare("SELECT email FROM usuarios WHERE email = :email");
    $stmtEmail->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtEmail->execute();
    $rowCountEmail = $stmtEmail->rowCount();

    $stmtTelefone = $pdo->prepare("SELECT telefone FROM usuarios WHERE telefone = :telefone");
    $stmtTelefone->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $stmtTelefone->execute();
    $rowCountTelefone = $stmtTelefone->rowCount();

    if ($rowCountEmail > 0 || $rowCountTelefone > 0) {
    die("Email ou telefone já cadastrados no sistema.");
}

    // Verifica se a variável $erros está vazia, caso esteja começa a inserção no banco de dados
    if(!$erro || !$rowCountEmail > 0 || !$rowCountTelefone > 0){
            $stmt = $pdo->prepare("INSERT INTO usuarios(nome, email, telefone, senha) VALUES (:nome, :email, :telefone, :senha)");
            
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senha_criptografada, PDO::PARAM_STR);
            
            if($stmt->execute()){
                header("Location: formulario_voluntario.php");
                echo "Usuário cadastrado.";
                exit;
            } else{
                echo "Erro ao cadastrar o usuário no banco de dados.";
            }
    
        }
            // Caso a variável não esteja vazia, caso não esteja ele vai mostrar todos os erros que estiver armazenado
            if($erro){
                echo "<p>Preencha corretamente os campos corretamente.<p>";
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

            <form action="" method = "post" id="registro-form" autocomplete="off">
                    <div class="inputs">
                        <label for="ipt-nome">Nome</label>
                        <input type="text" name="nome" id="ipt-nome" class="caixa-input" placeholder="Digite o seu nome..." oninput="validateNome()">
                        <p id="erroNome" class="erro"></p>
                    </div>
                
                    <div class="inputs">
                        <label for="ipt-email">E-mail</label>
                        <input type="text" name="email" id="ipt-email" class="caixa-input" placeholder="Digite seu e-mail..." oninput="validateEmail()">
                        <p id="erroEmail" class="erro"></p>
                    </div>
                
                    <div class="inputs">
                        <label for="ipt-tel">Telefone</label>
                        <input type="text" name="telefone" id="ipt-tel" class="caixa-input" placeholder="Digite seu número de telefone..." oninput="validateTelefone()">
                        <p id="erroTelefone" class="erro"></p>
                    </div>

                    <div class="inputs">
                        <label for="ipt-senha">Senha</label>
                        <input type="password"  name="senha" id="ipt-senha" class="caixa-input" placeholder="Crie uma senha..." oninput="validateSenha()">
                        <p id="erroSenha" class="erro"></p>
                    </div>

                    <div class="btn">
                        <button id="btn-enviar" type="submit">CADASTRAR</button>
                    </div>
            </form>
    </main>
</body>
</html>