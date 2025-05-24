<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require '../sistema/verifica_login.php';
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nova Venda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <?php include '../sistema/navbar.php'; ?>
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

            <?php
            // Cabeçalhos da API
            $headers = [
              "apikey: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4dmFueWhtcGlvc2liamhueHBxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxNTkxMDgsImV4cCI6MjA1OTczNTEwOH0.rjQAET7doqcGLSZcSJ1vb05wm7RfhV-5R0e8nquexeM",
              "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4dmFueWhtcGlvc2liamhueHBxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxNTkxMDgsImV4cCI6MjA1OTczNTEwOH0.rjQAET7doqcGLSZcSJ1vb05wm7RfhV-5R0e8nquexeM",
              "Content-Type: application/json"
            ];

            // Buscar produtos
            $url_produtos = "https://dxvanyhmpiosibjhnxpq.supabase.co/rest/v1/estoque?quantidade=gt.0&select=id,nome_produto,quantidade";
            $ch = curl_init($url_produtos);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $produtos = json_decode(curl_exec($ch), true);
            curl_close($ch);

            // Buscar vendedores
            $url_vendedores = "https://dxvanyhmpiosibjhnxpq.supabase.co/rest/v1/vendedores?select=id,nome";
            $ch = curl_init($url_vendedores);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $vendedores = json_decode(curl_exec($ch), true);
            curl_close($ch);
            ?>

            <form action="../acoes.php" method="POST">
              <div class="mb-3">
                <label>Produto</label>
                <select name="id_produto" class="form-select" required>
                  <option value="">Selecione</option>
                  <?php foreach ($produtos as $produto): ?>
                    <option value="<?= $produto['id']; ?>">
                      <?= $produto['nome_produto']; ?> (<?= $produto['quantidade']; ?> disponíveis)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>Vendedor</label>
                <select name="id_vendedor" class="form-select" required>
                  <option value="">Selecione</option>
                  <?php foreach ($vendedores as $vendedor): ?>
                    <option value="<?= $vendedor['id']; ?>">
                      <?= htmlspecialchars($vendedor['nome']); ?>
                    </option>
                  <?php endforeach; ?>
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
