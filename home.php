<?php
require 'sistema/conexao.php';
require 'sistema/verifica_login.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem'] = "Você precisa estar logado para acessar a home.";
    header("Location: login.php");
    exit;
}

?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('sistema/navbar.php');
 ?>
  <div class="container mt-5">
  <h2 class="mb-4 text-center">Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>

  <div class="row justify-content-center text-center g-4">
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Usuários</h5>
          <p class="card-text">Gerencie os usuários cadastrados no sistema.</p>
          <a href="/CRUD-Pesqueiro/usuarios/usuarios.php" class="btn btn-primary">Ir para Usuários</a>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Vendas</h5>
          <p class="card-text">Registre vendas e atualize o estoque automaticamente.</p>
          <a href="/CRUD-Pesqueiro/vendas/vendas.php" class="btn btn-success">Ir para Vendas</a>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Estoque</h5>
          <p class="card-text">Controle os produtos e quantidades disponíveis.</p>
          <a href="/CRUD-Pesqueiro/estoque/estoque.php" class="btn btn-warning">Ir para Estoque</a>
        </div>
      </div>
    </div>
  </div>
</div>


</body>
</html>
