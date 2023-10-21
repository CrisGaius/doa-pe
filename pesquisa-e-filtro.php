<?php
require_once "lib/conexao.php";

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

if (isset($_GET['pesquisa']) && isset($_GET['tipo-ong']) && isset($_GET['regiao-ong'])) {
    $pesquisa = trim($_GET['pesquisa']);
    $tipo_ong = $_GET['tipo-ong'];
    $regiao_ong = $_GET['regiao-ong'];

    $sql_code_select_ongs = "SELECT tipos_de_ongs.tipo, regioes.nome_regiao, ongs.id_ong, ongs.foto, ongs.nome, ongs.descricao FROM ongs JOIN regioes 
        ON regioes.id_regiao = ongs.id_regiao
        JOIN tipos_de_ongs
        ON tipos_de_ongs.id_tipo_ong = ongs.id_tipo_ong
        WHERE (ongs.nome LIKE :pesquisa OR tipos_de_ongs.tipo LIKE :pesquisa OR regioes.nome_regiao LIKE :pesquisa) AND status = 'aprovado'";

    // retirado o else desses dois ifs para colocar nessa consulta acima porque nã estava funcionando.
    if (in_array($tipo_ong, $tipos)) {
        $sql_code_select_ongs = $sql_code_select_ongs . " AND tipos_de_ongs.tipo = '$tipo_ong'";
    }

    if (in_array($regiao_ong, $regioes)) {
        $sql_code_select_ongs = $sql_code_select_ongs . " AND regioes.nome_regiao = '$regiao_ong'";
    } 

    $sql_query_select_ongs = $pdo->prepare($sql_code_select_ongs);
    $sql_query_select_ongs->bindValue(":pesquisa", "%$pesquisa%", PDO::PARAM_STR);
    $sql_query_select_ongs->execute() or die("Erro ao selecionar os campos no banco de dados.");
} else {
    $sql_code_select_ongs = "SELECT tipos_de_ongs.tipo, regioes.nome_regiao, ongs.id_ong, ongs.foto, ongs.nome, ongs.descricao FROM ongs JOIN regioes 
    ON regioes.id_regiao = ongs.id_regiao
    JOIN tipos_de_ongs
    ON tipos_de_ongs.id_tipo_ong = ongs.id_tipo_ong
    WHERE status = 'aprovado'";

    $sql_query_select_ongs = $pdo->prepare($sql_code_select_ongs);
    $sql_query_select_ongs->execute() or die ("Erro ao selecionar os campos no banco de dados.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa e filtro - back-end</title>
    <link rel="stylesheet" href="styles/config.css">
    <style>
        form#formulario {
            flex-direction: column;
            width: 600px;
            margin: auto;
            margin-top: 50px;
            gap: 15px;
        }

        section#secao-pesquisa {
            position: relative;
            width: 50%;
        }

        section#secao-pesquisa input {
            padding: 10px;
            background-color: rosybrown;
            width: 100%;
            color: white;
        }

        section#secao-pesquisa input::placeholder {
            color: white;
        }

        section#secao-pesquisa img#icone-lupa {
            width: 20px;
            cursor: pointer;
            position: absolute;
            right: 10px;
        }

        section#filtros {
            gap: 30px;
        }

        section#filtros select {
            width: 200px;
        }

        button#botao-filtrar {
            padding: 5px;
            font-weight: bold;
            background-color: rosybrown;
            color: white;
            cursor: pointer;
        }

        section#conteudo {
            margin: 30px 0 0 20px;
        }
    </style>
</head>

<body>
    <main>
        <form action="" id="formulario" class="flex-container">
            <section action="" id="secao-pesquisa" class="flex-container">
                <input type="text" name="pesquisa" id="pesquisa" placeholder="Pesquise aqui..." value="<?php if (isset($_GET['pesquisa']) && strlen($_GET['pesquisa']) > 0) echo $_GET['pesquisa']?>">
                <img id="icone-lupa" src="icons/icone-lupa.svg" alt="ícone de lupa de pesquisa" onclick="
                const form = document.querySelector('form#formulario') 
                form.submit()
                ">
            </section>

            <section id="filtros" class="flex-container">
                <div>
                    <p>Tipo de ong:</p>
                    <select name="tipo-ong" id="tipo-ong">
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
                <div>
                    <p>Região de ong:</p>
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
            </section>
            <button id="botao-filtrar">FILTRAR</button>
        </form>

        <section id="conteudo">
            <?php if (isset($sql_query_select_ongs) && $sql_query_select_ongs->rowCount() > 0) { ?>
                <?php $tipo = null;
                $regiao = null; ?>
                <?php foreach ($sql_query_select_ongs as $ong) {
                    $id = $ong['id_ong'];
                    $foto = $ong['foto'];
                    $nome = $ong['nome'];
                    $descricao = $ong['descricao'];
                ?>
                    <div>
                        <?php if ($ong['tipo'] !== $tipo) { ?>
                            <h1><?php echo $ong['tipo'];
                                $tipo = $ong['tipo']; ?></h1>
                        <?php } ?>
                        <?php if ($ong['nome_regiao'] !== $regiao) { ?>
                            <p style="font-weight: bold;"><?php echo $ong['nome_regiao'];
                                $regiao = $ong['nome_regiao']; ?></p>
                        <?php } ?>
                        <p>ID da ONG: <?php echo $id; ?></p>
                        <p>Nome da ONG: <?php echo $nome; ?></p>
                        <p>Foto da ong: </p>
                        <img src="<?php echo $foto; ?>" alt="" width="100px;"> <br>
                        <details style="display: inline; background-color: red;">
                            <summary>Descrição</summary>
                            <p><?php echo $descricao;?></p>
                        </details>
                    </div> <br>
                <?php } ?>
            <?php } else { ?>
                <p>
                <h1>Nenhum resultado encontrado...</h1>
                </p>
            <?php } ?>
        </section>

    </main>
</body>

</html>