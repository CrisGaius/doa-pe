<?php 
    require_once ("lib/conexao.php");

    if (isset($_GET['id'])) {
        $id_ong = intval($_GET['id']);

        $sql_code_select_info_ongs = "SELECT ongs.nome, ongs.email FROM ongs WHERE ongs.id_ong = $id_ong LIMIT 1"; 
        // AND ongs.id_usuario = $id_usuario --> para verificar se aquele usuário realmente cadastrou aquela ong. obs: antes do limit.

        $sql_query_select_info_ongs = $pdo->prepare($sql_code_select_info_ongs);
        $sql_query_select_info_ongs->execute() or die("Erro ao consultar as informações da ONG no banco de dados.");
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
    <form action="" method="post">
        <?php if (isset($sql_query_select_info_ongs) && $sql_query_select_info_ongs->rowCount() > 0) {?>
            <?php foreach ($sql_query_select_info_ongs as $dados) {
                $nome = $dados['nome'];
                $email = $dados['email'];?>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo "$nome" ?>">
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" id="endereco" value="Falta atualizar no banco...">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo "$email"?>">
        <?php }?>
        <button>Atualizar</button>
        <?php } else {?>
            <h1>Ocorreu um erro ao procurar por essa ong... Volte e tente novamente.</h1>
        <?php }?>
    </form>
</body>
</html>