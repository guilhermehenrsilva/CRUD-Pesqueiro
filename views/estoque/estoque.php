<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estoque</title>
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
              <h4>Estoque
                <a href="<?= BASE_URL ?>/estoque/create" class="btn btn-primary float-end">Adicionar Produto</a>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (is_array($produtos) && count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                      <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= htmlspecialchars($produto['nome_produto']) ?></td>
                        <td><?= $produto['quantidade'] ?></td>
                        <td>R$ <?= number_format($produto['preco_unitario'], 2, ',', '.') ?></td>
                        <td>
                          <div class="d-flex justify-content-center gap-1 flex-wrap">
                            <a href="<?= BASE_URL ?>/estoque/view/<?= $produto['id'] ?>" class="btn btn-secondary btn-sm">
                              <span class="bi-eye-fill"></span> Visualizar
                            </a>
                            <a href="<?= BASE_URL ?>/estoque/edit/<?= $produto['id'] ?>" class="btn btn-success btn-sm">
                                <span class="bi-pencil-fill"></span> Editar
                            </a>
                            <form action="<?= BASE_URL ?>/estoque/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')" class="d-inline">
                              <input type="hidden" name="delete_estoque" value="<?= $produto['id'] ?>">
                              <button type="submit" class="btn btn-danger btn-sm">
                                <span class="bi-trash3-fill"></span> Excluir
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5"><h5>Nenhum produto encontrado</h5></td>
                    </tr>
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