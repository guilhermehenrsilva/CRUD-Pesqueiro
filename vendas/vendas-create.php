<?php
require '../sistema/conexao.php';
require '../sistema/verifica_login.php';
include '../sistema/navbar.php';

// Buscar os produtos disponíveis no estoque
$produtos = mysqli_query($conexao, "SELECT id, nome_produto, quantidade FROM estoque WHERE quantidade > 0");
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nova Venda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <?php include '../sistema/mensagem.php'; ?>

    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-header">
            <h4>Registrar Venda
              <a href="vendas.php" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <form action="../acoes.php" method="POST">
              <div class="mb-3">
                <label>Produto</label>
                <select name="id_produto" class="form-select" required>
                  <option value="">Selecione</option>
                  <?php while ($produto = mysqli_fetch_assoc($produtos)): ?>
                    <option value="<?= $produto['id']; ?>">
                      <?= $produto['nome_produto']; ?> (<?= $produto['quantidade']; ?> disponíveis)
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>Quantidade</label>
                <input type="number" name="quantidade" class="form-control" min="1" required>
              </div>

              <div class="mb-3">
                <label>Data da Venda</label>
                <input type="date" name="data_venda" class="form-control" required>
              </div>

              <div class="mb-3">
                <button type="submit" name="create_venda" class="btn btn-primary">Registrar</button>
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
