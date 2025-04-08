<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'conexao.php';
require 'verifica_login.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
      <?php include('mensagem.php'); ?>
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
                  $sql = 'SELECT * FROM usuarios';
                  $usuarios = mysqli_query($conexao, $sql);
                  if (mysqli_num_rows($usuarios) > 0) {
                    foreach($usuarios as $usuario) {
                  ?>
                  <tr>
                    <td><?=$usuario['id']?></td>
                    <td><?=$usuario['nome']?></td>
                    <td><?=$usuario['email']?></td>
                    <td><?=date('d/m/Y', strtotime($usuario['data_nascimento']))?></td>
                    <td>
                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                    <a href="usuario-view.php?id=<?= $usuario['id'] ?>" class="btn btn-secondary btn-sm">
                      <span class="bi-eye-fill"></span> Visualizar
                    </a>

                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                      <a href="usuario-edit.php?id=<?= $usuario['id'] ?>" class="btn btn-success btn-sm">
                        <span class="bi-pencil-fill"></span> Editar
                      </a>
                      <form action="acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')" class="d-inline">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
