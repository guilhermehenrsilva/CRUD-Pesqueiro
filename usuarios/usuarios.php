<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../sistema/verifica_login.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
    <?php include('../sistema/navbar.php'); ?>
    <div class="container mt-4">
      <?php include('../sistema/mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4> Lista de Usuários
              <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="usuario-create.php" class="btn btn-primary float-end">Adicionar usuário</a>
              <?php endif; ?>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data Nascimento</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Requisição cURL ao Supabase
                  $url = "https://dxvanyhmpiosibjhnxpq.supabase.co/rest/v1/usuarios?select=*";
                  $headers = [
                    "apikey: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4dmFueWhtcGlvc2liamhueHBxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxNTkxMDgsImV4cCI6MjA1OTczNTEwOH0.rjQAET7doqcGLSZcSJ1vb05wm7RfhV-5R0e8nquexeM",
                    "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4dmFueWhtcGlvc2liamhueHBxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxNTkxMDgsImV4cCI6MjA1OTczNTEwOH0.rjQAET7doqcGLSZcSJ1vb05wm7RfhV-5R0e8nquexeM",
                    "Content-Type: application/json"
                  ];

                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                  $response = curl_exec($ch);
                  curl_close($ch);

                  $usuarios = json_decode($response, true);

                  if ($usuarios && count($usuarios) > 0) {
                    foreach ($usuarios as $usuario) {
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td>
                      <?= isset($usuario['data_nascimento']) ? date('d/m/Y', strtotime($usuario['data_nascimento'])) : '' ?>
                    </td>
                    <td>
                      <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="../usuarios/usuario-view.php?id=<?= $usuario['id'] ?>" class="btn btn-secondary btn-sm">
                          <span class="bi-eye-fill"></span> Visualizar
                        </a>

                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                          <a href="../usuarios/usuario-edit.php?id=<?= $usuario['id'] ?>" class="btn btn-success btn-sm">
                            <span class="bi-pencil-fill"></span> Editar
                          </a>
                          <form action="../acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')" class="d-inline">
                            <button type="submit" name="delete_usuario" value="<?= $usuario['id'] ?>" class="btn btn-danger btn-sm">
                              <span class="bi-trash3-fill"></span> Excluir
                            </button>
                          </form>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="5"><h5>Nenhum usuário encontrado</h5></td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../sistema/footer.php'; ?>

  </body>
</html>
