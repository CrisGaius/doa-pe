<?php 
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['funcao']) || !$_SESSION['funcao']) {
        header("Location: home-logado.php");
        die();
    }

    require_once("../lib/conexao.php");

    $sql_code_select_usuarios_analise = "SELECT id_usuario FROM usuarios WHERE status = 'analise'";

    $sql_query_select_usuarios_analise = $pdo->prepare($sql_code_select_usuarios_analise);
    $sql_query_select_usuarios_analise->execute() or die ("Erro ao selecionar o id dos usuários no banco de dados.");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/validar-usuario-ong.css">
</head>

<body>
    <header id="cabecalho" class="flex-container">
        <section id="caixa-voltar" class="flex-container">
            <a href="#">
                <img src="../icons/icone-voltar.svg" alt="Ícone de voltar" id="icone-voltar">
                <p>VOLTAR</p>
            </a>
        </section>
        <h1>TELA DE VALIDAÇÃO</h1>
    </header>

    <main class="flex-container">
        <div class="container-maior">
            <div class="sub-titulo">
                <h2>VALIDAR USUÁRIO</h2>
            </div>
            <?php if ($sql_query_select_usuarios_analise->rowCount() > 0) {?>
                <?php foreach($sql_query_select_usuarios_analise as $usuario) { ?>
                    <div class="container-menor">
                        <div class="flex-vertical">
                            <a href="validar-usuario.php?id=<?php echo $usuario['id_usuario'] ?>">#<?php echo $usuario['id_usuario']?></a>

                            <div class="buttons">
                                <a href="aceitar_usuario_admin.php?id=<?php echo $usuario['id_usuario'] ?>" class="btn-aceitar flex-container">Aceitar</a>
                                <a href="deletar_usuario_admin.php?id=<?php echo $usuario['id_usuario'] ?>" class="btn-recusar flex-container">Recusar</a>
                            </div>
                        </div>
                    </div>
                <?php }?>
            <?php } else {?>
                <h3><strong>Nenhum usuário em validação!</strong></h3>
            <?php }?>
        </div>

        <div class="container-maior">
            <div class="sub-titulo">
                <h2>VALIDAR ONG</h2>
            </div>

            <div class="container-menor">
                <div class="flex-vertical">
                    <a href="validar-ong.html">#ID</a >

                    <div class="buttons">
                        <a class="btn-aceitar flex-container">Aceitar</a>
                        <a class="btn-recusar flex-container">Recusar</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>