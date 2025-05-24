<?php
session_start();
require 'sistema/conexao.php';
require 'sistema/verifica_login.php';

// Função para realizar requisições cURL para o Supabase
function supabaseRequest($method, $endpoint, $data = null, $queryParams = '')
{
    global $supabaseUrl, $supabaseKey;

    $url = $supabaseUrl . '/rest/v1/' . $endpoint . $queryParams;

    $headers = [
        "apikey: $supabaseKey",
        "Authorization: Bearer $supabaseKey",
        "Content-Type: application/json"
    ];

    // Cabeçalho diferente para DELETE
    if (strtoupper($method) === 'DELETE') {
        $headers[] = "Prefer: return=minimal";
    } else {
        $headers[] = "Prefer: return=representation";
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    if ($data) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [$response, $httpcode];
}

// ====================== USUÁRIOS ===========================
if (isset($_POST['create_usuario'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $senha = !empty($_POST['senha']) ? password_hash(trim($_POST['senha']), PASSWORD_DEFAULT) : '';
    $is_admin = isset($_POST['is_admin']) ? true : false;

    if (empty($nome) || empty($email) || empty($data_nascimento) || empty($senha)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        header("Location: ../usuarios/usuarios.php");
        exit;
    }

    list($response, $code) = supabaseRequest('POST', 'usuarios', [
        'nome' => $nome,
        'email' => $email,
        'data_nascimento' => $data_nascimento,
        'senha' => $senha,
        'is_admin' => $is_admin
    ]);

    $_SESSION['mensagem'] = ($code === 201) ? "Usuário adicionado com sucesso!" : "Erro ao adicionar usuário.";
    header("Location: ../usuarios/usuarios.php");
    exit;
}

if (isset($_POST['update_usuario'])) {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        $_SESSION['mensagem'] = "Você não tem permissão para editar usuários.";
        header("Location: ../usuarios/usuarios.php");
        exit;
    }

    $usuario_id = $_POST['usuario_id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $is_admin = isset($_POST['is_admin']) ? true : false;

    $data = [
        'nome' => $nome,
        'email' => $email,
        'data_nascimento' => $data_nascimento,
        'is_admin' => $is_admin
    ];

    if (!empty($_POST['senha'])) {
        $data['senha'] = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    }

    list($response, $code) = supabaseRequest('PATCH', "usuarios?id=eq.$usuario_id", $data);

    $_SESSION['mensagem'] = ($code === 200) ? "Usuário atualizado com sucesso!" : "Erro ao atualizar usuário.";
    header("Location: ../usuarios/usuarios.php");
    exit;
}

if (isset($_POST['delete_usuario'])) {
    $usuario_id = $_POST['delete_usuario'];

    list($response, $code) = supabaseRequest('DELETE', "usuarios?id=eq.$usuario_id");

    $_SESSION['mensagem'] = ($code === 204) ? "Usuário excluído com sucesso!" : "Erro ao excluir usuário.";
    header("Location: ../usuarios/usuarios.php");
    exit;
}

// ====================== ESTOQUE ===========================

if (isset($_POST['create_estoque'])) {
    $nome_produto = trim($_POST['nome_produto']);
    $quantidade = trim($_POST['quantidade']);
    $preco_unitario = trim($_POST['preco_unitario']);

    if (empty($nome_produto) || empty($quantidade) || empty($preco_unitario)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        header("Location: ../estoque/estoque-create.php");
        exit;
    }

    list($response, $code) = supabaseRequest('POST', 'estoque', [
        'nome_produto' => $nome_produto,
        'quantidade' => (int)$quantidade,
        'preco_unitario' => (float)$preco_unitario
    ]);

    $_SESSION['mensagem'] = ($code === 201) ? "Produto adicionado com sucesso!" : "Erro ao adicionar produto.";
    header("Location: ../estoque/estoque.php");
    exit;
}

if (isset($_POST['update_estoque'])) {
    $estoque_id = $_POST['estoque_id'];
    $nome_produto = trim($_POST['nome_produto']);
    $quantidade = trim($_POST['quantidade']);
    $preco_unitario = trim($_POST['preco_unitario']);

    if (empty($estoque_id) || empty($nome_produto) || empty($quantidade) || empty($preco_unitario)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        header("Location: ../estoque/estoque-edit.php?id=" . urlencode($estoque_id));
        exit;
    }

    list($response, $code) = supabaseRequest('PATCH', "estoque?id=eq.$estoque_id", [
        'nome_produto' => $nome_produto,
        'quantidade' => (int)$quantidade,
        'preco_unitario' => (float)$preco_unitario
    ]);

    $_SESSION['mensagem'] = ($code === 200) ? "Produto atualizado com sucesso!" : "Erro ao atualizar produto.";
    header("Location: ../estoque/estoque.php");
    exit;
}

if (isset($_POST['delete_estoque'])) {
    $estoque_id = $_POST['delete_estoque'];

    if (empty($estoque_id)) {
        $_SESSION['mensagem'] = "ID inválido para exclusão.";
        header("Location: ../estoque/estoque.php");
        exit;
    }

    // Verifica se existe alguma venda com esse produto
    list($vendas, $vendaCode) = supabaseRequest('GET', "vendas?id_produto=eq.$estoque_id");

    if ($vendaCode === 200 && !empty(json_decode($vendas, true))) {
        $_SESSION['mensagem'] = "Não é possível excluir este produto. Existem vendas vinculadas a ele.";
        header("Location: ../estoque/estoque.php");
        exit;
    }

    // Executa a exclusão se não houver vendas
    list($response, $code) = supabaseRequest('DELETE', "estoque?id=eq.$estoque_id");

    $_SESSION['mensagem'] = ($code === 204) ? "Produto excluído com sucesso!" : "Erro ao excluir produto.";
    header("Location: ../estoque/estoque.php");
    exit;
}






// ====================== VENDAS ===========================

if (isset($_POST['create_venda'])) {
    $id_produto = $_POST['id_produto'];
    $id_vendedor = $_POST['id_vendedor'];
    $quantidade = (int)$_POST['quantidade'];
    $data_venda = $_POST['data_venda'];

    // Buscar o produto no estoque
    list($estoque_json, $status) = supabaseRequest('GET', "estoque?id=eq.$id_produto");
    $produto = json_decode($estoque_json, true);

    if (!$produto || $produto[0]['quantidade'] < $quantidade) {
        $_SESSION['mensagem'] = "Produto inexistente ou quantidade insuficiente.";
        header("Location: ../vendas/vendas-create.php");
        exit;
    }

    $total = $quantidade * $produto[0]['preco_unitario'];

    list($resVenda, $codeVenda) = supabaseRequest('POST', 'vendas', [
        'id_produto' => $id_produto,
        'quantidade' => $quantidade,
        'total' => $total,
        'data_venda' => $data_venda,
        'id_vendedor' => $id_vendedor
    ]);

    if ($codeVenda === 201) {
        $nova_qtd = $produto[0]['quantidade'] - $quantidade;
        supabaseRequest('PATCH', "estoque?id=eq.$id_produto", ['quantidade' => $nova_qtd]);
        $_SESSION['mensagem'] = "Venda registrada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao registrar a venda.";
    }

    header("Location: ../vendas/vendas.php");
    exit;
}

if (isset($_POST['delete_venda'])) {
    $id_venda = $_POST['id_venda'];

    // Buscar a venda
    list($resVenda, $statusVenda) = supabaseRequest('GET', "vendas?id=eq.$id_venda");
    $venda = json_decode($resVenda, true);

    if ($statusVenda !== 200 || empty($venda)) {
        $_SESSION['mensagem'] = "Venda não encontrada.";
        header("Location: ../vendas/vendas.php");
        exit;
    }

    $id_produto = $venda[0]['id_produto'];
    $quantidade = $venda[0]['quantidade'];

    // Buscar produto no estoque
    list($resProduto, $statusEstoque) = supabaseRequest('GET', "estoque?id=eq.$id_produto");
    $produto = json_decode($resProduto, true);

    if ($statusEstoque !== 200 || empty($produto)) {
        $_SESSION['mensagem'] = "Produto não encontrado no estoque.";
        header("Location: ../vendas/vendas.php");
        exit;
    }

    $nova_qtd = $produto[0]['quantidade'] + $quantidade;

    // Excluir venda
    list($resDelete, $statusDelete) = supabaseRequest('DELETE', "vendas?id=eq.$id_venda");

    if ($statusDelete === 204) {
        supabaseRequest('PATCH', "estoque?id=eq.$id_produto", ['quantidade' => $nova_qtd]);
        $_SESSION['mensagem'] = "Venda excluída com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir a venda.";
    }

    header("Location: ../vendas/vendas.php");
    exit;
}

// ====================== VENDEDORES ===========================
// CRIAR VENDEDOR
if (isset($_POST['create_vendedor'])) {
    $nome = trim($_POST['nome_vendedor']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    // Validação simples
    if (empty($nome) || empty($email)) {
        $_SESSION['mensagem'] = "Nome e e-mail são obrigatórios.";
        header("Location: ./vendedores/vendedores-create.php");
        exit;
    }

    // Enviar para Supabase
    list($resVendedor, $codeVendedor) = supabaseRequest('POST', 'vendedores', [
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone
    ]);

    if ($codeVendedor === 201) {
        $_SESSION['mensagem'] = "Vendedor cadastrado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar o vendedor.";
    }

    header("Location: ./vendas/vendedores.php");
    exit;
}

if (isset($_POST['update_vendedor'])) {
    $vendedor_id = $_POST['vendedor_id'];
    $nome_vendedor = trim($_POST['nome_vendedor']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    $data = [
        'nome' => $nome_vendedor,
        'email' => $email,
        'telefone' => $telefone
    ];

    list($response, $code) = supabaseRequest('PATCH', "vendedores?id=eq.$vendedor_id", $data);

    $_SESSION['mensagem'] = ($code === 200 || $code === 204) ? "Vendedor atualizado com sucesso!" : "Erro ao atualizar vendedor.";
    header("Location: ../vendas/vendedores.php");
    exit;
}

if (isset($_POST['delete_vendedor'])) {
    // Recupera o ID do vendedor
    $vendedor_id = $_POST['vendedor_id'];

    // Envia a requisição DELETE para Supabase
    list($resDelete, $statusDelete) = supabaseRequest('DELETE', "vendedores?id=eq.$vendedor_id");

    // Verifica o status da resposta
    if ($statusDelete === 204) {
        $_SESSION['mensagem'] = "Vendedor excluído com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir o vendedor.";
    }

    // Redireciona para a lista de vendedores
    header("Location: ../vendas/vendedores.php");
    exit;
}






?>
