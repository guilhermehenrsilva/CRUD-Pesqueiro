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

?>