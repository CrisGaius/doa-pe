<?php 
    if(!isset($_SESSION)) {
        session_start();
    }

    if(!isset($_SESSION['id_usuario'])) {
        header("Location: ../index-deslogado.html");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/home-logado.css">
    <title>Doa PE</title>
</head>
<body>
    <header id="cabecalho">
        <nav id="navbar" class="flex-container">
            <a href=""><img src="../images/logo.png" alt="Logo da Doa PE" id="logo-doa-pe"></a>
            <ul id="lista" class="flex-container">
                <li><a href="../pages/home-logado.html" id="atual">Início</a></li>
                <li><a href="../pages/sobre.html">Sobre</a></li>
                <li><a href="../pages/minhas-ongs.html">Minha ONG</a></li>
                <li><a href="../pages/cadastrar-ong.html" id="botao-cadastrar-ong">Cadastrar ONG</a></li>
                <li id="botao-logout" class="flex-container">
                    <a href="../logout.php">Logout</a>
                    <img src="../icons/icone-logout.svg" alt="ícone de logout">
                </li>
                <img src="../icons/icone-menu.svg" alt="ícone do menu" id="icone-menu">
            </ul>
        </nav>
        <div class="menu-mobile">
            <ul id="lista" class="flex-container">
                <li><a href="" id="atual">Início</a></li>
                <li><a href="">Sobre</a></li>
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
        <div class="container">
            <form action="" method="post">
                <div class="pesquisa">
                    <div class="input-container">
                        <input id="pesquisa" type="text" placeholder="Pesquisar">
                        <button type="submit" id="botao-procurar">
                            <img src="../images/lupa.png" alt="Pesquisar">
                        </button>
                    </div>
                </div>
                <div class="pesquisa">
                    <div class="selects">
                        <div class="column">
                            <label for="slc-tipo-ong">Tipo da ONG:</label>
                            <select name="slc-tipo-ong" id="slc-tipo-ong">
                                <option disabled selected>Geral</option>
                                <option value="Animal">Animal</option>
                                <option value="Ajuda">Ajuda</option>
                            </select>
                        </div>
                        <div class="column">
                            <label for="regiao-ong">Região da ONG:</label>
                            <select name="regiao-ong" id="regiao-ong">
                                <option disabled selected>Regiões</option>
                                <option value="RMR">Região Metropolitana</option>
                                <option value="AGR">Agreste</option>
                                <option value="ZDM">Zona da Mata</option>
                                <option value="SRT">Sertão</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="botao-filtrar">
                    <button type="submit">FILTRAR</button>
                </div>
            </form>
        </div>

        <section class="ongs">
            <div class="flex-container-card2">
                <div class="card-ong">
                    <img class="card-img" src="../images/img-temp.jpeg" alt="Imagem do card">
                    <div class="card-conteudo">
                        <h2 class="card-title">ONG 1</h2>
                        <div class="card-details">
                            <details>
                                <summary>Detalhes</summary>
                            </details>
                        </div>
                        <div class="card-botoes">
                            <button class="botao-doar">DOAR</button>
                            <button class="botao-voluntariar">Voluntariar</button>
                        </div>
                    </div>
                </div>
                <div class="card-ong">
                    <img class="card-img" src="../images/img-temp.jpeg" alt="Imagem do card">
                    <div class="card-conteudo">
                        <h2 class="card-title">ONG 2</h2>
                        <div class="card-details">
                            <details>
                                <summary>Detalhes</summary>
                            </details>
                        </div>
                        <div class="card-botoes">
                            <button class="botao-doar">DOAR</button>
                            <button class="botao-voluntariar">Voluntariar</button>
                        </div>
                    </div>
                </div>
                <div class="card-ong">
                    <img class="card-img" src="../images/img-temp.jpeg" alt="Imagem do card">
                    <div class="card-conteudo">
                        <h2 class="card-title">ONG 3</h2>
                        <div class="card-details">
                            <details>
                                <summary>Detalhes</summary>
                            </details>
                        </div>
                        <div class="card-botoes">
                            <button class="botao-doar">DOAR</button>
                            <button class="botao-voluntariar">Voluntariar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-container-card2">
                <div class="card-ong">
                    <img class="card-img" src="../images/img-temp.jpeg" alt="Imagem do card">
                    <div class="card-conteudo">
                        <h2 class="card-title">ONG 4</h2>
                        <div class="card-details">
                            <details>
                                <summary>Detalhes</summary>
                            </details>
                        </div>
                        <div class="card-botoes">
                            <button class="botao-doar">DOAR</button>
                            <button class="botao-voluntariar">Voluntariar</button>
                        </div>
                    </div>
                </div>
                <div class="card-ong">
                    <img class="card-img" src="../images/img-temp.jpeg" alt="Imagem do card">
                    <div class="card-conteudo">
                        <h2 class="card-title">ONG 5</h2>
                        <div class="card-details">
                            <details>
                                <summary>Detalhes</summary>
                            </details>
                        </div>
                        <div class="card-botoes">
                            <button class="botao-doar">DOAR</button>
                            <button class="botao-voluntariar">Voluntariar</button>
                        </div>
                    </div>
                </div>
                <div class="card-ong">
                    <img class="card-img" src="../images/img-temp.jpeg" alt="Imagem do card">
                    <div class="card-conteudo">
                        <h2 class="card-title">ONG 6</h2>
                        <div class="card-details">
                            <details>
                                <summary>Detalhes</summary>
                            </details>
                        </div>
                        <div class="card-botoes">
                            <button class="botao-doar">DOAR</button>
                            <button class="botao-voluntariar">Voluntariar</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>