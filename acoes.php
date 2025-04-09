<?php
session_start();
require 'sistema/conexao.php';

/** CRIAÇÃO DE USUÁRIO **/
if (isset($_POST['create_usuario'])) {
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $senha = isset($_POST['senha']) ? mysqli_real_escape_string($conexao, password_hash(trim($_POST['senha']), PASSWORD_DEFAULT)) : '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (empty($nome) || empty($email) || empty($data_nascimento) || empty($senha)) {
        $_SESSION['mensagem'] = "Não foi possível incluir um usuário no banco.";
        header("Location: usuarios/usuarios.php");
        exit;
    }

    $sql = "INSERT INTO usuarios (nome, email, data_nascimento, senha, is_admin) VALUES ('$nome', '$email', '$data_nascimento', '$senha', '$is_admin')";

    if (mysqli_query($conexao, $sql) && mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Usuário adicionado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Não foi possível incluir um usuário no banco.";
    }

    header("Location: usuarios/usuarios.php");
    exit;
}

/** ATUALIZAÇÃO DE USUÁRIO **/
if (isset($_POST['update_usuario'])) {
    $usuario_id = mysqli_real_escape_string($conexao, $_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $senha = mysqli_real_escape_string($conexao, trim($_POST['senha']));
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', data_nascimento = '$data_nascimento', is_admin = '$is_admin'";

    if (!empty($senha)) {
        $sql .= ", senha='" . password_hash($senha, PASSWORD_DEFAULT) . "'";
    }

    $sql .= " WHERE id = '$usuario_id'";

    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Usuário atualizado com sucesso';
    } else {
        $_SESSION['mensagem'] = 'Usuário não foi atualizado';
    }

    header('Location: usuarios/usuarios.php');
    exit;
}

/** EXCLUSÃO DE USUÁRIO **/
if (isset($_POST['delete_usuario'])) {
    $usuario_id = mysqli_real_escape_string($conexao, $_POST['delete_usuario']);
    $sql = "DELETE FROM usuarios WHERE id = '$usuario_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Usuário excluído com sucesso';
    } else {
        $_SESSION['mensagem'] = 'Usuário não foi excluído';
    }

    header('Location: usuarios/usuarios.php');
    exit;
}

/** CRIAÇÃO DE PRODUTO NO ESTOQUE **/
if (isset($_POST['create_estoque'])) {
    $nome_produto = mysqli_real_escape_string($conexao, trim($_POST['nome_produto']));
    $quantidade = mysqli_real_escape_string($conexao, trim($_POST['quantidade']));
    $preco_unitario = mysqli_real_escape_string($conexao, trim($_POST['preco_unitario']));

    if (empty($nome_produto) || empty($quantidade) || empty($preco_unitario)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        header("Location: estoque/estoque-create.php");
        exit;
    }

    $sql = "INSERT INTO estoque (nome_produto, quantidade, preco_unitario) VALUES ('$nome_produto', '$quantidade', '$preco_unitario')";

    if (mysqli_query($conexao, $sql) && mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Produto adicionado ao estoque com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao adicionar produto ao estoque.";
    }

    header("Location: estoque/estoque.php");
    exit;
}

/** ATUALIZAÇÃO DE PRODUTO NO ESTOQUE **/
if (isset($_POST['update_estoque'])) {
    $estoque_id = mysqli_real_escape_string($conexao, $_POST['estoque_id']);
    $nome_produto = mysqli_real_escape_string($conexao, trim($_POST['nome_produto']));
    $quantidade = mysqli_real_escape_string($conexao, trim($_POST['quantidade']));
    $preco_unitario = mysqli_real_escape_string($conexao, trim($_POST['preco_unitario']));

    if (empty($nome_produto) || empty($quantidade) || empty($preco_unitario)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        header("Location: estoque/estoque-edit.php?id=$estoque_id");
        exit;
    }

    $sql = "UPDATE estoque SET nome_produto = '$nome_produto', quantidade = '$quantidade', preco_unitario = '$preco_unitario' WHERE id = '$estoque_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Produto não foi atualizado.";
    }

    header("Location: estoque/estoque.php");
    exit;
}

/** EXCLUSÃO DE PRODUTO DO ESTOQUE **/
if (isset($_POST['delete_estoque'])) {
    $estoque_id = mysqli_real_escape_string($conexao, $_POST['delete_estoque']);
    $sql = "DELETE FROM estoque WHERE id = '$estoque_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Produto excluído com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir o produto.";
    }

    header("Location: estoque/estoque.php");
    exit;
}

/** CRIAÇÃO DE VENDA **/
if (isset($_POST['create_venda'])) {
    $id_produto = mysqli_real_escape_string($conexao, $_POST['id_produto']);
    $quantidade = mysqli_real_escape_string($conexao, $_POST['quantidade']);
    $data_venda = mysqli_real_escape_string($conexao, $_POST['data_venda']);

    // Verificar se o produto existe e se tem quantidade suficiente
    $consulta = mysqli_query($conexao, "SELECT quantidade FROM estoque WHERE id = '$id_produto'");
    $produto = mysqli_fetch_assoc($consulta);

    if (!$produto || $produto['quantidade'] < $quantidade) {
        $_SESSION['mensagem'] = "Produto inexistente ou quantidade insuficiente no estoque.";
        header("Location: vendas/venda-create.php");
        exit;
    }

    // Registrar a venda
    $sql = "INSERT INTO vendas (id_produto, quantidade, data_venda) VALUES ('$id_produto', '$quantidade', '$data_venda')";
    if (mysqli_query($conexao, $sql)) {
        // Atualizar o estoque
        $nova_quantidade = $produto['quantidade'] - $quantidade;
        mysqli_query($conexao, "UPDATE estoque SET quantidade = '$nova_quantidade' WHERE id = '$id_produto'");
        $_SESSION['mensagem'] = "Venda registrada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao registrar a venda.";
    }

    header("Location: vendas/vendas.php");
    exit;
}

// EXCLUIR VENDA
if (isset($_POST['delete_venda'])) {
    $id_venda = mysqli_real_escape_string($conexao, $_POST['delete_venda']);

    // Verifica se o usuário é administrador
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        // Recuperar os dados da venda antes de excluir (para devolver ao estoque)
        $query_venda = "SELECT id_produto, quantidade FROM vendas WHERE id = '$id_venda'";
        $result_venda = mysqli_query($conexao, $query_venda);

        if ($result_venda && mysqli_num_rows($result_venda) > 0) {
            $venda = mysqli_fetch_assoc($result_venda);
            $id_produto = $venda['id_produto'];
            $quantidade_vendida = $venda['quantidade'];

            // Devolver a quantidade ao estoque
            $query_update_estoque = "UPDATE estoque SET quantidade = quantidade + $quantidade_vendida WHERE id = '$id_produto'";
            mysqli_query($conexao, $query_update_estoque);

            // Excluir a venda
            $query_delete = "DELETE FROM vendas WHERE id = '$id_venda'";
            $result_delete = mysqli_query($conexao, $query_delete);

            if ($result_delete) {
                $_SESSION['mensagem'] = "Venda excluída com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir a venda.";
            }
        } else {
            $_SESSION['mensagem'] = "Venda não encontrada.";
        }
    } else {
        $_SESSION['mensagem'] = "Você não tem permissão para excluir vendas.";
    }

    header("Location: vendas/vendas.php");
    exit();
}


?>
