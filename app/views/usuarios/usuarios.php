<?php
//if (session_status() === PHP_SESSION_NONE) {
 //   session_start();
//}
//require '../sistema/verifica_login.php';
?>
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
   <?php include('../../system/navbar.php'); ?>
    <div class="container mt-4">
      <?php include('../../system/mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4> Lista de Usuários
              <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="/usuarios/create" class="btn btn-primary float-end">Adicionar usuário</a>
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
                  <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td>
                      <?= isset($usuario['data_nascimento']) ? date('d/m/Y', strtotime($usuario['data_nascimento'])) : '' ?>
                    </td>
                    <td>
                      <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="/usuarios/edit/<?= $usuario['id'] ?>" class="btn btn-success btn-sm">
                             <span class="bi-pencil-fill"></span> Editar
                        </a>

                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                          <a href="/usuarios/edit/<?= $usuario['id'] ?>" class="btn btn-success btn-sm">
                            <span class="bi-pencil-fill"></span> Editar
                          </a>
                          <form form action="/usuarios/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')" class="d-inline">
                            <button type="submit" name="delete_usuario" value="<?= $usuario['id'] ?>" class="btn btn-danger btn-sm">
                              <span class="bi-trash3-fill"></span> Excluir
                            </button>
                          </form>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="5"><h5>Nenhum usuário encontrado</h5></td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../../system/footer.php'; ?>

  </body>
</html>
