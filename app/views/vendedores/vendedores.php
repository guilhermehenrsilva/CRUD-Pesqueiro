<?php
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendedores</title>
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
              <h4> Lista de Vendedores
                <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                  <a href="/vendedores/create" class="btn btn-primary float-end">Adicionar vendedor</a>
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
                    <th>Telefone</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  
                  <tr>
                    <td><?= htmlspecialchars($vendedor['id']) ?></td>
                    <td><?= htmlspecialchars($vendedor['nome']) ?></td>
                    <td><?= htmlspecialchars($vendedor['email']) ?></td>
                    <td><?= htmlspecialchars($vendedor['telefone']) ?></td>
                    <td>
                      <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="/vendedores/edit/<?= $vendedor['id'] ?>" class="btn btn-success btn-sm">
                            <span class="bi-pencil-fill"></span> Editar
                        </a>

                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                          <a href="/vendedores/view/<?= $vendedor['id'] ?>" class="btn btn-success btn-sm">
                            <span class="bi-pencil-fill"></span> Editar
                          </a>
                          <form action="/vendedores/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este vendedor?')" class="d-inline">
                            <input type="hidden" name="vendedor_id" value="<?= $vendedor['id'] ?>">
                            <button type="submit" name="delete_vendedor" class="btn btn-danger btn-sm">
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
                    echo '<tr><td colspan="5"><h5>Nenhum vendedor encontrado</h5></td></tr>';
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
