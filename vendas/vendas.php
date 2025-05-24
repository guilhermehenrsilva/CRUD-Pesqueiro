<?php
require '../sistema/conexao.php';
require '../sistema/verifica_login.php';
include '../sistema/navbar.php';
include '../sistema/mensagem.php';

// Função para requisição GET do Supabase
function supabaseGET($endpoint, $query = '') {
    global $supabaseUrl, $supabaseKey;

    $url = $supabaseUrl . "/rest/v1/$endpoint" . $query;
    $headers = [
        "apikey: $supabaseKey",
        "Authorization: Bearer $supabaseKey"
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

// Buscar vendas com informações dos produtos e vendedores
$vendas = supabaseGET("vendas?select=id,id_produto,id_vendedor,quantidade,data_venda,estoque(id,nome_produto,preco_unitario),vendedores(id,nome)&order=data_venda.desc");

// Calcular total por produto
$totais = [];
$total_geral = 0;

if ($vendas) {
    foreach ($vendas as $v) {
        $produto = $v['estoque']['nome_produto'];
        $subtotal = $v['quantidade'] * $v['estoque']['preco_unitario'];
        $totais[$produto] = ($totais[$produto] ?? 0) + $subtotal;
        $total_geral += $subtotal;
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lista de Vendas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <?php if (isset($_SESSION['mensagem'])): ?>
      <div class="alert alert-info"><?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
      <div class="card-header">
        <h4>Lista de Vendas
          <a href="vendas-create.php" class="btn btn-primary float-end">Nova Venda</a>
        </h4>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Produto</th>
              <th>Quantidade</th>
              <th>Preço Unitário</th>
              <th>Data da Venda</th>
              <th>Vendedor</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($vendas)): ?>
              <?php foreach ($vendas as $v): ?>
                <tr>
                  <td><?= $v['id'] ?></td>
                  <td><?= $v['estoque']['nome_produto'] ?></td>
                  <td><?= $v['quantidade'] ?></td>
                  <td>R$ <?= number_format($v['estoque']['preco_unitario'], 2, ',', '.') ?></td>
                  <td><?= date('d/m/Y', strtotime($v['data_venda'])) ?></td>
                  <td><?= $v['vendedores']['nome'] ?? 'N/A' ?></td>
                  <td>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                      <form action="../acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
                        <input type="hidden" name="id_venda" value="<?= $v['id'] ?>">
                        <button type="submit" name="delete_venda" class="btn btn-danger btn-sm">Excluir</button>

                      </form>
                    <?php else: ?>
                      <span class="text-muted">Somente admin</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center">Nenhuma venda registrada.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- TOTAIS POR PRODUTO -->
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h5>Totais por Produto</h5>
      </div>
      <div class="card-body">
        <?php if (!empty($totais)): ?>
          <ul class="list-group">
            <?php foreach ($totais as $produto => $total): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $produto ?>
                <span class="badge bg-primary rounded-pill">R$ <?= number_format($total, 2, ',', '.') ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>Nenhuma venda registrada.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- TOTAL GERAL -->
    <div class="alert alert-info text-end">
      <strong>Total Geral de Vendas: R$ <?= number_format($total_geral, 2, ',', '.') ?></strong>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  <?php include '../sistema/footer.php'; ?>

</body>
</html>
