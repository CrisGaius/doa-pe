<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: home-logado.php");
    die();
}

require_once("../lib/conexao.php");
require_once("../lib/funcoes_uteis.php");

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

    if (count($_POST) > 0) {
        $erro = false;
        if (strlen($_POST['nome']) >= 3 && strlen($_POST['endereco']) >= 20 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['contato']) && !empty($_POST['cnpj']) && in_array($_POST['tipo-ong'], $id_tipos_ongs) && strlen($_POST['chave-pix']) >= 11 && !empty($_POST['conta']) && in_array($_POST['tipo-conta'], $tipos_de_contas) && strlen($_POST['instituicao']) > 5 && strlen($_POST['agencia']) === 4 && in_array($_POST['regiao-ong'], $id_regioes) && strlen($_POST['descricao']) <= 500) {

            $contato = preg_replace("/[^0-9]/", "", $_POST['contato']);
            if (strlen($contato) < 10 || strlen($contato) > 11) {
                $erro = true;
            }

            $cnpj = preg_replace("/[^0-9]/", "", $_POST['cnpj']);
            if (strlen($cnpj) !== 14) {
                $erro = true;
            }

            $sql_code_select_cnpj = "SELECT cnpj FROM ongs WHERE cnpj = :cnpj LIMIT 1";

            $sql_query_select_cnpj = $pdo->prepare($sql_code_select_cnpj);
            $sql_query_select_cnpj->bindValue(":cnpj", $cnpj, PDO::PARAM_STR);
            $sql_query_select_cnpj->execute();

            if ($sql_query_select_cnpj->rowCount() !== 0) {
                $mensagem_erro = "Cnpj já em uso!";
                $erro = true;
            }

            $conta = preg_replace("/[^0-9]/", "", $_POST['conta']);
            if (strlen($conta) !== 8) {
                $erro = true;
            }

            $foto = $_FILES['foto'];

            if (empty($foto['name'])) {
                $mensagem_erro = "Imagem não pode ser vazia!";
                $erro = true;
            }

            if (!$erro) {
                $nome = $_POST['nome'];
                $endereco = $_POST['endereco'];
                $email = $_POST['email'];
                $tipo_ong = $_POST['tipo-ong'];
                $regiao_ong = $_POST['regiao-ong'];
                $descricao = $_POST['descricao'];

                $caminho = enviar_imagens(true, $foto['error'], $foto['size'], $foto['name'], $foto['tmp_name']);

                if ($caminho) {
                    $sql_code_insert_ong = "INSERT INTO ongs (id_ong, id_regiao, id_tipo_ong, id_usuario, nome, email, contato, cnpj, descricao, foto, endereco) VALUES (NULL, :id_regiao, :id_tipo_ong, :id_usuario, :nome, :email, :contato, :cnpj, :descricao, :foto, :endereco)";

                    $sql_query_insert_ong = $pdo->prepare($sql_code_insert_ong);

                    $sql_query_insert_ong->bindValue(":id_regiao", $regiao_ong, PDO::PARAM_INT);
                    $sql_query_insert_ong->bindValue(":id_tipo_ong", $tipo_ong, PDO::PARAM_INT);
                    $sql_query_insert_ong->bindValue(":id_usuario", $_SESSION['id_usuario'], PDO::PARAM_INT);
                    $sql_query_insert_ong->bindValue(":nome", $nome, PDO::PARAM_STR);
                    $sql_query_insert_ong->bindValue(":email", $email, PDO::PARAM_STR);
                    $sql_query_insert_ong->bindValue(":contato", $contato, PDO::PARAM_STR);
                    $sql_query_insert_ong->bindValue(":cnpj", $cnpj, PDO::PARAM_STR);
                    $sql_query_insert_ong->bindValue(":descricao", $descricao, PDO::PARAM_STR);
                    $sql_query_insert_ong->bindValue(":foto", $caminho, PDO::PARAM_STR);
                    $sql_query_insert_ong->bindValue(":endereco", $endereco, PDO::PARAM_STR);

                    if ($sql_query_insert_ong->execute()) {
                        $id_ong = intval($pdo->lastInsertId());

                        if ($id_ong) {
                            $chave_pix = $_POST['chave-pix'];
                            $tipo_conta = $_POST['tipo-conta'];
                            $instituicao = $_POST['instituicao'];
                            $agencia = $_POST['agencia'];

                            $sql_code_insert_info_bancarias = "INSERT INTO informacoes_bancarias (id_info_bancarias, id_ong, chave_pix, conta, agencia, instituicao, tipo_de_conta) VALUES (NULL, :id_ong, :chave_pix, :conta, :agencia, :instituicao, :tipo_conta)";

                            $sql_query_insert_info_bancarias = $pdo->prepare($sql_code_insert_info_bancarias);

                            $sql_query_insert_info_bancarias->bindValue(":id_ong", $id_ong, PDO::PARAM_STR);
                            $sql_query_insert_info_bancarias->bindValue(":chave_pix", $chave_pix, PDO::PARAM_STR);
                            $sql_query_insert_info_bancarias->bindValue(":conta", $conta, PDO::PARAM_STR);
                            $sql_query_insert_info_bancarias->bindValue(":agencia", $agencia, PDO::PARAM_STR);
                            $sql_query_insert_info_bancarias->bindValue(":instituicao", $instituicao, PDO::PARAM_STR);
                            $sql_query_insert_info_bancarias->bindValue(":tipo_conta", $tipo_conta, PDO::PARAM_STR);

                            if ($sql_query_insert_info_bancarias->execute()) {
                                $erro = false;
                                unset($_POST);
                                header("Location: analise-dados-ong.html");
                            } else {
                                $erro = true;
                                $mensagem_erro = "Erro ao inserir infos bancárias!";
                            }
                        } else {
                            $erro = true;
                            $mensagem_erro = "Erro ao pegar o último id!";
                        }
                    } else {
                        $erro = true;
                        $mensagem_erro = "Erro ao inserir dados da ong!";
                    }
                } else {
                    $erro = true;
                    $mensagem_erro = "Erro ao enviar imagem!";
                }
            }
        } else {
            $erro = true;
            $mensagem_erro = "Erro no preenchimento de dados!";
        }
    }
} else {
    $mensagem_erro = "Erro ao selecionar tipos e regiões!";
    $erro = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex"> <!-- Esse meta serva para retirar da pesquisa do google -->
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">

    <title>Cadastrar ONG | Doa PE</title>
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="stylesheet" href="../styles/cadastrar-ou-editar-ong.css">
    <script src="../scripts/cadastrar-ou-editar-ong.js" defer></script>
</head>

<body>
    <header>
        <!-- Voltar pra HOME LOGADO (USER) -->
        <a href="../index-deslogado.html">
            <button id="btn-voltar">
                <img src="../icons/icone-voltar.svg" id="seta-voltar" alt="Botão Voltar">voltar
            </button>
        </a>
    </header>

    <main>
        <section class="flex-container">
            <div id="paragrafo">
                <h1>Cadastrar ONG</h1>
            </div>

            <?php if ($sql_query_select_tipos->rowCount() > 0 && $sql_query_select_regioes->rowCount() > 0) { ?>
                <!-- Formulário -->
                <form method="post" enctype="multipart/form-data" autocomplete="off">


                    <!-- Input NOME -->
                    <div class="inputs">
                        <label for="ipt-nome">Nome</label>
                        <input type="text" name="nome" id="ipt-nome" class="caixa-input campos" maxlength="255" placeholder="Digite o nome da sua ONG..." oninput="nameValidate()" value="<?php if (isset($_POST['nome'])) echo $_POST['nome'] ?>">

                        <span class="alerta" id="min-carac-nome">Mínimo de 3 caracteres</span>
                    </div>

                    <!-- Input ENDEREÇO -->
                    <div class="inputs">
                        <label for="ipt-endereco">Endereço</label>
                        <input type="text" name="endereco" id="ipt-endereco" class="caixa-input campos" placeholder="Ex: Rua Padre Inglês, 356 - Boa Vista, Recife" oninput="enderecoValidate()" value="<?php if (isset($_POST['endereco'])) echo $_POST['endereco'] ?>">

                        <span class="alerta">Mínimo de 20 caracteres</span>
                    </div>

                    <!-- Input E-MAIL -->
                    <div class="inputs">
                        <label for="ipt-email">E-mail</label>
                        <input type="text" name="email" id="ipt-email" class="caixa-input campos" maxlength="345" placeholder="Ex: email@gmail.com" oninput="emailValidate()" value="<?php if (isset($_POST['email'])) echo $_POST['email'] ?>">

                        <span class="alerta">E-mail inválido</span>
                    </div>

                    <!-- Input CONTATO -->
                    <div class="inputs">
                        <label for="ipt-contato">Contato</label>
                        <input type="text" id="telefone" name="contato" class="caixa-input campos" maxlength="15" placeholder="Número de contato da ONG..." oninput="numeroValidate()" value="<?php if (isset($_POST['contato'])) echo formatar_numero($_POST['contato']) ?>">

                        <span class="alerta">Número inválido</span>
                    </div>

                    <!-- Input CNPJ -->
                    <div class="inputs">
                        <label for="ipt-cnpj">CNPJ</label>
                        <input type="text" id="cnpj" name="cnpj" class="caixa-input campos" maxlength="18" placeholder="Ex: xx.xxx.xxx/xxxx-xx" oninput="cnpjValidate()" value="<?php if (isset($_POST['cnpj'])) echo formatar_cnpj($_POST['cnpj']) ?>">

                        <span class="alerta">CNPJ incompleto</span>
                    </div>

                    <!-- Input TIPO -->
                    <div class="inputs">
                        <label for="ipt-tipos">Finalidade</label>
                        <select id="select-finalidade" name="tipo-ong" class="caixa-input campos">
                            <?php foreach ($tipos as $indice => $tipo) {
                                $indice = $indice + 1
                            ?>
                                <option value="<?php echo $tipo['id_tipo_ong'] ?>"><?php echo $indice . " - " . $tipo['tipo'] ?></option>
                            <?php } ?>
                        </select>

                        <!-- <span id="span-FinalidadeONG">Selecione uma opção</span> -->
                    </div>

                    <!-- Caixa Formas de Pagamento -->
                    <div class="inputs">
                        <p class="subtitulo">Formas de Pagamentos</p>
                        <div id="ipt-pagamento">
                            <!-- Pix -->
                            <div class="flex-container-pagamento">
                                <label for="ipt-pix" class="label-pagameto">Pix</label>
                                <input type="text" name="chave-pix" id="ipt-pix" class="campos" maxlength="32" placeholder="Digite seu pix..."  value="<?php if (isset($_POST['chave-pix'])) echo $_POST['chave-pix'] ?>">

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
                                            <input type="text" name="conta" id="ipt-NumConta" class="ipt-cbancaria campos" maxlength="8" placeholder="Número da conta" value="<?php if (isset($_POST['conta'])) echo formatar_conta($_POST['conta']) ?>">

                                            <span class="alerta" id="spanNumConta">Número inválido</span>
                                        </div>

                                        <div class="ipt-span-vertical">
                                            <!-- Agência -->
                                            <input type="text" name="agencia" id="ipt-agencia" class="ipt-cbancaria campos" placeholder="Agência da conta" maxlength="4" value="<?php if (isset($_POST['agencia'])) echo $_POST['agencia'] ?>">

                                            <span class="alerta" id="spanAgencia">Agência inválida</span>
                                        </div>
                                    </div>
                                    <div class="alinhando-pagamentos">
                                        <div class="ipt-span-vertical">
                                            <!-- Instituição -->
                                            <input type="text" name="instituicao" id="ipt-Instituicao" class="ipt-cbancaria campos" placeholder="Instituição da conta" value="<?php if (isset($_POST['instituicao'])) echo $_POST['instituicao'] ?>">

                                            <span class="alerta" id="spanInstituicao">Instituição inválida</span>
                                        </div>


                                        <div class="ipt-span-vertical">
                                            <select id="select-conta" name="tipo-conta" class="ipt-cbancaria">
                                                <?php foreach ($tipos_de_contas as $tipo_conta) { ?>
                                                    <option value="<?php echo $tipo_conta ?>"><?php echo $tipo_conta ?></option>
                                                <?php } ?>
                                            </select>

                                            <!-- <span id="span-TipoConta">Selecione uma opção</span> -->
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
                                <option value="<?php echo $regiao['id_regiao'] ?>"><?php echo $indice . " - " . $regiao['nome_regiao'] ?></option>
                            <?php } ?>
                        </select>

                        <!-- <span id="span-regiao">Selecione uma opção</span> -->
                    </div>


                    <!-- Input DESCRIÇÃO -->
                    <div class="inputs">
                        <label for="ipt-descricao">Descrição</label>
                        <textarea id="ipt-descricao" name="descricao" rows="10" minlength="20" maxlength="500" placeholder="Descreva em até 500 caracteres..." oninput="descricaoValidate()"><?php if (isset($_POST['descricao'])) echo $_POST['descricao'] ?></textarea>

                        <span class="alerta" id="min-carac">Mínimo de 20 caracteres</span>
                    </div>

                    <!-- Input FOTO -->
                    <div id="area-maior">
                        <div class="area-menor">
                            <div id="caixa-file">
                                <input type="file" name="foto" id="file">
                                <label for="file">Procurar Foto</label>

                                <img src="../icons/icone-lupa.svg" alt="Lupa" id="icon-lupa">
                            </div>

                            <div id="escolher-img">
                                <img id="imagemPreview" src="" alt="Prévia da imagem">
                            </div>

                            <span class="alerta" id="alerta-img">Foto não selecionada</span>
                        </div>
                    </div>
                    <div id="aviso">
                        <p>* Por favor, antes de enviar o fomulário, certifique-se que os campos foram respondidos corretamente. Caso contrário, sua ONG poderá não ser aprovado.</p>
                    </div>
                    <button type="submit" id="btn-enviar">Enviar</button>
                </form>
            <?php } else { ?>
                <h1>Algo deu errado...</h1>
            <?php } ?>
            <?php if (isset($erro) && $erro) { ?>
                <section id="acerto">
                    <div id="conteudo-acerto" style="background-color: var(--vermelho);">
                        <?php if (isset($mensagem_erro)) { ?>
                            <p><strong><?php echo $mensagem_erro ?></strong></p>
                        <?php } else { ?>
                            <p><strong>Erro ao enviar dados!</strong></p>
                        <?php } ?>
                        <img id="botao-fechar" src="../icons/icone-fechar.svg" alt="ícone de fechar">
                    </div>
                </section>
                <script>
                    const botaoFecharCaixaAcerto = document.querySelector("img#botao-fechar")

                    botaoFecharCaixaAcerto.addEventListener('click', () => {
                        const sectionAcerto = document.querySelector('section#acerto')

                        sectionAcerto.style.display = 'none'
                    })
                </script>
            <?php } ?>

        </section>
    </main>


    <script>
        // Mostrar prévia da foto
        const inputImgEspecifico = document.getElementById('file');
        const previaImgEspecifica = document.getElementById('imagemPreview');
        // Deixa vázia a caixa da imagem, sem aparecer o ícone de quando uma imagem não é selecionada
        previaImgEspecifica.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";

        inputImgEspecifico.addEventListener('change', function() {
            const file = inputImgEspecifico.files[0]; // Obtém o arquivo selecionado

            if (file) {
                const reader = new FileReader();

                // Define uma função para ser executada quando o arquivo for lido
                reader.onload = function(e) {
                    previaImgEspecifica.src = e.target.result; // Define o src da tag <img> com o conteúdo da imagem
                };

                // Lê o arquivo como uma URL de dados
                reader.readAsDataURL(file);
            } else {
                // Define a imagem de espaço reservado vazia se nenhum arquivo for selecionado
                previaImgEspecifica.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
            }
        });
    </script>
</body>

</html>