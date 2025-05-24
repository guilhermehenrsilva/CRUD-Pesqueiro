<?php
require '../sistema/verifica_login.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  </head>
  <body>
    <?php include('../sistema/navbar.php'); ?>
    
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Adicionar Produto ao Estoque
                <a href="estoque.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <form action="../acoes.php" method="POST">
                <div class="mb-3">
                  <label>Nome do Produto</label>
                  <input type="text" name="nome_produto" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Quantidade</label>
                  <input type="number" name="quantidade" class="form-control" required min="0">
                </div>
                <div class="mb-3">
                  <label>Preço Unitário (R$)</label>
                  <input type="number" step="0.01" name="preco_unitario" class="form-control" required min="0">
                </div>
                <div class="mb-3">
                  <button type="submit" name="create_estoque" class="btn btn-primary">Salvar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    

    
  </body>
  
