<?php
//quando for abrir a tela de editar, informar que a ong irá para análise novamente após a edição. (Atualizar status para "analise" novamente)

require_once("lib/conexao.php");
require_once("lib/funcoes_uteis.php");

if (isset($_GET['id'])) {
    $id_ong = intval($_GET['id']);

    $sql_code_select_tipos = "SELECT id_tipo_ong, tipo FROM tipos_de_ongs ORDER BY id_tipo_ong";
    $sql_query_select_tipos = $pdo->prepare($sql_code_select_tipos);
    $sql_query_select_tipos->execute() or die("Erro ao pesquisar os tipos de ongs.");

    $sql_code_select_regioes = "SELECT id_regiao, nome_regiao FROM regioes ORDER BY id_regiao";
    $sql_query_select_regioes = $pdo->prepare($sql_code_select_regioes);
    $sql_query_select_regioes->execute() or die("Erro ao pesquisar as regiões");

    if ($sql_query_select_tipos->rowCount() > 0 && $sql_query_select_regioes->rowCount() > 0) {
        $tipos = $sql_query_select_tipos->fetchAll();

        $id_tipos_ongs = [];

        foreach ($tipos as $tipo) {
            array_push($id_tipos_ongs, $tipo['id_tipo_ong']);
        }

        $regioes = $sql_query_select_regioes->fetchAll();

        $id_regioes = [];

        foreach ($regioes as $regiao) {
            array_push($id_regioes, $regiao['id_regiao']);
        }

        $tipos_de_contas = ["Conta Corrente", "Poupança"];

        // testando para ver se o botão foi clicado.
        if (isset($_POST['botao'])) {
            $erro = false;
            if (strlen($_POST['nome']) >= 3 && strlen($_POST['endereco']) > 20 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['contato']) && !empty($_POST['cnpj']) && in_array($_POST['tipo-ong'], $id_tipos_ongs) && strlen($_POST['chave-pix']) >= 11 && !empty($_POST['conta']) && in_array($_POST['tipo-conta'], $tipos_de_contas) && strlen($_POST['instituicao']) > 5 && strlen($_POST['agencia']) === 4 && in_array($_POST['regiao-ong'], $id_regioes) && strlen($_POST['descricao']) <= 500) {

                $contato = preg_replace("/[^0-9]/", "", $_POST['contato']);
                if (strlen($contato) < 10 || strlen($contato) > 11) {
                    $erro = true;
                }

                $cnpj = preg_replace("/[^0-9]/", "", $_POST['cnpj']);
                if (strlen($cnpj) !== 14) {
                    $erro = true;
                }

                $conta = preg_replace("/[^0-9]/", "", $_POST['conta']);
                if (strlen($conta) !== 8) {
                    $erro = true;
                }

                if (!$erro) {
                    $nome = $_POST['nome'];
                    $endereco = $_POST['endereco'];
                    $email = $_POST['email'];
                    $tipo_ong = $_POST['tipo-ong'];
                    $chave_pix = $_POST['chave-pix'];
                    
                    $tipo_conta = $_POST['tipo-conta'];
                    $instituicao = $_POST['instituicao'];
                    $agencia = $_POST['agencia'];
                    $regiao_ong = $_POST['regiao-ong'];
                    $descricao = $_POST['descricao'];
                    $foto = $_FILES['atualizar-foto'];

                    if (isset($foto) && !empty($foto['name'])) {
                        $caminho = enviar_imagens($foto['error'], $foto['size'], $foto['name'], $foto['tmp_name']);

                        if ($caminho) {
                            $sql_code_select_caminho_antigo = "SELECT foto FROM ongs WHERE id_ong = $id_ong LIMIT 1";
                            $sql_query_select_caminho_antigo = $pdo->prepare($sql_code_select_caminho_antigo);
                            $sql_query_select_caminho_antigo->execute();

                            if ($sql_query_select_caminho_antigo->rowCount() > 0) {
                                $caminho_antigo = $sql_query_select_caminho_antigo->fetchColumn();

                                $sql_code_update_foto = "UPDATE ongs 
                            SET foto = '$caminho'
                            WHERE id_ong = $id_ong 
                            LIMIT 1";
                                $sql_query_update_foto = $pdo->prepare($sql_code_update_foto);

                                if ($sql_query_update_foto->execute()) {
                                    if (unlink($caminho_antigo)) {
                                        $erro = false;
                                    } else {
                                        die("A imagem antiga com caminho igual a $caminho_antigo não foi removida do sistema. Comunique ao <a href='https://www.instagram.com/04_cristiano/'>suporte</a> para corrigir isso. Obs: passe o caminho para ele. Caminho novo da imagem: $caminho.");
                                    }
                                } else {
                                    die("Não foi possível atualizar o caminho no banco de dados. Caminho antigo é: $caminho_antigo e o novo caminho era pra ser: $caminho");
                                }
                            } else {
                                die("Erro ao selecionar o caminho antigo da imagem. Caminho novo: $caminho.");
                            }
                        } else {
                            die("ERRO AO enviar imagem");
                        }
                    }

                    $sql_code_update_ong = "UPDATE ongs 
                SET nome = :nome, endereco = :endereco, email = :email, contato = :contato, cnpj = :cnpj, 
                id_tipo_ong = :tipo_ong, id_regiao = :regiao_ong, descricao = :descricao, status = 'analise'
                WHERE id_ong = $id_ong 
                LIMIT 1";
                    // AND ongs.id_usuario = $id_usuario --> para verificar se aquele usuário realmente cadastrou aquela ong. obs: antes do limit.
                    $sql_query_update_ong = $pdo->prepare($sql_code_update_ong);

                    $sql_query_update_ong->bindValue(":nome", $nome, PDO::PARAM_STR);
                    $sql_query_update_ong->bindValue(":endereco", $endereco, PDO::PARAM_STR);
                    $sql_query_update_ong->bindValue(":email", $email, PDO::PARAM_STR);
                    $sql_query_update_ong->bindValue(":contato", $contato, PDO::PARAM_STR);
                    $sql_query_update_ong->bindValue(":cnpj", $cnpj, PDO::PARAM_STR);
                    $sql_query_update_ong->bindValue(":tipo_ong", $tipo_ong, PDO::PARAM_INT);
                    $sql_query_update_ong->bindValue(":regiao_ong", $regiao_ong, PDO::PARAM_INT);
                    $sql_query_update_ong->bindValue(":descricao", $descricao, PDO::PARAM_STR);

                    if ($sql_query_update_ong->execute()) {
                        $sql_code_update_info_bancarias = "UPDATE informacoes_bancarias 
                    SET chave_pix = :chave_pix, conta = :conta, agencia = :agencia,
                    instituicao = :instituicao, tipo_de_conta = :tipo_conta
                    WHERE id_ong = $id_ong 
                    LIMIT 1";
                        $sql_query_update_info_bancarias = $pdo->prepare($sql_code_update_info_bancarias);

                        $sql_query_update_info_bancarias->bindValue(":chave_pix", $chave_pix, PDO::PARAM_STR);
                        $sql_query_update_info_bancarias->bindValue(":conta", $conta, PDO::PARAM_STR);
                        $sql_query_update_info_bancarias->bindValue(":agencia", $agencia, PDO::PARAM_STR);
                        $sql_query_update_info_bancarias->bindValue(":instituicao", $instituicao, PDO::PARAM_STR);
                        $sql_query_update_info_bancarias->bindValue(":tipo_conta", $tipo_conta, PDO::PARAM_STR);

                        if ($sql_query_update_info_bancarias->execute()) {
                            $erro = false;
                        } else {
                            die("As informações financeiras da ONG não foi atualizada.");
                        }
                    } else {
                        die("Não foi possível atualizar as informações da ONG.");
                    }
                    // se der erro no update, guardar o caminho (se ele estiver definido e mostrar o caminho no erro e mandar enviar para o suporte que a imagem foi enviada, mas houve um erro no update).
                } else {
                    die("Algo deu errado no preenchimento dos dados.");
                }
            }
        }

        $sql_code_select_info_ongs = "SELECT ongs.nome, ongs.endereco, ongs.email, ongs.contato, ongs.cnpj, ongs.id_tipo_ong, info_bancarias.chave_pix, info_bancarias.conta, info_bancarias.tipo_de_conta, info_bancarias.instituicao, info_bancarias.agencia, ongs.id_regiao, ongs.descricao, ongs.foto, ongs.status
        FROM ongs
        JOIN informacoes_bancarias info_bancarias
        ON ongs.id_ong = info_bancarias.id_ong
        WHERE ongs.id_ong = $id_ong LIMIT 1";
        // AND ongs.id_usuario = $id_usuario --> para verificar se aquele usuário realmente cadastrou aquela ong. obs: antes do limit.

        $sql_query_select_info_ongs = $pdo->prepare($sql_code_select_info_ongs);
        $sql_query_select_info_ongs->execute() or die("Erro ao consultar as informações da ONG no banco de dados.");
    }
} else {
    die("<h1>Ong não cadastrada no sistema.</h1>");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ong</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (isset($sql_query_select_info_ongs) && $sql_query_select_info_ongs->rowCount() > 0) { ?>
            <?php foreach ($sql_query_select_info_ongs as $dados) {
                $nome = $dados['nome'];
                $endereco = $dados['endereco'];
                $email = $dados['email'];
                $contato = formatar_numero($dados['contato']);
                $cnpj = formatar_cnpj($dados['cnpj']);
                $id_tipo_ong_atual = $dados['id_tipo_ong'];
                $chave_pix = $dados['chave_pix'];
                $conta = formatar_conta($dados['conta']);
                $tipo_de_conta_atual = $dados['tipo_de_conta'];
                $instituicao = $dados['instituicao'];
                $agencia = $dados['agencia'];
                $id_regiao_atual = $dados['id_regiao'];
                $descricao = $dados['descricao'];
                $foto = $dados['foto'];
                $status = $dados['status'];
            ?>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo "$nome" ?>"> <br> <br>
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" id="endereco" value="<?php echo "$endereco" ?>"> <br> <br>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo "$email" ?>"> <br> <br>
                <label for="contato">Contato:</label>
                <input type="tel" name="contato" id="contato" value="<?php echo "$contato" ?>"> <br> <br>
                <label for="cnpj">CNPJ:</label>
                <input type="text" name="cnpj" id="cnpj" value="<?php echo $cnpj ?>">
                <br> <br>
                <select name="tipo-ong" id="tipo-ong">
                    <?php foreach ($tipos as $indice => $tipo) {
                        $indice = $indice + 1
                    ?>
                        <option value="<?php echo $tipo['id_tipo_ong'] ?>" <?php if ($tipo['id_tipo_ong'] === $id_tipo_ong_atual) echo "selected" ?>><?php echo $indice . " - " . $tipo['tipo'] ?></option>
                    <?php } ?>
                </select> <br> <br>
                <label for="chave-pix">Chave Pix</label>
                <input type="text" name="chave-pix" id="chave-pix" value="<?php echo $chave_pix ?>"> <br> <br>
                <label for="conta">Conta</label>
                <input type="text" name="conta" id="conta" value="<?php echo $conta ?>"> <br> <br>
                <select name="tipo-conta" id="tipo-conta">
                    <?php foreach ($tipos_de_contas as $tipo_conta) { ?>
                        <option value="<?php echo $tipo_conta ?>" <?php if ($tipo_conta === $tipo_de_conta_atual) echo "selected" ?>><?php echo $tipo_conta ?></option>
                    <?php } ?>
                </select> <br> <br>
                <label for="instituicao">Instituição</label>
                <input type="text" name="instituicao" id="instituicao" value="<?php echo $instituicao ?>"> <br> <br>
                <label for="agencia">Agência</label>
                <input type="text" name="agencia" id="agencia" value="<?php echo $agencia ?>"> <br> <br>
                <select name="regiao-ong" id="regiao-ong">
                    <?php foreach ($regioes as $indice => $regiao) {
                        $indice = $indice + 1 ?>
                        <option value="<?php echo $regiao['id_regiao'] ?>" <?php if ($regiao['id_regiao'] === $id_regiao_atual) echo "selected" ?>><?php echo $indice . " - " . $regiao['nome_regiao'] ?></option>
                    <?php } ?>
                </select> <br>
                <textarea name="descricao" id="" cols="30" rows="10"><?php echo $descricao ?></textarea> <br>
                <img id="imagem-ong" src="<?php echo $foto ?>" alt="Imagem da ong" width="200" height="100"> <br>
                <input type="file" name="atualizar-foto" id="atualizar-foto"> <br> <br>
                <span>Status:</span>
                <input type="text" name="status" id="status" value="<?php if ($status === "aprovado") {
                                                                        echo "Aprovado";
                                                                    } else {
                                                                        echo "Análise";
                                                                    } ?>" disabled>
                <p><strong>Adendo:</strong> ao clicar no botão <strong>ATUALIZAR</strong>, a ong será enviada para análise novamente.</p>
            <?php } ?>
            <button name="botao">Atualizar</button>
        <?php } else { ?>
            <h1>Ocorreu um erro ao procurar por essa ong... Volte e tente novamente.</h1>
        <?php } ?>
    </form>
    <script>
        const img = document.querySelector("img#imagem-ong")
        const srcAntigo = img.src
        const inputFile = document.querySelector("input#atualizar-foto")

        inputFile.addEventListener("change", () => {
            if (inputFile.files[0]) {
                const reader = new FileReader()

                reader.onload = (e) => {
                    img.src = e.target.result
                }

                reader.readAsDataURL(inputFile.files[0])
            } else {
                img.src = srcAntigo
            }
        })
    </script>
</body>

</html>