<?php
session_start();
require '../sistema/conexao.php';
require '../sistema/verifica_login.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['mensagem'] = "Você não tem permissão para acessar essa página.";
    header("Location: usuarios.php");
    exit;
}

// Verifica se o ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID de usuário inválido.";
    header("Location: usuarios.php");
    exit;
}

// Função para buscar usuário no Supabase
function buscarUsuarioPorId($id) {
    $url = 'https://dxvanyhmpiosibjhnxpq.supabase.co/rest/v1/usuarios?id=eq.' . $id;
    
    $headers = [
        'apikey: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4dmFueWhtcGlvc2liamhueHBxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxNTkxMDgsImV4cCI6MjA1OTczNTEwOH0.rjQAET7doqcGLSZcSJ1vb05wm7RfhV-5R0e8nquexeM',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR4dmFueWhtcGlvc2liamhueHBxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxNTkxMDgsImV4cCI6MjA1OTczNTEwOH0.rjQAET7doqcGLSZcSJ1vb05wm7RfhV-5R0e8nquexeM',
        'Content-Type: application/json'
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

$usuario = null;
if (isset($_GET['id'])) {
    $usuario_id = htmlspecialchars($_GET['id']);
    $resultado = buscarUsuarioPorId($usuario_id);
    if ($resultado && count($resultado) > 0) {
        $usuario = $resultado[0];
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include('../sistema/navbar.php'); ?>
  
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Editar Usuário
              <a href="usuarios.php" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <?php if ($usuario): ?>
              <form action="../acoes.php" method="POST">
                <input type="hidden" name="usuario_id" value="<?= $usuario['id']; ?>">

                <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Data de Nascimento</label>
                  <input type="date" class="form-control" name="data_nascimento" value="<?= $usuario['data_nascimento']; ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                  <input type="password" class="form-control" name="senha">
                </div>

                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" <?= $usuario['is_admin'] ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="is_admin">Administrador</label>
                </div>

                <div class="d-flex justify-content-between">
                  <button type="submit" name="update_usuario" class="btn btn-success">Salvar Alterações</button>
                  <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
                </div>
              </form>
            <?php else: ?>
              <h5>Usuário não encontrado</h5>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
