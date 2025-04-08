<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-md d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="/CRUD-Pesqueiro/home.php">CRUD - Guilherme</a>

    <div class="d-flex align-items-center">
      <?php if (isset($_SESSION['usuario_id'])): ?>
          <span class="text-white me-3">
            Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
            <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <span class="badge bg-warning text-dark ms-2">Admin</span>
            <?php endif; ?>
        </span>


        <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
