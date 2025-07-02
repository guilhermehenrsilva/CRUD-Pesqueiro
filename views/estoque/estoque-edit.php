<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <?php include(PROJECT_ROOT . '/system/navbar.php'); ?>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar Produto no Estoque
                            <a href="<?= BASE_URL ?>/estoque" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>/estoque/edit/<?= $produto['id'] ?>" method="POST">
                            <input type="hidden" name="estoque_id" value="<?= htmlspecialchars($produto['id']); ?>">

                            <div class="mb-3">
                                <label>Nome do Produto</label>
                                <input type="text" name="nome_produto" class="form-control" value="<?= htmlspecialchars($produto['nome_produto']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Quantidade</label>
                                <input type="number" name="quantidade" class="form-control" value="<?= htmlspecialchars($produto['quantidade']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Preço Unitário</label>
                                <input type="number" step="0.01" name="preco_unitario" class="form-control" value="<?= htmlspecialchars($produto['preco_unitario']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <button type="submit" name="update_estoque" class="btn btn-primary">Salvar Alterações</button>
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