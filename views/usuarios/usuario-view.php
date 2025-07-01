
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuário - Visualizar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include(PROJECT_ROOT . '/system/navbar.php'); ?>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Visualizar Usuário
              <a  href="<?= BASE_URL ?>/usuario" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <?php if ($usuario): ?>
              <div class="mb-3">
                <label>Nome</label>
                <p class="form-control"><?= htmlspecialchars($usuario['nome']) ?></p>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <p class="form-control"><?= htmlspecialchars($usuario['email']) ?></p>
              </div>
              <div class="mb-3">
                <label>Data Nascimento</label>
                <p class="form-control"><?= date('d/m/Y', strtotime($usuario['data_nascimento'])) ?></p>
              </div>
            <?php else: ?>
              <h5>Usuário não encontrado</h5>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
