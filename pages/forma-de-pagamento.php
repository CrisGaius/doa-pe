<?php 
    require_once("../lib/conexao.php");
    require_once("../lib/funcoes_uteis.php");

    if(isset($_GET['id'])) {
        $erro = false;
        $id_ong = intval($_GET['id']);

        $sql_code_select_info_bancarias = "SELECT chave_pix, instituicao, agencia, conta, tipo_de_conta FROM informacoes_bancarias WHERE id_ong = :id_ong LIMIT 1";
        $sql_query_select_info_bancarias = $pdo->prepare($sql_code_select_info_bancarias);

        $sql_query_select_info_bancarias->bindValue(":id_ong", $id_ong, PDO::PARAM_INT);

        $sql_query_select_info_bancarias->execute() or die("Erro ao selecionar as informações da ong");

        if($sql_query_select_info_bancarias->rowCount() > 0) {
            $info_bancarias = $sql_query_select_info_bancarias->fetch(PDO::FETCH_ASSOC);
        } else {
            $erro = true;
        }

    } else {
        $erro = true;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/forma-de-pagamento.css">
    <link rel="stylesheet" href="../styles/config.css">
    <link rel="shortcut icon" href="../favicon/fav.ico" type="image/x-icon">
    <title>Formas de Pagamento | DOA PE</title>
</head>
<body>
    <header id="cabecalho" class="flex-container">
        <section id="caixa-voltar" class="flex-container">
            <img src="../icons/icone-voltar.svg" alt="Ícone de voltar" id="icone-voltar">
            <p>VOLTAR</p>
        </section>
        <div id="titulo-cabecalho" class="flex-container">
            <h1>FORMAS DE PAGAMENTO</h1>
            <img src="../icons/icone-dinheiro.svg" alt="Icone de Dinheiro">
        </div>
    </header>
    <main>
        <?php if (isset($erro) && !$erro && isset($info_bancarias)) {?>
        <section id="sct-pagamento" class="flex-container">
            <div class="flex-container titulo-forma">
                <img src="../icons/icone-pix.svg" alt="Ícone Pix">
                <h1>PIX</h1>
            </div>
            <div class="inputs">
                <label for="chave">Chave</label>
                <input readonly type="text" name="chave" id="chave" value="<?php echo $info_bancarias['chave_pix'] ?>">
                <button id="copiar">Copiar</button>
            </div>
            <div class="flex-container titulo-forma">
                <img src="../icons/icone-banco.svg" alt="Icone Banco">
                <h1>TRANFERÊNCIA BANCÁRIA</h1>
            </div>
            <div class="inputs" id="transf-bank">
                <label for="instituicao">Instituição</label>
                <input readonly type="text" name="instituicao" id="instituicao" value="<?php echo $info_bancarias['instituicao']?>">
                <div id="conta-agencia">
                    <div>
                        <label for="agencia">Agência</label>
                        <input readonly type="text" name="agencia" id="agencia" value="<?php echo $info_bancarias['agencia']?>">
                    </div>
                    <div>
                        <label for="conta">Conta</label>
                        <input readonly type="text" name="conta" id="conta" value="<?php echo formatar_conta($info_bancarias['conta'])?>">
                    </div>
                </div>
                <label for="tipo-de-conta">Tipo de Conta</label>
                <input readonly type="text" name="tipo-de-conta" id="tipo-de-conta" value="<?php echo $info_bancarias['tipo_de_conta']?>">
            </div>
        </section>
        <?php } else { ?>
            <h1 id="erro">Erro ao selecionar as formas de pagamento da ong...</h1>
        <?php }?>
    </main>
    <script src="../scripts/forma-de-pagamento.js"></script>
</body>
</html>