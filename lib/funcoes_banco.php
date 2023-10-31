<?php

# colocar esse arquivo ou não? se atualizar algo no arquivo principal, esse aqui não será mudado e terei que mudar em dois lugares. Por enquanto, só deixei no outro.
function buscar_tipos_de_ongs($pdo)
{
    $sql_code_select_tipos = "SELECT id_tipo_ong, tipo FROM tipos_de_ongs ORDER BY id_tipo_ong";
    $sql_query_select_tipos = $pdo->prepare($sql_code_select_tipos);
    $sql_query_select_tipos->execute() or die("Erro ao pesquisar os tipos de ongs.");

    return $sql_query_select_tipos;
}

function buscar_regioes($pdo)
{
    $sql_code_select_regioes = "SELECT id_regiao, nome_regiao FROM regioes ORDER BY id_regiao";
    $sql_query_select_regioes = $pdo->prepare($sql_code_select_regioes);
    $sql_query_select_regioes->execute() or die("Erro ao pesquisar as regiões");

    return $sql_query_select_regioes;
}

function buscar_caminho_antigo_imagem($id_ong, $pdo)
{
    $sql_code_select_caminho_antigo = "SELECT foto FROM ongs WHERE id_ong = $id_ong LIMIT 1";
    $sql_query_select_caminho_antigo = $pdo->prepare($sql_code_select_caminho_antigo);
    $sql_query_select_caminho_antigo->execute();

    return $sql_query_select_caminho_antigo;
}

function puxar_atualizar_imagem($id_ong, $caminho, $pdo)
{
    $sql_code_update_foto = "UPDATE ongs 
                            SET foto = '$caminho'
                            WHERE id_ong = $id_ong 
                            LIMIT 1";
    $sql_query_update_foto = $pdo->prepare($sql_code_update_foto);

    return $sql_query_update_foto;
}

function puxar_atualizar_ong($id_ong, $nome, $endereco, $email, $contato, $cnpj, $tipo_ong, $regiao_ong, $descricao, $pdo)
{
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

    return $sql_query_update_ong;
}

function puxar_atualizar_info_bancarias($id_ong, $chave_pix, $conta, $agencia) {} // Continuar se sentir a necessidade.
