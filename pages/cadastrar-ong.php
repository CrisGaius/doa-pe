    <?php


    require_once("lib/conexao.php");


    if(count($_POST) > 0){

        $erro = false;

        $nome = $_POST['nome'];
        $endereco = $_POST['endereco'];
        $email = $_POST['email'];
        $contato = $_POST['contato'];
        $cnpj = $_POST['cnpj'];
        $tipoOng = $_POST['tipo-ong'];
        $pix = $_POST['pix'];
        $numeroConta = $_POST['numero-conta'];
        $agenciaConta = $_POST['agencia-conta'];
        $instituicao = $_POST['instituicao'];
        $tipoConta = $_POST['tipo-conta'];
        $regiao = $_POST['regiao'];
        $descricao = $_POST['descricao'];
        $foto = $_FILES['foto'];


        if (empty($nome) || empty($endereco) || empty($email) || empty($contato) || empty($cnpj) || $tipoOng === "FinalidadeONG" ||
            empty($pix) || empty($numeroConta) || empty($agenciaConta) || empty($instituicao) || $tipoConta === "tipoConta" || $regiao === "SelecioneRegiao" || empty($descricao)) {
            $erro = true;
        }

        if(strlen($nome) < 5){
            $erro = true;
        }

        if(strlen($contato) < 11){
            $erro = true;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro = true;
        }

        if (empty($_FILES['foto']['name'])) {
            $erro = true;
        }

        $stmtCnpj = $pdo->prepare("SELECT cnpj FROM ongs WHERE cnpj = :cnpj");
        $stmtCnpj->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
        $stmtCnpj->execute();
        $rowCountCnpj = $stmtCnpj->rowCount();

        if ($rowCountCnpj > 0) {
            $erro = true;
            echo "CNPJ já cadastrado no sistema.";
        }


        if(!erro || !$rowCountCnpj > 0){
            $stmtInfoBancarias = $pdo->prepare("INSERT INTO informacoes_bancarias(chave_pix, conta, agencia, instituicao, tipo_de_conta) VALUES (:chave_pix, :conta, :agencia, :instituicao, :tipo_de_conta)");
                
                $stmtInfoBancarias->bindParam(':chave_pix', $pix, PDO::PARAM_STR);
                $stmtInfoBancarias->bindParam(':conta', $numeroConta, PDO::PARAM_STR);
                $stmtInfoBancarias->bindParam(':agencia', $agenciaConta, PDO::PARAM_STR);
                $stmtInfoBancarias->bindParam(':instituicao', $instituicao, PDO::PARAM_STR);
                $stmtInfoBancarias->bindParam(':tipo_de_conta', $tipoConta, PDO::PARAM_STR);

                $stmtInfoBancarias->execute();

            $stmtRegiao = $pdo->prepare("INSERT INTO regioes(nome_regiao) VALUES (:nome_regiao)");
                
                $stmtRegiao->bindParam(':nome_regiao', $regiao, PDO::PARAM_STR);
                $stmtRegiao->execute();

            $stmtTipoOng = $pdo->prepare("INSERT INTO tipos_de_ongs(tipo) VALUES (:tipo)");
                
                $stmtTipoOng->bindParam(':tipo', $tipoOng, PDO::PARAM_STR);
                $stmtTipoOng->execute();

            $stmtOng = $pdo->prepare("INSERT INTO ongs (nome, endereco, contato, email, descricao, cnpj) 
                VALUES (:nome, :endereco, :contato, :email, :descricao, :cnpj)");

                $stmtOng->bindParam(':nome', $nome, PDO::PARAM_STR);
                $stmtOng->bindParam(':endereco', $endereco, PDO::PARAM_STR);
                $stmtOng->bindParam(':contato', $contato, PDO::PARAM_STR);
                $stmtOng->bindParam(':email', $email, PDO::PARAM_STR);
                $stmtOng->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                $stmtOng->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
                $stmtOng->execute();
            
        }

        if($erro){
            echo "<p>Preencha corretamente os campos corretamente.<p>";
        } else{
            header("Location: caminho.php");
        }
    }
    ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex"> <!-- Esse meta serva para  -->
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

            <!-- Formulário -->
            <form>

                <!-- Input NOME -->
                <div class="inputs">
                    <label for="ipt-nome">Nome</label>
                    <input type="text" name="nome" id="ipt-nome" class="caixa-input campos" maxlength="255" placeholder="Digite o nome da sua ONG..." oninput="nameValidate()">

                    <span class="alerta" id="min-carac-nome">Mínimo de 3 caracteres</span>
                </div>
            
                <!-- Input ENDEREÇO -->
                <div class="inputs">
                    <label for="ipt-endereco">Endereço</label>
                    <input type="text" name="endereco" id="ipt-endereco" class="caixa-input campos" placeholder="Ex: Rua Padre Inglês, 356 - Boa Vista, Recife" oninput="enderecoValidate()">

                    <span class="alerta">Mínimo de 20 caracteres</span>
                </div>
                
                <!-- Input E-MAIL -->
                <div class="inputs">
                    <label for="ipt-email">E-mail</label>
                    <input type="text" name="email" id="ipt-email" class="caixa-input campos" maxlength="345" placeholder="Ex: email@gmail.com" oninput="emailValidate()">

                    <span class="alerta">E-mail inválido</span>
                </div>
                
                <!-- Input CONTATO -->
                <div class="inputs">
                    <label for="ipt-contato">Contato</label>
                    <input type="text" id="telefone" name="contato" class="caixa-input campos" maxlength="15" placeholder="Número de contato da ONG..." oninput="numeroValidate()">

                    <span class="alerta">Número inválido</span>
                </div>

                <!-- Input CNPJ -->
                <div class="inputs">
                    <label for="ipt-cnpj">CNPJ</label>
                    <input type="text" id="cnpj" name="cnpj" class="caixa-input campos" maxlength="18" placeholder="Ex: xx.xxx.xxx/xxxx-xx" oninput="cnpjValidate()">

                    <span class="alerta">CNPJ incompleto</span>
                </div>

                <!-- Input TIPO -->
                <div class="inputs">
                    <label for="ipt-tipos">Finalidade</label>
                    <select id="select-finalidade" name="tipo-ong" class="caixa-input campos">
                        <optgroup label="Escolha">
                            <option value="FinalidadeONG">Finalidade da ONG</option>
                            <option value="cuidaMeioAmb">Cuida do meio ambiente</option>
                            <option value="cuidaAnimAban">Cuida de animais abandonados</option>
                            <option value="cuidaCrianAdol">Cuida de crianças e adolescentes</option>
                            <option value="refeicao">Faz refeição</option>
                            <option value="distribuiRoupa">Distribuição de roupas</option>
                        </optgroup>
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
                            <input type="text" id="ipt-pix" name="pix" class="campos"  maxlength="32" placeholder="Digite seu pix..." oninput="pixValidate()">

                            <span class="alerta">Pix inválido</span>
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
                                        <input type="text" name="numero-conta" class="ipt-cbancaria campos" maxlength="8" placeholder="Número da conta" oninput="numcontaValidate()">

                                        <span class="alerta">Número inválido</span>
                                    </div>

                                    <div class="ipt-span-vertical">
                                        <input type="text" name="agencia-conta" class="ipt-cbancaria campos" placeholder="Agência da conta" maxlength="4" oninput="agenciaValidate()">

                                        <span class="alerta">Agência inválida</span>
                                    </div>
                                </div>
                                <div class="alinhando-pagamentos">
                                    <div class="ipt-span-vertical">
                                        <input type="text" name="instituicao" class="ipt-cbancaria campos" placeholder="Instituição da conta" oninput="instituicaoValidate()">

                                        <span class="alerta">Instituição inválida</span>
                                    </div>

                                    
                                    <div class="ipt-span-vertical">
                                        <select id="select-conta" name="tipo-conta" class="ipt-cbancaria">
                                            <option id="option-fisrt-conta" value="tipoConta">Tipo da conta</option>
                                            <option value="contaCorrente">Conta corrente</option>
                                            <option value="contaPoupanca">Conta poupança</option>
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
                    <select id="select-regiao"  name="regiao">
                        <option value="SelecioneRegiao">Selecione uma região</option>
                        <option value="">Zona da Mata</option>
                        <option value="">Metropolitana</option>
                        <option value="">Agreste</option>
                        <option value="">Sertão</option>
                        <option value="">São Francisco</option>
                    </select>

                    <span id="span-regiao">Selecione uma opção</span>
                </div>
                

                <!-- Input DESCRIÇÃO -->
                <div class="inputs">
                    <label for="ipt-descricao">Descrição</label>
                    <textarea id="ipt-descricao" name="descricao" rows="10" minlength="20" maxlength="500" placeholder="Descreva em até 500 caracteres..." oninput="descricaoValidate()"></textarea>

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
            </form>

            <div id="aviso">
                <p>* Por favor, antes de enviar o fomulário, certifique-se que os campos foram respondidos corretamente. Caso contrário, sua ONG poderá não ser aprovado.</p>
            </div>
        </section>
    </main>

    <footer>
        <a href="../index-deslogado.html">
            <button type="submit" id="btn-enviar">Enviar</button>
        </a>
    </footer>
</body>
</html>