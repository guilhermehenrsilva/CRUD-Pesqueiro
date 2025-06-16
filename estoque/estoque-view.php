<?php
require '../sistema/verifica_login.php';
require '../sistema/conexao.php';

if (!isset($_GET['id'])) {
    $_SESSION['mensagem'] = 'ID do produto não fornecido.';
    header('Location: estoque.php');
    exit;
}

$id = htmlspecialchars($_GET['id']);

// Requisição para buscar o produto no Supabase
$url = $supabaseUrl . "/rest/v1/estoque?id=eq.$id";
$headers = [
    "apikey: $supabaseKey",
    "Authorization: Bearer $supabaseKey"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    $_SESSION['mensagem'] = 'Erro ao buscar produto no Supabase.';
    header('Location: estoque.php');
    exit;
}

$produtos = json_decode($response, true);
if (!$produtos || count($produtos) === 0) {
    $_SESSION['mensagem'] = 'Produto não encontrado.';
    header('Location: estoque.php');
    exit;
}

$produto = $produtos[0];
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Visualizar Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include('../sistema/navbar.php'); ?>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-header">
            <h4>Visualizar Produto
              <a href="estoque.php" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label>Nome do Produto</label>
              <p class="form-control"><?= htmlspecialchars($produto['nome_produto']); ?></p>
            </div>
            <div class="mb-3">
              <label>Quantidade</label>
              <p class="form-control"><?= htmlspecialchars($produto['quantidade']); ?></p>
            </div>
            <div class="mb-3">
              <label>Preço Unitário</label>
              <p class="form-control">R$ <?= number_format($produto['preco_unitario'], 2, ',', '.'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
