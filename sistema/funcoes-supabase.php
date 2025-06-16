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
?>