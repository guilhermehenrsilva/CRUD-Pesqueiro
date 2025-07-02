<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Novo Vendedor</title>
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
            <h4>Cadastrar Novo Vendedor
              <a href="<?= BASE_URL ?>/vendedor" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <form action="<?= BASE_URL ?>/vendedor/create" method="POST">
              <div class="mb-3">
                <label for="nome_vendedor">Nome do Vendedor</label>
                <input type="text" name="nome" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" class="form-control">
              </div>

              <div class="mb-3">
                <button type="submit" name="create_vendedor" class="btn btn-primary">Salvar</button>
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