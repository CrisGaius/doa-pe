<?php
require_once "../lib/conexao.php";

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    die();
} else {
    $id_usuario = intval($_SESSION['id_usuario']);
}

if (isset($id_usuario)) {
    $_SESSION['id_usuario'];

    $query = "SELECT id_ong, nome, foto, status FROM ongs WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/minhas-ongs.css">
    <link rel="stylesheet" href="../styles/modal-excluir.css">
    <script src="../scripts/menu-mobile.js" defer></script>
    <script src="../scripts/modal.js" defer></script>
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Minhas Ongs | DOA PE</title>
</head>

<body>
    <header id="cabecalho">
        <!-- Menu principal -->
        <nav id="navbar" class="flex-container">
            <a href=""><img src="../images/logo.png" alt="Logo da Doa PE" id="logo-doa-pe"></a>
            <ul id="lista" class="flex-container">
                <li><a href="../index.php" >Início</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                </div>

                <?php if (isset($_SESSION)) {
                    if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && !$_SESSION['funcao']) { //apresenta os elementos do menu do usuário
                ?>
                        <li><a href="minhas-ongs.php" id="atual">Minha ONG</a></li>
                        <li><a href="cadastrar-ong.php" id="botao-cadastrar-ong">Cadastrar ONG</a></li>
                        <li id="botao-logout" class="flex-container">
                            <a href="../logout.php">Logout</a>
                            <img src="../icons/icone-logout.svg" alt="ícone de logout">
                        </li>
                    <?php } else if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && $_SESSION['funcao']) { // coloca as coisas do adm 
                    ?>
                        <li><a href="validar-usuario-ong.php">Validação</a></li>
                        <li id="botao-logout" class="flex-container">
                            <a href="../logout.php">Logout</a>
                            <img src="../icons/icone-logout.svg" alt="ícone de logout">
                        </li>
                    <?php } else { //coloca a navbar da pessoa deslogada 
                    ?>
                        <li id="botao-login" class="flex-container">
                            <a href="login-usuario.php">Login</a>
                            <img src="../icons/icone-login.svg" alt="ícone de Login">
                        </li>
                <?php }
                } ?>
                <img src="../icons/icone-menu.svg" alt="ícone do menu" id="icone-menu">
            </ul>
        </nav>
                <!-- Menu Mobile -->
        <div class="menu-mobile">
            <ul id="lista" class="flex-container">
                <li><a href="../index.php">Início</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <?php if (isset($_SESSION)) {
                    if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && !$_SESSION['funcao']) { //apresenta os elementos do menu do usuário mobile
                ?>
                        <li><a href="minhas-ongs.php" id="atual">Minha ONG</a></li>
                        <li><a href="cadastrar-ong.php" id="botao-cadastrar-ong">Cadastrar ONG</a></li>
                        <li id="botao-logout" class="flex-container">
                            <a href="../logout.php">Logout</a>
                            <img src="../icons/icone-logout.svg" alt="ícone de logout">
                        </li>
                    <?php } else if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && $_SESSION['funcao']) { // coloca as coisas do adm mobile
                    ?>
                        <li><a href="validar-usuario-ong.php">Validação</a></li>
                        <li id="botao-logout" class="flex-container">
                            <a href="../logout.php">Logout</a>
                            <img src="../icons/icone-logout.svg" alt="ícone de logout">
                        </li>
                    <?php } else { //coloca a navbar da pessoa deslogada mobile
                    ?>
                        <li id="botao-login" class="flex-container">
                            <a href="login-usuario.php">Login</a>
                            <img src="../icons/icone-login.svg" alt="ícone de Login">
                        </li>
                <?php }
                } ?>
            </ul>
        </div>
    </header>
    <main>
        <h1 id="titulo">Minha ONG</h1>
        <?php if ($stmt->rowCount() > 0) : ?>
            <?php foreach ($stmt as $ong) : ?>
                <div class="box-layout">
                    <div class="logo-imagem flex-container">
                        <img src="<?= "../". $ong['foto'] ?>" alt="">
                    </div>
                    <h1 class="flex-container"><?= $ong['nome'] ?></h1>
                    <p id="status-ong" style="<?php if($ong['status'] === "aprovado") echo "color: var(--verde);"?>">
                    STATUS: <?php if($ong['status'] === "aprovado") { 
                        echo "APROVADO";
                    } else {
                        echo "ANÁLISE";
                    }?>
                    </p>
                    <div class="botoes flex-container">
                        <a href="editar-ong.php?id=<?= $ong['id_ong'] ?>" class="btn-editar">EDITAR</a>
                        <a class="abrir-modal btn-excluir" href="../pages/deletar-ong.php?id=<?= $ong['id_ong'] ?>">EXCLUIR</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="flex-container">Você não possui ONGs cadastradas!</div>
        <?php endif; ?>

        <section id="fundo-modal" class="flex-container">
            <section id="modal" class="flex-container">
                <p id="texto-principal">DESEJA REALMENTE EXCLUIR A ONG?</p>
                <div id="caixa-botoes" class="flex-container">
                    <a href="" id="botao-confirmar">SIM</a>
                    <button type="button" href="" id="botao-negar">NÃO</button>
                </div>
                <button id="botao-fechar-modal" class="flex-container">X</button>
            </section>
        </section>
    </main>
</body>

</html>