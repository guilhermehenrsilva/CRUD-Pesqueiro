<?php
// Garante que apenas usuários logados possam acessar esta seção
require_once __DIR__ . '/../system/verifica_login.php';
require_once __DIR__ . '/../models/EstoqueModel.php';
require_once __DIR__ . '/../models/VendaModel.php'; // Para verificar vendas vinculadas

class EstoqueController {
    private $estoqueModel;
    private $vendaModel;

    public function __construct() {
        $this->estoqueModel = new EstoqueModel();
        $this->vendaModel = new VendaModel();
    }

    public function index() {
        $produtos = $this->estoqueModel->getAllEstoque();
        require_once PROJECT_ROOT . '/views/estoque/estoque.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome_produto = trim($_POST['nome_produto']);
            $quantidade = trim($_POST['quantidade']);
            $preco_unitario = trim($_POST['preco_unitario']);

            if (empty($nome_produto) || !isset($quantidade) || !isset($preco_unitario)) {
                $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/estoque/create");
                exit;
            }

            if ($this->estoqueModel->createProduto([
                'nome_produto' => $nome_produto,
                'quantidade' => (int)$quantidade,
                'preco_unitario' => (float)$preco_unitario
            ])) {
                $_SESSION['mensagem'] = "Produto adicionado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao adicionar produto.";
            }
            // CORRIGIDO
            header("Location: " . BASE_URL . "/estoque");
            exit;
        } else {
            require_once PROJECT_ROOT . '/views/estoque/estoque-create.php';
        }
    }

    public function edit($params) {
        $id = $params[0] ?? $_POST['estoque_id'] ?? null;

        if (!$id) {
            $_SESSION['mensagem'] = 'ID do produto não fornecido.';
            // CORRIGIDO
            header('Location: ' . BASE_URL . '/estoque');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome_produto = trim($_POST['nome_produto']);
            $quantidade = trim($_POST['quantidade']);
            $preco_unitario = trim($_POST['preco_unitario']);

            if (empty($id) || empty($nome_produto) || !isset($quantidade) || !isset($preco_unitario)) {
                $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/estoque/edit/$id");
                exit;
            }

            if ($this->estoqueModel->updateProduto($id, [
                'nome_produto' => $nome_produto,
                'quantidade' => (int)$quantidade,
                'preco_unitario' => (float)$preco_unitario
            ])) {
                $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao atualizar produto.";
            }
            // CORRIGIDO
            header("Location: " . BASE_URL . "/estoque");
            exit;
        } else {
            $produto = $this->estoqueModel->getProdutoById($id);
            if (!$produto) {
                $_SESSION['mensagem'] = 'Produto não encontrado.';
                // CORRIGIDO
                header('Location: ' . BASE_URL . '/estoque');
                exit;
            }
            require_once PROJECT_ROOT . '/views/estoque/estoque-edit.php';
        }
    }

    public function view($params) {
        $id = $params[0] ?? null;

        if (!$id) {
            $_SESSION['mensagem'] = 'ID do produto não fornecido.';
            // CORRIGIDO
            header('Location: ' . BASE_URL . '/estoque');
            exit;
        }

        $produto = $this->estoqueModel->getProdutoById($id);
        if (!$produto) {
            $_SESSION['mensagem'] = 'Produto não encontrado.';
            // CORRIGIDO
            header('Location: ' . BASE_URL . '/estoque');
            exit;
        }
        require_once PROJECT_ROOT . '/views/estoque/estoque-view.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estoque_id = $_POST['delete_estoque'];

            if (empty($estoque_id)) {
                $_SESSION['mensagem'] = "ID inválido para exclusão.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/estoque");
                exit;
            }

            $vendasVinculadas = $this->vendaModel->getAllVendas("?id_produto=eq.$estoque_id");

            if (!empty($vendasVinculadas)) {
                $_SESSION['mensagem'] = "Não é possível excluir este produto. Existem vendas vinculadas a ele.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/estoque");
                exit;
            }

            if ($this->estoqueModel->deleteProduto($estoque_id)) {
                $_SESSION['mensagem'] = "Produto excluído com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir produto.";
            }
        }
        // CORRIGIDO
        header("Location: " . BASE_URL . "/estoque");
        exit;
    }
}
?>