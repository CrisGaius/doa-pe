<?php 
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['funcao']) || !$_SESSION['funcao']) {
    header("Location: home-logado.php");
    die();
}

require_once("../lib/conexao.php");
require_once("../lib/funcoes_uteis.php");

if(isset($_GET['id'])) {
    $id_ong = intval($_GET['id']);

    $sql_code_select_info_ong = "SELECT ongs.foto, ongs.nome, ongs.endereco, ongs.contato, ongs.cnpj, tipos_de_ongs.tipo, info_bancarias.chave_pix, info_bancarias.conta, info_bancarias.agencia, info_bancarias.instituicao, info_bancarias.tipo_de_conta, regioes.nome_regiao, ongs.email FROM ongs 
    JOIN informacoes_bancarias info_bancarias
    ON info_bancarias.id_ong = ongs.id_ong
    JOIN tipos_de_ongs
    ON ongs.id_tipo_ong = tipos_de_ongs.id_tipo_ong
    JOIN regioes
    ON ongs.id_regiao = regioes.id_regiao
    WHERE ongs.id_ong = $id_ong AND ongs.status = 'analise' LIMIT 1";

    $sql_query_select_info_ong = $pdo->prepare($sql_code_select_info_ong);
    $sql_query_select_info_ong->execute() or die("Erro ao consultar as informações da ong no banco de dados.");

    if($sql_query_select_info_ong->rowCount() > 0) {
        $dados = $sql_query_select_info_ong->fetch(PDO::FETCH_ASSOC);

        if(!$dados) {
            die("Erro ao guardar as informações do usuário");
        }
    }
} else {
    die("Id da ONG não foi passado.");
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
    <link rel="stylesheet" href="../styles/validar-ong.css">
</head>

<body>
    <header id="cabecalho" class="flex-container">
        <section id="caixa-voltar" class="flex-container">
            <a href="validar-usuario-ong.html">
                <img src="../icons/icone-voltar.svg" alt="Ícone de voltar" id="icone-voltar">
                <p>VOLTAR</p>
            </a>
        </section>
        <h1 i>TELA DE VALIDAÇÃO</h1>
    </header>
    
    <div class="container">
        <?php if($sql_query_select_info_ong->rowCount() > 0 && isset($dados) && $dados) {?>
            <div class="content">
                <div class="conteudo">
                    <p class="titulo"><?php echo strtoupper($dados['nome']) ?></p>
                    
                    <img src="<?php echo "../" . $dados['foto']?>">
                    <form class="formulario">

                        <p class="text-form">Nome ONG</p>
                        <label for="nome-reponsável"><input type="text" placeholder="Nome do Responsavel" value="<?php echo $dados['nome']?>" disabled></label>

                        <p class="text-form">Endereço</p>
                        <label for="endereço"><input type="text" placeholder="Endereço" value="<?php echo $dados['endereco']?>" disabled></label>

                        <p class="text-form">Email</p>
                        <label for="nome-reponsavel"><input type="text" placeholder="whatever" value="<?php echo $dados['email']?>" disabled></label>

                        <p class="text-form">Contato</p>
                        <label for="contato"><input type="text" placeholder="(81) 98888-8888" value="<?php echo formatar_numero($dados['contato']) ?>" disabled></label>

                        <p class="text-form">CNPJ</p>
                        <label for="cnpj"><input type="text" placeholder="00.000.000/0000-00" value="<?php echo formatar_cnpj($dados['cnpj']) ?>" disabled></label>

                        <p class="text-form">Tipo</p>
                        <label for="tipo"><input type="text" placeholder="Animais" value="<?php echo $dados['tipo']?>" disabled></label>

                        <!-- <p class="text-form">Forma de Pagamento</p>
                        <label for="nome-reponsavel"><input type="text" placeholder="Pix" disabled></label>
                        <p class="text-form">Informação da conta</p>
                        <label for="nome-reponsavel"><input type="text" placeholder="Agencia, Nº conta, Nome banco, Tipo conta" disabled></label> -->

                        <div class="inputs">
                            <p class="text-form">Formas de Pagamento</p>
                            <div id="ipt-pagamento">
                                <!-- Pix -->
                                <div class="flex-container-pagamento">
                                    <label for="ipt-pix" class="label-pagameto">Pix</label>
                                    <input type="text" id="ipt-pix" placeholder="Digite seu pix..." value="<?php echo $dados['chave_pix']?>" disabled>
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
                                            <input type="text" id="ipt-numero" class="ipt-cbancaria" placeholder="00000000-00" value="<?php echo formatar_conta($dados['conta']) ?>" disabled>
        
                                            <input type="text" id="ipt-agencia" class="ipt-cbancaria" placeholder="0000" value="<?php echo $dados['agencia']?>" disabled>
                                        </div>
                                        <div class="alinhando-pagamentos">
                                            <input type="text" id="ipt-instituicao" class="ipt-cbancaria" placeholder="CAIXA" value="<?php echo $dados['instituicao']?>" disabled>
        
                                            <input type="text" id="ipt-tipconta" class="ipt-cbancaria" placeholder="CORRENTE" value="<?php echo $dados['tipo_de_conta']?>" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            

                        <p class="text-form">Região</p>
                        <label for="nome-reponsavel"><input type="text" placeholder="Metropolitana" value="<?php echo $dados['nome_regiao']?>" disabled></label>
                        <div class="botao">
                            <button class="btn-recusar" value=""><a href="deletar_ong_admin.php?id=<?php echo $id_ong?>">Recusar</a></button>
                            <button class="btn-aceitar" value=""><a href="aceitar_ong_admin.php?id=<?php echo $id_ong ?>">Aceitar</a></button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else {?>
            <h1 style="margin-top: 30px;"><strong>Nenhuma ONG encontrada!</strong></h1>
        <?php }?>
    </div>
</body>

</html>