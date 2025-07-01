<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center bg-light" style="min-height: 100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-lg">
          <div class="card-body">
            <h4 class="text-center mb-4">Login</h4>
            <?php if (isset($erro)): ?>
              <div class="alert alert-danger"><?= $erro ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['mensagem'])): ?>
              <div class="alert alert-success"><?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?= BASE_URL ?>/login">
            
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
              </div>
              <button name="entrar" class="btn btn-primary w-100">Entrar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>