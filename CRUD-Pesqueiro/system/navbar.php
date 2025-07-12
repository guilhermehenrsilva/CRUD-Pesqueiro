<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/home">🎣 Pé da Serra - Painel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPainel">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarPainel">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/usuario">Usuários</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/estoque">Estoque</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/venda">Vendas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/vendedor">Vendedores</a>
        </li>
      </ul>
      <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['usuario_id'])): ?>
          <span class="navbar-text text-white me-3">
            Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong>
            <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
              <span class="badge bg-warning text-dark ms-2">Admin</span>
            <?php endif; ?>
          </span>
          <a href="<?= BASE_URL ?>/logout" class="btn btn-outline-light btn-sm">Sair</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>/login" class="btn btn-outline-light btn-sm">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>