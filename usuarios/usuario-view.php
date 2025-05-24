<?php
require '../sistema/verifica_login.php';

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
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuário - Visualizar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include('../sistema/navbar.php'); ?>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Visualizar Usuário
              <a href="../usuarios/usuarios.php" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <?php if ($usuario): ?>
              <div class="mb-3">
                <label>Nome</label>
                <p class="form-control"><?= htmlspecialchars($usuario['nome']) ?></p>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <p class="form-control"><?= htmlspecialchars($usuario['email']) ?></p>
              </div>
              <div class="mb-3">
                <label>Data Nascimento</label>
                <p class="form-control"><?= date('d/m/Y', strtotime($usuario['data_nascimento'])) ?></p>
              </div>
            <?php else: ?>
              <h5>Usuário não encontrado</h5>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
