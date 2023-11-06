<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['funcao'])) {
    header("Location: ../index.php");
    die();
} else {
    $id_usuario = intval($_SESSION['id_usuario']);
    $funcao = intval($_SESSION['funcao']);
}

//quando for abrir a tela de editar, informar que a ong irá para análise novamente após a edição. (Atualizar status para "analise" novamente)

require_once("../lib/conexao.php");
require_once("../lib/funcoes_uteis.php");

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
        if (count($_POST) > 0) {
            $erro = false;
            if (strlen($_POST['nome']) >= 3 && strlen($_POST['endereco']) > 20 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['contato']) && !empty($_POST['cnpj']) && in_array($_POST['tipo-ong'], $id_tipos_ongs) && strlen($_POST['chave-pix']) >= 11 && !empty($_POST['conta']) && in_array($_POST['tipo-conta'], $tipos_de_contas) && strlen($_POST['instituicao']) > 5 && strlen($_POST['agencia']) === 4 && in_array($_POST['regiao-ong'], $id_regioes) && strlen($_POST['descricao']) <= 500) {

                $contato = preg_replace("/[^0-9]/", "", $_POST['contato']);
                if (strlen($contato) < 10 || strlen($contato) > 11) {
                    $erro = true;
                }

                $cnpj = preg_replace("/[^0-9]/", "", $_POST['cnpj']);

                if (strlen($cnpj) !== 14) {
                    $erro = true;
                } else {
                    $sql_code_select_cnpj = "SELECT cnpj FROM ongs";

                    $sql_query_select_cnpj = $pdo->prepare($sql_code_select_cnpj);

                    $sql_query_select_cnpj->execute();

                    if ($sql_query_select_cnpj->rowCount() > 0) {
                        $cnpjs = [];

                        foreach ($sql_query_select_cnpj as $dados) {
                            array_push($cnpjs, $dados['cnpj']);
                        }

                        if(in_array($cnpj, $cnpjs)) {
                            $erro = true;
                            
                            $sql_code_select_cnpj_atual = "SELECT cnpj FROM ongs WHERE id_ong = $id_ong LIMIT 1";
    
                            $sql_query_select_cnpj_atual = $pdo->prepare($sql_code_select_cnpj_atual);
                            $sql_query_select_cnpj_atual->execute();

                            if($sql_query_select_cnpj_atual->rowCount() > 0) {
                                $cnpj_atual = $sql_query_select_cnpj_atual->fetchColumn();

                                if($cnpj !== $cnpj_atual) {
                                    $mensagem_erro = "CNPJ já cadastrado.";
                                    $erro= true;
                                } else {
                                    $erro = false;
                                }
                            } else {
                                $mensagem_erro = "Erro ao selecionar o cnpj atual.";
                                $erro = true;
                            }
                        } else {
                            $erro = false;
                        }
                    } else {
                        $erro = true;
                    }
                }

                $conta = preg_replace("/[^0-9]/", "", $_POST['conta']);
                if (strlen($conta) < 8 || strlen($conta) > 20) {
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
                        $caminho = enviar_imagens(true, $foto['error'], $foto['size'], $foto['name'], $foto['tmp_name']);

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
                                    if (unlink("../" . $caminho_antigo)) {
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
                            die("As informações financeiras da ONG não foram atualizada.");
                        }
                    } else {
                        die("Não foi possível atualizar as informações da ONG.");
                    }
                    // se der erro no update, guardar o caminho (se ele estiver definido e mostrar o caminho no erro e mandar enviar para o suporte que a imagem foi enviada, mas houve um erro no update).
                } else {
                    if (isset($mensagem_erro)) {
                        echo "$mensagem_erro <br>";
                    }
                    die("Algo deu errado no preenchimento dos dados.");
                }
            } else {
                die("Erro nas validações para enviar os dados.");
            }
        }

        $sql_code_select_info_ongs = "SELECT ongs.nome, ongs.endereco, ongs.email, ongs.contato, ongs.cnpj, ongs.id_tipo_ong, info_bancarias.chave_pix, info_bancarias.conta, info_bancarias.tipo_de_conta, info_bancarias.instituicao, info_bancarias.agencia, ongs.id_regiao, ongs.descricao, ongs.foto, ongs.status
        FROM ongs
        JOIN informacoes_bancarias info_bancarias
        ON ongs.id_ong = info_bancarias.id_ong
        WHERE ongs.id_ong = $id_ong";
        // AND ongs.id_usuario = $id_usuario --> para verificar se aquele usuário realmente cadastrou aquela ong. obs: antes do limit.

        if (!$funcao) {
            $sql_code_select_info_ongs .= " AND ongs.id_usuario = $id_usuario";
        }

        $sql_code_select_info_ongs .= " LIMIT 1";

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
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">

    <title>Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/cadastrar-ou-editar-ong.css">
    <script src="../scripts/cadastrar-ou-editar-ong.js" defer></script>
</head>

<body>
    <header>
        <!-- Voltar pra HOME LOGADO (USER) -->
        <a href="minhas-ongs.php">
            <button id="btn-voltar">
                <img src="../images/seta-voltar.png" alt="Botão Voltar">voltar
            </button>
        </a>
    </header>

    <main>
        <section class="flex-container">
            <div id="paragrafo">
                <h1>Editar ONG</h1>
            </div>

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
                    <!-- Formulário -->
                    <form autocomplete="off" method="post" enctype="multipart/form-data">
                        <!-- OBS: O Chrome não está aceitando o autocomplete="off" em alguns casos, então uma forma para resolver isso é criar dois inputs, colocar o mesmo id para os dois e colocar o display: none no primeiro. -->

                        <!-- Input NOME -->
                        <div class="inputs">
                            <label for="ipt-nome">Nome</label>
                            <input type="text" id="ipt-nome" style="display: none;">
                            <input type="text" id="ipt-nome" name="nome" class="caixa-input campos" maxlength="255" placeholder="Digite o nome da sua ONG..." oninput="nameValidate()" value="<?php echo $nome ?>">

                            <span class="alerta" id="min-carac-nome">Mínimo de 3 caracteres</span>
                        </div>

                        <!-- Input ENDEREÇO -->
                        <div class="inputs">
                            <label for="ipt-endereco">Endereço</label>
                            <input type="text" name="endereco" id="ipt-endereco" class="caixa-input campos" placeholder="Ex: Rua Padre Inglês, 356 - Boa Vista, Recife" oninput="enderecoValidate()" value="<?php echo $endereco ?>">

                            <span class="alerta">Mínimo de 20 caracteres</span>
                        </div>

                        <!-- Input E-MAIL -->
                        <div class="inputs">
                            <label for="ipt-email">E-mail</label>
                            <input type="text" id="ipt-email" name="email" class="caixa-input campos" maxlength="345" placeholder="Ex: email@gmail.com" oninput="emailValidate()" value="<?php echo $email ?>">

                            <span class="alerta">E-mail inválido</span>
                        </div>

                        <!-- Input CONTATO -->
                        <div class="inputs">
                            <label for="ipt-contato">Contato</label>
                            <input type="text" id="telefone" name="contato" class="caixa-input campos" maxlength="15" placeholder="Número de contato da ONG..." oninput="numeroValidate()" value="<?php echo $contato ?>">

                            <span class="alerta">Número inválido</span>
                        </div>

                        <!-- Input CNPJ -->
                        <div class="inputs">
                            <label for="ipt-cnpj">CNPJ</label>
                            <input type="text" id="cnpj" name="cnpj" class="caixa-input campos" maxlength="18" placeholder="Ex: xx.xxx.xxx/xxxx-xx" oninput="cnpjValidate()" value="<?php echo $cnpj ?>">

                            <span class="alerta">CNPJ incompleto</span>
                        </div>

                        <!-- Input TIPO -->
                        <div class="inputs">
                            <label for="ipt-tipos">Finalidade</label>
                            <select id="select-finalidade" name="tipo-ong" class="caixa-input campos">
                                <?php foreach ($tipos as $indice => $tipo) {
                                    $indice = $indice + 1
                                ?>
                                    <option value="<?php echo $tipo['id_tipo_ong'] ?>" <?php if ($tipo['id_tipo_ong'] === $id_tipo_ong_atual) echo "selected" ?>><?php echo $indice . " - " . $tipo['tipo'] ?></option>
                                <?php } ?>
                            </select>

                            <span id="span-FinalidadeONG">Selecione uma opção</span>
                        </div>

                        <!-- Caixa Formas de Pagamento -->
                        <div class="inputs">
                            <p class="subtitulo">Formas de Pagamentos</p>
                            <div id="ipt-pagamento">
                                <!-- Pix -->
                                <div class="flex-container-pagamento">
                                    <label for="ipt-pix" class="label-pagameto">Pix</label>
                                    <input type="text" id="ipt-pix" name="chave-pix" class="campos" maxlength="32" placeholder="Digite seu pix..." value="<?php echo $chave_pix ?>">

                                    <span class="alerta" id="spanPix">Pix inválido</span>
                                </div>

                                <!-- Linha Divisória -->
                                <div id="flex-container-linha">
                                    <div class="linha-vertical"></div>
                                    <div id="linha-divisoria"></div>
                                    <div class="linha-vertical"></div>
                                </div>

                                <!-- Conta Bancária -->
                                <div class="flex-container-pagamento">
                                    <label class="label-pagameto">Conta Bancária</label>
                                    <div class="flex">
                                        <div class="alinhando-pagamentos">
                                            <div class="ipt-span-vertical">
                                                <!-- Número da conta -->
                                                <input type="text" name="conta" id="ipt-NumConta" class="ipt-cbancaria campos" maxlength="8" placeholder="Número da conta" value="<?php echo $conta ?>">

                                                <span class="alerta" id="spanNumConta">Número inválido</span>
                                            </div>

                                            <div class="ipt-span-vertical">
                                                <!-- Agência -->
                                                <input type="text" name="agencia" id="ipt-agencia" class="ipt-cbancaria campos" placeholder="Agência da conta" maxlength="4" oninput="agenciaValidate()" value="<?php echo $agencia ?>">

                                                <span class="alerta" id="spanAgencia">Agência inválida</span>
                                            </div>
                                        </div>
                                        <div class="alinhando-pagamentos">
                                            <div class="ipt-span-vertical">
                                                <!-- Instituição -->
                                                <input type="text" name="instituicao" id="ipt-Instituicao" class="ipt-cbancaria campos" placeholder="Instituição da conta" oninput="instituicaoValidate()" value="<?php echo $instituicao ?>">

                                                <span class="alerta" id="spanInstituicao">Instituição inválida</span>
                                            </div>


                                            <div class="ipt-span-vertical">
                                                <select id="select-conta" name="tipo-conta" class="ipt-cbancaria">
                                                    <?php foreach ($tipos_de_contas as $tipo_conta) { ?>
                                                        <option value="<?php echo $tipo_conta ?>" <?php if ($tipo_conta === $tipo_de_conta_atual) echo "selected" ?>><?php echo $tipo_conta ?></option>
                                                    <?php } ?>
                                                </select>

                                                <span id="span-TipoConta">Selecione uma opção</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Input REGIÃO-->
                        <div class="inputs">
                            <label for="select-regiao">Região</label>
                            <select id="select-regiao" name="regiao-ong">
                                <?php foreach ($regioes as $indice => $regiao) {
                                    $indice = $indice + 1 ?>
                                    <option value="<?php echo $regiao['id_regiao'] ?>" <?php if ($regiao['id_regiao'] === $id_regiao_atual) echo "selected" ?>><?php echo $indice . " - " . $regiao['nome_regiao'] ?></option>
                                <?php } ?>
                            </select>

                            <span id="span-regiao">Selecione uma opção</span>
                        </div>


                        <!-- Input DESCRIÇÃO -->
                        <div class="inputs">
                            <label for="ipt-descricao">Descrição</label>
                            <textarea id="ipt-descricao" name="descricao" rows="10" minlength="20" maxlength="500" placeholder="Descreva em até 500 caracteres..." oninput="descricaoValidate()"><?php echo $descricao ?></textarea>

                            <span class="alerta" id="min-carac">Mínimo de 20 caracteres</span>
                        </div>

                        <!-- Input FOTO -->
                        <div id="area-maior">
                            <div class="area-menor">
                                <div id="caixa-file">
                                    <input type="file" id="file" name="atualizar-foto">
                                    <label for="file">Trocar Foto</label>

                                    <img src="../icons/icone-lupa.svg" alt="Lupa" id="icon-lupa">
                                </div>

                                <div id="escolher-img">
                                    <img id="imagemPreview" src="<?php echo "../" . $foto ?>" alt="Prévia da imagem">
                                </div>

                                <span class="alerta" id="alerta-img">Foto não selecionada</span>
                            </div>
                        </div>
                        <span>Status:</span>
                        <input type="text" name="status" id="status" value="<?php if ($status === "aprovado") {
                                                                                echo "Aprovado";
                                                                            } else {
                                                                                echo "Análise";
                                                                            } ?>" readonly>
                        <p><strong>Adendo:</strong> ao clicar no botão <strong>EDITAR</strong>, a ong será enviada para análise novamente.</p>
                    <?php } ?>
                    <button type="submit" id="btn-enviar" onclick="verificar()">Editar</button>
                <?php } else { ?>
                    <h1>Ocorreu um erro ao procurar por essa ong... Volte e tente novamente.</h1>
                <?php } ?>
                    </form>

        </section>
    </main>

    <!--<footer>
        <a href="../pages/minhas-ongs.html">
            <button type="submit" id="btn-enviar" onclick="verificar()">Editar</button>
        </a>
    </footer> -->
</body>

</html>