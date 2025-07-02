<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Visualizar Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include(PROJECT_ROOT . '/system/navbar.php'); ?>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-header">
            <h4>Visualizar Produto
              <a href="<?= BASE_URL ?>/estoque" class="btn btn-danger float-end">Voltar</a>
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