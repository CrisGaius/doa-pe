<?php 
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['funcao']) || !$_SESSION['funcao']) {
    header("Location: home-logado.php");
    die();
}

require_once("../lib/conexao.php");
require_once("../lib/funcoes_uteis.php");

if(isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']);

    $sql_code_select_info_usuario = "SELECT nome, email, telefone FROM usuarios WHERE id_usuario = $id_usuario AND status = 'analise' LIMIT 1";
    
    $sql_query_select_info_usuario = $pdo->prepare($sql_code_select_info_usuario);
    $sql_query_select_info_usuario->execute() or die("Erro ao consultar as informações do usuário no banco de dados.");

    if($sql_query_select_info_usuario->rowCount() > 0) {
        $info_usuario = $sql_query_select_info_usuario->fetch(PDO::FETCH_ASSOC);

        if(!$info_usuario) {
            die("Erro ao guardar as informações do usuário.");
        }
    }
} else {
    die("Id da ONG não foi passado.");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Validar Usuário | Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/validar-usuario.css">
</head>
<body>
    <header>
        <a href="validar-usuario-ong.html">
            <section class="flex-container">
                <img src="../icons/icone-voltar.svg" alt="Botão de Voltar" id="icone-voltar">
                <p>VOLTAR</p>
            </section>
        </a>
        <h1>TELA DE VALIDAÇÃO</h1>
    </header>
    <main>
        <section id="flex-container">

            
            <?php if($sql_query_select_info_usuario->rowCount() > 0 && isset($info_usuario) && $info_usuario) {?>
                <h2>INFORMAÇÕES DO USUÁRIO</h2>
                <form action="" id="form">
                    <p>Nome</p>
                    <label for="inome"><input type="text" id="inome" value="<?php echo $info_usuario['nome'] ?>" disabled></label>
                    <p>E-mail</p>
                    <label for="iemail"><input type="text" id="iemail" value="<?php echo $info_usuario['email'] ?>" disabled></label>
                    <p>Telefone</p>
                    <label for="itel"><input type="text" id="itel" value="<?php echo formatar_numero($info_usuario['telefone']) ?>" disabled></label>
                    <div id="botoes">
                        <a href="aceitar_usuario_admin.php?id=<?php echo $id_usuario ?>">
                            <button type="button" id="aceitar">ACEITAR</button>
                        </a>
                        <a href="deletar_usuario_admin.php?id=<?php echo $id_usuario ?>">
                            <button type="button" id="recusar">RECUSAR</button>
                        </a>
                    </div>
                </form>
            <?php } else {?>
                <h2><strong>Nenhum usuário encontrado!</strong></h2>
            <?php }?>
        </section>
    </main>
</body>
</html>