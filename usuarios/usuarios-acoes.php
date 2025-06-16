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
?>