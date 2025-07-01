<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
   <?php include(PROJECT_ROOT . '/system/navbar.php'); ?>

    <div class="container mt-4">
      <?php include(PROJECT_ROOT . '/system/mensagem.php'); ?>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4> Lista de Usuários
              <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="<?= BASE_URL ?>/usuario/create" class="btn btn-primary float-end">Adicionar usuário</a>
              <?php endif; ?>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data Nascimento</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($usuarios) && is_array($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                      <tr>
                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td>
                          <?= isset($usuario['data_nascimento']) ? date('d/m/Y', strtotime($usuario['data_nascimento'])) : '' ?>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="<?= BASE_URL ?>/usuario/view/<?= $usuario['id'] ?>" class="btn btn-secondary btn-sm">
                              <span class="bi-eye-fill"></span> Visualizar
                            </a>
                            
                            <a href="<?= BASE_URL ?>/usuario/edit/<?= $usuario['id'] ?>" class="btn btn-success btn-sm">
                              <span class="bi-pencil-fill"></span> Editar
                            </a>

                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                              <form action="<?= BASE_URL ?>/usuario/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')" class="d-inline mb-0">
                                <input type="hidden" name="delete_usuario" value="<?= $usuario['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                  <i class="bi bi-trash3-fill"></i> Excluir
                                </button>
                              </form>
                            <?php endif; ?>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="5"><h5>Nenhum usuário encontrado</h5></td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include(PROJECT_ROOT . '/system/footer.php'); ?>
  </body>
</html>