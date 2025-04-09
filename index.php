<?php
session_start();
require 'sistema/conexao.php';

if (isset($_POST['entrar'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($result)) {
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['is_admin'] = $usuario['is_admin']; 
            header("Location: home.php"); // Redireciona para a home após login
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
