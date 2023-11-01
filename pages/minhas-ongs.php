<?php
require_once "../lib/conexao.php";

$id_usuario = 1;

$query = "SELECT id_ong, nome, foto FROM ongs WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

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
        <nav id="navbar" class="flex-container">
            <a href=""><img src="../images/logo.png" alt="Logo da Doa PE" id="logo-doa-pe"></a>
            <ul id="lista" class="flex-container">
                <li><a href="">Início</a></li>
                <li><a href="" >Sobre</a></li>
                <li><a href="" id="atual">Minhas ONGs</a></li>
                <li><a href="" id="botao-cadastrar-ong">Cadastrar ONG</a></li>
                <li id="botao-logout" class="flex-container">
                    <a href="">Logout</a>
                    <img src="../icons/icone-logout.svg" alt="ícone de logout">
                </li>
                <img src="../icons/icone-menu.svg" alt="ícone do menu" id="icone-menu">
            </ul>
        </nav>
        <div class="menu-mobile">
            <ul id="lista" class="flex-container">
                <li><a href="">Início</a></li>
                <li><a href="" id="atual">Sobre</a></li>
                <li><a href="">Minha ONG</a></li>
                <li><a href="" id="botao-cadastrar-ong">Cadastrar ONG</a></li>
                <li id="botao-logout" class="flex-container">
                    <a href="">Logout</a>
                    <img src="../icons/icone-logout.svg" alt="ícone de logout">
                </li>
            </ul>
        </div>
    </header>
    <main>
        <h1 id="titulo">Minhas ONGs</h1>
        <?php if ($stmt->rowCount() > 0): ?>
            <?php foreach($stmt as $ong): ?>
                <div class="box-layout">
                    <div class="logo-imagem flex-container">
                        <img src="<?= $ong['foto'] ?>" alt="">
                    </div>
                    <h1 class="flex-container"><?= $ong['nome'] ?></h1>
                    <div class="botoes flex-container">
                        <a href="editar-ong?id=<?= $ong['id_ong'] ?>" class="btn-editar">EDITAR</a>
                        <a class="abrir-modal btn-excluir" href="../pages/excluir-ong.html?id=<?= $ong['id_ong'] ?>">EXCLUIR</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
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