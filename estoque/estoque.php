<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../sistema/conexao.php';
require '../sistema/verifica_login.php';


$url = $supabaseUrl . "/rest/v1/estoque?select=*";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabaseKey",
    "Authorization: Bearer $supabaseKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "<pre>Erro cURL: " . curl_error($ch) . "</pre>";
}

$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Exibir resposta apenas se houver erro na requisição
if (!in_array($http_status, [200, 204])) {
    echo "<pre>Status HTTP: $http_status\nResposta bruta da API:\n$response</pre>";
}

$produtos = json_decode($response, true);
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
    <?php include('../sistema/navbar.php'); ?>
    <div class="container mt-4">
      <?php include('../sistema/mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Estoque
                <a href="estoque-create.php" class="btn btn-primary float-end">Adicionar Produto</a>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (is_array($produtos) && count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                      <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= htmlspecialchars($produto['nome_produto']) ?></td>
                        <td><?= $produto['quantidade'] ?></td>
                        <td>R$ <?= number_format($produto['preco_unitario'], 2, ',', '.') ?></td>
                        <td>
                          <div class="d-flex justify-content-center gap-1 flex-wrap">
                            <a href="estoque-view.php?id=<?= $produto['id'] ?>" class="btn btn-secondary btn-sm">
                              <span class="bi-eye-fill"></span> Visualizar
                            </a>
                            <a href="estoque-edit.php?id=<?= $produto['id'] ?>" class="btn btn-success btn-sm">
                              <span class="bi-pencil-fill"></span> Editar
                            </a>
                            <form action="/acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')" class="d-inline">
                              <button type="submit" name="delete_estoque" value="<?= $produto['id'] ?>" class="btn btn-danger btn-sm">
                                <span class="bi-trash3-fill"></span> Excluir
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5"><h5>Nenhum produto encontrado</h5></td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../sistema/footer.php'; ?>

  </body>
</html>
