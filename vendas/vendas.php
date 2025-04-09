<?php
require '../sistema/conexao.php';
require '../sistema/verifica_login.php';
include '../sistema/navbar.php';
include '../sistema/mensagem.php';

// Consulta principal das vendas
$sql = "SELECT v.id, e.nome_produto AS produto, v.quantidade, e.preco_unitario, v.data_venda
        FROM vendas v
        INNER JOIN estoque e ON v.id_produto = e.id
        ORDER BY v.data_venda DESC";

$vendas = mysqli_query($conexao, $sql);

// Consulta para total de vendas por produto
$sql_totais = "SELECT e.nome_produto, SUM(v.quantidade * e.preco_unitario) AS total_vendido
               FROM vendas v
               INNER JOIN estoque e ON v.id_produto = e.id
               GROUP BY e.nome_produto";

$totais_por_produto = mysqli_query($conexao, $sql_totais);

// Cálculo do total geral
$total_geral = 0;
$totais = [];
if ($totais_por_produto) {
    while ($linha = mysqli_fetch_assoc($totais_por_produto)) {
        $total_geral += $linha['total_vendido'];
        $totais[$linha['nome_produto']] = $linha['total_vendido'];
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
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($vendas) > 0): ?>
              <?php mysqli_data_seek($vendas, 0); ?>
              <?php while ($v = mysqli_fetch_assoc($vendas)): ?>
                <tr>
                  <td><?= $v['id'] ?></td>
                  <td><?= $v['produto'] ?></td>
                  <td><?= $v['quantidade'] ?></td>
                  <td>R$ <?= number_format($v['preco_unitario'], 2, ',', '.') ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($v['data_venda'])) ?></td>
                  <td>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                      <form action="../acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
                        <input type="hidden" name="delete_venda" value="<?= $v['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                      </form>
                    <?php else: ?>
                      <span class="text-muted">Somente admin</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center">Nenhuma venda registrada.</td>
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
</body>
</html>
