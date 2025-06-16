<?php

session_start();
require '../sistema/conexao.php';
require '../sistema/verifica_login.php';

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

?>