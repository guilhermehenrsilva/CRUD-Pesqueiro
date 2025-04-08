<?php
session_start();
require 'conexao.php';

/** CRIAÇÃO DE USUÁRIO **/
if (isset($_POST['create_usuario'])) {
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $senha = isset($_POST['senha']) ? mysqli_real_escape_string($conexao, password_hash(trim($_POST['senha']), PASSWORD_DEFAULT)) : '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (empty($nome) || empty($email) || empty($data_nascimento) || empty($senha)) {
        $_SESSION['mensagem'] = "Não foi possível incluir um usuário no banco.";
        header("Location: index.php");
        exit;
    }

    $sql = "INSERT INTO usuarios (nome, email, data_nascimento, senha, is_admin) VALUES ('$nome', '$email', '$data_nascimento', '$senha', '$is_admin')";

    if (mysqli_query($conexao, $sql) && mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Usuário adicionado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Não foi possível incluir um usuário no banco.";
    }

    header("Location: index.php");
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

    header('Location: index.php');
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

    header('Location: index.php');
    exit;
}

/** CRIAÇÃO DE PRODUTO NO ESTOQUE **/
if (isset($_POST['create_estoque'])) {
    $nome_produto = mysqli_real_escape_string($conexao, trim($_POST['nome_produto']));
    $quantidade = mysqli_real_escape_string($conexao, trim($_POST['quantidade']));
    $preco_unitario = mysqli_real_escape_string($conexao, trim($_POST['preco_unitario']));

    if (empty($nome_produto) || empty($quantidade) || empty($preco_unitario)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        header("Location: estoque-create.php");
        exit;
    }

    $sql = "INSERT INTO estoque (nome_produto, quantidade, preco_unitario) VALUES ('$nome_produto', '$quantidade', '$preco_unitario')";

    if (mysqli_query($conexao, $sql) && mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Produto adicionado ao estoque com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao adicionar produto ao estoque.";
    }

    header("Location: estoque.php");
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
        header("Location: estoque-edit.php?id=$estoque_id");
        exit;
    }

    $sql = "UPDATE estoque SET nome_produto = '$nome_produto', quantidade = '$quantidade', preco_unitario = '$preco_unitario' WHERE id = '$estoque_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Produto não foi atualizado.";
    }

    header("Location: estoque.php");
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

    header("Location: estoque.php");
    exit;
}
?>
