<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
<?php include(PROJECT_ROOT . '/system/navbar.php'); ?>

<div class="container mt-5">
  <h2 class="mb-4 text-center">Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>
  <div class="row justify-content-center text-center g-4 mt-5"><br><br></div>

  <div class="row justify-content-center text-center g-4">
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card shadow-sm h-100 d-flex flex-column">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Usuários</h5>
          <p class="card-text">Gerencie os usuários cadastrados no sistema.</p>
          <div class="mt-auto">
            <a href="<?= BASE_URL ?>/usuario" class="btn btn-secondary w-100">
              <i class="bi bi-people-fill"></i> Ir para Usuários
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card shadow-sm h-100 d-flex flex-column">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Vendas</h5>
          <p class="card-text">Registre vendas e atualize o estoque automaticamente.</p>
          <div class="mt-auto">
            <a href="<?= BASE_URL ?>/venda" class="btn btn-secondary w-100">
              <i class="bi bi-cart-fill"></i> Ir para Vendas
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card shadow-sm h-100 d-flex flex-column">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Estoque</h5>
          <p class="card-text">Controle os produtos e quantidades disponíveis.</p>
          <div class="mt-auto">
            <a href="<?= BASE_URL ?>/estoque" class="btn btn-secondary w-100">
              <i class="bi bi-box-seam"></i> Ir para Estoque
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card shadow-sm h-100 d-flex flex-column">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Vendedores</h5>
          <p class="card-text">Gerencie os vendedores responsáveis pelas vendas.</p>
          <div class="mt-auto">
            <a href="<?= BASE_URL ?>/vendedor" class="btn btn-secondary w-100">
              <i class="bi bi-person-badge-fill"></i> Ir para Vendedores
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(PROJECT_ROOT . '/system/footer.php'); ?>
</body>
</html>