<?php
require_once __DIR__ . '/../config/conexao.php';

class VendaModel {
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct() {
        global $supabaseUrl, $supabaseKey;
        $this->supabaseUrl = $supabaseUrl;
        $this->supabaseKey = $supabaseKey;
    }

   private function supabaseRequest($method, $endpoint, $data = null, $queryParams = '') {
    $url = $this->supabaseUrl . '/rest/v1/' . $endpoint . $queryParams;
    $headers = [
        "apikey: " . $this->supabaseKey,
        "Authorization: Bearer " . $this->supabaseKey,
        "Content-Type: application/json"
    ];

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
    
    // ===================================================================
    // ADIÇÃO PARA DEBUG: Desabilita temporariamente a verificação SSL
    // Isso nos ajuda a confirmar se o problema é o certificado.
    // Lembre-se de remover ou comentar esta linha para produção.
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // ===================================================================

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    // --- NOVO BLOCO DE DIAGNÓSTICO ---
    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        curl_close($curl);
        // Exibe um erro claro na tela para que você possa ver
        die("ERRO NA CONEXÃO cURL: " . $error_msg);
    }
    // --- FIM DO BLOCO DE DIAGNÓSTICO ---

    curl_close($curl);

    return [$response, $httpcode];
}

    public function getAllVendas($query = '') {
        list($response, $code) = $this->supabaseRequest('GET', 'vendas', null, $query);
        return ($code === 200) ? json_decode($response, true) : [];
    }

    public function getVendaById($id) {
        list($response, $code) = $this->supabaseRequest('GET', 'vendas', null, '?id=eq.' . $id);
        $vendas = json_decode($response, true);
        return ($code === 200 && count($vendas) > 0) ? $vendas[0] : null;
    }

    public function createVenda($data) {
        list($response, $code) = $this->supabaseRequest('POST', 'vendas', $data);
        return $code === 201;
    }

    public function deleteVenda($id) {
        list($response, $code) = $this->supabaseRequest('DELETE', 'vendas', null, '?id=eq.' . $id);
        return $code === 204;
    }
}
?>