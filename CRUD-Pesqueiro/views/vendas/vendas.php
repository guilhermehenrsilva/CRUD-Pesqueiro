<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Estilos CSS embutidos */
        body {
            background-color: #f8f9fa; /* Um fundo mais suave */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.05); /* Sombra sutil para a navbar */
        }

        .container.mt-5 {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .card {
            border: none; /* Remove a borda padrão */
            border-radius: 0.75rem; /* Bordas mais arredondadas */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Sombra mais visível e elegante */
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; /* Efeito hover */
        }

        .card:hover {
            transform: translateY(-3px); /* Leve levantada no hover */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Sombra mais pronunciada no hover */
        }

        .card-header {
            background-color: #ffffff; /* Fundo branco para cabeçalhos de card */
            border-bottom: 1px solid #e9ecef; /* Linha divisória sutil */
            font-weight: 600;
            font-size: 1.15rem;
            padding: 1.25rem 1.5rem;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        /* Estilo para os cards de métricas principais */
        .metric-card {
            text-align: center;
            background-color: #fff;
            padding: 1.5rem;
        }

        .metric-card .icon {
            font-size: 2.5rem; /* Tamanho dos ícones */
            margin-bottom: 0.5rem;
            color: #6c757d; /* Cor padrão do ícone */
        }

        .metric-card h5 {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .metric-card .value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #343a40;
        }

        /* Cores específicas para os ícones das métricas (usando classes utilitárias agora) */
        /* Removidas aqui, pois serão aplicadas diretamente nos ícones com `text-primary`, etc. */

        /* Tabela */
        .table th, .table td {
            vertical-align: middle;
        }
        .table thead th {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 600;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .03); /* Linhas alternadas mais suaves */
        }

        /* Botões da tabela */
        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
            border-radius: 0.3rem;
        }

        /* Alerta de total geral */
        .alert.total-geral {
            background-color: #e2f3e8; /* Um tom verde claro para o total */
            border-color: #d1e7dd;
            color: #0f5132;
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        /* Estilo para gráficos (se Chart.js for usado) */
        #vendasPorProdutoChart, #vendasPorVendedorChart {
            max-height: 350px; /* Limita a altura do gráfico */
            width: 100%;
        }
    </style>
</head>
<body>
    <?php include(PROJECT_ROOT . '/system/navbar.php'); ?>

    <div class="container mt-5">
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        

        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="card metric-card">
                    <div class="card-body">
                        <div class="icon text-primary"><i class="fas fa-dollar-sign"></i></div>
                        <h5>Total Geral de Vendas</h5>
                        <div class="value">R$ <?= number_format($total_geral, 2, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card metric-card">
                    <div class="card-body">
                        <div class="icon text-success"><i class="fas fa-cube"></i></div>
                        <h5>Total de Itens Vendidos</h5>
                        <?php 
                            $total_itens_vendidos = array_sum(array_column($vendas, 'quantidade'));
                        ?>
                        <div class="value"><?= $total_itens_vendidos ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card metric-card">
                    <div class="card-body">
                        <div class="icon text-info"><i class="fas fa-boxes"></i></div>
                        <h5>Produtos Únicos Vendidos</h5>
                        <?php 
                            $produtos_vendidos = [];
                            foreach ($vendas as $v) {
                                $produtos_vendidos[$v['estoque']['nome_produto']] = true;
                            }
                        ?>
                        <div class="value"><?= count($produtos_vendidos) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card metric-card">
                    <div class="card-body">
                        <div class="icon text-warning"><i class="fas fa-user-tie"></i></div>
                        <h5>Vendedores Ativos</h5>
                        <?php 
                            $vendedores_ativos = [];
                            foreach ($vendas as $v) {
                                if (isset($v['vendedores']['nome'])) {
                                    $vendedores_ativos[$v['vendedores']['nome']] = true;
                                }
                            }
                        ?>
                        <div class="value"><?= count($vendedores_ativos) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2"></i>Vendas por Produto
                    </div>
                    <div class="card-body">
                        <canvas id="vendasPorProdutoChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2"></i>Vendas por Vendedor
                    </div>
                    <div class="card-body">
                        <canvas id="vendasPorVendedorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mb-5">

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-list me-2"></i>Lista de Vendas</h4>
                <a href="<?= BASE_URL ?>/venda/create" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nova Venda
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unit.</th>
                                <th>Total Item</th> <th>Data Venda</th>
                                <th>Vendedor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($vendas)): ?>
                                <?php foreach ($vendas as $v): 
                                    $total_item = $v['quantidade'] * $v['estoque']['preco_unitario'];
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($v['id']) ?></td>
                                        <td><?= htmlspecialchars($v['estoque']['nome_produto']) ?></td>
                                        <td><?= htmlspecialchars($v['quantidade']) ?></td>
                                        <td>R$ <?= number_format($v['estoque']['preco_unitario'], 2, ',', '.') ?></td>
                                        <td>R$ <?= number_format($total_item, 2, ',', '.') ?></td> <td><?= date('d/m/Y', strtotime($v['data_venda'])) ?></td>
                                        <td><?= htmlspecialchars($v['vendedores']['nome'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                                <form action="<?= BASE_URL ?>/venda/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
                                                    <input type="hidden" name="id_venda" value="<?= htmlspecialchars($v['id']) ?>">
                                                    <button type="submit" name="delete_venda" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i> Excluir
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Admin Only</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Nenhuma venda registrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="alert alert-info text-center total-geral" role="alert">
            <strong>Total Geral de Vendas: R$ <?= number_format($total_geral, 2, ',', '.') ?></strong>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Dados de exemplo para os gráficos (você precisa preencher isso com seus dados PHP)
        // Certifique-se que estas variáveis PHP (vendasPorProdutoData, vendasPorVendedorData)
        // estejam sendo populadas no seu controlador antes de serem usadas aqui.
        <?php
        // Exemplo de como você pode processar os dados no seu controlador ou antes de incluir a view
        // Isso é apenas um EXEMPLO. Você deve ter a lógica de agregação no seu PHP.
        $chart_data_produtos = [];
        $chart_data_vendedores = [];

        if (!empty($vendas)) {
            foreach ($vendas as $v) {
                $produto_nome = $v['estoque']['nome_produto'];
                $vendedor_nome = $v['vendedores']['nome'] ?? 'Desconhecido';
                $valor_venda = $v['quantidade'] * $v['estoque']['preco_unitario'];

                // Agrega vendas por produto
                if (!isset($chart_data_produtos[$produto_nome])) {
                    $chart_data_produtos[$produto_nome] = 0;
                }
                $chart_data_produtos[$produto_nome] += $valor_venda;

                // Agrega vendas por vendedor
                if (!isset($chart_data_vendedores[$vendedor_nome])) {
                    $chart_data_vendedores[$vendedor_nome] = 0;
                }
                $chart_data_vendedores[$vendedor_nome] += $valor_venda;
            }
        }
        
        // Transforma para JSON para ser usado no JavaScript
        $js_produtos_labels = json_encode(array_keys($chart_data_produtos));
        $js_produtos_values = json_encode(array_values($chart_data_produtos));
        
        $js_vendedores_labels = json_encode(array_keys($chart_data_vendedores));
        $js_vendedores_values = json_encode(array_values($chart_data_vendedores));
        ?>

        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Vendas por Produto
            const ctxProduto = document.getElementById('vendasPorProdutoChart');
            if (ctxProduto) {
                new Chart(ctxProduto, {
                    type: 'bar', // Pode ser 'bar', 'horizontalBar', 'line', 'pie', 'doughnut'
                    data: {
                        labels: <?= $js_produtos_labels ?>, // Dados do PHP
                        datasets: [{
                            label: 'Valor Total Vendido (R$)',
                            data: <?= $js_produtos_values ?>, // Dados do PHP
                            backgroundColor: 'rgba(106, 17, 203, 0.7)',
                            borderColor: 'rgba(106, 17, 203, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Gráfico de Vendas por Vendedor
            const ctxVendedor = document.getElementById('vendasPorVendedorChart');
            if (ctxVendedor) {
                new Chart(ctxVendedor, {
                    type: 'doughnut', // Gráfico de rosca é bom para proporções
                    data: {
                        labels: <?= $js_vendedores_labels ?>, // Dados do PHP
                        datasets: [{
                            label: 'Vendas (R$)',
                            data: <?= $js_vendedores_values ?>, // Dados do PHP
                            backgroundColor: [
                                '#0d6efd', '#198754', '#6c757d', '#dc3545', '#ffc107', '#0dcaf0', '#6f42c1', '#20c997'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }
        });
    </script>

    <?php include(PROJECT_ROOT . '/system/footer.php'); ?>
</body>
</html>