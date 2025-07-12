<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Vendedor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include(PROJECT_ROOT . '/system/navbar.php'); ?>
  <?php include(PROJECT_ROOT . '/system/mensagem.php'); ?>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Editar Vendedor
              <a href="<?= BASE_URL ?>/vendedor" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <?php if (isset($vendedor) && $vendedor): ?>
              <form action="<?= BASE_URL ?>/vendedor/edit/<?= $vendedor['id'] ?>" method="POST">
                <input type="hidden" name="vendedor_id" value="<?= $vendedor['id']; ?>">

                <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($vendedor['nome']); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($vendedor['email']); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Telefone</label>
                  <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($vendedor['telefone']); ?>">
                </div>

                <div class="d-flex justify-content-between">
                  <button type="submit" name="update_vendedor" class="btn btn-success">Salvar Alterações</button>
                  <a href="<?= BASE_URL ?>/vendedor" class="btn btn-secondary">Cancelar</a>
                </div>
              </form>
            <?php else: ?>
              <h5>Vendedor não encontrado</h5>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>