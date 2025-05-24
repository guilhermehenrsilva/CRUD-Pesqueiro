<?php
session_start();

if (isset($_POST['entrar'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Dados do Supabase
    $url = "https://dxvanyhmpiosibjhnxpq.supabase.co/rest/v1/usuarios?email=eq.$email";
    $apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4dmFueWhtcGlvc2liamhueHBxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxNTkxMDgsImV4cCI6MjA1OTczNTEwOH0.rjQAET7doqcGLSZcSJ1vb05wm7RfhV-5R0e8nquexeM";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $apiKey",
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $usuarios = json_decode($response, true);

    if ($usuarios && count($usuarios) > 0) {
        $usuario = $usuarios[0];
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['is_admin'] = $usuario['is_admin'];
            header("Location: home.php");
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "E-mail não encontrado!";
    }
}
?>

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
            <form method="POST">
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
