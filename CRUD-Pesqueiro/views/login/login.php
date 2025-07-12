<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/login.css"> 
  
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card">
          <div class="card-body">
            <h4 class="text-center">Bem-vindo(a)!</h4>
            
            <?php 
            // Exibição de mensagens de erro ou sucesso
            if (isset($erro)): ?>
              <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($erro) ?>
              </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['mensagem'])): ?>
              <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_SESSION['mensagem']); 
                unset($_SESSION['mensagem']); ?>
              </div>
            <?php endif; ?>
            
            <form method="POST" action="<?= BASE_URL ?>/login">
              <div class="mb-3">
                <label for="emailInput" class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  <input type="email" name="email" id="emailInput" class="form-control" placeholder="seu@email.com" required>
                </div>
              </div>
              <div class="mb-4">
                <label for="senhaInput" class="form-label">Senha</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" name="senha" id="senhaInput" class="form-control" placeholder="********" required>
                </div>
              </div>
              <button type="submit" name="entrar" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Entrar
              </button>
            </form>
            
           
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>