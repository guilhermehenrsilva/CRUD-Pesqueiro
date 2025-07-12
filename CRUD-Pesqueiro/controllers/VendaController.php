<?php
// Garante que apenas usuários logados possam acessar esta seção
require_once __DIR__ . '/../system/verifica_login.php';
require_once __DIR__ . '/../models/VendaModel.php';
require_once __DIR__ . '/../models/EstoqueModel.php';
require_once __DIR__ . '/../models/VendedorModel.php';

class VendaController {
    private $vendaModel;
    private $estoqueModel;
    private $vendedorModel;

    public function __construct() {
        $this->vendaModel = new VendaModel();
        $this->estoqueModel = new EstoqueModel();
        $this->vendedorModel = new VendedorModel();
    }

    public function index() {
        $vendas = $this->vendaModel->getAllVendas("?select=id,id_produto,id_vendedor,quantidade,data_venda,estoque(id,nome_produto,preco_unitario),vendedores(id,nome)&order=data_venda.desc");

        $totais = [];
        $total_geral = 0;

        if ($vendas) {
            foreach ($vendas as $v) {
                if (isset($v['estoque']['nome_produto'])) {
                    $produto = $v['estoque']['nome_produto'];
                    $subtotal = $v['quantidade'] * $v['estoque']['preco_unitario'];
                    $totais[$produto] = ($totais[$produto] ?? 0) + $subtotal;
                    $total_geral += $subtotal;
                }
            }
        }
        require_once PROJECT_ROOT . '/views/vendas/vendas.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_produto = $_POST['id_produto'];
            $id_vendedor = $_POST['id_vendedor'];
            $quantidade = (int)$_POST['quantidade'];
            $data_venda = $_POST['data_venda'];

            $produto = $this->estoqueModel->getProdutoById($id_produto);

            if (!$produto || $produto['quantidade'] < $quantidade) {
                $_SESSION['mensagem'] = "Produto inexistente ou quantidade insuficiente.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/venda/create");
                exit;
            }

            $total = $quantidade * $produto['preco_unitario'];

            if ($this->vendaModel->createVenda([
                'id_produto' => $id_produto,
                'quantidade' => $quantidade,
                'total' => $total,
                'data_venda' => $data_venda,
                'id_vendedor' => $id_vendedor
            ])) {
                $nova_qtd = $produto['quantidade'] - $quantidade;
                $this->estoqueModel->updateProduto($id_produto, ['quantidade' => $nova_qtd]);
                $_SESSION['mensagem'] = "Venda registrada com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao registrar a venda.";
            }
            // CORRIGIDO
            header("Location: " . BASE_URL . "/venda");
            exit;
        } else {
            $produtos = $this->estoqueModel->getAllEstoque('?quantidade=gt.0&select=id,nome_produto,quantidade');
            $vendedores = $this->vendedorModel->getAllVendedores('?select=id,nome');
            require_once PROJECT_ROOT . '/views/vendas/vendas-create.php';
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_venda = $_POST['id_venda'];

            $venda = $this->vendaModel->getVendaById($id_venda);

            if (!$venda) {
                $_SESSION['mensagem'] = "Venda não encontrada.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/venda");
                exit;
            }

            $id_produto = $venda['id_produto'];
            $quantidade_vendida = $venda['quantidade'];

            $produto = $this->estoqueModel->getProdutoById($id_produto);

            // A exclusão de uma venda deve restaurar o estoque, então não falhamos se o produto não existir mais.
            // Apenas tentamos atualizar se ele for encontrado.
            if ($produto) {
                 $nova_qtd = $produto['quantidade'] + $quantidade_vendida;
                 $this->estoqueModel->updateProduto($id_produto, ['quantidade' => $nova_qtd]);
            }

            if ($this->vendaModel->deleteVenda($id_venda)) {
                $_SESSION['mensagem'] = "Venda excluída com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir a venda.";
            }
        }
        // CORRIGIDO
        header("Location: " . BASE_URL . "/venda");
        exit;
    }
}
?>