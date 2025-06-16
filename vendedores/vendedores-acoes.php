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

    header("Location: ./vendedores/vendedores.php");
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
    header("Location: ../vendedores/vendedores.php");
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
    header("Location: ../vendedores/vendedores.php");
    exit;
}
?>