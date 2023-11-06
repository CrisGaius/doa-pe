<?php 
require_once "../lib/conexao.php";

if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página que conta um pouco sobre o projeto da Doa PE e dos seus desenvolvedores.">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Sobre Nós - Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/sobre.css">
    <script src="../scripts/menu-mobile.js" defer></script>
</head>

<body>
    <header id="cabecalho">
        <!-- Menu principal -->
        <nav id="navbar" class="flex-container">
            <a href=""><img src="../images/logo.png" alt="Logo da Doa PE" id="logo-doa-pe"></a>
            <ul id="lista" class="flex-container">
                <li><a href="../index.php" >Início</a></li>
                <li><a href="sobre.php" id="atual">Sobre</a></li>
                </div>

                <?php if (isset($_SESSION)) {
                    if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && !$_SESSION['funcao']) { //apresenta os elementos do menu do usuário
                ?>
                        <li><a href="minhas-ongs.php" >Minha ONG</a></li>
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
                <li><a href="sobre.php" id="atual">Sobre</a></li>
                <?php if (isset($_SESSION)) {
                    if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && !$_SESSION['funcao']) { //apresenta os elementos do menu do usuário mobile
                ?>
                        <li><a href="minhas-ongs.php" >Minha ONG</a></li>
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

    <main class="flex-container">
        <img src="../images/foto-sobre.jpeg" alt="Foto dos desenvolvedores do web app" id="foto-principal">
        <img src="../icons/icone-pessoas.svg" alt="Ícone de pessoas" id="icone-pessoas">
        <h1 id="titulo-quem-somos">QUEM SOMOS?</h1>
        <p id="texto-quem-somos">Somos um grupo de seis desenvolvedores, os idealizadores do projeto da Doa PE. <br>
            Inicialmente, fomos notificados pela faculdade que precisariamos realizar o sexto período
            no nosso quarto período e realizar o projeto de desenvolvimento de uma startup. <br>
            Devido a essa necessidade, pensamos em um tema que ajudaria as pessoas que gostariam <br>
            de realizar doações, mas não encontram ONGs confiáveis para o fazer. <br>
            Com isso, surgiu o projeto da DOA PE, que tem seu diferencial ao apresentar um design único e simples de
            entender.</p>
        <section id="container-cards-devs">
            <div class="flex-container">
                <div>
                    <img src="../images/foto-cristiano.jpg" alt="Foto do dev" class="foto-dev" loading="lazy">
                    <p class="nome-dev">CRISTIANO GOMES</p>
                    <div class="redes-sociais-dev flex-container">
                        <a href="https://github.com/CrisGaius" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-github.svg" alt="Ícone do github" class="icone-github">
                                <p class="paragrafo-github" loading="lazy">GitHub</p>
                            </div>
                        </a>
                        <a href="https://www.linkedin.com/in/cristiano-santos-dev/" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-linkedin.svg" alt="Ícone do linkedin" class="icone-linkedin"
                                    loading="lazy">
                                <p class="paragrafo-linkedin">LinkedIn</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <img src="../images/foto-aislan.jpg" alt="Foto do dev" class="foto-dev" loading="lazy">
                    <p class="nome-dev">AISLAN DAYVSON</p>
                    <div class="redes-sociais-dev flex-container">
                        <a href="https://github.com/AislanDayvson" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-github.svg" alt="Ícone do github" class="icone-github">
                                <p class="paragrafo-github" loading="lazy">GitHub</p>
                            </div>
                        </a>
                        <a href="https://www.linkedin.com/in/aislan-dayvson-573a46233/" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-linkedin.svg" alt="Ícone do linkedin" class="icone-linkedin"
                                    loading="lazy">
                                <p class="paragrafo-linkedin">LinkedIn</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <img src="../images/foto-caio.jpg" alt="foto do dev" class="foto-dev" loading="lazy">
                    <p class="nome-dev">CAIO MONTENEGRO</p>
                    <div class="redes-sociais-dev flex-container">
                        <a href="https://github.com/CaioMBM" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-github.svg" alt="Ícone do github" class="icone-github"
                                    loading="lazy">
                                <p class="paragrafo-github">GitHub</p>
                            </div>
                        </a>
                        <a href="https://www.linkedin.com/in/caio-montenegro-486571250/" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-linkedin.svg" alt="Ícone do linkedin" class="icone-linkedin"
                                    loading="lazy">
                                <p class="paragrafo-linkedin">LinkedIn</p>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <div class="flex-container" id="segunda-fileira-cards">
                <div>
                    <img src="../images/foto-paulo.jpg" alt="foto do dev" class="foto-dev" loading="lazy">
                    <p class="nome-dev">PAULO HENRIQUE</p>
                    <div class="redes-sociais-dev flex-container">
                        <a href="https://github.com/LockynBr" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-github.svg" alt="Ícone do github" class="icone-github"
                                    loading="lazy">
                                <p class="paragrafo-github">GitHub</p>
                            </div>
                        </a>
                        <a href="https://www.linkedin.com/in/paulo-luz-dev/" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-linkedin.svg" alt="Ícone do linkedin" class="icone-linkedin"
                                    loading="lazy">
                                <p class="paragrafo-linkedin">LinkedIn</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <img src="../images/foto-wendel.jpg" alt="foto do dev" class="foto-dev" loading="lazy">
                    <p class="nome-dev">WENDEL NÍCOLAS</p>
                    <div class="redes-sociais-dev flex-container">
                        <a href="https://github.com/wendelncols" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-github.svg" alt="Ícone do github" class="icone-github"
                                    loading="lazy">
                                <p class="paragrafo-github">GitHub</p>
                            </div>
                        </a>
                        <a href="https://www.linkedin.com/in/wendel-n%C3%ADcolas-95220b1aa/" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-linkedin.svg" alt="Ícone do linkedin" class="icone-linkedin"
                                    loading="lazy">
                                <p class="paragrafo-linkedin">LinkedIn</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <img src="../images/foto-welton.jpg" alt="foto do dev" class="foto-dev" loading="lazy">
                    <p class="nome-dev">WELTON KELLYSON</p>
                    <div class="redes-sociais-dev flex-container">
                        <a href="https://github.com/WeltonKellyson" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-github.svg" alt="Ícone do github" class="icone-github">
                                <p class="paragrafo-github" loading="lazy">GitHub</p>
                            </div>
                        </a>
                        <a href="https://www.linkedin.com/in/weltonkellyson/" target="_blank">
                            <div class="flex-container">
                                <img src="../icons/icone-linkedin.svg" alt="Ícone do linkedin" class="icone-linkedin">
                                <p class="paragrafo-linkedin" loading="lazy">LinkedIn</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>