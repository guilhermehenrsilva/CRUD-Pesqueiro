<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nova Venda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <?php include(PROJECT_ROOT . '/system/navbar.php'); ?>
  <div class="container mt-5">
    <?php include(PROJECT_ROOT . '/system/mensagem.php'); ?>

    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-header">
            <h4>Registrar Venda
              <a href="<?= BASE_URL ?>/venda" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <form action="<?= BASE_URL ?>/venda/create" method="POST">
              <div class="mb-3">
                <label>Produto</label>
                <select name="id_produto" class="form-select" required>
                  <option value="">Selecione</option>
                  <?php foreach ($produtos as $produto): ?>
                    <option value="<?= $produto['id']; ?>">
                      <?= $produto['nome_produto']; ?> (<?= $produto['quantidade']; ?> disponíveis)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>Vendedor</label>
                <select name="id_vendedor" class="form-select" required>
                  <option value="">Selecione</option>
                  <?php foreach ($vendedores as $vendedor): ?>
                    <option value="<?= $vendedor['id']; ?>">
                      <?= htmlspecialchars($vendedor['nome']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>Quantidade</label>
                <input type="number" name="quantidade" class="form-control" min="1" required>
              </div>

              <div class="mb-3">
                <label>Data da Venda</label>
                <input type="date" name="data_venda" class="form-control" required>
              </div>

              <div class="mb-3">
                <button type="submit" name="create_venda" class="btn btn-primary">Registrar</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>