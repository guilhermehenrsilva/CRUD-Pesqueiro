<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Visualizar Vendedor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <?php include('../../system/navbar.php'); ?>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="card">
          <div class="card-header">
            <h4>Visualizar Vendedor
              <a href="/vendedores" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <?php if ($vendedor): ?>
              <div class="mb-3">
                <label>Nome</label>
                <p class="form-control"><?= htmlspecialchars($vendedor['nome']); ?></p>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <p class="form-control"><?= htmlspecialchars($vendedor['email']); ?></p>
              </div>
              <div class="mb-3">
                <label>Telefone</label>
                <p class="form-control"><?= htmlspecialchars($vendedor['telefone']); ?></p>
              </div>
            <?php else: ?>
              <h5>Vendedor não encontrado.</h5>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
