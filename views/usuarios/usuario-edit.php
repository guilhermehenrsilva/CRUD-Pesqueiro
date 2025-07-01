<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include(PROJECT_ROOT . '/system/navbar.php'); ?>
  
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Editar Usuário
              <a href="<?= BASE_URL ?>/usuario" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <?php if (isset($usuario) && $usuario): ?>
              <form action="<?= BASE_URL ?>/usuario/edit/<?= $usuario['id']; ?>" method="POST">
                <input type="hidden" name="usuario_id" value="<?= $usuario['id']; ?>">

                <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Data de Nascimento</label>
                  <input type="date" class="form-control" name="data_nascimento" value="<?= $usuario['data_nascimento']; ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                  <input type="password" class="form-control" name="senha">
                </div>

                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" <?= $usuario['is_admin'] ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="is_admin">Administrador</label>
                </div>

                <div class="d-flex justify-content-between">
                  <button type="submit" name="update_usuario" class="btn btn-success">Salvar Alterações</button>
                  <a href="<?= BASE_URL ?>/usuario" class="btn btn-secondary">Cancelar</a>
                </div>
              </form>
            <?php else: ?>
              <h5>Usuário não encontrado</h5>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>