<?php
if (!isset($_SESSION)) {
    session_start();
}

// if(!isset($_SESSION['id_usuario'])) {
//     // header("Location: ../index-deslogado.html");
// }

require_once "../lib/conexao.php";

$sql_code_select_regioes = "SELECT nome_regiao FROM regioes ORDER BY id_regiao";
$sql_query_select_regioes = $pdo->prepare($sql_code_select_regioes);
$sql_query_select_regioes->execute() or die("Erro ao selecionar as regiões no banco de dados.");

if ($sql_query_select_regioes->rowCount() > 0) {
    $regioes = [];
    foreach ($sql_query_select_regioes as $regiao) {
        array_push($regioes, $regiao['nome_regiao']);
    }
} else {
    die("Erro ao selecionar as regiões no banco de dados.");
}

$sql_code_select_tipos_de_ongs = "SELECT tipo FROM tipos_de_ongs ORDER BY id_tipo_ong";
$sql_query_select_tipos_de_ongs = $pdo->prepare($sql_code_select_tipos_de_ongs);
$sql_query_select_tipos_de_ongs->execute() or die("Erro ao selecionar os tipos de ongs no banco de dados.");

if ($sql_query_select_tipos_de_ongs->rowCount() > 0) {
    $tipos = [];
    foreach ($sql_query_select_tipos_de_ongs as $tipo_ong) {
        array_push($tipos, $tipo_ong['tipo']);
    }
} else {
    die("Erro ao selecionar os tipos no banco de dados.");
}

$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

$intervalo_das_paginas = 3;

$limite = 5;
$offset = ($pagina - 1) * $limite;

if (isset($_GET['pesquisa']) && isset($_GET['tipo-ong']) && isset($_GET['regiao-ong'])) {
    $pesquisa = trim($_GET['pesquisa']);
    $tipo_ong = $_GET['tipo-ong'];
    $regiao_ong = $_GET['regiao-ong'];

    $sql_code_select_total_ongs = "SELECT COUNT(id_ong) FROM ongs JOIN regioes 
    ON regioes.id_regiao = ongs.id_regiao
    JOIN tipos_de_ongs
    ON tipos_de_ongs.id_tipo_ong = ongs.id_tipo_ong
    WHERE (ongs.nome LIKE :pesquisa OR tipos_de_ongs.tipo LIKE :pesquisa OR regioes.nome_regiao LIKE :pesquisa) AND status = 'aprovado'";

    $sql_code_select_ongs = "SELECT tipos_de_ongs.tipo, regioes.nome_regiao, ongs.id_ong, ongs.foto, ongs.nome, ongs.descricao FROM ongs JOIN regioes 
        ON regioes.id_regiao = ongs.id_regiao
        JOIN tipos_de_ongs
        ON tipos_de_ongs.id_tipo_ong = ongs.id_tipo_ong
        WHERE (ongs.nome LIKE :pesquisa OR tipos_de_ongs.tipo LIKE :pesquisa OR regioes.nome_regiao LIKE :pesquisa) AND status = 'aprovado'";

    // retirado o else desses dois ifs para colocar nessa consulta acima porque nã estava funcionando.
    if (in_array($tipo_ong, $tipos)) {
        $sql_code_select_ongs = $sql_code_select_ongs . " AND tipos_de_ongs.tipo = '$tipo_ong'";
        $sql_code_select_total_ongs = $sql_code_select_total_ongs . " AND tipos_de_ongs.tipo = '$tipo_ong'";
    }

    if (in_array($regiao_ong, $regioes)) {
        $sql_code_select_ongs = $sql_code_select_ongs . " AND regioes.nome_regiao = '$regiao_ong'";
        $sql_code_select_total_ongs = $sql_code_select_total_ongs . " AND regioes.nome_regiao = '$regiao_ong'";
    }

    $sql_query_select_total_ongs = $pdo->prepare($sql_code_select_total_ongs);
    $sql_query_select_total_ongs->bindValue(":pesquisa", "%$pesquisa%", PDO::PARAM_STR);
    $sql_query_select_total_ongs->execute() or die("Erro ao selecionar o total de ongs no banco de dados.");

    $sql_code_select_ongs = $sql_code_select_ongs . " LIMIT $limite OFFSET $offset";

    $sql_query_select_ongs = $pdo->prepare($sql_code_select_ongs);
    $sql_query_select_ongs->bindValue(":pesquisa", "%$pesquisa%", PDO::PARAM_STR);
    $sql_query_select_ongs->execute() or die("Erro ao selecionar os campos no banco de dados.");
} else {
    $sql_code_select_total_ongs = "SELECT COUNT(id_ong) FROM ongs JOIN regioes 
    ON regioes.id_regiao = ongs.id_regiao
    JOIN tipos_de_ongs
    ON tipos_de_ongs.id_tipo_ong = ongs.id_tipo_ong
    WHERE status = 'aprovado'";

    $sql_query_select_total_ongs = $pdo->prepare($sql_code_select_total_ongs);
    $sql_query_select_total_ongs->execute() or die("Erro ao selecionar os campos no banco de dados.");

    $sql_code_select_ongs = "SELECT tipos_de_ongs.tipo, regioes.nome_regiao, ongs.id_ong, ongs.foto, ongs.nome, ongs.descricao FROM ongs JOIN regioes 
    ON regioes.id_regiao = ongs.id_regiao
    JOIN tipos_de_ongs
    ON tipos_de_ongs.id_tipo_ong = ongs.id_tipo_ong
    WHERE status = 'aprovado'";

    $sql_code_select_ongs = $sql_code_select_ongs . " LIMIT $limite OFFSET $offset";

    $sql_query_select_ongs = $pdo->prepare($sql_code_select_ongs);
    $sql_query_select_ongs->execute() or die("Erro ao selecionar os campos no banco de dados.");
}

if ($sql_query_select_total_ongs->rowCount() > 0) {
    $valor = $sql_query_select_total_ongs->fetchColumn();
    if ($valor > 0) {
        $total_ongs = $valor or die("Não foi possível selecionar o total de ongs.");
    }
}

if (isset($total_ongs)) {
    $numero_de_paginas = intval(ceil($total_ongs / $limite)) or die("Algo deu errado ao definir o total de páginas.");
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
    <script src="../scripts/menu-mobile.js" defer></script>
    <title>Doa PE</title>
</head>

<body>
    <header id="cabecalho">
        <!-- Menu Mobile -->
        <div class="menu-mobile">
            <ul id="lista" class="flex-container">
                <li><a href="home-logado.php" id="atual">Início</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <?php if (isset($_SESSION)) {
                    if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && !$_SESSION['funcao']) { //apresenta os elementos do menu do usuário mobile
                ?>
                        <li><a href="minhas-ongs.php">Minha ONG</a></li>
                        <li><a href="cadastrar-ong.php" id="botao-cadastrar-ong">Cadastrar ONG</a></li>
                        <li id="botao-logout" class="flex-container">
                            <a href="../logout.php">Logout</a>
                            <img src="../icons/icone-logout.svg" alt="ícone de logout">
                        </li>
                    <?php } else if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && $_SESSION['funcao']) { // coloca as coisas do adm mobile
                    ?>
                        <li><a href="validar-usuario-ong.html">Validação</a></li>
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
        <!-- Menu principal -->
        <nav id="navbar" class="flex-container">
            <a href=""><img src="../images/logo.png" alt="Logo da Doa PE" id="logo-doa-pe"></a>
            <ul id="lista" class="flex-container">
                <li><a href="home-logado.php" id="atual">Início</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                </div>

                <?php if (isset($_SESSION)) {
                    if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && !$_SESSION['funcao']) { //apresenta os elementos do menu do usuário
                ?>
                        <li><a href="minhas-ongs.php">Minha ONG</a></li>
                        <li><a href="cadastrar-ong.php" id="botao-cadastrar-ong">Cadastrar ONG</a></li>
                        <li id="botao-logout" class="flex-container">
                            <a href="../logout.php">Logout</a>
                            <img src="../icons/icone-logout.svg" alt="ícone de logout">
                        </li>
                    <?php } else if (isset($_SESSION['id_usuario']) && isset($_SESSION['funcao']) && $_SESSION['funcao']) { // coloca as coisas do adm 
                    ?>
                        <li><a href="validar-usuario-ong.html">Validação</a></li>
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
    </header>

    <main> <!-- colocar p na tag details -->
        <div class="container">
            <form action="" method="get">
                <div class="pesquisa">
                    <div class="input-container">
                        <input id="pesquisa" name="pesquisa" type="text" placeholder="Pesquisar" value="<?php if (isset($_GET['pesquisa']) && strlen($_GET['pesquisa']) > 0) echo $_GET['pesquisa'] ?>">
                        <button type="submit" id="botao-procurar">
                            <img src="../images/lupa.png" alt="Pesquisar">
                        </button>
                    </div>
                </div>
                <div class="pesquisa">
                    <div class="selects">
                        <div class="column">
                            <label for="slc-tipo-ong">Tipo da ONG:</label>
                            <select name="tipo-ong" id="slc-tipo-ong">
                                <option value="--" <?php if (isset($_GET['tipo-ong']) && $_GET['tipo-ong'] === "--") echo "selected" ?>>--</option>
                                <?php
                                foreach ($tipos as $indice => $tipo_ong) {
                                    $indice = $indice + 1;
                                ?>
                                    <option value="<?php echo $tipo_ong ?>" <?php if (isset($_GET['tipo-ong']) && $_GET['tipo-ong'] === $tipo_ong) echo "selected" ?>> <?php echo $indice . " - " . $tipo_ong ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                        <div class="column">
                            <label for="regiao-ong">Região da ONG:</label>
                            <select name="regiao-ong" id="regiao-ong">
                                <option value="--" <?php if (isset($_GET['regiao-ong']) && $_GET['regiao-ong'] === "--") echo "selected" ?>>--</option>
                                <?php
                                foreach ($regioes as $indice => $regiao) {
                                    $indice = $indice + 1;
                                ?>
                                    <option value="<?php echo $regiao ?>" <?php if (isset($_GET['regiao-ong']) && $_GET['regiao-ong'] === $regiao) echo "selected" ?>> <?php echo $indice . " - " . $regiao ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="botao-filtrar">
                    <button type="submit">FILTRAR</button>
                </div>
            </form>
        </div>
        <?php $contador = 0; ?>
        <?php if (isset($sql_query_select_ongs) && $sql_query_select_ongs->rowCount() > 0) {
        ?>
            <section class="ongs">
                <?php foreach ($sql_query_select_ongs as $ong) {
                    $id = intval($ong['id_ong']);
                    $foto = $ong['foto'];
                    $nome = $ong['nome'];
                    $descricao = $ong['descricao'];
                ?>
                    <?php if ($contador === 0) { ?>
                        <div class="flex-container-card2">
                        <?php } ?>
                        <div class="card-ong">
                            <div class="caixa-imagem">
                                <img class="card-img" src="<?php echo "../" . $foto ?>" alt="Imagem do card">
                            </div>
                            <div class="card-conteudo">
                                <h2 class="card-title"><?php echo $nome ?></h2>
                                <div class="card-details">
                                    <details>
                                        <summary>Detalhes</summary>
                                        <p><?php echo $descricao ?></p>
                                    </details>
                                </div>
                                <div class="card-botoes">
                                    <?php if (isset($_SESSION)) { ?>
                                        <a href="forma-de-pagamento.php?id=<?php echo $id ?>" class="botao-doar">DOAR</a>
                                        <?php if (isset($_SESSION['funcao']) && !$_SESSION['funcao']) { ?>
                                            <a href="formulario-voluntario.php?id=<?php echo $id ?>" class="botao-voluntariar">Voluntariar</a>
                                        <?php } else if (isset($_SESSION['funcao']) && $_SESSION['funcao']) { ?>
                                            <a href="deletar-ong.php?id=<?php echo $id ?>" class="botao-voluntariar">DELETAR</a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php $contador = $contador + 1 ?>
                        <?php if ($contador === 3) { ?>
                        </div>
                    <?php $contador = 0;
                        } ?>
                <?php } ?>
            <?php } else { ?>
                <h1>Nenhum resultado encontrado...</h1>
            <?php } ?>
            <?php if ($contador != 0) { ?>
                </div>
            <?php } ?>
            </section>
            <?php if ($sql_query_select_ongs->rowCount() > 0) { ?>
                <p id="paragrafo-paginas"><?php echo "Página: $pagina | Total de páginas: $numero_de_paginas" ?></p>
                <div id="links-paginacao" class="flex-container">
                    <?php if (isset($_GET['pesquisa']) && isset($_GET['tipo-ong']) && isset($_GET['regiao-ong'])) {
                        $pesquisa = trim($_GET['pesquisa']);
                        $tipo_ong = $_GET['tipo-ong'];
                        $regiao_ong = $_GET['regiao-ong']; ?>
                        <a href="<?php echo "?pesquisa=$pesquisa&tipo-ong=$tipo_ong&regiao-ong=$regiao_ong&pagina=1" ?>">[<<]</a>
                                <?php
                                $primeira_pagina = max($pagina - $intervalo_das_paginas, 1);
                                $ultima_pagina = min($numero_de_paginas, $pagina + $intervalo_das_paginas);

                                for ($p = $primeira_pagina; $p <= $ultima_pagina; $p++) {
                                ?>
                                    <a href="<?php echo "?pesquisa=$pesquisa&tipo-ong=$tipo_ong&regiao-ong=$regiao_ong&pagina=$p" ?>"><?php echo "[$p]"; ?></a>
                                <?php } ?>
                                <a href="<?php echo "?pesquisa=$pesquisa&tipo-ong=$tipo_ong&regiao-ong=$regiao_ong&pagina=$numero_de_paginas" ?>">[>>]</a>
                            <?php } else { ?>
                                <a href="?pagina=1">[<<]</a>
                                        <?php
                                        $primeira_pagina = max($pagina - $intervalo_das_paginas, 1);
                                        $ultima_pagina = min($numero_de_paginas, $pagina + $intervalo_das_paginas);
                                        for ($p = $primeira_pagina; $p <= $ultima_pagina; $p++) {
                                        ?>
                                            <a href="<?php echo "?pagina=$p" ?>"><?php echo "[$p]" ?></a>
                                        <?php } ?>
                                        <a href="<?php echo "?pagina=$numero_de_paginas" ?>">[>>]</a>
                                    <?php } ?>
                                    </p>
                                <?php } ?>
    </main>
</body>

</html>